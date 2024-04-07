<?php 
include 'nav/nav.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}

// Check if deleteid is set and numeric
if(isset($_GET['deleteid']) && is_numeric($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Prepare delete statement
 
    $delete_query = "DELETE FROM `customer` WHERE customer_id=$id";
    $result = mysqli_query($conn, $delete_query);
if ($result) {
    echo 'delete succesfully';
    header("location:customer.php");
} else {
    die(mysqli_error($conn));
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

// Fetch customer data with pagination
$query = "SELECT * FROM customer LIMIT $offset, $results_per_page";
$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM customer"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>
<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">Customer List</h1>
        <a href="add_customer.php"><button class="col-2 btn btn-primary add_button"> Add Customer</button> </a>
    </div>
    <?php
    if (isset($_SESSION['status'])) {
        echo "<p class='text-danger'>" . $_SESSION['status'] . "</p>";
        unset($_SESSION['status']);
    }
    ?>
    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = $offset + 1; // Initialize serial number based on offset
            if ($total_results > 0) {
                while ($result = mysqli_fetch_assoc($data)) {
                    echo '
                    <tr>
                        <td>' . $c . '</td>
                        <td>' . $result['customer_name'] . '</td>
                        <td>' . $result['customer_email'] . '</td>
                        <td>' . $result['customer_number'] . '</td>
                        <td>
                            <a href="eupdate.php?updateid=' . $result['customer_id'] . '" class="text-light"><button class="btn btn-primary"><i class="bx bxs-edit-alt"></i></button></a>
                            <a href="customer.php?deleteid=' . $result['customer_id'] . '" class="text-light"><button class="btn btn-danger"><i class="bx bxs-user-x"></i></button></a>
                        </td>
                    </tr>';
                    $c++;
                }
            } else {
                echo "<tr><td colspan='5'>No records Found</td></tr>";
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
    
    include 'nav/footer.php';
    ?>
