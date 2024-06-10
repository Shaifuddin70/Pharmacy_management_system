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
if (isset($_POST['submit'])) {
    // Retrieve form data
    $customer_id = $_POST['customer'];
    $discount = $_POST['discount'];
    $subtotal = $_POST['submit_subtotal'];
    $total = $_POST['submit_total'];
    $medicines = $_POST['medicine'];
    $quantities = $_POST['quantity'];
    $total_prices = $_POST['submit_total_price'];

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
        // Calculate profit for the current item
        echo $total_pprice . '" "';
    }
    $profit = ($subtotal - $total_pprice);
    // Add profit to the total profit
    // Insert invoice data into the database
    $query = "INSERT INTO invoices (customer_id, employee_id, discount, subtotal, total, profit) 
              VALUES ('$customer_id', '$employee_id', '$discount', '$subtotal', '$total', '$profit')";
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
                <select class="form-control form-control col-3 ml-2 mb-2" aria-label="Default select example" name="customer" id="customer">
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
                            <select class="form-control form-control-lg medicine" aria-label="Default select example" name="medicine[]" id="medicine">
                                <option selected disabled>Select Medicine</option>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <option value="<?php echo $row['medicine_id']; ?>"> <?php echo $row['medicine_name']; ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg available_quantity" name="available_quantity[]" id="available_quantity" aria-describedby="sizing-addon1" placeholder="Available Quantity" disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg quantity" required="true" name="quantity[]" id="quantity" placeholder="Item Quantity">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg unit_price" name="unit_price[]" id="unit_price" aria-describedby="sizing-addon1" placeholder="Unit Price" disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-lg total_price" name="total_price[]" id="total_price" aria-describedby="sizing-addon1" placeholder="Total Price" disabled>
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
                    <input type="text" class="form-control form-control-lg " name="total" id="total" placeholder="Total" disabled>
                    <input type="hidden" id="submit_total" name="submit_total" value="">
                    <label for="discount">Discount (%): </label>
                    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount %" required>
                    <label for="subtotal">Subtotal: </label>
                    <input type="text" class="form-control form-control-lg" name="subtotal" id="subtotal" placeholder="Subtotal" disabled>
                    <input type="hidden" id="submit_subtotal" name="submit_subtotal" value="">
                </div>
            </div>
            <div class="button">
                <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
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
                    calculateTotalPrice(row); // Calculate total price
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // In your JavaScript
    function calculateTotalPrice(row) {
        var sellQuantity = parseInt(row.find('.quantity').val()); // Get the sell quantity
        var unitPrice = parseFloat(row.find('.unit_price').val()); // Get the unit price
        var totalPrice = sellQuantity * unitPrice; // Calculate total price

        row.find('.total_price').val(totalPrice.toFixed(2)); // Set the total price in the visible field
        row.find('.submit_total_price').val(totalPrice.toFixed(2)); // Update hidden field
        calculateSubtotal(); // Recalculate subtotal
    }




    // When the sell quantity changes
    $(document).on('input', '.quantity', function() {
        var row = $(this).closest('.medicine-row');
        calculateTotalPrice(row); // Recalculate total price

    });

    // When the delete button is clicked
    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove(); // Remove the closest row
        calculateSubtotal(); // Recalculate subtotal
    });

    // Add Row
    $('.add-row').click(function() {
        // Check if there are existing rows in the table
        if ($('#medicineTable tbody tr').length === 1) {
            // If no rows exist, add a new row without cloning the last row
            $('#medicineTable tbody').append('<tr class="medicine-row">' +
                '<td><?php
                        $catagory = "SELECT * FROM Medicine";
                        $result = mysqli_query($conn, $catagory);
                        ?> <
                select class = "form-control form-control-lg medicine"
                aria - label = "Default select example"
                name = "medicine[]"
                id = "medicine" > < option selected disabled > Select Medicine < /option><?php while ($row = mysqli_fetch_assoc($result)) : ?><option value="<?php echo $row['medicine_id']; ?>"> <?php echo $row['medicine_name']; ?> </option > <?php endwhile; ?> < /select></td > ' +
            '<td><input type="text" class="form-control form-control-lg available_quantity" name="available_quantity[]" id="available_quantity" aria-describedby="sizing-addon1" placeholder="Available Quantity" disabled></td>' +
            '<td><input type="text" class="form-control form-control-lg quantity" required="true" name="quantity[]" id="quantity" placeholder="Item Quantity"></td>' +
            '<td><input type="text" class="form-control form-control-lg unit_price" name="unit_price[]" id="unit_price" aria-describedby="sizing-addon1" placeholder="Unit Price" disabled></td>' +
            '<td><input type="text" class="form-control form-control-lg total_price" name="total_price[]" id="total_price" aria-describedby="sizing-addon1" placeholder="Total Price" disabled></td>' +
            '<td><button type="button" class="btn btn-danger delete-row">Delete</button></td>' +
            '</tr>');
        } else {
            // If rows exist, clone the last row and add a new one
            var lastRow = $('#medicineTable tbody tr').last(); // Get the last row
            var newRow = lastRow.clone(); // Clone the last row
            newRow.find('input').val(''); // Clear input values in the new row
            newRow.find('select').prop('selectedIndex', 0); // Reset select element to default
            // newRow.find('.quantity').val('1'); // Set default sell quantity to 1
            $('#medicineTable tbody').append(newRow); // Append the new row to the table body
        }
    });

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

        // Update visible fields
        $('#total').val(total.toFixed(2)); // Update the total field
        $('#subtotal').val(subtotal.toFixed(2)); // Update the subtotal field

        // Update hidden fields for submission

        $('#submit_total').val(total.toFixed(2));
        $('#submit_subtotal').val(subtotal.toFixed(2));
    }


    // Event Handlers: Recalculate subtotal when these events occur
    $(document).on('input', '.quantity, #discount', function() {
        calculateSubtotal();
    });

    $(document).on('change', '.medicine', function() {
        var row = $(this).closest('.medicine-row');
        calculateTotalPrice(row); // Trigger price recalculation
        calculateSubtotal(); // Recalculate the subtotal
    });

    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove(); // Remove the closest row
        calculateSubtotal(); // Recalculate the subtotal
    });


    function validateForm() {
        var customer = document.getElementById("customer").value;
        var discount = document.getElementById("discount").value;
        var medicines = document.getElementsByName("medicine[]");

        if (customer == "" || discount == "") {
            alert("Customer and Discount fields are required.");
            return false; // Prevent form submission
        }

        for (var i = 0; i < medicines.length; i++) {
            if (medicines[i].value == "") {
                alert("Medicine selection cannot be empty.");
                return false; // Prevent form submission
            }
        }

        return true; // Submit the form if all fields are filled
    }

    function validateForm() {
        var customer = document.getElementById("customer").value;
        var discount = document.getElementById("discount").value;
        var medicines = document.getElementsByName("medicine[]");
        var quantities = document.getElementsByName("quantity[]");
        var availableQuantities = document.getElementsByName("available_quantity[]");

        if (customer == "" || discount == "") {
            alert("Customer and Discount fields are required.");
            return false; // Prevent form submission
        }

        for (var i = 0; i < medicines.length; i++) {
            var medicine = medicines[i].value;
            var quantity = parseInt(quantities[i].value);
            var availableQuantity = parseInt(availableQuantities[i].value);

            if (medicine == "") {
                alert("Medicine selection cannot be empty.");
                return false; // Prevent form submission
            }

            if (isNaN(quantity) || quantity <= 0) {
                alert("Quantity must be a positive number.");
                return false; // Prevent form submission
            }

            if (quantity > availableQuantity) {
                alert("Sell quantity cannot exceed available quantity for the selected medicine.");
                return false; // Prevent form submission
            }
        }

        return true; // Submit the form if all fields are filled and quantities are valid
    }
</script>

<?php include 'nav/footer.php'; ?>