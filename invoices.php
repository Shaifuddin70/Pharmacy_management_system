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
?>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">All Invoices</h1>
        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Customer Name</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                    <th>Created At</th>
                    <th>Details</th> <!-- New column for buttons -->
                </tr>
            </thead>
            <tbody>
                <?php 
                $serial_number = $offset + 1; // Initialize serial number based on offset
                while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $serial_number++; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['discount']; ?></td>
                        <td><?php echo $row['subtotal']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><a href="invoice_details.php?invoice_id=<?php echo $row['invoice_id']; ?>" class="btn btn-info">Details</a></td> <!-- Button for details -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="pagination">
        <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <!-- Next button -->
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            </li>
        </ul>
    </div>
</div>

<?php include 'nav/footer.php'; ?>
