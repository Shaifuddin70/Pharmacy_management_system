<?php
include '../db_connect.php';
$db = new dbObj();
$conn = $db->getConnstring();

// Check if invoiceId is set in the POST request
if(isset($_POST['invoiceId'])) {
    // Get the invoice ID from the POST data
    $invoiceId = $_POST['invoiceId'];

    // Initialize the discount variable
    $discount = 0;

    // Connect to the database
    $db = new dbObj();
    $conn = $db->getConnstring();

    // Prepare and execute SQL query to fetch discount for the given invoice ID
    $query = "SELECT discount FROM invoices WHERE invoice_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a discount is found
    if ($result->num_rows > 0) {
        // Fetch the discount value
        $row = $result->fetch_assoc();
        $discount = isset($row['discount']) ? $row['discount'] : 0;
    }

    // Close database connection
    $conn->close();

    // Return the discount as a JSON response
    header('Content-Type: application/json');
    echo json_encode(['discount' => $discount]);
} else {
    // If invoiceId is not set, return an error response
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invoice ID not provided']);
}
?>
