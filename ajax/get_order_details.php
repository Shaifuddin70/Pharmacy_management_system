<?php
include '../db_connect.php';
$db = new dbObj();
$conn = $db->getConnstring();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invoiceId'])) {
    $invoiceId = $_POST['invoiceId'];
    try {
        // Query to fetch discount
        $queryDiscount = "SELECT discount FROM invoices WHERE invoice_id = ?";
        $stmtDiscount = $conn->prepare($queryDiscount);
        $stmtDiscount->bind_param("i", $invoiceId);
        $stmtDiscount->execute();
        $discountResult = $stmtDiscount->get_result();
        $discountRow = $discountResult->fetch_assoc();
        $discount = isset($discountRow['discount']) ? (float)$discountRow['discount'] : 0;

        // Query to fetch order details
        $query = "SELECT 
            m.medicine_id,
            m.medicine_name,
            ii.quantity AS sold_quantity,
            ms.sprice AS unit_price
        FROM 
            invoice_items AS ii
        JOIN 
            medicine AS m ON ii.medicine_id = m.medicine_id
        JOIN 
            medicine_stock AS ms ON ii.medicine_id = ms.medicine_id
        WHERE ii.invoice_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the results and store in an array
        $orderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
        }

        // Add the discount to each row
        foreach ($orderDetails as &$orderDetail) {
            $orderDetail['discount'] = $discount;
        }

        // Set the Content-Type header to JSON
        header('Content-Type: application/json');
        echo json_encode($orderDetails);
    } catch (Exception $e) {
        // Return JSON response with error information
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Invalid request
    echo json_encode(['error' => 'Invalid request']);
}
