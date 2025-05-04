<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/ZonesDao.php";

class ZoneService extends BaseService {
    public function __construct() {
        parent::__construct(new ZonesDao());
    }

    public function create_zone($data) {
        if (empty($data['ZoneName']) || empty($data['zoneCapacity']) || empty($data['zonePrice'])) {
            throw new Exception("Missing required fields: ZoneName, zoneCapacity, or zonePrice.");
        }
        return $this->dao->insert($data);
    }

    public function update_zone($id, $data) {
        if (!is_array($data)) {
            throw new Exception("Invalid data format. Expected an array.");
        }
        return $this->dao->update($data, $id);
    }

    public function delete_zone($id) {
        if (empty($id)) {
            throw new Exception("Zone ID is required.");
        }
        return $this->dao->delete($id);
    }
}
?>