<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'customer_id',
        'total',
        'imprest',
        'date',
        'date',
        'status'
    ];

    protected $attributes = [
        'status' => 10,
    ];

    public $timestamps = true;

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function contractDetails() {
        return $this->hasMany(ContractDetail::class);
    }
}
