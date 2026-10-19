<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Services\LoginService;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{
    public function __construct(private LoginService $loginService)
    {}

    #[OA\Post(
        path: '/login',
        summary: 'Autentica o usuário e retorna um token de acesso',
        tags: ['Autenticação'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@email.com'),
                    new OA\Property(property: 'password', type: 'string', example: '1234'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login realizado com sucesso',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                    new OA\Property(property: 'token', type: 'string', example: '1|abcdef123456...'),
                ])
            ),
            new OA\Response(
                response: 401,
                description: 'Email ou senha inválidos',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            ),
            new OA\Response(response: 422, ref: '#/components/responses/ValidationError'),
        ]
    )]
    public function login(LoginRequest $request)
    {
        return response()->json($this->loginService->login($request->validated()));
    }
}
