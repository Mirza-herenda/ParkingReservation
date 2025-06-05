<?php
require_once 'BaseDao.php';

class ReservationDao extends BaseDao {
    public function __construct() {
        parent::__construct("parkingreservations");
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM parkingreservations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_by_user_id($user_id) {
    $stmt = $this->connection->prepare("SELECT * FROM parkingreservations WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM parkingreservations");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getAllWithDetails() {
    $sql = "
        SELECT 
            pr.id, 
            u.name, 
            u.surname, 
            u.licencePlate, 
            pr.duration, 
            DATE_FORMAT(pr.dateAndTime, '%d.%m.%Y %H:%i') AS startTime,
            z.ZoneName AS zone,
            COALESCE(m.message, '') AS message
        FROM parkingreservations pr
        JOIN users u ON pr.user_id = u.id
        JOIN zones z ON pr.zone = z.id
        LEFT JOIN (
            SELECT user_id, GROUP_CONCAT(message SEPARATOR '; ') AS message
            FROM messages
            GROUP BY user_id
        ) m ON m.user_id = u.id
        ORDER BY pr.dateAndTime DESC
    ";
    
    $stmt = $this->connection->prepare($sql);
    if (!$stmt->execute()) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception("SQL execute error: " . $errorInfo[2]);
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($data === false) {
        throw new Exception("FetchAll failed");
    }
    return $data;
}




public function insert($data) {
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO parkingreservations ($columns) VALUES ($placeholders)";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($data);
    return $this->connection->lastInsertId(); // Add this line to return the ID
}

    public function update($entity, $id, $id_column = "id") {
        $fields = "";
        foreach ($entity as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $sql = "UPDATE parkingreservations SET $fields WHERE $id_column = :id";
        $stmt = $this->connection->prepare($sql);
        $entity['id'] = $id;
        return $stmt->execute($entity);
    }

    public function delete($id) {
        $reservation = $this->getById($id);
        if (!$reservation) {
            throw new Exception("Reservation with ID $id does not exist.");
        }

        $stmt = $this->connection->prepare("DELETE FROM parkingreservations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete reservation with ID: $id");
        }

        return true;
    }

    public function deleteByUserId($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM parkingreservations WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->rowCount(); // broj izbrisanih redova
    }
}

?>
