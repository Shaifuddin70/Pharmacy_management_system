<?php

include 'nav/nav.php';

// Check if user is authorized
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}

// Pagination variables
$results_per_page = 10; // Number of results per page
$page = 1; // Default page

// Get current page number from URL
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
}

// Calculate the offset
$offset = ($page - 1) * $results_per_page;


// Count total records
$count_query = "SELECT COUNT(*) AS total FROM medicine_stock WHERE unit < 5"; // Updated query
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];

// Calculate total pages
$total_pages = ceil($total_records / $results_per_page);



// Fetch data with pagination
$sql = "SELECT m.medicine_name, m.catagory_id, m.brand_id, m.generic_id, c.catagory_name, b.brand_name, g.generic_name, s.unit, s.pprice, s.sprice, DATE(s.expiry_date) AS expiry_date, r.shelf_number
        FROM medicine_stock s
        JOIN medicine m ON s.medicine_id = m.medicine_id
        JOIN medicine_catagory c ON m.catagory_id = c.catagory_id
        JOIN medicine_brand b ON m.brand_id = b.brand_id
        JOIN medicine_generic g ON m.generic_id = g.generic_id
        LEFT JOIN shelf r ON s.shelf_id = r.shelf_id
        WHERE s.unit < 5 
        LIMIT $offset, $results_per_page
";
$data = mysqli_query($conn, $sql);

?>

<head>
    <style>
        .inputbox {
            position: relative;
            width: 200px;
            background: transparent;
            border: 1px solid #dddfe2;
            border-radius: 10px;
            color: rgb(0, 0, 0);
            outline: none;
            margin-top: revert-layer;
        }
    </style>
</head>

<div class="container">
    <h2 class="text-center text-uppercase p-2">All Stock Items</h2>



    <div id="table">
        <h2 id="invisible" class="d-none">Stock Report</h2>

        <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Generic </th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Shelf</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($data) > 0) {
                    $c = $offset + 1;
                    while ($result = mysqli_fetch_assoc($data)) {
                        echo '
                    <tr>
                        <td>' . $c . '</td>
                        <td>' . $result['medicine_name'] . '</td>
                        <td>' . $result['catagory_name'] . '</td>
                        <td>' . $result['brand_name'] . '</td>
                        <td>' . $result['generic_name'] . '</td>
                        <td>' . $result['unit'] . '</td>
                        <td>' . $result['expiry_date'] . '</td>
                        <td>' . $result['shelf_number'] . '</td>
                        <td><a href="purchase.php" class="text-light"><button class="btn btn-primary">Purchase</button></a></td>
                    </tr>';
                        $c++;
                    }
                } else {
                    echo "<tr><td colspan='10'>No records found</td></tr>";
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
    </div>
</div>

<script>
    const purchaseReport = () => {
        $("#invisible").removeClass("d-none");
        var divName = "table";
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        $("#invisible").addClass("d-none");
    }
</script>

<?php
include 'nav/footer.php';
?>