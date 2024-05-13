<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Check if customer_id is provided and numeric
if(isset($_GET['customer_id']) && is_numeric($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Pagination variables
    $results_per_page = 10; // Number of results per page

    // Get current page number from URL
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    // Ensure page is within bounds
    $page = max(1, $page);

    // Calculate offset
    $offset = ($page - 1) * $results_per_page;

    // Fetch invoices for the selected customer
    $query = "SELECT invoices.invoice_id, customer.customer_name, employee.name AS employee_name, invoices.discount, invoices.subtotal, invoices.total, invoices.created_at 
              FROM invoices 
              INNER JOIN customer ON invoices.customer_id = customer.customer_id
              INNER JOIN employee ON invoices.employee_id = employee.id
              WHERE customer.customer_id = $customer_id
              ORDER BY invoices.created_at DESC
              LIMIT $offset, $results_per_page";

    $result_set = mysqli_query($conn, $query);

    // Check if query was successful
    if (!$result_set) {
        die("Error: " . mysqli_error($conn));
    }

    // Calculate total number of invoices for pagination
    $total_invoices_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices WHERE customer_id = $customer_id");
    $total_invoices_row = mysqli_fetch_assoc($total_invoices_query);
    $total_invoices = $total_invoices_row['total'];

    // Calculate total number of pages
    $total_pages = ceil($total_invoices / $results_per_page);
} else {
    // Handle case where customer_id is not provided or not numeric
    echo "Customer ID is missing or invalid.";
    exit();
}
?>

<div class="container" style="margin-top: -45px;">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">All Sales</h2>
        <button onclick="purchaseReport()" class="btn btn-success mb-3"> Create Report</button>
    </div>
    <div id="table">
        <h2 id="invisible" class="d-none text-center text-uppercase p-2">Sales Report</h2>
        <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Seller</th>
                    <th>Customer Name</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                    <th>Date</th>
                    <th id="visible">Details</th>
                    <th id="visible">Action</th>
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
                        <td id="visible"><a href="invoice_details.php?invoice_id=<?php echo $row['invoice_id']; ?>" class="btn btn-info">Details</a></td>
                        <td id="visible">
                            <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                                <input type="hidden" name="invoice_id" value="<?php echo $row['invoice_id']; ?>">
                                <button class="btn btn-danger" name="delete_invoice">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&customer_id=<?php echo $customer_id; ?>">&laquo; Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&customer_id=<?php echo $customer_id; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&customer_id=<?php echo $customer_id; ?>">Next &raquo;</a>
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
                dateFormat: "yy-mm-dd",
            })
            .on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
            }),
            to = $("#to").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: "yy-mm-dd",
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
        $("th#visible, td#visible").addClass("d-none");
        var divName = "table";
        var printContents = document.getElementById(divName).outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        $("#invisible").addClass("d-none");
        $("th#visible, td#visible").removeClass("d-none");
    }
</script>
<?php
include 'nav/footer.php';
?>
