<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function add($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return $this->connection->lastInsertId(); // Return the ID of the inserted user
    }

    public function get_user_by_email($email) {
        $stmt = $this->connection->prepare("SELECT s* FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function get_users_by_role($role) {
        $stmt = $this->connection->prepare("SELECT user_id, username, email FROM users WHERE role = :role");
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt->fetchAll(); // Fetch all matching rows
    }

    public function get_user_by_id($user_id) {
        return $this->query_unique(
            "SELECT user_id, username, email, role
             FROM users 
             WHERE user_id = :user_id", 
            ["user_id" => $user_id]
        );
    }

    public function get_all() {
        $query = "SELECT * FROM users";
        return $this->query($query, []);
    }
}
?>