<?php
require_once 'BaseDao.php';

class ReservationDao extends BaseDao {
    public function __construct() {
        parent::__construct("parkingreservations"); // Assuming the table name is 'reservations'
    }

    // Get a reservation by ID
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM parkingreservations WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get all parkingreservations
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM parkingreservations");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Insert a new reservation
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO parkingreservations ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
        return $this->connection->lastInsertId(); // Return the ID of the inserted reservation
    }

    
    // Update an existing reservation
 
    public function update($entity, $id, $id_column = "id") {
        $fields = "";
        foreach ($entity as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $sql = "UPDATE parking_reservations SET $fields WHERE $id_column = :id";
        $stmt = $this->connection->prepare($sql);
        $entity['id'] = $id;
        return $stmt->execute($entity);
    }
    // Delete a reservation by ID
    public function delete($id) {
        // Check if the reservation exists
        $reservation = $this->getById($id);
        if (!$reservation) {
            throw new Exception("Reservation with ID $id does not exist.");
        }
    
        // Attempt to delete the reservation
        $stmt = $this->connection->prepare("DELETE FROM parkingreservations WHERE id = :id");
        $stmt->bindParam(':id', $id);
    
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete reservation with ID: $id");
        }
    
        return true;
    }
}
?>