<?php
require 'vendor/autoload.php';

// Update CORS headers
Flight::before('start', function(&$params, &$output){
    // Get the requesting origin
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    
    // Allow specific origins (replace with your frontend URL)
    if ($origin === 'http://localhost:8000') {
        header("Access-Control-Allow-Origin: http://localhost:8000");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }

    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
});
header("Access-Control-Allow-Origin: *"); // ili specificirano ako treba
header("Access-Control-Allow-Headers: Content-Type, Authentication");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");




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
require_once __DIR__ . '/backend/middleware/AuthMiddleware.php';
require_once __DIR__ . '/backend/routes/AuthRoutes.php';
require_once __DIR__ . '/backend/services/AuthService.php';
require_once __DIR__ . '/backend/services/StatisticsService.php';



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

// Change this line from StatisticsService_service to statistics_service
Flight::register('statistics_service', 'StatisticsService');

// Add route include after service registration
require_once __DIR__ . '/backend/routes/StatisticsRoute.php';


Flight::start();  // Start FlightPHP
?>