<?php

namespace App\Http\Controllers;

use App\Models\FreeGrooming;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index(Request $request, User $user)
    {
        $search = [
            "name" => $request->name ?? null,
            "email" => $request->email ?? null,
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
        User::create($this->validateCustomer($request,"store"));

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
        return view("formCustomer",[
            "user" => $customer
        ]);
    }

    public function update(Request $request, User $customer)
    {
        $customer->update($this->validateCustomer($request,"update"));

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

    private function validateCustomer($request , $action = "store") {

        $toValidate = [
            "phone" => "required|numeric",
            "name" => "required",
            "level" => "in:member,notmember"
        ];
   
        if ($action == "store") {
            $toValidate = array_merge($toValidate,["email" => "email|nullable|unique:users,email"]);
        }
        if ($action == "update") {
            $toValidate = array_merge($toValidate,["email" => "email|nullable|unique:users,email,".$request->customer->id]);
        }
        
        
        return $request->validate($toValidate);
    }
}
