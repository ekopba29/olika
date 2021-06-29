<?php

namespace App\Http\Controllers;

use App\Models\FreeGrooming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrewController extends Controller
{

    public function index(Request $request, User $user)
    {
        $search = [
            "name" => $request->name ?? null,
            "email" => $request->email ?? null,
            "unique_number" => $request->unique_number ?? null,
            "level" => $request->level ?? null,
            "phone" => $request->phone ?? null
        ];
        // dd($user->listUser(["member", "notmember"], $search)->appends($request->all()));
        return view("listCrew", [
            "users" => $user->listUser(["owner", "crew"], $search)
        ]);
    }

    public function create()
    {
        return view("formCrew");
    }

    public function store(Request $request)
    {
        $crew = $this->validateCrew($request, "store");
        DB::beginTransaction();
        try {
            User::create(
                array_merge(
                    ["level" => "crew"],
                    $crew
                )
            );
            FreeGrooming::create([
                'owner_id' => $crew->id,
                'total' => 0
            ]);
            DB::commit();
            return back()->with('status_success', 'Crew Added!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Add Crew Failed!');
        }
    }

    public function profile(User $user)
    {
        return view(
            'userProfile',
            [
                'user' => $user,
            ]
        );
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $crew)
    {
        return view("formCrew", [
            "user" => $crew
        ]);
    }

    public function update(Request $request, User $customer)
    {
        $customer->update($this->validateCrew($request, "update"));

        return back()->with('status_success', 'Ctrw Updated!');
    }

    public function destroy(User $user)
    {
        //
    }

    // public function upgradeToMember(User $user)
    // {
    //     $user->update(["level" => "member"]);
    //     FreeGrooming::create([
    //         "owner_id" => $user->id,
    //         "total" => 0
    //     ]);
    //     return redirect(route('customer.index'));
    // }

    private function validateCrew($request, $action = "store")
    {

        $toValidate = [
            "phone" => "required|numeric",
            "name" => "required",
            "level" => "in:member,notmember"
        ];

        if ($action == "store") {
            $toValidate = array_merge($toValidate, ["email" => "email|nullable|unique:users,email"]);
        }
        if ($action == "update") {
            $toValidate = array_merge($toValidate, ["email" => "email|nullable|unique:users,email," . $request->customer->id]);
        }


        return $request->validate($toValidate);
    }
}
