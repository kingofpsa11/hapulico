<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'number' => $this->number,
            'customer' => $this->customer,
            'customer_id' => $this->customer_id,
            'date' => $this->date,
            'contract_details' => ContractDetailResource::collection($this->contractDetails),
            'total' => $this->total
        ];
    }
}
