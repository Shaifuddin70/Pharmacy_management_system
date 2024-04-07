<?php
include '../db_connect.php';
$db = new dbObj();
$conn = $db->getConnstring();

if (isset($_POST['catagory_data'])) {
    $catagory_id = mysqli_real_escape_string($conn, $_POST['catagory_data']); // Sanitize input

    // Adjust your query if 'generic_id' is a string:
    $item = "SELECT * FROM medicine WHERE generic_id = '$catagory_id'"; 

    $item_qry = mysqli_query($conn, $item);

    $output = '<option value="">Select Medicine</option>';
    while ($item_row = mysqli_fetch_assoc($item_qry)) {
        $output .= '<option value="' . $item_row['medicine_id'] . '">' . $item_row['medicine_name'] . '</option>';
    }
    echo $output;
}
