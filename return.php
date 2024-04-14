<?php
include 'nav/nav.php';

if (isset($_SESSION['stuff'])) {
} elseif (isset($_SESSION['admin'])) {
} else {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}

// Initialize discount variable
$discount = 0;

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $invoiceId = $_POST['invoiceId'];
    $returnQuantities = $_POST['return_quantity'];

    // Loop through the return quantities
    foreach ($returnQuantities as $key => $returnQuantity) {
        // Get the corresponding invoice item details
        $query = "SELECT * FROM invoice_items WHERE invoice_id = $invoiceId";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        // Update stock quantity and unit price in medicine_stock table
        $medicineId = $row['medicine_id'];
        $currentStockQuery = "SELECT unit, sprice FROM medicine_stock WHERE medicine_id = $medicineId";
        $currentStockResult = mysqli_query($conn, $currentStockQuery);
        $currentStockRow = mysqli_fetch_assoc($currentStockResult);
        $currentStock = $currentStockRow['unit'];
        $unitPrice = $currentStockRow['sprice'];
        $updatedStock = $currentStock + $returnQuantity;
        $updateStockQuery = "UPDATE medicine_stock SET unit = $updatedStock WHERE medicine_id = $medicineId";
        mysqli_query($conn, $updateStockQuery);

        // Update sold quantity
        $currentSold = $row['quantity'];
        $updatedSold = $currentSold - $returnQuantity;
        $updateSoldQuery = "UPDATE invoice_items SET quantity = $updatedSold WHERE invoice_id = $invoiceId AND medicine_id = $medicineId";
        mysqli_query($conn, $updateSoldQuery);

        // Calculate return price and update total price in invoices table
        $returnPrice = $returnQuantity * $unitPrice;
        $query = "UPDATE invoices SET total = total - $returnPrice WHERE invoice_id = $invoiceId";
        mysqli_query($conn, $query);

        // Update total price in invoice_items table
        $currentTotalPrice = $row['total_price'];
        $updatedTotalPrice = $currentTotalPrice - $returnPrice;
        $updateTotalPriceQuery = "UPDATE invoice_items SET total_price = $updatedTotalPrice WHERE invoice_id = $invoiceId AND medicine_id = $medicineId";
        mysqli_query($conn, $updateTotalPriceQuery);

        // Update subtotal after return
        
        $query = "UPDATE invoices SET subtotal = subtotal-($returnPrice * (1 - $discount / 100)) WHERE invoice_id = $invoiceId";

        mysqli_query($conn, $query);
        // Check if sold quantity becomes 0, and delete the item from invoice_items table
        if ($updatedSold == 0) {
            $deleteQuery = "DELETE FROM invoice_items WHERE invoice_id = $invoiceId AND medicine_id = $medicineId";
            mysqli_query($conn, $deleteQuery);
        }
    }

    // Optionally, log the return transaction in a separate table
    // Insert code here to log the return transaction

    // Redirect to a success page or show a success message
    echo "<script>alert('Medicine return successful.')</script>";
    echo "<script>window.location='invoices.php'</script>";
    exit;
}


?>

<form method="post">
    <div class="container">
        <h1>Return Medicine</h1>
        <div class="customer">
            <h5>Select Invoice ID: </h5>
            <label for="invoiceId"></label>
            <select class="form-control form-control col-3 ml-2 mb-2" id="invoiceId" name="invoiceId">
                <option value="" selected disabled>Select Invoice ID</option>
                <?php
                // Fetch unique invoice IDs from the database
                $query = "SELECT DISTINCT invoice_id FROM invoices";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['invoice_id'] . "'>" . $row['invoice_id'] . "</option>";
                }
                ?>
            </select>

        </div>

        <table id="orderDetailsTable" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Sold Quantity</th>
                    <th>Return Quantity</th>
                    <th>Unit Price</th>
                    <th>Return Price</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="h5 col-sm-12 text-right ">

            Discount (%): <span id="discount"><?php echo $discount; ?></span>
        </div>
        <div class="h5 col-sm-12 text-right ">
            Total Return Price: <span id="totalReturnPrice">0.00</span>
        </div>
        <div class="button">
            <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
        </div>

    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize discount variable
        var discount = 0;

        // Function to calculate return price
        function calculateReturnPrice(returnQuantity, unitPrice) {
            return returnQuantity * unitPrice;
        }

        // Function to calculate total return price
        function calculateTotalReturnPrice() {
            var totalReturnPrice = 0;
            $('input[name="return_quantity[]"]').each(function() {
                var returnQuantity = parseInt($(this).val());
                var unitPrice = parseFloat($(this).closest('tr').find('td:eq(3)').text());
                var returnPrice = calculateReturnPrice(returnQuantity, unitPrice);
                totalReturnPrice += returnPrice;
            });
            return totalReturnPrice * (1 - discount / 100); // Apply discount to the total return price
        }

        // Event listener for any return quantity input field
        $(document).on('input', 'input[name="return_quantity[]"]', function() {
            var returnQuantity = parseInt($(this).val());
            var maxReturnQuantity = parseInt($(this).attr('max'));
            if (returnQuantity > maxReturnQuantity) {
                alert('Return quantity cannot be greater than the sold quantity.');
                $(this).val(maxReturnQuantity);
                returnQuantity = maxReturnQuantity;
            }

            // Update return price for this row
            var unitPrice = parseFloat($(this).closest('tr').find('td:eq(3)').text());
            var returnPrice = calculateReturnPrice(returnQuantity, unitPrice);
            $(this).closest('tr').find('td:eq(4)').text(returnPrice.toFixed(2));

            // Update total return price
            var totalReturnPrice = calculateTotalReturnPrice();
            $('#totalReturnPrice').text(totalReturnPrice.toFixed(2));
        });

        // Event listener for change in discount
        $('#discount').change(function() {
            discount = parseFloat($(this).val()); // Update discount value
            // Update total return price
            var totalReturnPrice = calculateTotalReturnPrice();
            $('#totalReturnPrice').text(totalReturnPrice.toFixed(2));
        });

        $('#invoiceId').change(function() {
            var invoiceId = $(this).val();

            if (invoiceId) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/get_discount.php',
                    data: {
                        invoiceId: invoiceId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#discount').text(data.discount); // Update discount value
                        discount = data.discount; // Set initial discount value
                        // Update total return price
                        var totalReturnPrice = calculateTotalReturnPrice();
                        $('#totalReturnPrice').text(totalReturnPrice.toFixed(2));
                    },
                    error: function(xhr, status, error) {
                        // Display detailed error message
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        alert('Error fetching discount: ' + errorMessage);
                    }
                });

                // Fetch order details based on selected invoice ID
                $.ajax({
                    type: 'POST',
                    url: 'ajax/get_order_details.php',
                    data: {
                        invoiceId: invoiceId
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Clear existing table rows
                        $('#orderDetailsTable tbody').empty();

                        // Add new rows based on fetched data
                        $.each(data, function(index, item) {
                            $('#orderDetailsTable tbody').append(
                                '<tr>' +
                                '<td>' + item.medicine_name + '</td>' +
                                '<td>' + item.sold_quantity + '</td>' +
                                '<td><input type="number" min="0" max="' + item.sold_quantity + '" name="return_quantity[]" value="0" class="form-control"></td>' +
                                '<td>' + item.unit_price + '</td>' +
                                '<td>0.00</td>' +
                                '</tr>'
                            );
                        });

                        // Update total return price
                        var totalReturnPrice = calculateTotalReturnPrice();
                        $('#totalReturnPrice').text(totalReturnPrice.toFixed(2));
                    },
                    error: function(xhr, status, error) {
                        // Display detailed error message
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        alert('Error fetching invoice details: ' + errorMessage);
                    }
                });
            } else {
                // Clear the table if no invoice ID is selected
                $('#orderDetailsTable tbody').empty();
                $('#totalReturnPrice').text('0.00'); // Clear total return price
            }
        });
    });
</script>

</script>

</script>

</script>

<?php include 'nav/footer.php'; ?>