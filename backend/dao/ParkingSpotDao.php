<?php
require_once 'BaseDao.php';

class parkingspotDao extends BaseDao {
    public function __construct() {
        parent::__construct("parkingspot"); // Assuming the table name is 'reservations'
    }

    // Get a reservation by ID
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM parkingspot WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $this->dao->getById($id);
    }

    // Get all parkingspot
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM parkingspot");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Insert a new reservation
public function insert($data) {
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO parkingspot ($columns) VALUES ($placeholders)";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($data);
    return $this->connection->lastInsertId();  // << OVO DODAJ
}


    // Update an existing reservation
    public function update($entity, $id, $id_column = "id") {
        $fields = "";
        foreach ($entity as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $sql = "UPDATE parkingspot SET $fields WHERE $id_column = :id";
        $stmt = $this->connection->prepare($sql);
        $entity['id'] = $id;
        return $stmt->execute($entity);
    }

    // Delete a reservation by ID
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM parkingspot WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>