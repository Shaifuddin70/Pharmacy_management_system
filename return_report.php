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

// Determine the type of report: sales or purchases
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : 'sales';

// Calculate total number of records based on the report type
if ($report_type === 'purchases') {
    $total_records_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM purchase_table");
} else {
    $total_records_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoices");
}
$total_records_row = mysqli_fetch_assoc($total_records_query);
$total_records = $total_records_row['total'];

// Calculate total number of pages
$total_pages = ceil($total_records / $results_per_page);

// Get current page number from URL
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

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

if (!empty($filter_from)) {
    $where_clause .= " AND DATE(purchase_table.date) >= '$filter_from'";
}
if (!empty($filter_to)) {
    $where_clause .= " AND DATE(purchase_table.date) <= '$filter_to'";
}

// Fetch data with customer names from the database with filtering
$query = "SELECT purchase_table.id, medicine.medicine_name, purchase_table.unit, purchase_table.pprice, purchase_table.sprice, purchase_table.date
          FROM purchase_table 
          INNER JOIN medicine ON purchase_table.medicine_id = medicine.medicine_id
          WHERE 1 $where_clause
          ORDER BY purchase_table.date DESC
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

<div class="container" style="margin-top: -45px;">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">Purchase Report</h2>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) : ?>
            <button onclick="generateReport()" class="btn btn-success"> Create Report</button>
        <?php endif; ?>
        <form method="get">
            <div class="input-group date" style="margin-bottom: 20px; margin-top:10px">
                <label for="from" class="col-0 col-form-label mx-2">From</label>
                <input type="date" id="from" class="form-control" name="from" value="<?php echo $filter_from; ?>" autocomplete="off">
                <label for="to" class="col-0 col-form-label mx-2">to</label>
                <input type="date" id="to" class="form-control mx-2" name="to" value="<?php echo $filter_to; ?>" autocomplete="off">
                <input type="hidden" name="report_type" value="<?php echo $report_type; ?>">
                <input type="submit" class="btn btn-info" name="submit" value="Filter">
            </div>
        </form>
    </div>
    <div id="table">
        <h2 id="invisible" class="d-none text-center text-uppercase p-2">Purchase Report</h2>
        <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Medicine Name</th>
                    <th>Unit</th>
                    <th>Purchase Price</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 1;
                while ($row = mysqli_fetch_assoc($result_set)) : ?>
                    <tr>
                        <td><?php echo $c; ?></td>
                        <td><?php echo $row['medicine_name']; ?></td>
                        <td><?php echo $row['unit']; ?></td>
                        <td><?php echo $row['pprice']; ?></td>
                        <td><?php echo $row['unit'] * $row['pprice']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                    </tr>
                <?php $c++; endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>&report_type=<?php echo $report_type; ?>">&laquo; Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>&report_type=<?php echo $report_type; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&emp=<?php echo $filter_employee; ?>&from=<?php echo $filter_from; ?>&to=<?php echo $filter_to; ?>&report_type=<?php echo $report_type; ?>">Next &raquo;</a>
            </li>
        </ul>
    </div>
</div>

<script>
    $(function() {
        var dateFormat = "yy-mm-dd",
            from = $("#from")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: dateFormat
            })
            .on("change", function() {
                to.datepicker("option", "minDate", getDate(this));
            }),
            to = $("#to").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: dateFormat
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

    const generateReport = () => {
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
