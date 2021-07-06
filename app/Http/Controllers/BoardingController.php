<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\FreeGrooming;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoardingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('formBoarding', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'cat' => 'required',
            'in' => 'required|date_format:d-m-Y',
            'out' => 'required|date_format:d-m-Y'
        ]);

        $datetime1 = new DateTime($request->in);
        $datetime2 = new DateTime($request->out);
        $interval = $datetime1->diff($datetime2)->d;
        if ($interval < 10) {
            return back()->with('status_error_custom', 'Minimum 10 days in Onawa')->withInput();
        }
        DB::beginTransaction();
        try {
            Boarding::create([
                'inputter_id' => Auth::user()->id,
                'owner_id' => $request->owner,
                'cat_id' => $request->cat,
                'in' => date('Y-m-d',strtotime($request->in)),
                'out' => date('Y-m-d',strtotime($request->out)),
            ]);
            
            $totalFreeGrooming = FreeGrooming::where('owner_id', $request->owner)->first()->total;
            $updatedTotal = $totalFreeGrooming + floor($interval / 10);
            FreeGrooming::where('owner_id', $request->owner)->update(['total' => $updatedTotal]);
            DB::commit();
            return back()->with('status_success', 'Success Add Boarding, Bonus ' . floor($interval / 10) . ' Free Grooming ( ' . $updatedTotal . ' )');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return back()->with('status_error_custom', 'Failed Add Boarding')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boarding  $boarding
     * @return \Illuminate\Http\Response
     */
    public function show(Boarding $boarding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Boarding  $boarding
     * @return \Illuminate\Http\Response
     */
    public function edit(Boarding $boarding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boarding  $boarding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boarding $boarding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boarding  $boarding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boarding $boarding)
    {
        //
    }
}
