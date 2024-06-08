<head>
    <link rel="stylesheet" href="invoice.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
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
$query = "SELECT invoices.invoice_id, customer.customer_name, invoices.discount, invoices.subtotal, invoices.total, invoices.created_at, 
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

$invoice_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $invoice_items[] = $row;
}

// Get customer and invoice information from the first item
$customer_name = $invoice_items[0]['customer_name'];
$invoice_date = date('Y-m-d', strtotime($invoice_items[0]['created_at']));
$subtotal = $invoice_items[0]['subtotal'];
$discount = $invoice_items[0]['discount'];
$total = $invoice_items[0]['total'];
?>

<div class="col-md-12">   
 <div class="row">
 <div class="receipt-main ">
            <div class="row">
                <div class="receipt-header">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="receipt-left">
                            <img class="img-responsive" alt="company_logo" src="https://bootdey.com/img/Content/avatar/avatar6.png" style="width: 71px; border-radius: 43px;">
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="receipt-right">
                            <h5>Company Name</h5>
                            <p>+1 3649-6589 <i class="fa fa-phone"></i></p>
                            <p>company@gmail.com <i class="fa fa-envelope-o"></i></p>
                            <p>USA <i class="fa fa-location-arrow"></i></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="receipt-header receipt-header-mid">
                    <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                        <div class="receipt-right">
                            <h5><?php echo $customer_name; ?></h5>
                            <p><b>Date:</b> <?php echo $invoice_date; ?></p>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="receipt-left">
                            <h3>INVOICE # <?php echo $invoice_id; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoice_items as $item) : ?>
                        <tr>
                            <td class="col-md-9"><?php echo $item['medicine_name']; ?></td>
                            <td class="col-md-3"><?php echo $item['quantity']; ?></td>
                            <td class="col-md-3"><?php echo $item['total_price']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-right" colspan="2">
                            <p>
                                <strong>Subtotal: </strong>
                            </p>
                            <p>
                                <strong>Discount: </strong>
                            </p>
                            <p>
                                <strong>Total: </strong>
                            </p>
                            </td>
                            <td>
                            <p>
                                <strong><?php echo $subtotal; ?></strong>
                            </p>
                            <p>
                                <strong><?php echo $discount; ?>%</strong>
                            </p>
                            <p>
                                <strong><?php echo $total; ?></strong>
                            </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="row">
                <div class="receipt-header receipt-header-mid receipt-footer">
                    <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                        <div class="receipt-right">
                            <p><b>Date :</b> <?php echo $invoice_date; ?></p>
                            <h5 style="color: rgb(140, 140, 140);">Thanks for shopping!</h5>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>    
    </div>
</div>
<?php
include 'nav/footer.php';
?>
