<?php
<<<<<<< HEAD
require 'vendor/autoload.php'; // Run autoloader
=======
require 'vendor/autoload.php';
Flight::before('start', function(&$params, &$output){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Authentication");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
});
header("Access-Control-Allow-Origin: *"); // ili specificirano ako treba
header("Access-Control-Allow-Headers: Content-Type, Authentication");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");



>>>>>>> c6c98a4 (crud operations for milestone 4,adding auth,and autorization)

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
<<<<<<< HEAD
=======
require_once __DIR__ . '/backend/middleware/AuthMiddleware.php';
require_once __DIR__ . '/backend/routes/AuthRoutes.php';
require_once __DIR__ . '/backend/services/AuthService.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
Flight::route('OPTIONS *', function() {
    // Samo vrati CORS headere — browseru je to dovoljno
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Authentication");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    exit(0);
});
Flight::route('GET /', function() {
    echo "Parking Reservation API radi.";
});
Flight::register('auth_middleware', 'AuthMiddleware');
Flight::register('auth_service', 'AuthService');

Flight::map('error', function(Throwable $ex){
    Flight::json(['success' => false, 'message' => $ex->getMessage()]);
});
>>>>>>> c6c98a4 (crud operations for milestone 4,adding auth,and autorization)

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