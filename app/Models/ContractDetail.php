<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'contract_id',
        'selling_price_id',
        'quantity',
        'profit_rate',
        'discount',
        'selling_price',
        'deadline',
        'note',
        'status'
    ];

    protected $attributes = [
        'status' => 10,
    ];

    public $timestamps = true;

    public function contract() {
        return $this->belongsTo(Contract::class);
    }

    public function sellingPrice() {
        return $this->belongsTo(SellingPrice::class);
    }
}
