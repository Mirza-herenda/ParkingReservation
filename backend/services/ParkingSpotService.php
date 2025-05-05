<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/ParkingSpotDao.php";

class ParkingSpotService extends BaseService {
    public function __construct() {
        parent::__construct(new ParkingSpotDao());
    }

    public function create_parking_spot($data) {
        if (empty($data['zona']) || empty($data['status'])) {
            throw new Exception("Missing required fields: zona or status.");
        }
        return $this->dao->insert($data);
    }

    public function update_parking_spot($id, $data) {
        if (!is_array($data)) {
            throw new Exception("Invalid data format. Expected an array.");
        }
        return $this->dao->update($data, $id);
    }

    public function delete_parking_spot($id) {
        if (empty($id)) {
            throw new Exception("Parking spot ID is required.");
        }
        return $this->dao->delete($id);
    }
}
?>