<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialProduct extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_name',
        'studio_id'
    ];

    public function materialCategories()
    {
        return $this->hasMany(MaterialCategory::class, 'material_product_id', 'id');
    }
}
