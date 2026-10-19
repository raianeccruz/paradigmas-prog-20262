<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(public UserService $userService)
    {}

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/users',
        summary: 'Lista os usuários',
        security: [['sanctum' => []]],
        tags: ['Usuários'],
        parameters: [
            new OA\Parameter(name: 'name', in: 'query', required: false, description: 'Filtra pelo nome (busca parcial)', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'email', in: 'query', required: false, description: 'Filtra pelo email (busca parcial)', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuários',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/User'))])
            ),
            new OA\Response(response: 401, ref: '#/components/responses/Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        return UserResource::collection($this->userService->getWithFilters($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/users',
        summary: 'Cadastra um usuário',
        security: [['sanctum' => []]],
        tags: ['Usuários'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', minLength: 3, maxLength: 100, example: 'Maria Silva'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@email.com'),
                    new OA\Property(property: 'password', type: 'string', minLength: 4, maxLength: 20, example: '1234'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Usuário criado', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/User')])),
            new OA\Response(response: 401, ref: '#/components/responses/Unauthenticated'),
            new OA\Response(response: 422, ref: '#/components/responses/ValidationError'),
        ]
    )]
    public function store(CreateUserRequest $request)
    {
        return new UserResource($this->userService->store($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/users/{id}',
        summary: 'Exibe um usuário',
        security: [['sanctum' => []]],
        tags: ['Usuários'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Usuário encontrado', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/User')])),
            new OA\Response(response: 401, ref: '#/components/responses/Unauthenticated'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound'),
        ]
    )]
    public function show(string $id)
    {
        return new UserResource($this->userService->show($id));
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/users/{id}',
        summary: 'Atualiza um usuário (todos os campos são opcionais)',
        security: [['sanctum' => []]],
        tags: ['Usuários'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', minLength: 3, maxLength: 100, example: 'Maria Silva'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@email.com'),
                    new OA\Property(property: 'password', type: 'string', minLength: 4, maxLength: 20, example: '1234'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Usuário atualizado', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/User')])),
            new OA\Response(response: 401, ref: '#/components/responses/Unauthenticated'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound'),
            new OA\Response(response: 422, ref: '#/components/responses/ValidationError'),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:100', 'min:3'],
            'email' => ['sometimes', 'email'],
            'password' => ['sometimes', 'min:4', 'max:20']
        ]);

        return new UserResource($this->userService->update($data, $id));
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/users/{id}',
        summary: 'Remove um usuário',
        security: [['sanctum' => []]],
        tags: ['Usuários'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Usuário deletado com sucesso!', content: new OA\MediaType(mediaType: 'text/plain', schema: new OA\Schema(type: 'string', example: 'Usuário deletado com sucesso!'))),
            new OA\Response(response: 401, ref: '#/components/responses/Unauthenticated'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound'),
        ]
    )]
    public function destroy(string $id)
    {
        $this->userService->destroy($id);

        return response('Usuário deletado com sucesso!');
    }
}
