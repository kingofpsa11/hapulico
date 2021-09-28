<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'name_bill', 'code', 'unit', 'category_id', 'status', 'note', 'file'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function purchase_prices()
    {
        return $this->hasMany(PurchasePrice::class);
    }

    public function selling_prices()
    {
        return $this->hasMany(SellingPrice::class);
    }
}
