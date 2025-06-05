<?php
/**
 * @OA\Tag(
 *     name="Messages",
 *     description="API Endpoints for managing messages"
 * )
 */

Flight::group('/messages', function() {

    /**
     * @OA\Get(
     *     path="/messages",
     *     tags={"Messages"},
     *     summary="Get all messages",
     *     description="Retrieve a list of all messages.",
     *     @OA\Response(
     *         response=200,
     *         description="A list of messages.",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Message"))
     *     )
     * )
     */
    Flight::route('GET /', function() {
        
        Flight::json(Flight::messages_service()->get_all());
    });

    /**
     * @OA\Get(
     *     path="/messages/{id}",
     *     tags={"Messages"},
     *     summary="Get a message by ID",
     *     description="Retrieve a specific message by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the message",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message details",
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Message not found"
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::messages_service()->get_by_id($id));
    });

    /**
     * @OA\Post(
     *     path="/messages",
     *     tags={"Messages"},
     *     summary="Create a new message",
     *     description="Add a new message to the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Message created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::messages_service()->create_message($data));
    });

    /**
     * @OA\Put(
     *     path="/messages/{id}",
     *     tags={"Messages"},
     *     summary="Update a message",
     *     description="Update the details of a specific message.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the message",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Message")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Message not found"
     *     )
     * )
     */
    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::messages_service()->update_message($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/messages/{id}",
     *     tags={"Messages"},
     *     summary="Delete a message",
     *     description="Remove a specific message from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the message",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Message not found"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        Flight::messages_service()->delete_message($id);
        Flight::json(['message' => 'Message deleted successfully']);
    });
});
?>