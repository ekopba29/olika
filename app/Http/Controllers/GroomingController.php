<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\FreeGrooming;
use App\Models\Grooming;
use App\Models\SettingFreeGrooming;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request as FacedesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

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
                "inputer_id" => Auth::id(),
                "grooming_at" => now(),
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
        } catch (\Exception $e) {
            DB::rollback();
            back()->with('error', 'Register Grooming Failed');
        }


        return back()->with('status_success', 'Grooming Added');
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
            "payment" => [
                "required",
                Rule::in(['cash', 'debit', 'credit', 'free']),
                function ($nameForm, $payment, $fail) {
                    $idUser = FacedesRequest::segment(2);
                    // cek ketersediaan free, jika customer owner / crew tidak ada pengecekan 
                    if ($payment === 'free') {

                        $level = User::find($idUser)->first()->level ?? $fail('User Not Found');

                        if (!in_array($level, ["owner", "crew"])) {
                            $totalFree = FreeGrooming::where("owner_id", $idUser)->first();

                            $totalFree->total ?? $fail('Customer Not an Member');

                            if ($totalFree->total < 1) {
                                $fail('Free Grooming is Empty.');
                            }
                        }
                    }
                },
            ],
            "cat" => ["required", "exists:cats,id"]
        ]);
    }

    public function addGrooming(HttpRequest $request, User $user)
    {

        return view("formAddGrooming", [
            "user" => $user,
            "cats" => $user->cats,
            "freeGrooming" => $user->freeGrooming->total ?? 0,
            "groomers" => User::whereIn("level", ["owner", "crew"])->get()
        ]);
    }

    public function addGroomingByCat(HttpRequest $request, Cat $cat)
    {
        return view("formAddGroomingByCat", [
            "datas" => $cat,
            "user" => $cat->owner,
            "freeGrooming" => $cat->users->freeGrooming->total ?? 0,
            "groomers" => User::whereIn("level", ["owner", "crew"])->get()
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
            $grooming = $grooming->whereBetween('grooming_at', [date('Y-m-d', strtotime($request->from)), date('y-m-d', strtotime($request->to))])->get();
        } else {
            $grooming = null;
        }
        return view('GroomingReport', ["datas" => $grooming]);
    }
}
