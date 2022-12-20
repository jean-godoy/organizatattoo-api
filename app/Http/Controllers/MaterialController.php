<?php

namespace App\Http\Controllers;

use App\Services\MaterialService;
use App\Services\UserService;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    private $user;
    private $studio;

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request)
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
            'product_name' => 'required|string'
        ]);

        $check_product = MaterialService::checkMaterialProduct($user['studio_uuid'], $fieldset['product_name']);

        if ($check_product) {
            return response()->json([
                "status" => false,
                "code" => 200,
                "replicated" => true,
                "message" => "Produto {$fieldset['product_name']} já cadastrado",
                "data" => $check_product
            ]);
        }

        if (!$check_product) {
            $reponse = MaterialService::productStore($user['studio_uuid'], $fieldset['product_name']);

            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Produto cadastrado com sucesso.",
                "data" => $reponse
            ]);
        }
    }

    public function  getAllProducts()
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

        $reponse = MaterialService::getAllProducts($user['studio_uuid']);

        if ($reponse) {
            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Lista de productos cadastrados.",
                "data" => $reponse
            ]);
        }

        return response()->json([
            "status" => false,
            "code" => 202,
            "message" => "Nenhum produto cadastrado.",
            "data" => []
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
        //
    }

    public function getBrandByProductId($id)
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

        $reponse = MaterialService::getBrandByProductId($user['studio_uuid'], $id);

        if ($reponse) {
            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Lista das marcas relacionadas ao produto.",
                "data" => $reponse
            ]);
        }

        return response()->json([
            "status" => false,
            "code" => 200,
            "message" => "Nenhuma marca registrada.",
            "data" => []
        ]);
    }

    public function addBrand(Request $request)
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
            'product_id' => 'required|string',
            'product_brand' => 'required|string',
        ]);

        $check_brand = MaterialService::checkBrand($user['studio_uuid'], $fieldset['product_brand']);

        if ($check_brand) {
            return response()->json([
                "status" => false,
                "code" => 200,
                "replicated" => true,
                "message" => "Marca {$fieldset['product_brand']} já cadastrada.",
                "data" => $check_brand
            ]);
        }

        $reponse = MaterialService::brandStore($user['studio_uuid'], $fieldset);

        if ($reponse) {
            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Marca registrada com sucesso",
                "data" => $reponse
            ]);
        }

        return response()->json([
            "status" => false,
            "code" => 200,
            "message" => "Erro ao registrar marca, tente novamente mais tarde.",
            "data" => []
        ]);
    }

    public function addCategory(Request $request)
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
            'material_product_id' => 'required|string',
            'brand_id' => 'required|string',
            'material_category' => 'required|string'
        ]);

        $check_category = MaterialService::checkCategory($user['studio_uuid'], $fieldset);

        if($check_category) {
            return response()->json([
                "status" => false,
                "code" => 200,
                "replicated" => true,
                "message" => "Marca {$fieldset['material_category']} já cadastrada.",
                "data" => $check_category
            ]);
        }

        if(!$check_category){
            $reponse = MaterialService::categoryStore($fieldset);

            if($reponse) {
                return response()->json([
                    "status" => true,
                    "code" => 200,
                    "message" => "Categoria salva com sucesso.",
                    "data" => $reponse
                ]);
            }

            return response()->json([
                "status" => false,
                "code" => 200,
                "message" => "Ocorreu algum erro para salvar categoria",
                "data" => []
            ]);
        }

    }

    public function getCategoryByBrandId($id)
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

        if(!$id) {
            return response()->json([
                "status" => false,
                "code" => 500,
                "message" => "Parametro obrigatório, ID",
                "data" => []
            ]);
        }

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $categories = MaterialService::getCategoryByBrandId($id);

        if($categories) {
            return response()->json([
                "status" => true,
                "code" => 200,
                "message" => "Lista de categorias",
                "data" => $categories
            ]);
        }

        return response()->json([
            "status" => false,
            "code" => 200,
            "message" => "Nenhuma categoria registrada.",
            "data" => []
        ]);
    }
}
