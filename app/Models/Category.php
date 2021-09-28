<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id'
    ];

    protected $attributes = [
        'parent_id' => 0
    ];

    public $timestamps = true;

}
