<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit(); // Stop further execution
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

// Fetch medicine data with pagination
$query = "SELECT employee.*, role.role_name 
          FROM employee 
          JOIN role ON role.role_id = employee.role 
          LIMIT $offset, $results_per_page";

$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM employee"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">Employee List</h1>
        <a href="add_employee.php"><button class="col-2 btn btn-primary add_button"> Add Employee</button></a>
    </div>
    <?php
    if (isset($_SESSION['status'])) {
        echo "<p class='text-danger'>" . $_SESSION['status'] . "<p>";
        unset($_SESSION['status']);
    }
    ?>

    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
        <thead> <!-- Fixed typo here -->
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody> <!-- Open tbody tag here -->
            <?php
            $c = $offset + 1; // Initialize serial number based on offset
            while ($result = mysqli_fetch_assoc($data)) {
                echo '
                <tr>
                    <td>' . $c . '</td>
                    <td>' . $result['name'] . '</td>
                    <td>' . $result['email'] . '</td>
                    <td>' . $result['number'] . '</td>
                    <td>' . $result['role_name'] . '</td>
                    <td>
                        <a href="eupdate.php?updateid=' . $result['id'] . '" class="text-light"><button class="btn btn-primary"><i class="bx bxs-edit-alt"></i></button></a>
                        <a href="edelete.php?deleteid=' . $result['id'] . '" class="text-light"><button class="btn btn-danger"><i class="bx bxs-user-x"></i></button></a>
                    </td>
                </tr>';
                $c++;
            }
            ?>
        </tbody> <!-- Close tbody tag here -->
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
