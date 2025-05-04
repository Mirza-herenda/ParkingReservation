<?php
require_once __DIR__ . '/config.php';

class BaseDao {
    protected $connection;
    protected $table;

    public function __construct($table) {
        $this->table = $table;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";port=" . Config::DB_PORT() . ";dbname=" . Config::DB_NAME(),
                Config::DB_USER(),
                Config::DB_PASSWORD()
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function query($query, $params = []) {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query_unique($query, $params = []) {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function get_all() {
        $query = "SELECT * FROM " . $this->table;
        return $this->query($query);
    }

    public function get_by_id($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        return $this->query_unique($query, ['id' => $id]);
    }

    public function add($entity) {
        $columns = implode(", ", array_keys($entity));
        $placeholders = ":" . implode(", :", array_keys($entity));
        $query = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        $entity['id'] = $this->connection->lastInsertId();
        return $entity;
    }

    public function update($entity, $id, $id_column = "id") {
        $query = "UPDATE " . $this->table . " SET ";
        foreach ($entity as $column => $value) {
            $query .= $column . "=:" . $column . ", ";
        }
        $query = rtrim($query, ", ");
        $query .= " WHERE $id_column = :id";
        $entity['id'] = $id;
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        return $entity;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['id' => $id]);
    }
}
?>