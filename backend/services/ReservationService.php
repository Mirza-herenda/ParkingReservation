<?php


require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/ReservationDao.php";

class ReservationService extends BaseService {
    public function __construct() {
        parent::__construct(new ReservationDao());
    }

    public function create_reservation($data) {
        if (empty($data['user_id']) || empty($data['parkingSpot_id']) || empty($data['dateAndTime'])) {
            throw new Exception("Missing required fields: user_id, parkingSpot_id, or dateAndTime.");
        }
        return $this->dao->insert($data);
    }
    

    public function get_reservation_by_id($id) {
        if (empty($id)) {
            throw new Exception("Reservation ID is required.");
        }
        return $this->dao->getById($id);
    }
  public function get_reservations_by_user_id($user_id) {
    if (empty($user_id)) {
        throw new Exception("User ID is required.");
    }
    return $this->dao->get_by_user_id($user_id);
}


    public function update_reservation($id, $data) {
        if (empty($id)) {
            throw new Exception("Reservation ID is required.");
        }
        if (empty($data)) {
            throw new Exception("No data provided for update.");
        }
        return $this->dao->update($id, $data);
    }

    public function delete_reservation($id) {
        if (empty($id)) {
            throw new Exception("Reservation ID is required.");
        }
        return $this->dao->delete($id);
    }

      public function deleteReservationsByUserId($user_id) {
        if (empty($user_id)) {
            throw new Exception("User ID is required.");
        }
        $deleted = $this->dao->deleteByUserId($user_id);
        return $deleted;
    }

    public function getAllWithFullDetails() {
    $query = "SELECT 
                pr.id, 
                u.name, 
                u.surname, 
                u.licencePlate, 
                pr.duration, 
                DATE_FORMAT(pr.dateAndTime, '%d.%m.%Y %H:%i') AS startTime,
                z.zonePrice AS zone_price,
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
              ORDER BY pr.dateAndTime DESC";

    return $this->dao->query($query, []); // Ovo izvršava SQL koji si napisao
}


}
?>