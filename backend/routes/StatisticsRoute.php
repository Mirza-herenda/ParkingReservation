<?php
// Simple direct route without groups
Flight::route('GET /api/statistics', function() {
    try {
        $stats = Flight::statistics_service()->getDashboardStats();
        Flight::json(['success' => true, 'data' => $stats]);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});
?>
