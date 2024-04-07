<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}
$query = "SELECT MAX(invoice_id) AS max_id FROM invoices";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$invoice_id = $row['max_id'] + 1; 
?>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">All Invoice</h1>
         <h5 style="text-align: right;">Invoice Id: <?php echo $invoice_id; ?></h5>
    <div class="customer" >
        <h5>Customer: </h5>
        <?php
                        $catagory = "SELECT * FROM customer";
                        $result = mysqli_query($conn, $catagory);
                        ?>
                        <select class="form-control form-control col-3 ml-2 mb-2" aria-label="Default select example" name="medicine" id="medicine">
                            <option selected disabled>Select Customer</option>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <option value="<?php echo $row['customer_id']; ?>"> <?php echo $row['customer_name']; ?> </option>
                            <?php endwhile; ?>
                        </select>
                        <a href="add_customer.php"><button class="col-2 btn btn-primary col-12 ml-2"> Add Customer</button> </a>
    </div>
        <div class="invoice_select">
            <table id="medicineTable" class="display table table-striped table-bordered table-hover">
                <tr>
                    <th>Medicine</th>
                    <th>Available</th>
                    <th>Sell</th>
                    <th>unit Price</th>
                    <th>Total</th>
                    <th>Action</th>

                </tr>
                <tr class="medicine-row">
                    <td>
                        <?php
                        $catagory = "SELECT * FROM Medicine";
                        $result = mysqli_query($conn, $catagory);
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
                        <input type="text" class="form-control form-control-lg quantity" required="true" name="quantity[]" id="quantity" value="1" placeholder="Item Quantity"> <!-- Set default sell quantity to 1 -->
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-lg unit_price" name="unit_price[]" id="unit_price" aria-describedby="sizing-addon1" placeholder="Unit Price" disabled>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-lg total_price" name="total_price[]" id="total_price" aria-describedby="sizing-addon1" placeholder="Total Price" disabled>
                    </td>
                    <td>
                    <button type="button" class="btn btn-danger delete-row">Delete</button>
                    </td>
                </tr>
            </table>
            <button type="button" class="btn btn-success add-row">Add Row</button>
            <div class="subtotal">
                <label for="discount">Discount: </label>
                <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount">
                <label for="subtotal">Subtotal: </label>
                <input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="Subtotal" disabled>
            </div>
        </div>
    </div>

    <div class="button">
        <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
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

    // Function to calculate total price
    function calculateTotalPrice(row) {
        var sellQuantity = parseInt(row.find('.quantity').val()); // Get the sell quantity
        var unitPrice = parseFloat(row.find('.unit_price').val()); // Get the unit price
        var totalPrice = sellQuantity * unitPrice; // Calculate total price
        row.find('.total_price').val(totalPrice.toFixed(2)); // Set the total price in the total field
    }

    // When the sell quantity changes
    $(document).on('input', '.quantity', function() {
        var row = $(this).closest('.medicine-row');
        calculateTotalPrice(row); // Recalculate total price
    });

     // When the delete button is clicked
     $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove(); // Remove the closest row
    });
// When the delete button is clicked
$(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove(); // Remove the closest row
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
                    ?><select class="form-control form-control-lg medicine" aria-label="Default select example" name="medicine[]" id="medicine"><option selected disabled>Select Medicine</option><?php while ($row = mysqli_fetch_assoc($result)) : ?><option value="<?php echo $row['medicine_id']; ?>"> <?php echo $row['medicine_name']; ?> </option><?php endwhile; ?></select></td>' +
                '<td><input type="text" class="form-control form-control-lg available_quantity" name="available_quantity[]" id="available_quantity" aria-describedby="sizing-addon1" placeholder="Available Quantity" disabled></td>' +
                '<td><input type="text" class="form-control form-control-lg quantity" required="true" name="quantity[]" id="quantity" value="1" placeholder="Item Quantity"></td>' +
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
            newRow.find('.quantity').val('1'); // Set default sell quantity to 1
            $('#medicineTable tbody').append(newRow); // Append the new row to the table body
        }
    });
     // When the delete button is clicked
     $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove(); // Remove the closest row
    });
      // Function to calculate subtotal
      function calculateSubtotal() {
        var subtotal = 0;
        $('.total_price').each(function() {
            var price = parseFloat($(this).val());
            if (!isNaN(price)) {
                subtotal += price;
            }
        });
        // Apply discount if available
        var discount = parseFloat($('#discount').val());
        if (!isNaN(discount)) {
            subtotal -= discount;
        }
        // Update subtotal input box
        $('#subtotal').val(subtotal.toFixed(2));
    }

</script>

<?php include 'nav/footer.php'; ?>