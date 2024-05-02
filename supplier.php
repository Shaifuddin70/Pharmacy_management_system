<?php
include 'nav/nav.php';

// Check if user is logged in as admin
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Check if deleteid is set and numeric
if (isset($_GET['deleteid']) && is_numeric($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Prepare delete statement
    $delete_query = "DELETE FROM `supplier` WHERE supplier_id=$id";
    $result = mysqli_query($conn, $delete_query);
    if ($result) {
        echo 'delete successfully';
        header("location:supplier.php");
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

// Fetch supplier data with pagination
$query = "SELECT * FROM supplier LIMIT $offset, $results_per_page";
$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM supplier"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>
<div class="container">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">Supplier List</h2>
        <a href="add_supplier.php"><button class="col-2 btn btn-primary add_button"> Add Supplier</button> </a>

    </div>

    <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
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
                        <td>' . $result['supplier_name'] . '</td>
                        <td>' . $result['supplier_email'] . '</td>
                        <td>' . $result['supplier_number'] . '</td>
                        <td>
                            <a href="supplier_update.php?updateid=' . $result['supplier_id'] . '" class="text-light"><button class="btn btn-primary"><i class="bx bxs-edit-alt"></i></button></a>
                            <a href="supplier.php?deleteid=' . $result['supplier_id'] . '" class="text-light"><button class="btn btn-danger"><i class="bx bxs-user-x"></i></button></a>
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
  
                    <!-- Pagination links -->
                    <div class="pagination">
                        <ul class="pagination">
                            <!-- Previous button -->
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo ($page <= 1) ? 1 : ($page - 1); ?>">&laquo; Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <!-- Next button -->
                            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo ($page >= $total_pages) ? $total_pages : ($page + 1); ?>">Next &raquo;</a>
                            </li>
                        </ul>
                    </div>
             
    <?php
    include 'nav/footer.php';
    ?>