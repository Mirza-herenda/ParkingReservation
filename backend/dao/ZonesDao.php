<?php
require_once 'BaseDao.php';

class zonesDao extends BaseDao {
    public function __construct() {
        parent::__construct("zones"); // Assuming the table name is 'reservations'
    }

    // Get a reservation by ID
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM zones WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get all zones
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM zones");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Insert a new reservation
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO zones ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    // Update an existing reservation
    public function update($entity, $id, $id_column = "id") {
        $fields = "";
        foreach ($entity as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $sql = "UPDATE zones SET $fields WHERE $id_column = :id";
        $stmt = $this->connection->prepare($sql);
        $entity['id'] = $id;
        return $stmt->execute($entity);
    }
    // Delete a reservation by ID
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM zones WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>