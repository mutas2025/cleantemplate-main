<?php
// login.php
// This script acts as the main entry point for our login API.
// It's often called a "route" or an "endpoint".

// --- Configuration for Development ---

// These settings are used for debugging during development.
// They make sure that if any errors happen, they will be displayed on the screen.
// In a real, live application, you would typically log these errors to a file instead of showing them to the user.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ///

// --- Main API Logic ---

// We need the LoginController class because it contains the actual logic for logging a user in.
// By including it here, we can create and use a LoginController object.
require_once 'controllers/LoginController.php';

// We tell the client (like a web browser or a mobile app) that we are going to send back data in JSON format.
// JSON (JavaScript Object Notation) is a standard and lightweight way to send data.
header('Content-Type: application/json');

// We only want to allow POST requests to this endpoint.
// A POST request is used when the client wants to send data to the server (like a username and password).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Handling the POST Request ---

    // The login data (username and password) is sent in the body of the POST request in JSON format.
    // `file_get_contents('php://input')` reads this raw data.
    // `json_decode()` then converts the JSON string into a PHP array.
    $data = json_decode(file_get_contents('php://input'), true);

    // We get the username and password from the decoded data.
    // The '??' (null coalescing operator) is a shortcut to provide a default value (an empty string)
    // if the 'username' or 'password' key doesn't exist. This helps prevent errors.
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    // We create a new instance of our LoginController.
    // This object knows how to handle the login process.
    $controller = new LoginController();

    // We call the `login` method on our controller object, passing it the username and password.
    // The method will return an array with the result (e.g., success or error).
    $response = $controller->login($username, $password);

    // We convert the response array back into a JSON string.
    // `echo` then sends this JSON string back to the client.
    echo json_encode($response);

} else {
    // If the request is not a POST request (e.g., it's a GET request),
    // we send back an error message.
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Please use POST.'
    ]);
}