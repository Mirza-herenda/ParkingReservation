<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/UserDao.php"; // Corrected path to UserDao.php

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function register_user($data) {
        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            throw new Exception("Missing required fields: email, password, or name.");
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if (strlen($data['password']) < 6) {
            throw new Exception("Password must be at least 6 characters.");
        }
        return $this->dao->add($data);
    }

    public function login_user($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required.");
        }
        $user = $this->dao->get_user_by_email($email);
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password.");
        }
        return $user;
    }
}
?>