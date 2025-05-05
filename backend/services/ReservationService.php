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
}
?>