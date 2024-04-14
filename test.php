<?php
include 'nav/nav.php';

if (isset($_SESSION['stuff'])) {
} elseif (isset($_SESSION['admin'])) {
} else {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
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
        <div class="h5 col-sm-12">
            Discount: <span id="discount"><?php echo $discount; ?></span>

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
            return totalReturnPrice;
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

            var unitPrice = parseFloat($(this).closest('tr').find('td:eq(3)').text());
            var returnPrice = calculateReturnPrice(returnQuantity, unitPrice);
            $(this).closest('tr').find('td:eq(4)').text(returnPrice.toFixed(2)); // Update return price in the corresponding table cell

            // Update total return price
            var totalReturnPrice = calculateTotalReturnPrice();
            $('#totalReturnPrice').text(totalReturnPrice.toFixed(2));
        });

        $('#invoiceId').change(function() {
            var invoiceId = $(this).val();

            if (invoiceId) {
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

<?php include 'nav/footer.php'; ?>