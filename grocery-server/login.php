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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function verifyAdmin($stmt, $username_client, $password_client)
{
    if ($stmt->num_rows > 0) 
    {
        $stmt->bind_result($password);
        $stmt->fetch();
    
        // Verify password
        if ($password_client == $password) 
            return TRUE;
        else 
            return FALSE;
    } 
    else 
    {
        return FALSE;
    }
}

function verifyUser($stmt, $username_client, $password_client)
{
    if ($stmt->num_rows > 0) 
    {
        $stmt->bind_result($password);
        $stmt->fetch();
    
        // Verify password
        if (password_verify($password_client, $password)) 
            return TRUE;
        else 
            return FALSE;
    } 
    else 
    {
        return FALSE;
    }
}

// Get POST data
$data = json_decode(file_get_contents("php://input"));
$username_client = $data->username;
$password_client = $data->password;

// Fetch user from admin database
$stmtAdmin = $conn->prepare("SELECT password FROM admin_login WHERE username = ?");
$stmtAdmin->bind_param("s", $username_client);
$stmtAdmin->execute();
$stmtAdmin->store_result();

// Fetch username from user database
$stmtUser = $conn->prepare("SELECT password FROM user_login WHERE username = ?");
$stmtUser->bind_param("s", $username_client);
$stmtUser->execute();
$stmtUser->store_result();

if(verifyAdmin($stmtAdmin, $username_client, $password_client))
    echo json_encode(["message" => "AdminLoginSuccess"]);
else if(verifyUser($stmtUser, $username_client, $password_client))
    echo json_encode(["message" => "UserLoginSuccess"]);
else
    echo json_encode(["message" => "LoginFail"]);

$stmtUser->close();
$stmtAdmin->close();
$conn->close();
?>