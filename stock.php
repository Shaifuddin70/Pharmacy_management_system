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

$cq = "";
$iq = "";
$brand = "";
$generic = "";

if (isset($_POST['submit'])) {
    if (!empty($_POST['brand'])) {
        $brand_id = $_POST['brand'];

        $q = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM medicine_brand WHERE brand_id=$brand_id"));
        $brand_name = $q['brand_name'];
        $cq = " AND b.brand_name='$brand_name'";
    }
    if (!empty($_POST['generic'])) {
        $generic_id = $_POST['generic'];

        $q = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM medicine_generic WHERE generic_id=$generic_id"));
        $generic_name = $q['generic_name'];
        $iq = " AND g.generic_name='$generic_name'";
    }
}

// Count total records
$count_query = "SELECT COUNT(*) AS total FROM medicine ";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];

// Calculate total pages
$total_pages = ceil($total_records / $results_per_page);

$whereClause = "";
if (!empty($cq) || !empty($iq)) {
    $whereClause = "WHERE 1 $cq $iq";
}

// Fetch data with pagination
// Fetch data with pagination
$sql = "
        SELECT m.medicine_name, m.catagory_id, m.brand_id, m.generic_id, c.catagory_name, b.brand_name, g.generic_name, s.unit, s.pprice, s.sprice, DATE(s.expiry_date) AS expiry_date, r.shelf_number
        FROM medicine_stock s
        JOIN medicine m ON s.medicine_id = m.medicine_id
        JOIN medicine_catagory c ON m.catagory_id = c.catagory_id
        JOIN medicine_brand b ON m.brand_id = b.brand_id
        JOIN medicine_generic g ON m.generic_id = g.generic_id
        LEFT JOIN shelf r ON s.shelf_id = r.shelf_id
        $whereClause
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
    <h1>All Stock Items</h1>
    <button onclick="purchaseReport()" class="btn btn-info"> Create Report</button>
    <form method="post">
        <div class="input-group date" style="margin-left:450px;bottom: 35px;font-weight: bold;">
            <label for="brand" class="col-1 col-form-label">Brand: </label>
            <?php
            $brand_query = "SELECT * FROM medicine_brand";
            $brand_result = mysqli_query($conn, $brand_query);
            ?>
            <select class="inputbox" aria-label="Default select example" name="brand" id="brand">
                <option selected disabled>Select Brand</option>
                <?php while ($brand_row = mysqli_fetch_assoc($brand_result)) : ?>
                    <option value="<?php echo $brand_row['brand_id']; ?>"> <?php echo $brand_row['brand_name']; ?> </option>
                <?php endwhile; ?>
            </select>
            <label for="generic" class="col-1 col-form-label">Generic: </label>
            <?php
            $generic_query = "SELECT * FROM medicine_generic";
            $generic_result = mysqli_query($conn, $generic_query);
            ?>
            <select class="inputbox" aria-label="Default select example" name="generic" id="generic">
                <option selected disabled>Select Generic</option>
                <?php while ($generic_row = mysqli_fetch_assoc($generic_result)) : ?>
                    <option value="<?php echo $generic_row['generic_id']; ?>"> <?php echo $generic_row['generic_name']; ?> </option>
                <?php endwhile; ?>
            </select>
            <input type="submit" class="btn btn-info" name="submit" value="Filter">
        </div>
    </form>
    <a href="purchase.php"><button class="btn btn-primary" style="margin-left:200px ; margin-bottom: 15px;margin-top: -120px;">Create Purchase Request</button></a>

    <div id="table">
        <h1 id="invisible" class="d-none">Stock Report</h1>

        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Generic </th>
                    <th>Quantity</th>
                    <th>Purchase Price</th>
                    <th>Sell Price</th>
                    <th>Expiry Date</th>
                    <th>Shelf</th>
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
                        <td>' . $result['pprice'] . '</td>
                        <td>' . $result['sprice'] . '</td>
                        <td>' . $result['expiry_date'] . '</td>
                        <td>' . $result['shelf_number'] . '</td>
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
