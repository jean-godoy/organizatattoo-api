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
        'material_product_id'
    ];

    public function materialBrands()
    {
        return $this->hasMany(MaterialBrand::class, 'material_category_id', 'id');
    }
}
