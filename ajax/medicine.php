<?php
// Include your database connection file
include '../db_connect.php';
$db = new dbObj();
$conn = $db->getConnstring();

// Check if catagory_id is set and not empty
if (isset($_POST['catagory_id']) && !empty($_POST['catagory_id'])) {
    // Sanitize the input to prevent SQL injection
    $catagory_id = mysqli_real_escape_string($conn, $_POST['catagory_id']);
    
    // Log the catagory_id to the console
    echo "<script>console.log('Category ID: $catagory_id');</script>";

    // Query to fetch medicines based on the selected category
    $query = "SELECT * FROM medicine WHERE catagory_id = $catagory_id";
    $result = mysqli_query($conn, $query);

    // Check if query was successful
    if ($result) {
        // Check if any medicines were found
        if (mysqli_num_rows($result) > 0) {
            // Output HTML for medicine options
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['medicine_id'] . '">' . $row['medicine_name'] . '</option>';
            }
        } else {
            // If no medicines were found for the selected category
            echo '<option value="">No Medicines Found</option>';
        }
    } else {
        // If there was an error with the query
        echo '<option value="">Error fetching medicines</option>';
    }
} else {
    // If catagory_id is not set or empty
    echo '<option value="">Invalid Category ID</option>';
}
?>
