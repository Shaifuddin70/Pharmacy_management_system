<?php
include 'db_connect.php';
$db = new dbObj();
$conn =  $db->getConnstring();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_invoice'])) {
    // Retrieve invoice ID
    $invoice_id = $_POST['invoice_id'];

    // Delete invoice items
    $delete_items_query = "DELETE FROM invoice_items WHERE invoice_id = ?";
    $stmt = $conn->prepare($delete_items_query);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();

    // Delete invoice
    $delete_invoice_query = "DELETE FROM invoices WHERE invoice_id = ?";
    $stmt = $conn->prepare($delete_invoice_query);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();

    // Redirect to the page where the deletion was initiated
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
} else {
    // Redirect to a page indicating unauthorized access if someone tries to directly access this file
    header("Location: unauthorized.php");
    exit();
}
?>
