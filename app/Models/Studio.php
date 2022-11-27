<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'studio_name',
        'studio_is_active'
    ];

    public function users()
    {
        //studio possui muitos usuários.
        return $this->hasMany(User::class, 'studio_uuid', 'uuid');
    }

    public function costumers()
    {
        return $this->hasMany(Costumer::class, 'studio_uuid', 'uuid');
    }
}