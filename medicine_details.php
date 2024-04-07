<?php
include 'nav/nav.php';
error_reporting(0);
// Check if user is authorized
if (!isset($_SESSION['admin']) && !isset($_SESSION['stuff'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}
// Pagination variables
$results_per_page = 10; // Number of results per page
$offset = 0; // Starting offset
// Get current page number from URL
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $offset = ($page - 1) * $results_per_page; // Calculate offset
} else {
    $page = 1;
}
// Fetch medicine data with pagination
$query = "SELECT m.medicine_id,c.catagory_name, m.medicine_name, b.brand_name, g.generic_name FROM medicine m 
JOIN medicine_brand b ON m.brand_id = b.brand_id 
JOIN medicine_generic g ON m.generic_id = g.generic_id 
JOIN medicine_catagory c ON m.catagory_id = c.catagory_id
LIMIT $offset, $results_per_page";
$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM medicine"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);


// Handle delete action
if (isset($_GET['deleteid'])) {
    $delete_id = $_GET['deleteid'];
    $delete_query = "DELETE FROM medicine WHERE medicine_id = $delete_id";
    $delete_result = mysqli_query($conn, $delete_query);
    if ($delete_result) {
        // Redirect to the same page after successful deletion
        header("Location: medicine_details.php?page=$page");
        exit;
    } else {
        echo "Error deleting medicine. Please try again.";
    }
}

?>
<div class="container">
    <div class="title">
        <h2 class="font-weight-bold">All Medicine</h2>
        <a href="add_medicine.php"><button href="add_medicine.php" class="col-2 btn btn-primary add_button"> Add Medicine</button></a>
    </div>
    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Medicine Name</th>
                <th>Brand</th>
                <th>Generic</th>
                <th>Catagory</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = $offset + 1; // Initialize serial number based on offset
            while ($result = mysqli_fetch_assoc($data)) {
                echo '
            <tr>
                <td>' . $c . '</td>
                <td>' . $result['medicine_name'] . '</td>
                <td>' . $result['brand_name'] . '</td>
                <td>' . $result['generic_name'] . '</td>
                <td>' . $result['catagory_name'] . '</td>
                <td>
                    <a href="medicine_update.php?updateid=' . $result['medicine_id'] . '" class="text-light"><button class="btn btn-primary"><i class="bx bxs-edit-alt"></i></button></a>
                    <a href="medicine_details.php?deleteid=' . $result['medicine_id'] . '" class="text-light"><button class="btn btn-danger"><i class="bx bxs-trash"></i></button></a>
                </td>
            </tr>';
                $c++;
            }
            ?>
        </tbody>
    </table>

    <?php
    // Display pagination links
    echo '<div class="pagination">';
    echo '<ul class="pagination">';

    // Previous button
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="medicine_details.php?page=' . ($page - 1) . '">&laquo; Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="medicine_details.php?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="medicine_details.php?page=' . ($page + 1) . '">Next &raquo;</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
    }

    echo '</ul>';
    echo '</div>';
    ?>

    <?php
    include 'nav/footer.php';
    ?>
