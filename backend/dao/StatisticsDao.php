<?php
require_once 'BaseDao.php';

class StatisticsDao extends BaseDao {
    public function __construct() {
        parent::__construct("statistics");
    }

    public function getDashboardStats() {
        $stats = [
            'reservations' => $this->getReservationsCount(),
            'users' => $this->getUsersCount(),
            'revenue' => $this->getTotalRevenue(),
            'availableSpots' => $this->getAvailableSpots()
        ];
        return $stats;
    }

    private function getReservationsCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM parkingreservations");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getUsersCount() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalRevenue() {
        $stmt = $this->connection->prepare("
            SELECT SUM(pr.duration * z.zonePrice) as total 
            FROM parkingreservations pr
            JOIN zones z ON pr.zone = z.id
        ");
        $stmt->execute();
        return number_format($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0, 2);
    }

    private function getAvailableSpots() {
        $stmt = $this->connection->prepare("
            SELECT 
                (SELECT SUM(zoneCapacity) FROM zones) - 
                (SELECT COUNT(*) FROM parkingreservations WHERE DATE(dateAndTime) = CURDATE()) 
            as available
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['available'];
    }
}
?>
