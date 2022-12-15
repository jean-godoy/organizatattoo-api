<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'studio_id',
        'name',
        'costumer_id',
        'costumer_name',
        'professional_id',
        'professional_name',
        'type_service',
        'style_service',
        'body_region',
        'project_photo',
        'sessions',
        'width',
        'heigth',
        'price',
        'validated_at',
        'note',
        'url_image'
    ];
}
