<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the Composer autoloader
require __DIR__ . '/../../../vendor/autoload.php'; // Corrected path to vendor/autoload.php

// Define the base URL based on the environment
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    define('BASE_URL', 'http://localhost:83/project/backend/');
} else {
    define('BASE_URL', 'https://add-production-server-after-deployment/backend/');
}

// Scan the specified directories for OpenAPI annotations
$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php', // Path to the Swagger setup file
    __DIR__ . '/../../../rest/routes' // Path to the routes directory
]);

// Set the content type to JSON and output the OpenAPI documentation
header('Content-Type: application/json');
echo $openapi->toJson();
?>