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

// Fetch invoices with customer names from the database
$query = "SELECT invoices.invoice_id, customer.customer_name, invoices.discount, invoices.subtotal, invoices.total, invoices.created_at 
FROM invoices 
INNER JOIN customer ON invoices.customer_id = customer.customer_id
ORDER BY invoices.created_at DESC
LIMIT $offset, $results_per_page";
$result = mysqli_query($conn, $query);


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
        <h1 class="font-weight-bold">All Invoices</h1>
        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Invoice ID</th> <!-- Changed header to 'Invoice ID' -->
                    <th>Customer Name</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                    <th>Created At</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize serial number based on offset
                $serial_number = $offset + 1;
                while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <!-- Display the invoice ID instead of serial number -->
                        <td><?php echo $row['invoice_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['discount']; ?></td>
                        <td><?php echo $row['subtotal']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><a href="invoice_details.php?invoice_id=<?php echo $row['invoice_id']; ?>" class="btn btn-info">Details</a></td>
                        <td>
                            <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                                <input type="hidden" name="invoice_id" value="<?php echo $row['invoice_id']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete_invoice">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="pagination">
        <ul class="pagination">
            <!-- Pagination links -->
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            </li>
        </ul>
    </div>
</div>

<?php include 'nav/footer.php'; ?>