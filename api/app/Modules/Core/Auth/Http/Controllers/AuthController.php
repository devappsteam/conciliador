<?php

namespace App\Modules\Core\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Auth\Http\Requests\LoginRequest;
use App\Modules\Core\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->service->authenticate($request->validated());

            return response()->json([
                'message' => 'Autenticação realizada com sucesso.',
                'data'    => $user // Retornará o User configurado no futuro com as Policies
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors'  => $e->errors()
            ], 422);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request);

        return response()->json([
            'message' => 'Sessão encerrada com sucesso.'
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        // Retorna o usuário autenticado na sessão corrente
        return response()->json([
            'data' => $request->user()
        ], 200);
    }

}
