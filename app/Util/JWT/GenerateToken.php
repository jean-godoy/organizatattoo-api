<?php

namespace App\Util\JWT;

use App\Models\Studio;
use App\Services\JwtService;
use DateTime;

class GenerateToken
{
    private static $key; //Application Key.
    private $jwtService;

    public function __construct()
    {
        self::$key = env('JWT_SECRET');
    }

    public static function login($email, $pass)
    {
        $jwt = new JwtService();
        $user = $jwt->getUserData($email, $pass);

        if(!$user['status']) {
            return [
                "status" => false,
                "message" => $user['message'],
                "data" => null
            ];
        }
        // dd($user['data']['studio_uuid']);
        $studio_uuid = $user['data']['studio_uuid'];

        $data = self::getStudio()->where('uuid', $studio_uuid)->first();

        if ($user) {
            //Header Token
            $header = [
                'typ' => 'JWT',
                'alg' => env('JWT_ALGO')
            ];

            $issuedAt = time();

            //Payload - Content
            $payload = [
                'email' => $user['data']['email'],
                'exp' => $issuedAt + 60,
                'roles' => 1,
                'isAdmin' => true
            ];

            //JSON
            $header = json_encode($header);
            $payload = json_encode($payload);

            //Base 64
            $header = self::base64UrlEncode($header);
            $payload = self::base64UrlEncode($payload);

            //Sign
            $sign = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $sign = self::base64UrlEncode($sign);

            //Token
            $token = $header . '.' . $payload . '.' . $sign;
            // dd($user)
            $response = [
                "status" => true,
                "message" => $user['message'],
                "data" => [
                    "studio" => $data,
                    "user" => $user['data'],
                    "token" => $token,
                    "type" => "Bearer"
                ]
            ];

            return json_decode(json_encode($response), true);
        }

        throw new \Exception('Não autenticado');
    }

    public static function checkAuth($token)
    {

        if (isset($token) && $token != null) {
            $current_date = date('Y-m-d H:i:s');
            // $bearer = explode(' ', $token);
            //$bearer[0] = 'bearer';
            //$bearer[1] = 'token jwt';

            // $token = explode('.', $bearer[1]);
            $token = explode('.', $token);
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];

            //Pega o payload e aplica base64_decode.
            $payloadDecode = base64_decode($token[1]);
            //Aplica o json_decode.
            $payload_data = json_decode($payloadDecode, true);
            //Pega o exp.
            $expired =  $payload_data['exp'];
            //Converte a data de expiraçao para fotmato 2022-11-16 00:02:12.
            $date_expired = date('d/m/Y H:i', $expired);

            $date_today = date('d/m/Y H:i');

            $date1 = strtotime($date_expired);
            $date2 = strtotime($date_today);

            $interval = ($date2 - $date1) / 60;

            $dt1 = DateTime::createFromFormat('d/m/Y H:i', "$date_expired");
            $dt2 = DateTime::createFromFormat('d/m/Y H:i', "$date_today");

            $diference = self::diff_real_minutes($dt1, $dt2);

            //Conferir Assinatura
            $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $valid = self::base64UrlEncode($valid);

            $is_valid = false;
            $is_expired =  false;

            //First IF, verify token is valid.
            if ($sign === $valid) {
                $is_valid = false;
            }
            //Check if the token has expired.
            if($diference >= 5000) {
                $is_expired = true;

            }
            // dd($diference);
            if ($is_valid || $is_expired) {
                return false;
            }

            return true;
        }
    }


    /*Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/
    private static function base64UrlEncode($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }




    private static function diff_real_minutes(DateTime $dt1, DateTime $dt2)
    {
        return abs($dt1->getTimestamp() - $dt2->getTimestamp()) / 60;
    }

    private static function getStudio()
    {   $studio = new Studio;
        return $studio;
    }
}
