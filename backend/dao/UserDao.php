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
    
    try {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return $this->connection->lastInsertId();
    } catch (PDOException $e) {
        error_log("Add user failed: " . $e->getMessage());
        return false;
    }
}


    public function get_user_by_email($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
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
public function update_user($id, $data) {
    if (empty($data)) return null;

    $fields = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key = :$key";
    }

    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE user_id = :user_id";

    $stmt = $this->connection->prepare($sql);
    $data['user_id'] = $id;

    $success = $stmt->execute($data);

    if ($success && $stmt->rowCount() > 0) {
        return $this->get_user_by_id($id); // vraćamo ažurirane podatke
    } else {
        return null; // ništa nije ažurirano
    }
}


    public function get_all() {
        $query = "SELECT * FROM users";
        return $this->query($query, []);
    }
}
?>