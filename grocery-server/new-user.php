<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$host = 'localhost';
$db = 'grocery_schema';
$user = 'root'; // default username
$pass = '';     // default password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$data = json_decode(file_get_contents("php://input"));
$username_client = $data->username;
$password_client = $data->password;

// Check if username already exists
$checkStmt = $conn->prepare("SELECT username FROM user_login WHERE username = ?");
$checkStmt->bind_param("s", $username_client);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) 
{
    // Username exists
    echo json_encode(["message" => "Failure"]);
} 
else 
{
    // Prepare and bind for insertion
    $hashed_password = password_hash($password_client, PASSWORD_DEFAULT); // Hash the password
    $stmt = $conn->prepare("INSERT INTO user_login (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username_client, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["message" => "Success"]);
    } else {
        echo json_encode(["error" => "Failure"]);
    }

    // Close the insert statement
    $stmt->close();
}

// Close the check statement and connection
$checkStmt->close();
$conn->close();
?>