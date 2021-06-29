<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cat;
use App\Models\User;

class CatController extends Controller
{
    public function index(Request $request, Cat $cat)
    {
        if ($request->has('cat_name') || $request->has('owner')) {
            $ownerName = $request->owner;
            $data = $cat->join('users as owner','cats.owner_id','=','owner.id')
                ->where('cats.name', 'like', '%' . urldecode($request->cat_name) . '%')
                ->where('owner.name', 'like', '%' . urldecode($ownerName) . '%');
        } else {
            $data = $cat->with('owner');
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
            'owner_id' => $request->owner, 'name' => request('name') ,'birth_date' => date('Y-m-d',strtotime($request->birth_date))
        ]);
        return redirect(route('grooming.add', ['user' => $request->owner]))->with('status_success', 'Cat Added');
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
}
