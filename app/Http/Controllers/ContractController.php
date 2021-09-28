<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContractDetailCollection;
use App\Http\Resources\ContractDetailResource;
use App\Http\Resources\ContractResource;
use Carbon\Carbon;
use App\Models\Contract;
use App\Models\ContractDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ContractDetailCollection
     */
    public function index(Request $request)
    {
//        $page = $request->page ? : 1;
//        $perPage = $request->perPage ? : 10;
//        return new ContractDetailCollection(ContractDetail::paginate($perPage,['*'],'page',$page)->appends('perPage',$perPage));
//            return ContractDetailResource::collection(ContractDetail::all());
        $results = [];
        extract($request->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));
        $query = json_decode($query);
        $data = ContractDetail::with(['contract', 'sellingPrice.product']);

        if (isset($query) && $query) {
            $data = $byColumn == 1 ?
                $this->filterByColumn($data, $query) :
                $this->filter($data, $query, $fields);
        }

        $count = $data->count();
        $data->limit($limit)
            ->skip($limit * ($page - 1));

        if (isset($orderBy)) {
            $direction = $ascending == 1 ? 'ASC' : 'DESC';
            $data->orderBy($orderBy, $direction);
        }

        $result = $data->get();

        foreach ($result as $value) {
            $results[] = [
                'id' => $value->contract_id,
                'customer_name' => $value->contract->customer->company_name,
                'number' => $value->contract->number,
                'product_name' => $value->sellingPrice->product->name,
                'quantity' => $value->quantity,
                'selling_price' => $value->selling_price,
                'date' => $value->contract->date,
                'deadline' => $value->deadline,
                'status' => $value->status,
                'order' => $value->order,
            ];
        }

        return [
            'data' => $results,
            'count' => $count,
        ];
    }

    protected function filterByColumn($data, $queries)
    {
        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                if (is_string($query)) {
                    switch ($field) {
                        case 'customer_name':
                            $q->whereHas('contract.customer', function (Builder $qry) use ($query) {
                                    $qry->where('company_name', 'LIKE', '%'. $query .'%');
                            });
                            break;
                        case 'number':
                            $q->whereHas('contract', function (Builder $qry1) use ($query) {
                                $qry1->where('number', $query );
                            });
                            break;
                        case 'product_name':
                            $q->whereHas('sellingPrice.product', function (Builder $qry) use ($query) {
                                $qry->where('name', 'LIKE', '%'. $query .'%');
                            });
                    }
                } else {
                    $start = Carbon::createFromFormat('Y-m-d H:i:s', $query->start)->format('Y-m-d');
                    $end = Carbon::createFromFormat('Y-m-d H:i:s', $query->end)->format('Y-m-d');
                    switch ($field) {
                        case 'date':
                            $q->whereHas('contract', function (Builder $qry) use ($field, $start, $end) {
                                $qry->whereBetween($field, [$start, $end]);
                            });
                            break;
                        case 'deadline':
                            $q->whereBetween($field, [$start, $end]);
                            break;
                    }
                }
            }
        });
    }

    protected function filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $contract = Contract::create($request->all());

        foreach ($request->details as $detail) {
            $contract->contractDetails()->create($detail);
        }
        return response()->json($contract->load('customer'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return ContractResource
     */
    public function show($id)
    {
        $contract = Contract::find($id);
        return new ContractResource($contract);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $result = Contract::whereHas('product', function (Builder $query) use ($q) {
            return $query->where('name', 'LIKE', '%' . $q . '%');
        })->take(20)->get();
        return response()->json($result, 200);
    }
}
