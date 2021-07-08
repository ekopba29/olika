<?php

namespace App\Http\Controllers;

use App\Models\GroomingType;
use Illuminate\Http\Request;

class GroomingTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('listGroomingType', ['types' => GroomingType::paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formGroomingType');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        GroomingType::create($request->validate([
            'grooming_name' => 'required',
            'price' => 'required|numeric',
            'allow_free' => 'in:y,n'
        ]));

        return back()->with('status_success','Success Add Grooming Type');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroomingType  $groomingType
     * @return \Illuminate\Http\Response
     */
    public function show(GroomingType $groomingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroomingType  $groomingType
     * @return \Illuminate\Http\Response
     */
    public function edit(GroomingType $groomingType)
    {
        return view('formGroomingType',['groomingType' => $groomingType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroomingType  $groomingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroomingType $groomingType)
    {
        $groomingType->update($request->validate([
            'grooming_name' => 'required',
            'price' => 'required|numeric',
            'allow_free' => 'in:y,n'
        ]));
        return back()->with('status_success','Success Update Grooming Type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroomingType  $groomingType
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroomingType $groomingType)
    {
        //
    }
}
