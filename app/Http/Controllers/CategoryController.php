<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\SubCategory;
use App\Services\CategoryService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = request()->bearerToken();

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $studio_uuid = $user['studio_uuid'];

        $studio = Studio::where('uuid', $studio_uuid)->first();

        $category = $studio->categories()->get(['id', 'category'])->toArray() ?? null;

        return response()->json([
            "status" => true,
            "message" => "Lista de categorias.",
            "data" => $category
        ]);
    }

    public function checkCategory($category)
    {
        $token = request()->bearerToken();

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $studio_uuid = $user['studio_uuid'];

        $response = CategoryService::checkCategory($studio_uuid, $category);
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

        $fieldset = $request->validate([
            'category' => 'required|string'
        ]);

        $email = UserService::getUserEmailByToken($token);
        $user = UserService::getUserDataByEmail($email);

        $studio_uuid = $user['studio_uuid'];

        $checkCategory = CategoryService::checkCategory($studio_uuid, $fieldset['category']);

        if ($checkCategory) {
            return response()->json([
                "status" => false,
                "replicated" => true,
                "message" => "Categoria já existente.",
                "data" => $checkCategory
            ]);
        }

        $response = CategoryService::registerCategory($fieldset, $studio_uuid);

        return response()->json([
            "status" => true,
            "message" => "Categoria criada com sucesso.",
            "data" => $response
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

    public function storeSubCategory(Request $request)
    {
        $token = request()->bearerToken();

        $fieldset = $request->validate([
            'sub_category' => 'required|string',
            'category_id' => 'required|string'
        ]);

        $checkSubCategory = CategoryService::checkSubCategory($fieldset['category_id'], $fieldset['sub_category']);

        if($checkSubCategory) {
            return response()->json([
                "status" => false,
                "replicated" => true,
                "message" => "Sub Categoria já existente.",
                "data" => $checkSubCategory
            ]);
        }

        $response = CategoryService::registerSubCategory($fieldset);

        if($response) {
            return response()->json([
                "status" => true,
                "messege" => "Sub categoria registrada com sucesso.",
                "data" => $response
            ]);
        }
    }

    public function getSubCategory($id)
    {
        $token = request()->bearerToken();

        if(!$id) {
            return response()->json([
                "status" => false,
                "message" => "Paramentro ID é obrigatŕio.",
                "data" => null
            ]);
        }

        $sub_category = SubCategory::where('category_id', $id)->get()->toArray() ?? null;

        if($sub_category) {
            return response()->json([
                "status" => true,
                "message" => "Lista das sub categorias.",
                "data" => $sub_category
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Nenhuma sub categoria corresponde ao ID.",
            "data" => []
        ]);
    }
}
