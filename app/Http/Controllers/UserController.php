<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\User;
use App\Services\StudioService;
use App\Services\UserService;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token= request()->bearerToken();

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $users = StudioService::getUsersByStudio($user['studio_uuid']);

        return response()->json([
            "status" => true,
            "message" => "Lista de Usuaŕios referente ao studio",
            "data" => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userService = new UserService();

        $token = $request->bearerToken();
        $is_valid = GenerateToken::checkAuth($token);

        if(!$is_valid) {
            return response()->json([
                "status" => false,
                "message" => "Token expirado, Por favor faça login novamente!",
                "data" => null
            ]);
        }

        $fildset = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            'rules' => 'required|string',
            'is_active' => 'boolean|required',
            'studio_uuid' => 'required|string'
        ]);

        $user = $userService->registerUser($fildset);

        return response()->json([
            "status" => true,
            "message" => "Usuário adicionado com sucesso.",
            "data" => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fieldset = $request->validate([
            'uuid' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            // 'password' => 'required|password',
            'rules' => 'required|string',
            'is_active' => 'required|boolean'
        ]);


        $user = User::where('uuid', $fieldset['uuid'])->update($fieldset);

        if($user) {
            return response()->json([
                "status" => true,
                "message" => "Usuário atualizado com sucesso.",
                "data" => $user
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Erro ao atualizar usuário.",
            "data" => $user
        ]);
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

    public function getLoginInfo($email)
    {
        $user = User::where('email', $email)->first()->toArray();
        $studio_uuid = $user['studio_uuid'];

        $studio = Studio::where('uuid', $studio_uuid)->first()->toArray();

        return response()->json([
            "status" => true,
            "message" => "Dados de login",
            "data" => [
                "user_uuid" => $user['uuid'],
                "studio_uuid" => $user['studio_uuid'],
                "studio_name" => $studio['studio_name'],
                "name" => $user['name'],
                "email" => $user['email'],
                "rules" => $user['rules']
            ]
        ], 200);
    }
}
