<?php
/**
 * @OA\Tag(
 *     name="Zones",
 *     description="API Endpoints for managing zones"
 * )
 */

Flight::group('/zones', function() {

    /**
     * @OA\Get(
     *     path="/zones",
     *     tags={"Zones"},
     *     summary="Get all zones",
     *     description="Retrieve a list of all zones.",
     *     @OA\Response(
     *         response=200,
     *         description="A list of zones.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Zone"))
     *     )
     * )
     */
    Flight::route('GET /', function() {
        Flight::json(Flight::zone_service()->get_all());
    });

    /**
     * @OA\Get(
     *     path="/zones/{id}",
     *     tags={"Zones"},
     *     summary="Get a zone by ID",
     *     description="Retrieve a specific zone by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the zone",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone details",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::zone_service()->get_by_id($id));
    });

    /**
     * @OA\Post(
     *     path="/zones",
     *     tags={"Zones"},
     *     summary="Create a new zone",
     *     description="Add a new zone to the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Zone created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::zone_service()->create_zone($data));
    });

    /**
     * @OA\Put(
     *     path="/zones/{id}",
     *     tags={"Zones"},
     *     summary="Update a zone",
     *     description="Update the details of a specific zone.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the zone",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Zone")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
     *     )
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::zone_service()->update_zone($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/zones/{id}",
     *     tags={"Zones"},
     *     summary="Delete a zone",
     *     description="Remove a specific zone from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the zone",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Zone not found"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        Flight::zone_service()->delete_zone($id);
        Flight::json(['message' => 'Zone deleted successfully']);
    });
});
?>