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
$item_name_client = $data->itemName;
$qty_to_add = $data->qty;

// Check if item already exists
$checkStmt = $conn->prepare("SELECT id FROM grocery_data WHERE LOWER(item_name) = LOWER(?);");
$checkStmt->bind_param("s", $item_name_client);
$checkStmt->execute();
$checkStmt->store_result();

// Check if any results were returned
if ($checkStmt->num_rows > 0) 
{
    // Bind the result to variables
    $checkStmt->bind_result($fetched_id);
    $checkStmt->fetch(); // Fetch the first (and should be only) row

    // Prepare the update statement
    $updateStmt = $conn->prepare("UPDATE grocery_data SET qty = qty + ? WHERE id = ?;");
    $updateStmt->bind_param("ii", $qty_to_add, $fetched_id);
    
    // Execute the update statement
    if ($updateStmt->execute()) 
    {
        echo json_encode(["message" => "Success"]);
    } 
    else 
    {
        echo json_encode(["message" => "Failure"]);
    }

    // Close the update statement
    $updateStmt->close();
} 
else
{
    // Get number of rows
    // Prepare the SQL statement to count rows
    $sql = "SELECT COUNT(*) AS total_rows FROM grocery_data";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalRows = $row['total_rows'];
    $newId = $totalRows + 1; // Set the new ID
    if($item_name_client == NULL)   // ANAZLYZE THIS
        exit();
    // Prepare the update statement
    $insertStmt = $conn->prepare("INSERT INTO grocery_data (id, item_name, qty) VALUES (?, ?, ?)");
    $insertStmt->bind_param("isi", $newId, $item_name_client, $qty_to_add);
    // Execute the update statement
    $insertStmt->execute();
    // Close the update statement
    $insertStmt->close();
}
?>