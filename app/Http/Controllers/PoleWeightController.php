<?php

namespace App\Http\Controllers;

use App\Models\PoleWeight;
use Illuminate\Http\Request;

class PoleWeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pole_weights = PoleWeight::all();
        return response()->json($pole_weights);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( $id)
    {
        $pole_weight = PoleWeight::find($id);
        return response()->json($pole_weight);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function edit(PoleWeight $poleWeight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PoleWeight $poleWeight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function destroy(PoleWeight $poleWeight)
    {
        //
    }
}
