<?php

require_once "UserService.php";
require_once "MessagesService.php";
require_once "ParkingSpotService.php";
require_once "ReservationService.php";
require_once "ZoneService.php";

$userService = new UserService();
$messagesService = new MessagesService();
$parkingSpotService = new ParkingSpotService();
$reservationService = new ReservationService();
$zoneService = new ZoneService();

// Prepopulate Data for Testing
function prepopulate_data($userService, $zoneService, $parkingSpotService) {
    try {
        // Create a user
        $userId = $userService->register_user([
            'email' => 'testuser17@example.com',
            'password' => 'password123',
            'name' => 'Test User'
        ]);

        // Create a zone
        $zoneId = $zoneService->create_zone([
            'ZoneName' => 'Zone A',
            'zoneCapacity' => 50,
            'zonePrice' => 10
        ]);

        // Create a parking spot using the created zone ID
        $parkingSpotId = $parkingSpotService->create_parking_spot([
            'zona' => 23, // Use the valid zone ID
            'status' => 'available'
        ]);
        $reservationId = $reservationService->create_reservation([
            'user_id' => 18,
            'parkingSpot_id' => 8,
            'dateAndTime' => '2025-04-01 10:00:00',
            'zone' => 23,
            'location' => 'Radnicka4',
            'duration' => 3,
            'price' => 5
        ]);
        echo "Prepopulated data: User ID = $userId, Zone ID = $zoneId, Parking Spot ID = $parkingSpotId\n";
        return [$userId, $zoneId, $parkingSpotId, $reservationId];
    } catch (Exception $e) {
        echo "Prepopulation failed: " . $e->getMessage() . "\n";
        return [null, null, null];
    }
}

// User Testing Functions
function test_create_user($userService) {
    try {
        $userId = $userService->register_user([
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'name' => 'New User'
        ]);
        echo "User created successfully! ID: $userId\n";
        return $userId;
    } catch (Exception $e) {
        echo "Create user failed: " . $e->getMessage() . "\n";
        return null;
    }
}

function test_update_user($userService, $userId) {
    try {
        $userService->update($userId, [
            'name' => 'Updated User'
        ]);
        echo "User updated successfully!\n";
    } catch (Exception $e) {
        echo "Update user failed: " . $e->getMessage() . "\n";
    }
}

function test_delete_user($userService, $userId) {
    try {
        $userService->delete($userId);
        echo "User deleted successfully!\n";
    } catch (Exception $e) {
        echo "Delete user failed: " . $e->getMessage() . "\n";
    }
}

// Messages Testing Functions
function test_create_message($messagesService, $userId) {
    try {
        $messageId = $messagesService->create_message([
            'user_id' => $userId,
            'emailAdress' => 'test@example.com',
            'title' => 'Test Message',
            'message' => 'This is a test message.'
        ]);
        echo "Message created successfully! ID: $messageId\n";
        return $messageId;
    } catch (Exception $e) {
        echo "Create message failed: " . $e->getMessage() . "\n";
        return null;
    }
}

// Parking Spot Testing Functions
function test_create_parking_spot($parkingSpotService, $zoneId) {
    try {
        $parkingSpotId = $parkingSpotService->create_parking_spot([
            'zona' => $zoneId,
            'status' => 'available'
        ]);
        echo "Parking spot created successfully! ID: $parkingSpotId\n";
        return $parkingSpotId;
    } catch (Exception $e) {
        echo "Create parking spot failed: " . $e->getMessage() . "\n";
        return null;
    }
}

// Reservation Testing Functions
function test_create_reservation($reservationService, $userId, $parkingSpotId, $zoneId) {
    try {
        $reservationId = $reservationService->create_reservation([
            'user_id' => $userId,
            'parkingSpot_id' => $parkingSpotId,
            'dateAndTime' => '2025-04-01 10:00:00',
            'zone' => $zoneId,
            'location' => 'Radnicka4',
            'duration' => 3,
            'price' => 5
        ]);
        echo "Reservation created successfully! ID: $reservationId\n";
        return $reservationId;
    } catch (Exception $e) {
        echo "Create reservation failed: " . $e->getMessage() . "\n";
        return null;
    }
}

// Zone Testing Functions
function test_create_zone($zoneService) {
    try {
        $zoneId = $zoneService->create_zone([
            'ZoneName' => 'Zone B',
            'zoneCapacity' => 100,
            'zonePrice' => 15
        ]);
        echo "Zone created successfully! ID: $zoneId\n";
        return $zoneId;
    } catch (Exception $e) {
        echo "Create zone failed: " . $e->getMessage() . "\n";
        return null;
    }
}

// Main Testing Flow
try {
    // Prepopulate required data
    list($userId, $zoneId, $parkingSpotId) = prepopulate_data($userService, $zoneService, $parkingSpotService);

    if ($userId && $zoneId && $parkingSpotId) {
        // Test Messages
        $messageId = test_create_message($messagesService, $userId);

        // Test Parking Spot
        $newParkingSpotId = test_create_parking_spot($parkingSpotService, $zoneId);

        // Test Reservation
        $reservationId = test_create_reservation($reservationService, $userId, $parkingSpotId, $zoneId);

        // Test Zone
        $newZoneId = test_create_zone($zoneService);
    }
} catch (Exception $e) {
    echo "Operation failed: " . $e->getMessage() . "\n";
}

?>