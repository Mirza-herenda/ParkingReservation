<?php

/**
 * @OA\Info(
 *     title="Parking Reservation API",
 *     description="API documentation for the Parking Reservation system.",
 *     version="1.0",
 *     @OA\Contact(
 *         email="mirza.herenda@stu.ibu.edu.ba",
 *         name="Mirza Herenda",
 *     )
 * )
 */

/**
 * @OA\Server(
 *      url="http://localhost/ParkingReservation/",
 *      description="Local API server for the Parking Reservation system"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */