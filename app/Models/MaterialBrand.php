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
        'product_name',
        'material_product_id'
    ];

    public function materialCatetgories()
    {
        return $this->hasMany(MaterialCategory::class, 'matarial_brand_id', 'id');
    }
}
