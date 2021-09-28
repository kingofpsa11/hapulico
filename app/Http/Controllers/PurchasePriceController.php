<?php

namespace App\Http\Controllers;

use App\Models\PurchasePrice;
use Illuminate\Http\Request;

class PurchasePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->page ? : 1;
        $perPage = $request->perPage ? : 2;
        $purchasePrice = PurchasePrice::with('product')->paginate($perPage,['*'],'page',$page);

        $purchasePrice->appends('perPage',$perPage);
        return response()->json($purchasePrice, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $purchasePrice = PurchasePrice::create($request->all());
        return response()->json($purchasePrice, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchasePrice  $purchasePrice
     * @return \Illuminate\Http\Response
     */
    public function show(PurchasePrice $purchasePrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchasePrice  $purchasePrice
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchasePrice $purchasePrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchasePrice  $purchasePrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchasePrice $purchasePrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchasePrice  $purchasePrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchasePrice $purchasePrice)
    {
        //
    }
}
