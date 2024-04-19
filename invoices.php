<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Pagination variables
$results_per_page = 10; // Number of results per page

// Calculate total number of invoices
$total_invoices_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices");
$total_invoices_row = mysqli_fetch_assoc($total_invoices_query);
$total_invoices = $total_invoices_row['total'];

// Calculate total number of pages
$total_pages = ceil($total_invoices / $results_per_page);

// Get current page number from URL
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Ensure page is within bounds
$page = max(1, min($page, $total_pages));

// Calculate offset
$offset = ($page - 1) * $results_per_page;

// Filter variables
$filter_employee = isset($_GET['emp']) ? $_GET['emp'] : '';
$filter_from = isset($_GET['from']) ? $_GET['from'] : '';
$filter_to = isset($_GET['to']) ? $_GET['to'] : '';

// Construct the WHERE clause for filtering
$where_clause = '';
if (!empty($filter_employee)) {
    $where_clause .= " AND invoices.employee_id = $filter_employee";
}
if (!empty($filter_from)) {
    $where_clause .= " AND invoices.created_at >= '$filter_from'";
}
if (!empty($filter_to)) {
    $where_clause .= " AND invoices.created_at <= '$filter_to'";
}

// Fetch invoices with customer names from the database with filtering
$query = "SELECT invoices.invoice_id, customer.customer_name, employee.name AS employee_name, invoices.discount, invoices.subtotal, invoices.total, invoices.created_at 
FROM invoices 
INNER JOIN customer ON invoices.customer_id = customer.customer_id
INNER JOIN employee ON invoices.employee_id = employee.id
WHERE 1 $where_clause
ORDER BY invoices.created_at DESC
LIMIT $offset, $results_per_page";
$result_set = mysqli_query($conn, $query);

if (isset($_POST['delete_invoice'])) {
    // Retrieve invoice ID
    $invoice_id = $_POST['invoice_id'];

    // Delete invoice items
    $delete_items_query = "DELETE FROM invoice_items WHERE invoice_id = $invoice_id";
    mysqli_query($conn, $delete_items_query);

    // Delete invoice
    $delete_invoice_query = "DELETE FROM invoices WHERE invoice_id = $invoice_id";
    mysqli_query($conn, $delete_invoice_query);

    // Redirect to this page to refresh the invoice list
    header("Location: invoices.php");
    exit();
}
?>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">All Sales</h1>
        <button onclick="purchaseReport()" class="btn btn-info"> Create Report</button>
        <form method="get">
            <div class="input-group date" style="margin-bottom: 20px; margin-top:10px">
                <label for="from" class="col-1 col-form-label">Employee: </label>
                <?php
                $employee_query = "SELECT * FROM employee";
                $employee_result = mysqli_query($conn, $employee_query);
                ?>
                <select class="form-control" aria-label="Default select example" name="emp" id="emp">
                    <option selected disabled>Select Emp.</option>
                    <?php while ($row = mysqli_fetch_assoc($employee_result)) : ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($filter_employee == $row['id']) ? 'selected' : ''; ?>> <?php echo $row['name']; ?> </option>
                    <?php endwhile; ?>
                </select>
                <label for="from" class="col-1 col-form-label">From</label>
                <input type="date" id="from" class="form-control" name="from" value="<?php echo $filter_from; ?>" autocomplete="off">
                <label for="to" class="col-0 col-form-label">to</label>
                <input type="date" id="to" class="form-control" name="to" value="<?php echo $filter_to; ?>" autocomplete="off">
                <input type="submit" class="btn btn-info" name="submit" value="Filter">
            </div>
        </form>
    </div>
    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%" id="table">
        <h1 id="invisible" class="d-none">Sales Report</h1>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Seller</th>
                <th>Customer Name</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Subtotal</th>
                <th>Date</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Initialize serial number based on offset
            $serial_number = $offset + 1;
            while ($row = mysqli_fetch_assoc($result_set)) : ?>
                <tr>
                    <td><?php echo $row['invoice_id']; ?></td>
                    <td><?php echo $row['employee_name']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['total']; ?></td>
                    <td><?php echo $row['discount']; ?>%</td>
                    <td><?php echo $row['subtotal']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                    <td><a href="invoice_details.php?invoice_id=<?php echo $row['invoice_id']; ?>" class="btn btn-info">Details</a></td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                            <input type="hidden" name="invoice_id" value="<?php echo $row['invoice_id']; ?>">
                            <button class="btn btn-danger" name="delete_invoice">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="pagination">
        <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>">&laquo; Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <!-- Next button -->
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>">Next &raquo;</a>
            </li>
        </ul>
    </div>
</div>

<script>
    $(function() {
        var dateFormat = "dd/mm/yy",
            from = $("#from")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: "yy-mm-dd", // Correct date format
            })
            .on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
            }),
            to = $("#to").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: "yy-mm-dd", // Correct date format
            })
            .on("change", function() {
                from.datepicker("option", "maxDate", getDate(this));
            });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    });

    const purchaseReport = () => {
        $("#invisible").removeClass("d-none");
        var divName = "zctb"; // Correct ID of the table
        var printContents = document.getElementById(divName).outerHTML; // Get outer HTML
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
