<?php

namespace App\Http\Controllers;

use App\Models\FreeGrooming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function index(Request $request, User $user)
    {
        $search = [
            "name" => $request->name ?? null,
            "email" => $request->email ?? null,
            "unique_number" => $request->unique_number ?? null,
            "address" => $request->address ?? null,
            "subdis_id" => substr($request->subdistricts,0,5) ?? null,
            "districts" => substr($request->districts,0,4) ?? null,
            "cities" => substr($request->cities,0,3) ?? null,
            "level" => $request->level ?? null,
            "phone" => $request->phone ?? null
        ];
        // dd($user->listUser(["member", "notmember"], $search)->appends($request->all()));
        return view("listCustomer", [
            "users" => $user->listUser(["member", "notmember"], $search)
        ]);
    }

    public function create()
    {
        return view("formCustomer");
    }

    public function store(Request $request)
    {
        $valid = $this->validateCustomer($request, "store");
        DB::beginTransaction();
        try {
            $user = User::create($valid);
            if ($request->level == "member" || $request->level == "crew" || $request->level == "owner") {
                FreeGrooming::create([
                    'owner_id' => $user->id,
                    'total' => 0
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return back()->with('status_error', 'Add Customer Failed!');
        }
        
        return back()->with('status_success', 'Customer Added!');

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

    public function edit(User $customer)
    {
        return view("formCustomer", [
            "user" => $customer
        ]);
    }

    public function update(Request $request, User $customer)
    {
        $customer->update($this->validateCustomer($request, "update"));

        return back()->with('status_success', 'Customer Updated!');
    }

    public function destroy(User $user)
    {
        //
    }

    public function upgradeToMember(User $user)
    {
        $user->update(["level" => "member"]);
        FreeGrooming::create([
            "owner_id" => $user->id,
            "total" => 0
        ]);
        return redirect(route('customer.index'));
    }

    private function validateCustomer($request, $action = "store")
    {

        $toValidate = [
            "phone" => "required|numeric",
            "name" => "required",
            "address" => "required",
            "cities" => "required",
            "districts" => "required",
            "subdis_id" => "required",
            "level" => "in:member,notmember",
        ];
        
        if ($action == "store") {
            $toValidate = array_merge($toValidate, ["email" => "email|nullable|unique:users,email"]);
        }
        if ($request->level == "member") {
            if ($action == "store") {
                $toValidate = array_merge($toValidate, ["unique_number" => "required|unique:users,unique_number"]);
            }
        }
        if ($action == "update") {
            $toValidate = array_merge($toValidate, [
                ["email" => "email|nullable|unique:users,email," . $request->customer->id],
            ]);
        }


        return $request->validate($toValidate);
    }
}
