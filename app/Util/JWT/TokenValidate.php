<?php

namespace App\Util\JWT;

class TokenValidate
{

    public static function chekAndValidateToken($token)
    {
        $is_valid = GenerateToken::checkAuth($token);

        if (!$is_valid) {
            return response()->json([
                "status" => false,
                "code" => 401,
                "message" => "Token expirado, Por favor faça login novamente!",
                "data" => null
            ]);
        }

        if (!$token) {
            return response()->json([
                "status" => false,
                "code" => 401,
                "message" => "Necessário chave autenticada, faça login!",
                "data" => null
            ]);
        }

        return true;
    }

}
