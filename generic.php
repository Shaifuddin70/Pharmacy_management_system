<?php 
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit(); // Stop further execution
}

// Check if deleteid is set in the URL
if (isset($_GET['deleteid'])) {
    // Include your database connection file
    include 'your_database_connection.php';

    // Sanitize the input to prevent SQL injection
    $deleteId = mysqli_real_escape_string($conn, $_GET['deleteid']);

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM medicine_generic WHERE generic_id = '$deleteId'";
    if (mysqli_query($conn, $deleteQuery)) {
        // If deletion is successful, redirect back to the same page with a success message
        echo "<script>alert('Generic deleted successfully');</script>";
        echo "<script>window.location='generic.php';</script>";
        exit(); // Stop further execution
    } else {
        // If deletion fails, show an error message
        echo "<script>alert('Failed to delete generic. Please try again.');</script>";
    }
}

// Pagination variables
$results_per_page = 10; // Number of results per page
$offset = 0; // Starting offset

// Get current page number from URL
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
    $offset = ($page - 1) * $results_per_page; // Calculate offset
} else {
    $page = 1;
}

// Fetch generic data with pagination
$query = "SELECT * FROM medicine_generic LIMIT $offset, $results_per_page";
$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM medicine_generic"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>

<div class="container">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">All Generic</h2>
        <a href="add_generic.php"><button class="col-2 btn btn-primary add_button"> Add Generic</button></a>
    </div>

    <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Generic Name</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = $offset + 1; // Initialize serial number based on offset
            if ($total_results != 0) {
                while ($result = mysqli_fetch_assoc($data)) {
                    echo '
                    <tr>
                        <td>' . $c . '</td>
                        <td>' . $result['generic_name'] . '</td>
                        <td>
                            <a href="medicine_details.php?genericid=' . $result['generic_id'] . '" class="text-light"><button class="btn btn-primary"><i class="uil uil-eye"></i></button></a>
                            <a href="gupdate.php?updateid=' . $result['generic_id'] . '" class="text-light"><button class="btn btn-primary"><i class="bx bxs-edit-alt"></i></button></a>
                            <a href="generic.php?deleteid=' . $result['generic_id'] . '" class="text-light"><button class="btn btn-danger"><i class="bx bxs-trash"></i></button></a>
                        </td>
                    </tr>';
                    $c++;
                }
            } else {
                echo "<tr><td colspan='3'>NO records Found</td></tr>";
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
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">&laquo; Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next &raquo;</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
    }

    echo '</ul>';
    echo '</div>';
    ?>

</div>

<?php include 'nav/footer.php'; ?>
