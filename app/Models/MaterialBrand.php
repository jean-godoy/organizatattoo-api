<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialBrand extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_brand',
        'product_measure',
        'minimum_amount',
        'descartable',
        'sterilizable',
        'total_amount',
        'is_active',
        'material_category_id'
    ];
}
