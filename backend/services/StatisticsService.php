<?php
require_once __DIR__ . '/../dao/StatisticsDao.php';

class StatisticsService {
    private $dao;

    public function __construct() {
        $this->dao = new StatisticsDao();
    }

    public function getDashboardStats() {
        return $this->dao->getDashboardStats();
    }
}
?>
