<?php
include 'nav/nav.php';

// Authorization check (if needed)
// Check if invoice_id is provided in the URL
if (!isset($_GET['invoice_id'])) {
    echo "<script>alert('Invoice ID not provided')</script>";
    echo "<script>window.location='all_invoices.php'</script>";
    exit();
}

$invoice_id = $_GET['invoice_id'];

// Fetch invoice details from the database
$query = "SELECT invoices.invoice_id, customer.customer_name, invoices.discount, invoices.subtotal,invoices.total, invoices.created_at, 
          invoice_items.medicine_id, Medicine.medicine_name, invoice_items.quantity, invoice_items.total_price 
          FROM invoices 
          INNER JOIN customer ON invoices.customer_id = customer.customer_id 
          INNER JOIN invoice_items ON invoices.invoice_id = invoice_items.invoice_id 
          INNER JOIN Medicine ON invoice_items.medicine_id = Medicine.medicine_id 
          WHERE invoices.invoice_id = $invoice_id";
$result = mysqli_query($conn, $query);

// Check if the invoice exists
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Invoice not found')</script>";
    echo "<script>window.location='all_invoices.php'</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);
?>

<!-- CSS for Printing -->
<style>
/* Hide unnecessary elements */
body {
    background: none;
}

/* Adjust styles for printing */
.container {
    width: 100%;
    margin: 0 auto;
    padding: 20px;
}

/* Additional styles for better print appearance */
.discount-subtotal {
    margin-top: 20px;
}

.float-right {
    float: right;
}
</style>

<!-- Print Button -->
<button class="btn btn-primary" onclick="window.print()">Print Invoice</button>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">Invoice Details</h1>
        <!-- Invoice details -->
        <h3>Invoice ID: <?php echo $invoice_id; ?></h3>
        <h4>Customer Name: <?php echo $row['customer_name']; ?></h4>

        <!-- Invoice items table -->
        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset result pointer
                mysqli_data_seek($result, 0);
                // Fetch invoice items from the result set
                while ($item_row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $item_row['medicine_name']; ?></td>
                        <td><?php echo $item_row['quantity']; ?></td>
                        <td><?php echo $item_row['total_price']; ?> TK</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Discount and Subtotal -->
        <div class="discount-subtotal">
            <div class="float-right">
                <h4>Total: <?php echo $row['total']; ?> TK</h4>
                <h4>Discount: <?php echo $row['discount']; ?> %</h4>
                <h4>Subtotal: <?php echo $row['subtotal']; ?> TK</h4>
            </div>
        </div>
    </div>
</div>

<?php include 'nav/footer.php'; ?>
