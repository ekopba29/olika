<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\Cat;
use App\Models\FreeGrooming;
use App\Models\FreeGroomingUsage;
use App\Models\Grooming;
use App\Models\GroomingType;
use App\Models\SettingFreeGrooming;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request as FacedesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroomingController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function storeGrooming(HttpRequest $request)
    {
        // dd($request);
        $OwnerId = FacedesRequest::segment(2);
        $this->validateStoreGrooming($request);

        DB::beginTransaction();
        try {
            $create = Grooming::create([
                "owner_id" => $OwnerId,
                "cat_id" => $request->cat,
                "groomer_id" => $request->groomer,
                "groomingtype_id" => $request->grooming_type,
                "payment_price" => GroomingType::where('id', $request->grooming_type)->first()->price,
                "inputer_id" => Auth::id(),
                "grooming_at" => $request->groom_date,
                "accumulated_free_grooming" => 'n',
                "payment" => $request->payment,
            ]);

            $levelOwner = User::where("id", $OwnerId)->first()->level;
            // if ($levelOwner != "notmember") {
            in_array($request->payment, ["free"]) ?
                $this->recalculateFreeGrooming($OwnerId, 'decrease', $create)
                :
                $this->recalculateFreeGrooming($OwnerId, 'increase', $create);
            // }
            DB::commit();
            return back()->with('status_success', 'Grooming Added');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('status_error', 'Register Grooming Failed ' . $e->getMessage())->withInput();
        }
    }

    public function storeGroomingByCat(HttpRequest $request)
    {
        // dd($request);
        $catId = FacedesRequest::segment(2);
        $this->validateStoreGrooming($request);

        DB::beginTransaction();
        try {
            $create = Grooming::create([
                "owner_id" => $request->owner,
                "cat_id" => $catId,
                "groomer_id" => $request->groomer,
                "inputer_id" => Auth::id(),
                "groomingtype_id" => $request->grooming_type,
                "payment_price" => GroomingType::where('id', $request->grooming_type)->first()->price,
                "grooming_at" =>  $request->groom_date,
                "accumulated_free_grooming" => 'n',
                "payment" => $request->payment,
            ]);
            $levelOwner = User::where("id", $request->owner)->first()->level;
            // if ($levelOwner != "notmember") {
            in_array($request->payment, ["free"]) ?
                $this->recalculateFreeGrooming($request->owner, 'decrease', $create)
                :
                $this->recalculateFreeGrooming($request->owner, 'increase', $create);
            // }
            DB::commit();
            return back()->with('status_success', 'Grooming Added');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('status_error_custom', 'Register Grooming Failed '.$e->getMessage())->withInput();
        }
    }

    private function recalculateFreeGrooming($OwnerId, $action = "decrease", $groomingDt)
    {
        $minimumFreeGrooming = SettingFreeGrooming::latest()->first()->minimum_grooming;
        $freeGrooming = FreeGrooming::where("owner_id", $OwnerId)->first();
        switch ($action) {
            case 'decrease':
                $freeGrooming->update(["total" => $freeGrooming->total - 1]);

                $boarding = Boarding::where('cat_id',$groomingDt->cat_id)->where('freegrooming_used','n')->first();
                // pakai free grooming dari boarding
                if ($boarding) {
                    $boarding->update(['freegrooming_used' => 'y']);
                    Grooming::where('id',$groomingDt->id)->update(['freegrooming_boarding_id'=>$boarding->id]);
                    // dd($boarding->id);
                }
                else {
                    $getGroupGrooming = Grooming::where(
                        [
                            'accumulated_free_grooming' => "y",
                            'freegrooming_used' => "n",
                            'owner_id' => $OwnerId
                        ]
                    )->where('payment', '!=', 'free')->take($minimumFreeGrooming)->first();
                    if(!isset( $getGroupGrooming->freegrooming_group)) {
                        // data grooming tidak memenuhi kalkulasi minimal grooming
                        throw new \Exception('Free Grooming Not Accepted');
                    }
                    FreeGroomingUsage::create(['grooming_id' => $groomingDt,'freegrooming_group' => $getGroupGrooming->freegrooming_group]);
                    Grooming::where('freegrooming_group',$getGroupGrooming->freegrooming_group)->update(['freegrooming_used' => 'y']);
                }
                break;

            case 'increase':
                $getGroomingBeforeAccumulated = Grooming::where(
                    [
                        'accumulated_free_grooming' => "n",
                        'owner_id' => $OwnerId
                    ]
                )->where('payment', '!=', 'free')->take($minimumFreeGrooming)->oldest();
                if ($getGroomingBeforeAccumulated->count() >= $minimumFreeGrooming) {
                    $getGroomingBeforeAccumulated->update(["accumulated_free_grooming" => "y", "freegrooming_group" => strtotime(now())]);
                    $freeGrooming->update(["total" => $freeGrooming->total + 1]);
                }
                break;
        }
    }

    private function validateStoreGrooming(HttpRequest $request)
    {
        $request->validate([
            "groomer" => ["required", "exists:users,id"],
            "grooming_type" => ["required"],
            "payment" => [
                "required",
                Rule::in(['cash', 'debit', 'credit', 'free']),
                function ($nameForm, $payment, $fail) use ($request) {
                    $idUser = $request->owner;
                    // cek ketersediaan free, jika customer owner / crew tidak ada pengecekan 
                    if ($payment === 'free') {
                        $level = User::where('id', $idUser)->first()->level ?? $fail('User Not Found');
                        if (!in_array($level, ["owner", "crew"])) {
                            $totalFree = FreeGrooming::where("owner_id", $idUser)->first();
                            // cek member atau bukan
                            $totalFree->total ?? $fail('Customer Not an Member');
                            // cek ketersediaan free grooming
                            if ($totalFree->total < 1) {
                                $fail('Free Grooming is Empty.');
                            }
                            $allow_free = GroomingType::where('id', $request->grooming_type)->first();
                            if ($allow_free->allow_free == "n") {
                                $fail('Grooming type ' . $allow_free->grooming_name . ' not available for free.');
                            }
                        }
                    }
                },
            ],
            "cat" => ["required", "exists:cats,id"],
            "groom_date" => ["required", "date_format:Y-m-d"]
        ]);
    }

    public function addGrooming(HttpRequest $request, User $user)
    {

        return view("formAddGrooming", [
            "user" => $user,
            "cats" => $user->cats,
            "freeGrooming" => $user->freeGrooming->total ?? 0,
            "groomers" => User::whereIn("level", ["owner", "crew"])->get(),
            "groomingType" => GroomingType::orderBy()->get()
        ]);
    }

    public function addGroomingByCat(HttpRequest $request, Cat $cat)
    {
        return view("formAddGroomingByCat", [
            "datas" => $cat,
            "user" => $cat->owner,
            "freeGrooming" => $cat->owner->freeGrooming->total ?? 0,
            "groomers" => User::whereIn("level", ["owner", "crew"])->get(),
            "groomingType" => GroomingType::get()
        ]);
    }

    public function edit(Grooming $idgrooming)
    {
        return view('formEditGrooming', [
            "grooming" => $idgrooming,
            "groomers" => User::whereIn("level", ["owner", "crew"])->get(),
            "groomingType" => GroomingType::get(),
            "cats" => $idgrooming->owner->cats,
        ]);
    }

    public function update(HttpRequest $request, Grooming $idgrooming)
    {
        $idgrooming->update($request->validate([
            'cat' => 'required',
            'grooming_type' => 'required',
            'groomer' => 'required',
            'groom_date' => 'required|date_format:Y-m-d',
        ]));

        if ($idgrooming->groomingtype_id != $request->grooming_type){
            $idgrooming->update([
                'grooming_at' => $request->groom_date,
                'cat_id' => $request->cat,
                'payment_price' => GroomingType::where('id', $request->grooming_type)->first()->price,
                'groomer_id' => $request->groomer,
                'groomingtype_id' => $request->grooming_type
            ]);
        }
        else {
            $idgrooming->update([
                'grooming_at' => $request->groom_date,
                'cat_id' => $request->cat,
                'groomer_id' => $request->groomer,
                'groomingtype_id' => $request->grooming_type
            ]);
        }

        return back()->with('status_success', 'Grooming Updated!')->withInput();
        // $idgrooming->update();
    }

    public function destroy(User $user)
    {
        //
    }

    public function report(HttpRequest $request, Grooming $grooming)
    {
        if ($request->has(['from', 'to'])) {
            $grooming = $grooming->with('groomType')
                // ->whereBetween('grooming_at', [
                //     date('Y-m-d', strtotime($request->from)),
                //     date('Y-m-d', strtotime($request->to))
                // ])->get()
                ->whereDate('grooming_at', '>=', date('Y-m-d', strtotime($request->from)))
                ->whereDate('grooming_at', '<=', date('Y-m-d', strtotime($request->to)))
                ->orderBy('grooming_at', 'desc')
                ->get();
        } else {
            $grooming = null;
        }
        // dd($grooming);
        return view('GroomingReport', ["datas" => $grooming]);
    }

    public function reportBy(HttpRequest $request, User $user)
    {
        if ($request->has(['from', 'to'])) {

            $grooming = Grooming::where('owner_id', $user->id)->with(['owner', 'groomType'])
                // $grooming = $user->with('groomingsCustomer')
                // ->whereBetween('grooming_at', [
                //     date('Y-m-d', strtotime($request->from)),
                //     date('Y-m-d', strtotime($request->to))
                // ])->get()
                ->whereDate('grooming_at', '>=', date('Y-m-d', strtotime($request->from)))
                ->whereDate('grooming_at', '<=', date('Y-m-d', strtotime($request->to)))
                ->orderBy('grooming_at', 'desc')
                ->get();
        } else {
            $grooming = null;
        }
        return view('GroomingReportBy', ["datas" => $grooming, "user" => $user]);
    }

    public function delete(HttpRequest $request, Grooming $idgrooming)
    {

        if ($idgrooming->accumulated_free_grooming == "y") {
            // $pengganti = Grooming::where('owner_id', $idgrooming->id)->where('accumulated_free_grooming','n')->where('payment','!=','free')->first();
            // if ($pengganti == null) {
            // $recalculate = $this->recalculateFreeGroomingAfterDelete($idgrooming);


            // }
            // else{
            //     $pengganti->update([
            //         'accumulated_free_grooming' => 'y'
            //     ]);
            // }
        }

        if ($idgrooming->accumulated_free_grooming != "y") {
            $idgrooming->delete();
            return back()->with('status_success', 'Data Grooming Deleted');
        } else {
            return back()->with('status_error_custom', 'Failed Delete');
        }
        // dd($idgrooming);
        // $delete = Grooming::delete($request->segment(2));
        // return back()->with('status_success','Data Grooming Deleted');
    }

    private function recalculateFreeGroomingAfterDelete($idgrooming)
    {
        DB::beginTransaction();
        try {
            // free grooming dikurangi 1
            $dataFreeGrooming = FreeGrooming::where('owner_id', $idgrooming->owner_id)->first();
            $total = $dataFreeGrooming->total;
            $dataFreeGrooming->update(["total" => $total - 1]);

            // ambil free grooming yang sekelompok dengan yang dihapus barusan dan update accumulated = "n"
            // kemudian recalculate freegrooming
            // update hasil recalculte yang terakumulasi ke "CCUMULATE FREE GROOMING = Y"
            Grooming::where('owner_id', $idgrooming->owner_id)->where('updated_at', $dataFreeGrooming->updated_at)->update(['accumulated_free_grooming' => 'n']);
            $dataGroomingBelumdiakumulasi = Grooming::where('owner_id', $idgrooming->owner_id)->where('accumulated_free_grooming', 'n')->where('payment', '!=', 'free');

            $settingFree = SettingFreeGrooming::latest()->first()->minimum_grooming;
            $dataGroomingCalonAkumulasi = $dataGroomingBelumdiakumulasi->count();
            $sisaAkumulasi = $dataGroomingBelumdiakumulasi->count() % $settingFree;
            $totalLoop = $dataGroomingCalonAkumulasi - $sisaAkumulasi;
            foreach ($dataGroomingBelumdiakumulasi->get() as $no => $dt) {
                $dt->update(['accumulated_free_grooming' => 'y']);
                $totalLoop = $totalLoop - 1;
                if ($totalLoop == 0) {
                    break;
                }
            }

            $dataFreeGrooming->update(["total" => floor($totalLoop / 10) + $total]);
            // dump($dataGroomingBelumdiakumulasi, $sisaAkumulasi, floor($totalLoop / 10) + $total);
            // die();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return false;
        }
    }
}
