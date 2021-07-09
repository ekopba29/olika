<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\FreeGrooming;
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
            Grooming::create([
                "owner_id" => $OwnerId,
                "cat_id" => $request->cat,
                "groomer_id" => $request->groomer,
                "groomingtype_id" => $request->grooming_type,
                "payment_price" => GroomingType::where('id',$request->grooming_type)->first()->price,
                "inputer_id" => Auth::id(),
                "grooming_at" => $request->groom_date,
                "accumulated_free_grooming" => 'n',
                "payment" => $request->payment,
            ]);

            $levelOwner = User::where("id", $OwnerId)->first()->level;
            if ($levelOwner != "notmember") {
                in_array($request->payment, ["free"]) ?
                    $this->recalculateFreeGrooming($OwnerId, 'decrease')
                    :
                    $this->recalculateFreeGrooming($OwnerId, 'increase');
            }
            DB::commit();
            return back()->with('status_success', 'Grooming Added');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return back()->with('status_error', 'Register Grooming Failed')->withInput();
        }
    }

    public function storeGroomingByCat(HttpRequest $request)
    {
        // dd($request);
        $catId = FacedesRequest::segment(2);
        $this->validateStoreGrooming($request);

        DB::beginTransaction();
        try {
            Grooming::create([
                "owner_id" => $request->owner,
                "cat_id" => $catId,
                "groomer_id" => $request->groomer,
                "inputer_id" => Auth::id(),
                "groomingtype_id" => $request->grooming_type,
                "payment_price" => GroomingType::where('id',$request->grooming_type)->first()->price,
                "grooming_at" =>  $request->groom_date,
                "accumulated_free_grooming" => 'n',
                "payment" => $request->payment,
            ]);

            $levelOwner = User::where("id", $request->owner)->first()->level;
            if ($levelOwner != "notmember") {
                in_array($request->payment, ["free"]) ?
                    $this->recalculateFreeGrooming($request->owner, 'decrease')
                    :
                    $this->recalculateFreeGrooming($request->owner, 'increase');
            }
            DB::commit();
            return back()->with('status_success', 'Grooming Added');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('status_error', 'Register Grooming Failed')->withInput();
        }
    }

    private function recalculateFreeGrooming($OwnerId, $action = "decrease")
    {
        $minimumFreeGrooming = SettingFreeGrooming::latest()->first()->minimum_grooming;
        $getGroomingBefore = Grooming::where(
            [
                'accumulated_free_grooming' => "n",
                'owner_id' => $OwnerId
            ]
        )->where('payment', '!=', 'free')->take($minimumFreeGrooming)->oldest();

        $freeGrooming = FreeGrooming::where("owner_id", $OwnerId)->first();
        switch ($action) {
            case 'decrease':
                $freeGrooming->update(["total" => $freeGrooming->total - 1]);
                break;

            case 'increase':
                if ($getGroomingBefore->count() >= $minimumFreeGrooming) {
                    $getGroomingBefore->update(["accumulated_free_grooming" => "y"]);
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
                        $level = User::where('id',$idUser)->first()->level ?? $fail('User Not Found');
                        if (!in_array($level, ["owner", "crew"])) {
                            $totalFree = FreeGrooming::where("owner_id", $idUser)->first();
                            // cek member atau bukan
                            $totalFree->total ?? $fail('Customer Not an Member');
                            // cek ketersediaan free grooming
                            if ($totalFree->total < 1) {
                                $fail('Free Grooming is Empty.');
                            }
                            $allow_free = GroomingType::where('id',$request->grooming_type)->first();
                            if($allow_free->allow_free == "n") {
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
            "groomingType" => GroomingType::get()
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

    public function edit(User $user)
    {
        //
    }

    public function update(HttpRequest $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }

    public function report(HttpRequest $request, Grooming $grooming)
    {
        if ($request->has(['from', 'to'])) {
            $grooming = $grooming
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
        return view('GroomingReport', ["datas" => $grooming]);
    }

    public function reportBy(HttpRequest $request, User $user)
    {
        if ($request->has(['from', 'to'])) {

            $grooming = Grooming::where('owner_id', $user->id)->with('owner')
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
        }
        else {
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
            $dataGroomingBelumdiakumulasi = Grooming::where('owner_id', $idgrooming->owner_id)->where('accumulated_free_grooming', 'n')->where('payment','!=', 'free');
            
            $settingFree = SettingFreeGrooming::latest()->first()->minimum_grooming;
            $dataGroomingCalonAkumulasi = $dataGroomingBelumdiakumulasi->count();
            $sisaAkumulasi = $dataGroomingBelumdiakumulasi->count() % $settingFree;
            $totalLoop = $dataGroomingCalonAkumulasi - $sisaAkumulasi;
            foreach($dataGroomingBelumdiakumulasi->get() as $no => $dt){
                $dt->update(['accumulated_free_grooming' => 'y']);
                $totalLoop = $totalLoop - 1 ;
                if($totalLoop == 0) {
                    break;
                }
            }
            
            $dataFreeGrooming->update(["total" => floor($totalLoop / 10) + $total]);
            dump($dataGroomingBelumdiakumulasi,$sisaAkumulasi,floor($totalLoop / 10) + $total);
            die();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return false;
        }
    }
}
