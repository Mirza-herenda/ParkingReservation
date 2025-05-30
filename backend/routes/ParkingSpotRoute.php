<?php
/**
 * @OA\Tag(
 *     name="Reservations",
 *     description="API Endpoints for managing parking reservations"
 * )
 */

Flight::group('/parkingspots', function() {

    // GET all parking spots
    Flight::route('GET /', function() {
        Flight::json(Flight::parking_spot_service()->get_all());
    });

    // GET one parking spot by ID
    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::parking_spot_service()->get_by_id($id));
    });

    // POST create new parking spot
  Flight::route('POST /create', function() {
    $data = Flight::request()->data->getData();
    $newId = Flight::parking_spot_service()->create_parking_spot($data);
    // Pretpostavimo da insert vraća ID novog reda:
    $newSpot = Flight::parking_spot_service()->get_by_id($newId);
    Flight::json([
        'success' => true,
        'data' => $newSpot
    ], 201);
});


    // PUT update parking spot
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::parking_spot_service()->update_parking_spot($id, $data));
    });

    // DELETE parking spot
    Flight::route('DELETE /@id', function($id) {
        Flight::parking_spot_service()->delete_parking_spot($id);
        Flight::json(['message' => 'Parking spot deleted successfully']);
    });
});

?>