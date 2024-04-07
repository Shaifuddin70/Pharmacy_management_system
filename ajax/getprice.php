<?php
// Include your database connection file here
include '../db_connect.php';
$db = new dbObj();
$conn = $db->getConnstring();

// Check if category_data is provided in the request
if (isset($_POST['category_data'])) {
    $medicine_id = mysqli_real_escape_string($conn, $_POST['category_data']);

    // Query to fetch medicine details
    $query = "SELECT medicine_stock.unit AS available_quantity, medicine_stock.sprice 
              FROM medicine_stock
              WHERE medicine_stock.medicine_id = $medicine_id";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $available_quantity = $row['available_quantity'];
            $unit_price = $row['sprice'];
            
            // Prepare response data
            $response = array('available_quantity' => $available_quantity, 'unit_price' => $unit_price);

            // Send response
            echo json_encode($response);
        } else {
            // No rows found
            echo json_encode(array('error' => 'Medicine not found'));
        }
    } else {
        // Error executing the query
        echo json_encode(array('error' => 'Error executing query: ' . mysqli_error($conn)));
    }
} else {
    // Category ID not provided
    echo json_encode(array('error' => 'Category ID not provided'));
}
?>
