<?php

namespace App\Http\Controllers;

use App\Models\FreeGrooming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{

    public function index(Request $request, User $user)
    {
        $search = [
            "name" => $request->name ?? null,
            "email" => $request->email ?? null,
            "unique_number" => $request->unique_number ?? null,
            "address" => $request->address ?? null,
            "subdis_id" => substr($request->subdistricts, 0, 5) ?? null,
            "districts" => substr($request->districts, 0, 4) ?? null,
            "cities" => substr($request->cities, 0, 3) ?? null,
            "level" => $request->level ?? null,
            "phone" => $request->phone ?? null
        ];
        // dd($user->listUser(["member", "notmember"], $search)->appends($request->all()));
        return view("listCustomer", [
            "users" => $user->listUser(["member", "notmember"], $search, "customer")
        ]);
    }

    public function create()
    {
        return view("formCustomer");
    }

    public function generateUniqueId($action = "create")
    {
        $get = DB::table('users')->where('level', 'member')->count();
        // $plus = $action == "create" ? 1 : 2;
        $newInt =  ($get + 1);
        // echo "ONAWA" . date('ymd') . str_pad($newInt, 4, "0", STR_PAD_LEFT);
        return "ONAWA" . date('ymd') . str_pad($newInt, 4, "0", STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $valid = $this->validateCustomer($request, "store");
        DB::beginTransaction();
        try {
            $user = User::create($valid);
            // if ($request->level == "member" || $request->level == "crew" || $request->level == "owner") {
                FreeGrooming::create([
                    'owner_id' => $user->id,
                    'total' => 0
                ]);
            // }
            DB::commit();
            return Redirect::route('cat.createFor', ['user' => $user->id])->with('status_success', 'Customer Added!');
        } catch (\Exception $e) {
            // dd($e);
            DB::rollback();
            return back()->with('status_error', 'Add Customer Failed!');
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

    public function edit(User $customer)
    {
        return view("formCustomer", [
            "user" => $customer->select('*')->leftJoin('subdistricts', 'subdistricts.subdis_id', '=', 'users.subdis_id')
                ->leftJoin('districts', 'districts.dis_id', '=', 'subdistricts.dis_id')
                ->leftJoin('cities', 'cities.city_id', '=', 'districts.city_id')->where('id', $customer->id)->first()
        ]);
    }

    public function update(Request $request, User $customer)
    {
        $customer->update($this->validateCustomer($request, "update"));

        return back()->with('status_success', 'Customer Updated!');
    }

    public function setFreegroomingManual(Request $request,User $user)
    {
        if(Auth::user()->level == "owner"){
            FreeGrooming::where("owner_id",$user->id)->update(["total"=>$request->total]);
            return back()->with('status_success', 'FreeGrooming Updated!');
        }
        else {
            return back()->with('status_error', 'Failed Update FreeGrooming!');
        }
    }

    public function destroy(User $user)
    {
        //
    }

    public function upgradeToMember(User $user)
    {
        $user->update(["level" => "member", 'unique_number' => $this->generateUniqueId()]);
        FreeGrooming::create([
            "owner_id" => $user->id,
            "total" => 0
        ]);
        return redirect(route('customer.index'))->with('status_success', 'Customer Upgraded To Member!');
    }

    private function validateCustomer($request, $action = "store")
    {

        $toValidate = [
            "phone" => "required|numeric",
            "address" => "required",
            "cities" => "required",
            "districts" => "required",
            "subdis_id" => "required",
            "level" => "in:member,notmember",
        ];

        if ($action == "store") {
            $toValidate = array_merge(
                $toValidate,
                [
                    "email" => "email|nullable|unique:users,email",
                    "name" => "required|unique:users,name"
                ]
            );
        }
        if ($request->level == "member") {
            if ($action == "store") {
                $toValidate = array_merge($toValidate, ["unique_number" => "required|unique:users,unique_number"]);
            }
        }
        if ($action == "update") {
            $toValidate = array_merge($toValidate, [
                    "email" => "email|nullable|unique:users,email," . $request->customer->id,
                    "name" => "required",
            ]);
        }


        return $request->validate($toValidate);
    }
}
