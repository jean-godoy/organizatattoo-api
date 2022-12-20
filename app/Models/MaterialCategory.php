<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'material_category',
        'material_brand_id'
    ];
}
