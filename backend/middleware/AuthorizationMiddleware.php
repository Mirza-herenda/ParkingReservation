<?php
class AuthorizationMiddleware {
    public static function authorizeAdmin() {
        return function() {
            $headers = getallheaders();
            $user = Flight::get('user');
            
            if (!$user || $user['role'] !== 'admin') {
                Flight::json(['error' => 'Unauthorized - Admin access required'], 403);
                return false;
            }
            return true;
        };
    }

    public static function authorizeUser() {
        return function() {
            $headers = getallheaders();
            $user = Flight::get('user');
            
            if (!$user) {
                Flight::json(['error' => 'Unauthorized - Login required'], 401);
                return false;
            }
            return true;
        };
    }
}
?>
