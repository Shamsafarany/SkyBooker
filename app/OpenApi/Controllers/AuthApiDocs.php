<?php

namespace App\OpenApi\Controllers;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Auth',
    description: 'Authentication API Endpoints'
)]
class AuthApiDocs
{
    #[OA\Post(
        path: '/api/v1/register',
        tags: ['Auth'],
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'first_name','last_name','email','username','password'
                ],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'username', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                    new OA\Property(property: 'date_of_birth', type: 'string', nullable: true),
                    new OA\Property(property: 'password', type: 'string'),
                    new OA\Property(property: 'role', type: 'string', example: 'passenger'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'User registered successfully'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public static function register() {}

    #[OA\Post(
        path: '/api/v1/login',
        tags: ['Auth'],
        summary: 'Login a user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email','password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'User logged in successfully'),
            new OA\Response(response: 401, description: 'Invalid credentials')
        ]
    )]
    public static function login() {}

    #[OA\Post(
        path: '/api/v1/logout',
        tags: ['Auth'],
        summary: 'Logout the authenticated user',
        security: [
            new OA\SecurityScheme(
                securityScheme: 'sanctum',
                type: 'apiKey',
                name: 'Authorization',
                in: 'header'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Logged out successfully')
        ]
    )]
    public static function logout() {}

    #[OA\Get(
        path: '/api/v1/user',
        tags: ['Auth'],
        summary: 'Get authenticated user',
        security: [
            new OA\SecurityScheme(
                securityScheme: 'sanctum',
                type: 'apiKey',
                name: 'Authorization',
                in: 'header'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Authenticated user retrieved')
        ]
    )]
    public static function user() {}
}
