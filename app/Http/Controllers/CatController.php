<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class CatController extends Controller
{
    public function index(Request $request, Cat $cat)
    {
        // dump(Hash::make("mamalika"));
        $data = $cat->select("owner.name as owner","cats.*")->join('users as owner', 'cats.owner_id', '=', 'owner.id');
        $ownerName = $request->owner;
        if ($request->has('cat_name') && $request->cat_name != "") {
            $data->where('cats.name', 'like', '%' . $request->cat_name . '%');
        }
        if ($request->has('owner') && $request->owner != "") {
            $data->where('owner.name', 'like', '%' . $ownerName . '%');
        }
        return view("listCat", ["cats" => $data->paginate(5)]);
    }

    public function createfor(User $user)
    {
        return view('formCat', ["user" => $user]);
    }

    public function storefor(Request $request, User $user)
    {
        $request->validate([
            'owner' => 'required',
            'name' => 'required',
            'birth_date' => 'required'
        ]);
        Cat::create([
            'owner_id' => $request->owner, 'name' => request('name'), 'birth_date' => date('Y-m-d', strtotime($request->birth_date))
        ]);
        // return redirect(route('grooming.add', ['user' => $request->owner]))->with('status_success', 'Cat Added');
        return Redirect::route('cat.createFor', ['user' => $user->id])->with('status_success', 'Cat Added!');

    }

    public function showBy(User $user)
    {
        return view(
            'catsBy',
            [
                'user' => $user,
            ]
        );
    }

    public function edit(Cat $cat)
    {
        return view('formCat', ["cat" => $cat,"user"=>$cat->owner]);
    }

    public function update(Request $request,Cat $cat)
    {
        $request->validate([
            'name' => 'required',
            'birth_date' => 'required'
        ]);

        $cat->update([
            'name' => $request->name,
            'birth_date' => date('Y-m-d',strtotime($request->birth_date))
        ]);

        return back()->with('status_success', 'Cat Updated!');
    }
}
