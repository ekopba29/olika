<?php

namespace App\Http\Controllers;

use App\Models\FreeGrooming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CrewController extends Controller
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
        return view("listCrew", [
            "users" => $user->listUser(["owner", "crew"], $search, "crew")
        ]);
    }

    public function editPassword()
    {
        return view('formEditPassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'password_confrim' => 'same:password'
        ]);

        $updateUser = User::where('id',Auth::user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status_success', 'Password Updated');
    }

    public function create()
    {
        return view("formCrew");
    }

    public function store(Request $request)
    {
        $crewValidation = $this->validateCrew($request, "store");
        DB::beginTransaction();
        try {
            $crew = User::create(
                array_merge(
                    ["level" => "crew"],
                    $crewValidation
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
            return back()->with('status_error', 'Add Crew Failed!');
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

    public function update(Request $request, User $crew)
    {
        // dd($request);
        $crew->update($this->validateCrew($request, "update"));

        return back()->with('status_success', 'Crew Updated!');
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
            $toValidate = array_merge($toValidate, ["email" => "email|nullable|unique:users,email," . $request->crew->id]);
        }


        return $request->validate($toValidate);
    }

    public function resetPassword(User $crew)
    {
        if(Auth::user()->level == "owner") {
            $updateUser = $crew->update([
                'password' => Hash::make('password')
            ]);
    
            return back()->with('status_success', 'Password Resetted');
        }
        else {
            abort(403);
        }

    }
}
