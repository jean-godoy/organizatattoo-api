<?php

namespace App\Services;

use App\Models\Costumer;
use App\Models\Studio;

class CostumerService
{
    public static function registerCostumer($costumer)
    {

        $date = str_replace('/', '-', $costumer['birth_date']);
        $birth_date = date('Y-m-d', strtotime($date));

        $studio = Studio::where('uuid', $costumer['studio_uuid'])->first();

        $studio->costumers()->create([
            'uuid' => md5(uniqid(rand() . "", true)),
            'name' => $costumer['name'],
            'phone' => $costumer['phone'],
            'email' => $costumer['email'],
            'cpf' => $costumer['cpf'],
            'birth_date' => $birth_date,
            'sex' => $costumer['sex'],
            'address' => $costumer['address'],
            'district' => $costumer['district'],
            'city' => $costumer['city'],
            'cep' => $costumer['cep'],
            'uf' => $costumer['uf'],
            'is_active' => $costumer['is_active']
        ]);

        return $studio->toArray();
    }

    public static function editCostumer($costumer)
    {
        $response = Costumer::where('uuid', $costumer['uuid'])->update($costumer);

        return $response;
    }
}
