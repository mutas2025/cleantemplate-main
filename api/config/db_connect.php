<?php
// db_connect.php
// This script is dedicated to one single, important task: connecting to our MySQL database.

// First, we need our database credentials (like the host, database name, username, and password).
// These are stored in 'db_config.php', so we include that file here.
// `__DIR__` is a special PHP constant that gives us the absolute path to the current directory ('config'),
// which ensures we can always find our config file.
require_once __DIR__ . '/db_config.php';

// --- The Database Connection ---

// We will use a modern PHP feature called PDO (PHP Data Objects) to connect.
// PDO is great because it provides a consistent way to connect to many different types of databases, not just MySQL.

// We wrap our connection code in a 'try...catch' block. This is a robust way to handle errors.
// If anything goes wrong inside the 'try' part, the code immediately jumps to the 'catch' part,
// preventing the application from crashing and allowing us to show a friendly error message.
try {
    // --- 1. The DSN (Data Source Name) ---
    // The DSN is a string that tells PDO everything it needs to know to find and connect to our database.
    // It includes the type of database (mysql), the host, the name of the database, and the character set.
    // Using 'utf8mb4' is recommended as it supports a wide range of characters, including emojis!
    $dsn = 'mysql:host=' . DB_HOST_CSR . ';dbname=' . DB_NAME_CSR . ';charset=utf8mb4';

    // --- 2. Connection Options ---
    // This is an array of settings that configure how our PDO connection behaves.
    $options = [
        // This tells PDO to throw an "exception" (a type of error) if something goes wrong with a query.
        // This is very useful for catching bugs.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        
        // This sets the default way PDO will return data to us when we fetch from the database.
        // `PDO::FETCH_ASSOC` means it will return data as an associative array (e.g., ['username' => 'john']).
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        // This setting can improve security and performance by telling the database engine to handle
        // query preparation directly.
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // --- 3. Creating the PDO Object ---
    // This is the line where the actual connection happens.
    // We create a new PDO object, passing it the DSN, username, password, and our options.
    // If the connection is successful, the new object is stored in our `$con` variable.
    $con = new PDO($dsn, DB_USER_CSR, DB_PASS_CSR, $options);
    
    // If the script gets to this point without errors, it means the connection was successful!

} catch (PDOException $e) {
    // If an error (a PDOException) occurred in the 'try' block, the code comes here.
    // We stop the script (`die()`) and display a helpful error message.
    // In a real application, you might log this error to a file instead of showing it to the user.
    die('Database Connection Failed: ' . $e->getMessage());
}