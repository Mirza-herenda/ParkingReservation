<?php
require 'vendor/autoload.php'; // Run autoloader

// Corrected paths with directory separators
require_once __DIR__ . '/backend/routes/UserRoute.php';
require_once __DIR__ . '/backend/routes/MessagesRoute.php';
require_once __DIR__ . '/backend/routes/ParkingSpotRoute.php';
require_once __DIR__ . '/backend/routes/ReservationRoute.php';
require_once __DIR__ . '/backend/routes/ZoneRoute.php';
require_once __DIR__ . '/backend/services/UserService.php';
require_once __DIR__ . '/backend/services/ZoneService.php';
require_once __DIR__ . '/backend/services/MessagesService.php';
require_once __DIR__ . '/backend/services/ParkingSpotService.php';
require_once __DIR__ . '/backend/services/ReservationService.php';

// Map user_service to an instance of UserService
Flight::map('user_service', function() {
    return new UserService();
});

Flight::map('zone_service', function() {
    return new ZoneService();
});
Flight::map('messages_service', function() {
    return new MessagesService();
});
Flight::map('parking_spot_service', function() {
    return new ParkingSpotService();
});
Flight::map('parking_reservation_service', function() {
    return new ReservationService();
});


Flight::start();  // Start FlightPHP
?>