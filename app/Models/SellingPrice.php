<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price',
        'effective_date',
        'note'
    ];

    protected $attributes = [
        'status' => 10
    ];

    public $timestamps = true;

    public function  product()
    {
        return $this->belongsTo(Product::class);
    }
}
