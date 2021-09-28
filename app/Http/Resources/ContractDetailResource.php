<?php

namespace App\Http\Resources;

use App\Models\SellingPrice;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractDetailResource extends JsonResource
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
            'date' => $this->contract->date,
            'customer' => $this->contract->customer,
            'contract' => $this->whenLoaded('contract'),
            'product' => $this->sellingPrice->product,
            'quantity' => $this->quantity,
            'price' => $this->sellingPrice->price,
            'selling_price' => $this->selling_price,
            'profit_rate' => $this->profit_rate,
            'discount' => $this->discount,
            'deadline' => $this->deadline,
            'note' => $this->note
        ];
    }
}
