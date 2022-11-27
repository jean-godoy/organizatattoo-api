<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\User;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "message" => "success"
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'studio_name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            'is_admin' => 'required|boolean',
            'name' => 'required|string'
        ]);

        $studio = Studio::create([
            'uuid' => md5(uniqid(rand() . "", true)),
            'studio_name' => $request->studio_name,
            'studio_is_active' => true
        ]);



        $user = User::create([
            'uuid' => md5(uniqid(rand() . "", true)),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rules' => "admin",
            'studio_uuid' => $studio->uuid,
            'is_active' => 1
        ]);

        return response()->json([
            'response' => $studio,
            'data' => $user
        ]);
    }

    /**
     * Método que verifica as credenciais,
     * válida e gera o token.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $fielsd = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $response = GenerateToken::login($fielsd['email'], $fielsd['password']);
        return $response;
    }

    /**
     * Método que verifica se o tokne é válido.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isValid(Request $request)
    {
        $token = $request->bearerToken();
        $is_valid = GenerateToken::checkAuth($token);


        if ($is_valid) {
            return response()->json([
                "message" => "token is valid",
                "status" => true
            ], 200);
        }

        return  response()->json([
            "message" => "token is not valid",
            "status" => false
        ], 401);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
