<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit(); // Stop further execution
}

// Fetch medicine details based on selection
if (isset($_GET['acceptid'])) {
    $id = $_GET['acceptid'];

    // Fetch details from purchase_table
    $purchase_query = "SELECT * FROM purchase_table WHERE id = ?";
    $purchase_stmt = mysqli_prepare($conn, $purchase_query);
    mysqli_stmt_bind_param($purchase_stmt, "i", $id);
    mysqli_stmt_execute($purchase_stmt);
    $purchase_result = mysqli_stmt_get_result($purchase_stmt);
    $purchase_row = mysqli_fetch_assoc($purchase_result);

    // Retrieve necessary data
    $medicine_id = $purchase_row['medicine_id'];
    $quantity = $purchase_row['unit'];
    // Fetch medicine details from medicine table using medicine_id from purchase_table
    $medicine_id = $purchase_row['medicine_id'];
    $medicine_query = "SELECT * FROM medicine WHERE medicine_id = ?";
    $medicine_stmt = mysqli_prepare($conn, $medicine_query);
    mysqli_stmt_bind_param($medicine_stmt, "i", $medicine_id);
    mysqli_stmt_execute($medicine_stmt);
    $medicine_result = mysqli_stmt_get_result($medicine_stmt);
    $medicine_row = mysqli_fetch_assoc($medicine_result);

    // Fetch shelf numbers from database for dropdown
    $shelf_query = "SELECT * FROM shelf";
    $shelf_result = mysqli_query($conn, $shelf_query);

    // Fetch supplier from database for dropdown
    $supplier_query = "SELECT * FROM supplier";
    $supplier_result = mysqli_query($conn, $supplier_query);
} else {
    // If acceptid is not provided, handle the scenario accordingly
    echo "<script>alert('Purchase ID not provided');</script>";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $shelf_number = $_POST['shelf_number'];
    $expiry_date = $_POST['expiry_date'];
    $purchase_price = $_POST['purchase_price'];
    $sell_price = $_POST['sell_price'];
    $supplier_id = $_POST['supplier_id']; // Corrected column name

    // Check if the medicine is already present in the medicine_stock table
    $stock_query = "SELECT * FROM medicine_stock WHERE medicine_id = ?";
    $stock_stmt = mysqli_prepare($conn, $stock_query);
    mysqli_stmt_bind_param($stock_stmt, "i", $medicine_id);
    mysqli_stmt_execute($stock_stmt);
    $stock_result = mysqli_stmt_get_result($stock_stmt);
    $num_rows = mysqli_num_rows($stock_result);

    if ($num_rows > 0) {
        // If medicine is already present, update the existing record
        $update_query = "UPDATE medicine_stock SET unit = unit + ?, expiry_date = ?, pprice = ?, sprice = ?, shelf_id = ?, supplier_id = ? WHERE medicine_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "isddiii", $quantity, $expiry_date, $purchase_price, $sell_price, $shelf_number, $supplier_id, $medicine_id);
        mysqli_stmt_execute($update_stmt);
    } else {
        // If medicine is not present, insert a new record
        $insert_query = "INSERT INTO medicine_stock (medicine_id, unit, shelf_id, expiry_date, pprice, sprice, supplier_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iiissdi", $medicine_id, $quantity, $shelf_number, $expiry_date, $purchase_price, $sell_price, $supplier_id);
        mysqli_stmt_execute($insert_stmt);
    }

    // Update status and other fields in purchase_table to mark the purchase as accepted
    $update_query = "UPDATE purchase_table SET status = 1, pprice = ?, sprice = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ddi", $purchase_price, $sell_price, $id);
    mysqli_stmt_execute($update_stmt);

    // Redirect to a success page or display a success message
    echo "<script>alert('Purchase accepted and added to stock successfully')</script>";
    echo "<script>window.location='stock.php'</script>";
    exit();
}

?>

<div class="container">
    <h2 class="text-center text-uppercase p-2">Accept Medicine Purchase</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?acceptid=" . $id); ?>">
        <table id="zctb"class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td><label for="medicine_name">Medicine Name:</label></td>
                    <td><input type="text" class="form-control" id="medicine_name" value="<?php echo $medicine_row['medicine_name']; ?>" readonly></td>
                </tr>
                <tr>
                    <td><label for="quantity">Quantity:</label></td>
                    <td><input type="text" class="form-control" id="quantity" value="<?php echo $quantity; ?>" readonly></td>
                </tr> 
                <tr>
                    <td><label for="shelf_number">Supplier:</label></td>
                    <td>
                        <select class="form-control" id="supplier_id" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            <?php while ($row = mysqli_fetch_assoc($supplier_result)) : ?>
                                <option value="<?php echo $row['supplier_id']; ?>"><?php echo $row['supplier_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="shelf_number">Shelf Number:</label></td>
                    <td>
                        <select class="form-control" id="shelf_number" name="shelf_number" required>
                            <option value="">Select Shelf Number</option>
                            <?php while ($row = mysqli_fetch_assoc($shelf_result)) : ?>
                                <option value="<?php echo $row['shelf_id']; ?>"><?php echo $row['shelf_number']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="expiry_date">Expiry Date:</label></td>
                    <td><input type="date" class="form-control" id="expiry_date" name="expiry_date" required></td>
                </tr>
                <tr>
                    <td><label for="purchase_price">Purchase Price:</label></td>
                    <td><input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" required></td>
                </tr>
                <tr>
                    <td><label for="sell_price">Sell Price:</label></td>
                    <td><input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" required></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Accept Purchase</button>
    </form>
</div>

<?php include 'nav/footer.php'; ?>
