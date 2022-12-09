<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Studio;
use App\Services\CostumerService;
use App\Services\StudioService;
use App\Services\UserService;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token= request()->bearerToken();

        $is_valid = GenerateToken::checkAuth($token);

        if (!$is_valid) {
            return response()->json([
                "status" => false,
                "message" => "Token expirado, Por favor faça login novamente!",
                "data" => null
            ]);
        }

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Necessário chave autenticada, faça login!',
                'data' => null
            ]);
        }

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);
        $costumers = StudioService::getCostumersByStudio($user['studio_uuid']);

        return response()->json([
            "status" => true,
            "message" => "Relatótios dos clientes cadastrados",
            "data" => $costumers
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
        $token = $request->bearerToken();

        $is_valid = GenerateToken::checkAuth($token);

        if (!$is_valid) {
            return response()->json([
                "status" => false,
                "message" => "Token expirado, Por favor faça login novamente!",
                "data" => null
            ]);
        }

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Necessário chave autenticada, faça login!',
                'data' => null
            ]);
        }

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $fieldset =  $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'cpf' => 'required|numeric',
            'birth_date' => 'required|date',
            'sex' => 'required|string',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|numeric',
            'uf' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $fieldset['studio_uuid'] = $user['studio_uuid'];

        $respone = CostumerService::registerCostumer($fieldset);

        return response()->json([
            'status' => true,
            'message' => 'Cliente cadastrado com sucesso.',
            'data' => $respone
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
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'cpf' => 'required|numeric',
            'birth_date' => 'required|date',
            'sex' => 'required|string',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|numeric',
            'uf' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $costumer = CostumerService::editCostumer($fieldset);

        if($costumer) {
            return response()->json([
                "status" => true,
                "message" => "Cliente atualizado com sucesso.",
                "data" => $costumer
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Erro ao atualizaer o cliente.",
            "data" => null
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

    public function getCostumerSearch($search)
    {
        $costumers = Costumer::where('name', 'LIKE', "%$search%")->get(['uuid', 'name'])->toArray() ?? null;

        if($costumers) {
            return response()->json([
                "status" => true,
                "message" => "Resultado da pesquisa...",
                "data" => $costumers
            ]);
        }
    }
}
