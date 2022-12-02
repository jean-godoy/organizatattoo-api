<?php

namespace App\Http\Controllers;

use App\Models\BankData;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\ProfessionalAddress;
use App\Services\ProfessionalService;
use App\Services\UserService;
use App\Util\JWT\GenerateToken;
use App\Util\JWT\TokenValidate;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
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

        $response = ProfessionalService::all($user['studio_uuid']);

        if ($response) {
            return response()->json([
                "status" => true,
                "message" => "Lista de Profissionais cadastrados",
                "data" => $response
            ]);
        }
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

        $fieldset = $request->validate([
            'studio_id' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'cell_phone' => 'required|numeric',
            'phone' => 'required|numeric',
            'birth_date' => 'required|date',
            'cpf' => 'required|numeric',
            'cnpj' => 'numeric',
            'sex' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|numeric',
            'district' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|numeric',
            'uf' => 'required|string',
            'payment' => 'required|string',
            'bank' => 'required|string',
            'agency' => 'required|numeric',
            'account' => 'required|numeric',
            'pix' => 'required|numeric',
            'is_active' => 'required|boolean',
            'rules' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $res = ProfessionalService::register($fieldset);

        if ($res) {
            return response()->json([
                "status" => true,
                "message" => "Profissional cadastrado com sucesso",
                "data" => $res
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Erro ao cadastrar profissional",
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

    public function getProfessionalData($id)
    {
    }

    public function getProfessionalAddress($id)
    {
        $token = request()->bearerToken();

        $permission = TokenValidate::chekAndValidateToken($token);

        if ($permission) {
            $address = ProfessionalAddress::where('professional_id', $id)->get();
            dd($address->toArray());
        }
    }

    public function getProfessionalBankData($id)
    {
    }

    public function getProfessionalPayment($id)
    {
    }


    public function getProfessionalFullData($id)
    {
        $token = request()->bearerToken();
        $permission = TokenValidate::chekAndValidateToken($token);

        if ($permission) {

            $professional = Professional::where('id', $id)->get()->toArray() ?? null;
            if ($professional) {
                $address = ProfessionalAddress::where('professional_id', $id)->get()->toArray();
                $bank = BankData::where('professional_id', $id)->get()->toArray();
                $payment = Payment::where('professional_id', $id)->get()->toArray();

                return response()->json([
                    "status" => true,
                    "message" => "Dados completos do profissonal para edição.",
                    "data" => [
                        "address" => $address,
                        "bank" => $bank,
                        "payment" => $payment
                    ]
                ]);
            }

            return response()->json([
                "status" => false,
                "message" => "Nenhum profissional encontrado.",
                "data" => []
            ]);
        }
    }
}
