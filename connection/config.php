<?php
// Database configuration - stores the connection details
$servername = "localhost"; // The server address where MySQL is running (localhost means this machine)
$username = "root"; // The MySQL username used to authenticate the connection
$password = ""; // The MySQL password for the user (empty string means no password set)
$database = "phpblog"; // The name of the database to connect to

// Create connection - establishes a new MySQL database connection
$conn = new mysqli($servername, $username, $password, $database); // Creates a MySQLi object that manages the database connection

// Check connection - validates if the connection was successful
if ($conn->connect_error) { // If there's a connection error, this condition is true
    die("Connection failed: " . $conn->connect_error); // Terminates the script and displays the error message
}

// Set charset to utf8 - ensures proper handling of special characters
$conn->set_charset("utf8"); // Sets the character set to utf8 for encoding/decoding data correctly
?>