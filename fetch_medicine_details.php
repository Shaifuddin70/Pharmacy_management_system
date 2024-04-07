<?php
include 'nav/nav.php'; // Assuming this includes database connection details

if (isset($_GET['medicine_id'])) {
    $medicineId = $_GET['medicine_id'];

    $query = "SELECT unit_price, available_quantity FROM medicine WHERE medicine_id = ?";
    $stmt = $conn->prepare($query); 
    $stmt->bind_param('i', $medicineId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc(); 
        echo json_encode($data); 
    } else {
        echo json_encode(array('error' => 'Medicine not found'));
    }
}
?>
