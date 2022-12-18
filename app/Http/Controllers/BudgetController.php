<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Services\BudgetService;
use App\Services\UserService;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = request()->bearerToken();

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

        $response = BudgetService::getBudgetByStudioId($user['studio_uuid']);
        // dd($response['status']);
        if ($response['status']) {
            return response()->json([
                "status" => true,
                "message" => "Lista de agendamentos",
                "data" => $response
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Ocorreu algum erro ao buscar agendamentos",
            "data" => []
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
        $token = request()->bearerToken();

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

        $fieldset = $request->validate([
            // 'user_id' => 'required|string',
            'studio_id' => 'required|string',
            'costumer_id' => 'required|string',
            'name' => 'required|string',
            'costumer_name' => 'required|string',
            'professional_id' => 'required|string',
            'professional_name' => 'required|string',
            'type_service' => 'required|string',
            'style_service' => 'required|string',
            'body_region' => 'required|string',
            'project_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sessions' => 'required|string',
            'width' => 'numeric',
            'heigth' => 'numeric',
            'price' => 'required|string',
            'validated_at' => 'required|string',
            'note' => 'required|string',
        ]);

        $imageName = null;
        //Verifica se existe a imagem.
        if (array_key_exists('project_image', $fieldset)) {
            $imageName = time() . '.' . $fieldset['project_image']->extension();
            // $fieldset['project_image']->move(public_path('images/budget'), $imageName);
            $upload = $request->project_image->storeAs('public/budgets', $imageName);
            $imageName = "/storage/budgets/$imageName";
        }

        $i = Storage::url('budgets/1670630935.png');

        // dd($i);
        $response = BudgetService::registerBudget($fieldset, $user['uuid'], $imageName);

        if ($response) {
            return response()->json([
                "status" => true,
                "message" => "Orçamento criado com sucesso.",
                "data" => $response
            ]);
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $token = request()->bearerToken();

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

        $response = BudgetService::destroyBudget($user['studio_uuid'], $id);

        if ($response) {
            return response()->json([
                "status" => true,
                "code" => 204,
                "message" => "Orçamento deleteado com sucesso.",
                "data" => []
            ]);

            return response()->json([
                "status" => false,
                "code" => 501,
                "message" => "Ocorreu algum erro ao deletar orçamento.",
                "data" => []
            ]);
        }
    }

    public function getImage()
    {
        return Storage::url('budgets/1670718780.jpg');
    }

    public function search($costumer)
    {
        $token = request()->bearerToken();

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

        $response = BudgetService::searchBudget($user['studio_uuid'], $costumer);

        if ($response) {
            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Resultado da busca",
                "data" => $response
            ]);
        }

        if (!$response) {
            return response()->json([
                "status" => false,
                "code" => 400,
                "message" => "Ocoreu algum erro durante a busca.",
                "data" => []
            ]);
        }
    }
}
