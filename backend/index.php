<?php
require_once __DIR__ . '/services/StatisticsService.php';
require_once __DIR__ . '/routes/StatisticsRoute.php';

// Register the statistics service
Flight::register('statistics_service', 'StatisticsService');
