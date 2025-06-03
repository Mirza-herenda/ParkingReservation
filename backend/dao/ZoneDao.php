<?php
require_once 'BaseDao.php';

class ZoneDao extends BaseDao {
    public function __construct() {
        parent::__construct("zones");
    }

    public function getAvailableSpots($zoneId) {
        $sql = "SELECT 
                    z.zoneCapacity - COUNT(pr.id) as available
                FROM zones z
                LEFT JOIN parkingreservations pr ON pr.zone = z.id
                    AND DATE(pr.dateAndTime) = CURDATE()
                    AND DATE_ADD(pr.dateAndTime, INTERVAL pr.duration HOUR) > NOW()
                WHERE z.id = :zoneId
                GROUP BY z.id, z.zoneCapacity";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['zoneId' => $zoneId]);
        $result = $stmt->fetch();
        return $result ? $result['available'] : 0;
    }

    public function getAllZonesWithAvailability() {
        $sql = "SELECT 
                    z.*,
                    (z.zoneCapacity - COUNT(pr.id)) as availableSpots
                FROM zones z
                LEFT JOIN parkingreservations pr ON pr.zone = z.id
                    AND DATE(pr.dateAndTime) = CURDATE()
                    AND DATE_ADD(pr.dateAndTime, INTERVAL pr.duration HOUR) > NOW()
                GROUP BY z.id, z.zoneCapacity";
        
        return $this->query($sql);
    }
}
?>
