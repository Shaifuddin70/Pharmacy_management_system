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
    echo "<script>window.location='invoices.php'</script>";
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
<!-- <button class="btn btn-primary" onclick="window.print()">Print Invoice</button> -->

<div class="container" id="container">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">Invoice Details</h2>
        <!-- Invoice details -->
        <!-- Display only the date -->
        <?php
        // Format the date using the date() function
        $invoice_date = date('Y-m-d', strtotime($row['created_at']));
        ?>
        <h5 class="text-right">Invoice Date: <?php echo $invoice_date; ?></h5>
        <h5 class="text-right">Invoice ID: <?php echo $invoice_id; ?></h5>
        <h4 class="font-weight-bold mb-3">Cutomer Name: <?php echo $row['customer_name']; ?></h4>
    </div>

    <!-- Invoice items table -->
    <table id="zctb" class="display table table-bordered table-hover " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Unit Price (TK)</th>
                <th>Quantity</th>
                <th>Total Price (TK)</th>
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
                    <td><?php echo $item_row['total_price'] / $item_row['quantity']; ?></td>
                    <td><?php echo $item_row['quantity']; ?></td>
                    <td><?php echo $item_row['total_price']; ?> TK</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


    <!-- Discount and Subtotal -->
    <div class="discount-subtotal">
        <div class="float-right">
            <h5>Total: <?php echo $row['total']; ?> TK</h5>
            <h5>Discount: <?php echo $row['discount']; ?> %</h5>
            <h5>Subtotal: <?php echo $row['subtotal']; ?> TK</h5>
        </div>
    </div>
</div>

<!-- Print Button -->
<div class="container text-center mt-3">
<button onclick="purchaseReport()" class="btn btn-info">Print Invoice</button>
</div>
<script>
const purchaseReport = () => {
        var divName = "container";
        var printContents = document.getElementById(divName).outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>





<?php include 'nav/footer.php'; ?>