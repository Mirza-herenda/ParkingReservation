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
    // Primer iz flight funkcije

   

/**
 * @OA\Get(
 *     path="/parkingreservations/details/full",
 *     tags={"Reservations"},
 *     summary="Get detailed list of reservations",
 *     description="Returns a detailed list of all reservations with user, zone, and message data.",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="object")
 *         )
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/parkingreservations/user/{user_id}",
 *     tags={"Reservations"},
 *     summary="Get reservations by user ID",
 *     description="Returns all parking reservations that belong to the specified user.",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reservations for the user",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User or reservations not found"
 *     )
 * )
 */
Flight::route('GET /user/@user_id', function($user_id) {
    try {
        $reservations = Flight::parking_reservation_service()->get_reservations_by_user_id($user_id);
        Flight::json(['success' => true, 'data' => $reservations]);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'error' => $e->getMessage()], 404);
    }
});

Flight::route('GET /details/full', function() {
    try {
        $data = Flight::parking_reservation_service()->getAllWithFullDetails();
        Flight::json(['success' => true, 'data' => $data]);
    } catch (Exception $e) {
        Flight::json(['success' => false, 'error' => $e->getMessage()]);
    }
});

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
    Flight::route('POST /create', function() {
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

    /**
     * @OA\Delete(
     *     path="/parkingreservations/user/{user_id}",
     *     tags={"Reservations"},
     *     summary="Delete all reservations for a user",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Number of deleted reservations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="deletedCount", type="integer")
     *         )
     *     )
     * )
     */
    Flight::route('DELETE /user/@user_id', function($user_id) {
        try {
            $count = Flight::parking_reservation_service()->deleteReservationsByUserId($user_id);
            Flight::json(['deletedCount' => $count]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

});
?>