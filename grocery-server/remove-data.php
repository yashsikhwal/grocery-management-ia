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
$qty_to_subtract = $data->qty;

// Check if item exists
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
    $currentQtyStmt = $conn->prepare("SELECT qty FROM grocery_data WHERE id = ?");
    $currentQtyStmt->bind_param("i", $fetched_id);
    $currentQtyStmt->execute();
    $currentQtyStmt->bind_result($current_qty);
    $currentQtyStmt->fetch();
    $currentQtyStmt->close();

    // Check if subtracting will result in a negative quantity
    if ($current_qty >= $qty_to_subtract) 
    {
        // Prepare the update statement
        $updateStmt = $conn->prepare("UPDATE grocery_data SET qty = qty - ? WHERE id = ?;");
        $updateStmt->bind_param("ii", $qty_to_subtract, $fetched_id);
        
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
        echo json_encode(["message" => "Failure"]);
    }
} 
else
{
    echo json_encode(["message" => "Failure"]);
}
?>