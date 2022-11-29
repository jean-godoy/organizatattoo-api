<?php

namespace App\Services;

use App\Models\Professional;
use App\Models\Studio;

class ProfessionalService
{

    public static function all($studio_id)
    {
        $studio = Studio::where('uuid', $studio_id)->first();
        $professional = $studio->professionals()->get();
        return $professional->toarray();
    }

    public static function register($data)
    {

        if (is_array($data)) {

            $date = str_replace('/', '-', $data['birth_date']);
            $birth_date = date('Y-m-d', strtotime($date));

            $studio = Studio::where('uuid', $data['studio_id'])->first();

            $professional_id = md5(uniqid(rand() . "", true));

            $studio->professionals()->create([
                'id' => $professional_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'cell_phone' => $data['cell_phone'],
                'phone' => $data['phone'],
                'birth_date' => $birth_date,
                'cpf' => $data['cpf'],
                // 'cnpj' => $data['cnpj'],
                'sex' => $data['sex'],
                'rules' => $data['rules'],
                'is_active' => $data['is_active']
            ]);

            $professional = Professional::where('id', $professional_id)->first();

            $professional->address()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'address' => $data['address'],
                'number' => $data['number'],
                'district' => $data['district'],
                'city' => $data['city'],
                'cep' => $data['cep'],
                'uf' => $data['uf']
            ]);

            $professional->bank()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'bank' => $data['bank'],
                'agency' => $data['agency'],
                'account' => $data['account'],
                'pix' => $data['pix']
            ]);

            $professional->payment()->create([
                'id' => md5(uniqid(rand() . "", true)),
                'payment' => $data['payment']
            ]);

            return $professional;
        }
    }
}
