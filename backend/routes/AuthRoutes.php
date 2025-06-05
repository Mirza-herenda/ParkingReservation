<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     security={
     *         {"ApiKey": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Add new user",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "email"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="some_password",
     *                     description="User password"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="demo@gmail.com",
     *                     description="User email"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User has been added."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route("POST /register", function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->register($data);
    
        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });
    
    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="Student data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="Student email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="Student password")
     *          )
     *      )
     * )
     */
  Flight::route('POST /login', function() {
    $data = Flight::request()->data->getData();
    $response = Flight::auth_service()->login($data);

    if ($response['success']) {
        // Generate JWT token with user role
        $token = JWT::encode([
            'user' => [
                'id' => $response['data']['id'],
                'role' => $response['data']['role'],
                'exp' => time() + 7200 // 2 hour expiration
            ]
        ], Config::JWT_SECRET(), 'HS256');

        Flight::json([
            'message' => 'User logged in successfully',
            'token' => $token,
            'data' => [
                'id' => $response['data']['id'],
                'name' => $response['data']['name'],
                'role' => $response['data']['role']
            ]
        ]);
    } else {
        Flight::json([
            'success' => false,
            'error' => $response['error']
        ], 401);
    }
});

});


?>