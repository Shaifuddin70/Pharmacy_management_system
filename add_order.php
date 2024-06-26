<?php
include 'nav/nav.php';
error_reporting(0);

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Retrieve employee ID from session if available
$employee_id = isset($_SESSION['eid']) ? $_SESSION['eid'] : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $customer_id = $_POST['customer'];
    $discount = $_POST['discount'];
    $subtotal = $_POST['submit_subtotal'];
    $total = $_POST['submit_total'];
    $medicines = $_POST['medicine'];
    $quantities = $_POST['quantity'];
    $total_prices = $_POST['submit_total_price'];

    // Round subtotal as per the required logic
    $decimalPart = $subtotal - floor($subtotal);
    $rounded_subtotal = ($decimalPart > 0.5) ? ceil($subtotal) : floor($subtotal);

    // Calculate total profit for the invoice
    $total_pprice = 0;

    foreach ($medicines as $index => $medicine_id) {
        $quantity = $quantities[$index] ?? '';
        $total_price = $total_prices[$index] ?? '';

        // Retrieve the buying price of the medicine from the database
        $query = "SELECT pprice FROM medicine_stock WHERE medicine_id = $medicine_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $buying_price = $row['pprice'];
        $total_pprice += $buying_price * $quantity;
    }
    $profit = ($rounded_subtotal - $total_pprice);

    // Insert invoice data into the database
    $query = "INSERT INTO invoices (customer_id, employee_id, discount, subtotal, total, profit) 
              VALUES ('$customer_id', '$employee_id', '$discount', '$rounded_subtotal', '$total', '$profit')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    } else {
        // Retrieve the auto-generated invoice_id after insertion
        $invoice_id = mysqli_insert_id($conn);

        // Insert invoice items into the database
        foreach ($medicines as $index => $medicine_id) {
            $quantity = $quantities[$index] ?? '';
            $total_price = $total_prices[$index] ?? '';

            $query = "INSERT INTO invoice_items (invoice_id, medicine_id, quantity, total_price) 
                      VALUES ('$invoice_id', '$medicine_id', '$quantity', '$total_price')";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }

        // Update the medicine stock
        foreach ($medicines as $index => $medicine_id) {
            $quantity = $quantities[$index] ?? '';

            // Deduct the sold quantity from the medicine stock
            $updateQuery = "UPDATE medicine_stock SET unit = unit - $quantity WHERE medicine_id = $medicine_id";
            $updateResult = mysqli_query($conn, $updateQuery);
        }

        echo "<script>alert('Invoice submitted successfully')</script>";
        echo "<script>window.location='invoices.php'</script>";
        // End script execution after redirection
        exit();
    }
}
?>

<div class="container">
    <div class="title">
        <form action="" method="post" onsubmit="return validateForm()">
            <h2 class="text-center text-uppercase p-2">Create New Invoice</h2>
            <?php
            // Retrieve the maximum invoice_id from the invoices table
            $query = "SELECT MAX(invoice_id) AS max_id FROM invoices";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $invoice_id = $row['max_id'] + 1;
            ?>
            <h5 style="text-align: right;">Invoice Id: <?php echo $invoice_id; ?></h5>
            <div class="customer">
                <h5>Customer: </h5>
                <?php
                // Retrieve customer data from the database
                $query = "SELECT * FROM customer";
                $result = mysqli_query($conn, $query);
                ?>
                <select class="form-control form-control col-3 ml-2 mb-2" aria-label="Default select example" name="customer" id="customer" required>
                    <option selected disabled>Select Customer</option>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <option value="<?php echo $row['customer_id']; ?>"> <?php echo $row['customer_name']; ?> </option>
                    <?php endwhile; ?>
                </select>
                <a href="add_customer.php">
                    <p class="col-2 btn btn-primary col-12 ml-2"> Add Customer</p>
                </a>
            </div>
            <div class="invoice_select">
                <table id="medicineTable" class="display table table-bordered table-hover text-center">
                    <tr>
                        <th>Medicine</th>
                        <th>Available</th>
                        <th>Sell</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    <tr class="medicine-row">
                        <td>
                            <?php
                            // Retrieve medicine data from the database
                            $query = "SELECT m.* FROM Medicine m INNER JOIN medicine_stock s ON m.medicine_id = s.medicine_id WHERE s.unit > 0";
                            $result = mysqli_query($conn, $query);
                            ?>
                            <select class="form-control form-control-lg medicine" aria-label="Default select example" name="medicine[]" required>
                                <option selected disabled>Select Medicine</option>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <option value="<?php echo $row['medicine_id']; ?>"> <?php echo $row['medicine_name']; ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg available_quantity" name="available_quantity[]" placeholder="Available Quantity" disabled>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-lg quantity" name="quantity[]" placeholder="Item Quantity" required>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg unit_price" name="unit_price[]" placeholder="Unit Price" disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg total_price" name="total_price[]" placeholder="Total Price" disabled>
                            <input type="hidden" class="submit_total_price" name="submit_total_price[]" value="">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger delete-row">Delete</button>
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-success add-row">Add New Medicine</button>
                <div class="subtotal col-3 mb-2 mt-2">
                    <label for="total">Total: </label>
                    <input type="text" class="form-control form-control-lg" name="total" id="total" placeholder="Total" disabled>
                    <input type="hidden" id="submit_total" name="submit_total" value="">
                    <label for="discount">Discount (%): </label>
                    <input type="number" class="form-control" name="discount" id="discount" placeholder="Enter discount percentage">
                    <div class="sub-total">
                        <div class="actual-total">
                            <label for="actual_subtotal">Actual Subtotal: </label>
                            <input type="text" class="form-control" name="actual_subtotal" id="actual_subtotal" placeholder="Actual Subtotal" disabled>
                        </div>
                        <div class="equal">
                            <span>≈</span>
                        </div>
                        <div class="rounded-subtotal">
                            <label for="subtotal">Rounded Subtotal: </label>
                            <input type="text" class="form-control" name="subtotal" id="subtotal" placeholder="Subtotal" disabled>
                        </div>
                    </div>
                    <input type="hidden" id="submit_subtotal" name="submit_subtotal" value="">
                </div>
                <div class="text-center col-3 mb-2 mt-2">
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // When the medicine selection changes
    $(document).on('change', '.medicine', function() {
        var medicine_id = $(this).val(); // Get the selected medicine ID
        var row = $(this).closest('.medicine-row');
        $.ajax({
            url: 'ajax/getprice.php',
            type: 'POST',
            data: {
                category_data: medicine_id // Send medicine_id instead of catagory_data
            },
            success: function(result) {
                var data = JSON.parse(result);
                if (data.error) {
                    // Handle error when medicine is not available
                    row.find('.available_quantity').val('NA');
                    row.find('.unit_price').val('NA');
                } else {
                    // Update available quantity and unit price
                    row.find('.available_quantity').val(data.available_quantity);
                    row.find('.unit_price').val(data.unit_price);
                    row.find('.quantity').val(''); // Clear the quantity field
                    row.find('.total_price').val(''); // Clear the total price field
                    row.find('.submit_total_price').val(''); // Clear the hidden total price field
                }
            }
        });
    });

    // When quantity changes
    $(document).on('input', '.quantity', function() {
        var row = $(this).closest('.medicine-row');
        calculateTotalPrice(row); // Calculate total price
    });

    // Function to calculate the total price
    function calculateTotalPrice(row) {
        var availableQuantity = parseFloat(row.find('.available_quantity').val());
        var quantity = parseFloat(row.find('.quantity').val());
        var unitPrice = parseFloat(row.find('.unit_price').val());
        var totalPriceInput = row.find('.total_price');
        var submitTotalPriceInput = row.find('.submit_total_price');

        if (!isNaN(quantity) && !isNaN(unitPrice)) {
            if (quantity > availableQuantity) {
                // Handle the case where the ordered quantity is greater than the available quantity
                alert("Quantity exceeds available stock.");
                row.find('.quantity').val('');
                totalPriceInput.val('');
                submitTotalPriceInput.val('');
                calculateSubtotal(); // Recalculate the subtotal
            } else {
                var totalPrice = quantity * unitPrice;
                totalPriceInput.val(totalPrice.toFixed(2));
                submitTotalPriceInput.val(totalPrice.toFixed(2));
                calculateSubtotal(); // Recalculate the subtotal
            }
        } else {
            totalPriceInput.val('');
            submitTotalPriceInput.val('');
        }
    }

    function calculateSubtotal() {
        var total = 0;
        $('.total_price').each(function() {
            var price = parseFloat($(this).val());
            if (!isNaN(price)) {
                total += price;
            }
        });

        var discount = parseFloat($('#discount').val());
        var subtotal = total;

        if (!isNaN(discount) && discount > 0) {
            var discountAmount = total * (discount / 100);
            subtotal = total - discountAmount;
        }

        // Calculate rounded subtotal
        var roundedSubtotal;
        var decimalPart = subtotal - Math.floor(subtotal);

        if (decimalPart > 0.5) {
            roundedSubtotal = Math.ceil(subtotal);
        } else {
            roundedSubtotal = Math.floor(subtotal);
        }

        // Update visible fields
        $('#total').val(total.toFixed(2)); // Update the total field
        $('#subtotal').val(roundedSubtotal.toFixed(2)); // Update the rounded subtotal field
        $('#actual_subtotal').val(subtotal.toFixed(2)); // Update the actual subtotal field

        // Update hidden fields for submission
        $('#submit_total').val(total.toFixed(2));
        $('#submit_subtotal').val(roundedSubtotal.toFixed(2));
    }

    $(document).on('input', '#discount', function() {
        calculateSubtotal(); // Recalculate the subtotal when discount changes
    });
    // Add new medicine row
    $('.add-row').click(function() {
        var newRow = $('.medicine-row:first').clone();
        newRow.find('input').val('');
        newRow.find('select').val('');
        $('#medicineTable').append(newRow);
    });

    // Delete medicine row
    $(document).on('click', '.delete-row', function() {
        $(this).closest('.medicine-row').remove();
        calculateSubtotal(); // Recalculate the subtotal
    });

    // Validate form before submission
    function validateForm() {
        var customer = $('#customer').val();
        if (!customer) {
            alert('Please select a customer.');
            return false;
        }
        return true;
    }
</script>