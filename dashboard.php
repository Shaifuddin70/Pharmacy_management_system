<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit(); // Stop further execution
}


// Retrieve data from the database
$total_orders = 0;
$total_sales = 0;
$total_profit = 0;
$total_purchase = 0;

$sql_orders = "SELECT COUNT(*) AS total_orders FROM invoices";
$result_orders = $conn->query($sql_orders);
if ($result_orders && $result_orders->num_rows > 0) {
    $row_orders = $result_orders->fetch_assoc();
    $total_orders = $row_orders['total_orders'];
}

$sql_sales = "SELECT SUM(subtotal) AS total_sales FROM invoices";
$result_sales = $conn->query($sql_sales);
if ($result_sales && $result_sales->num_rows > 0) {
    $row_sales = $result_sales->fetch_assoc();
    $total_sales = $row_sales['total_sales'];
}

// Calculate total profit
$sql_profit = "SELECT SUM(profit) AS total_profit FROM invoices";
$result_profit = $conn->query($sql_profit);
if ($result_profit && $result_profit->num_rows > 0) {
    $row_profit = $result_profit->fetch_assoc();
    $total_profit = $row_profit['total_profit'];
}
// Initialize total purchase


// Retrieve data from the purchase table
$sql_purchase = "SELECT  unit, pprice FROM purchase_table";
$result_purchase = $conn->query($sql_purchase);

// Check if query was successful and there are rows returned
if ($result_purchase && $result_purchase->num_rows > 0) {
    // Loop through each row
    while ($row_purchase = $result_purchase->fetch_assoc()) {
        // Calculate total purchase for each medicine by multiplying unit with price
        $total_purchase += $row_purchase['unit'] * $row_purchase['pprice'];
    }
}
// Fetch recent 5 invoices
$sql_recent_invoices = "SELECT invoices.invoice_id, invoices.subtotal,invoices.created_at, customer.customer_name 
                        FROM invoices 
                        INNER JOIN customer ON invoices.customer_id = customer.customer_id
                        ORDER BY invoices.invoice_id DESC LIMIT 5";
$result_recent_invoices = $conn->query($sql_recent_invoices);



?>

<head>
    <link rel="stylesheet" href="nav/dashboard.css">
</head>
<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Order</div>
                <div class="number"><?php echo $total_orders; ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Till Now</span>
                </div>
            </div>
            <i class="bx bx-cart-alt cart"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Sales</div>
                <div class="number"><?php echo $total_sales; ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Till Now</span>
                </div>
            </div>
            <i class="bx bxs-cart-add cart two"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Profit</div>
                <div class="number"><?php echo $total_profit; ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Till Now</span>
                </div>
            </div>
            <i class="bx bx-cart cart three"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Total Purchase</div>
                <div class="number"><?php echo $total_purchase; ?></div>
                <div class="indicator">
                    <i class="bx bx-down-arrow-alt down"></i>
                    <span class="text">Till Now</span>
                </div>
            </div>
            <i class="bx bxs-cart-download cart four"></i>
        </div>
    </div>

    <div class="sales-boxes">
        <div class="recent-sales box">
            <div class="title text-center">Recent Sales</div>
            <div class="sales-details">
                <table id="zctb" class="display table  table-hover text-center border" cellspacing="0" width="100%">
                    <?php
                    if ($result_recent_invoices && $result_recent_invoices->num_rows > 0) {

                        echo "<tr><th>Date</th><th>Invoice ID</th><th>Customer</th><th>Total Amount</th></tr>";
                        while ($row_invoice = $result_recent_invoices->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>". date('Y-m-d', strtotime($row_invoice['created_at'])) . "</td>";
                            echo "<td>" . $row_invoice['invoice_id'] . "</td>";
                            echo "<td>" . $row_invoice['customer_name'] . "</td>";
                            echo "<td>" . $row_invoice['subtotal'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "No recent sales found.";
                    }
                    ?>
                </table>
            </div>
            <div class="button">
                <a href="invoices.php">See All</a>
            </div>
        </div>
        <div class="top-sales box">
    <div class="title text-center">Top Selling Medicine</div>
    <table id="zctb" class="display table  table-hover text-center border" cellspacing="0" width="100%">
        <tr>
            <th>Medicine Name</th>
            <th>Total Quantity Sold</th>
        </tr>
        <?php
        // Fetch top-selling medicines
        $sql_top_medicines = "SELECT m.medicine_name, SUM(i.quantity) as total_quantity 
                              FROM invoice_items i 
                              INNER JOIN medicine m ON i.medicine_id = m.medicine_id 
                              GROUP BY i.medicine_id 
                              ORDER BY total_quantity DESC 
                              LIMIT 5";
        $result_top_medicines = $conn->query($sql_top_medicines);
        if ($result_top_medicines && $result_top_medicines->num_rows > 0) {
            while ($row_top_medicines = $result_top_medicines->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_top_medicines['medicine_name'] . "</td>";
                echo "<td>" . $row_top_medicines['total_quantity'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No top selling medicine found.</td></tr>";
        }
        ?>
    </table>
</div>


    </div>
</div>
<?php include 'nav/footer.php'; ?>