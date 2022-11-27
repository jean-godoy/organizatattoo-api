<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'email',
        'cpf',
        'birth_date',
        'sex',
        'address',
        'district',
        'city',
        'cep',
        'uf',
        'is_active'
    ];

}
