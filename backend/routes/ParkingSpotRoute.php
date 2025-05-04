<?php
/**
 * @OA\Tag(
 *     name="Reservations",
 *     description="API Endpoints for managing parking reservations"
 * )
 */

Flight::group('/parkingreservations', function() {

    /**
     * @OA\Get(
     *     path="/parkingreservations",
     *     tags={"Reservations"},
     *     summary="Get all reservations",
     *     description="Retrieve a list of all parking reservations.",
     *     @OA\Response(
     *         response=200,
     *         description="A list of reservations.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))
     *     )
     * )
     */
    Flight::route('GET /', function() {
        Flight::json(Flight::parking_reservation_service()->get_all());
    });

    /**
     * @OA\Get(
     *     path="/parkingreservations/{id}",
     *     tags={"Reservations"},
     *     summary="Get a reservation by ID",
     *     description="Retrieve a specific parking reservation by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation details",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::parking_reservation_service()->get_reservation_by_id($id));
    });

    /**
     * @OA\Post(
     *     path="/parkingreservations",
     *     tags={"Reservations"},
     *     summary="Create a new reservation",
     *     description="Add a new parking reservation to the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservation created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::parking_reservation_service()->create_reservation($data));
    });

    /**
     * @OA\Put(
     *     path="/parkingreservations/{id}",
     *     tags={"Reservations"},
     *     summary="Update a reservation",
     *     description="Update the details of a specific parking reservation.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::parking_reservation_service()->update_reservation($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/parkingreservations/{id}",
     *     tags={"Reservations"},
     *     summary="Delete a reservation",
     *     description="Remove a specific parking reservation from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        Flight::parking_reservation_service()->delete_reservation($id);
        Flight::json(['message' => 'Reservation deleted successfully']);
    });
});
?>