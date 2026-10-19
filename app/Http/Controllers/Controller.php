<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Paradigmas de Programação',
    description: 'Documentação da API. Para acessar as rotas protegidas, faça login em POST /login, copie o token retornado e clique em "Authorize".'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    description: 'Token obtido no endpoint POST /login'
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'O campo nome é obrigatório.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            example: ['name' => ['O campo nome é obrigatório.']],
            additionalProperties: new OA\AdditionalProperties(type: 'array', items: new OA\Items(type: 'string'))
        ),
    ]
)]
#[OA\Response(
    response: 'Unauthenticated',
    description: 'Token ausente ou inválido',
    content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
)]
#[OA\Response(
    response: 'NotFound',
    description: 'Registro não encontrado',
    content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
)]
#[OA\Response(
    response: 'ValidationError',
    description: 'Erro de validação',
    content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
)]
abstract class Controller
{
    //
}
