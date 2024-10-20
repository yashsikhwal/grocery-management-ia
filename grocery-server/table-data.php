<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$host = 'localhost';
$db = 'grocery_schema';
$user = 'root'; // default username
$pass = '';     // default password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM grocery_data";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) 
    {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>