<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JwtService
{
    public function getUserData(string $email, string $password)
    {
        $user = User::where('email', $email)->first() ?? null;
        if(!$user) {
            return [
                "status" => false,
                "message" => "E-mail oui senha incorretos, Por favor tente novamente.",
                "data" => null
            ];
        }

        $password = Hash::check($password, $user->password);

        if(!$password) {
            return [
                "status" => false,
                "message" => "E-mail oui senha incorretos, Por favor tente novamente..",
                "data" => null
            ];
         }

        $data = $user->toArray();
        return [
            "status" => true,
            "message" => "Usuário logado com sucesso.",
            "data" => $data
        ];
    }

    public function checkToken()
    {
        return [];
    }
}
