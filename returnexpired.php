<?php
include 'nav/nav.php';

if (isset($_GET['medicine_id'])) {
    $medicine_id = $_GET['medicine_id'];

    // Delete the medicine from the stock based on the medicine_id
    $delete_query = "DELETE FROM medicine_stock WHERE medicine_id = $medicine_id";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Expired medicine returned successfully')</script>";
        echo "<script>window.location='stock.php'</script>";
        exit();
    } else {
        echo "<script>alert('Failed to return expired medicine')</script>";
        echo "<script>window.location='stock.php'</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request')</script>";
    echo "<script>window.location='stock.php'</script>";
    exit();
}
?>
