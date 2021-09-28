<?php

namespace App\Http\Controllers;

use App\Models\SellingPrice;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
//use PhpParser\Builder;
use function PHPUnit\Framework\throwException;
use Illuminate\Database\Eloquent\Builder;

class SellingPriceController extends Controller
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
        $sellingPrices = SellingPrice::with('product')->paginate($perPage,['*'],'page',$page);

        $sellingPrices->appends('perPage',$perPage);
        return response()->json($sellingPrices, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if(is_null(SellingPrice::where($request->all())->first())) {
            $sellingPrice=SellingPrice::create($request->all());
            return response()->json($sellingPrice->load('product'), 201);
        } else {
            abort(409,'Exist');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellingPrice  $sellingPrice
     * @return \Illuminate\Http\Response
     */
    public function show(SellingPrice $sellingPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellingPrice  $sellingPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(SellingPrice $sellingPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SellingPrice  $sellingPrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellingPrice $sellingPrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellingPrice  $sellingPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellingPrice $sellingPrice)
    {
        //
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $result = SellingPrice::whereHas('product', function (Builder $query) use ($q) {
            return $query->where('name', 'LIKE', '%' . $q . '%');
        })->take(20)->get();

        return response()->json($result->load('product'), 200);
    }
}
