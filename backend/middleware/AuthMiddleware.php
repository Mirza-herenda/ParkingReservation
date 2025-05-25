<?php
require_once __DIR__ . '/../dao/config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private $key;

    public function __construct() {
        $this->key = Config::JWT_SECRET();
    }

    public function verifyToken($token) {
        if (!$token) {
            throw new Exception("Token nije dostavljen.");
        }

        $token = str_replace("Bearer ", "", $token);

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            throw new Exception("Nevalidan token: " . $e->getMessage());
        }
    }

    public function generateToken($user) {
        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role'],
            "exp" => time() + 3600 // token važi 1h
        ];
        return JWT::encode($payload, $this->key, 'HS256');
    }
}
