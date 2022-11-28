<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Professional extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'cell_phone',
        'phone',
        'birth_date',
        'cpf',
        'sex',
        'rules',
        'is_active'
    ];

    public function address()
    {
        return $this->hasOne(ProfessionalAddress::class, 'professional_id', 'id');
    }

    public function bank()
    {
        return $this->hasMany(BankData::class, 'professional_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'professional_id', 'id');
    }
}
