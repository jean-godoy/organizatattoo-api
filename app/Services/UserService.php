<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function registerUser($user)
    {
        $studio = Studio::where('uuid', $user['studio_uuid'])->first();

        $studio->users()->create([
            'uuid' => md5(uniqid(rand() . "", true)),
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'rules' => $user['rules'],
            'is_active' => $user['is_active'],
        ]);

        return $studio->toArray();
    }

    public static function getUserEmailByToken($token)
    {
        $token = explode('.', $token);
        $header = $token[0];
        $payload = $token[1];
        $sign = $token[2];

         //Pega o payload e aplica base64_decode.
         $payloadDecode = base64_decode($token[1]);
         //Aplica o json_decode.
         $payload_data = json_decode($payloadDecode, true);

         $user_email = $payload_data['email'];

        return $user_email;
    }

    public static function getUserDataByEmail($email)
    {
        $user = User::where("email" , $email)->first()->toArray();
        return $user;
    }
}
