<?php 
include 'nav/nav.php';

if(isset($_SESSION['stuff'])) {
    
} elseif(isset($_SESSION['admin'])) {

} else {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}
?>

<form method="post">        
    <div class="container">
        <h1>Purchase Medicine</h1>
        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <tr>
                <th>Catagory</th>
                <th>Medicine</th>
                <th>Quantity</th>
            </tr>
            <tr>
                <td>
                    <?php
                    $catagory_query = "SELECT * FROM medicine_catagory";
                    $catagory_result = mysqli_query($conn, $catagory_query);
                    ?>
                    <select class="form-control form-control-lg" aria-label="Default select example" name="catagory" id="catagory">
                        <option selected disabled>Select Catagory</option>
                        <?php while ($row = mysqli_fetch_assoc($catagory_result)) : ?>
                            <option value="<?php echo $row['catagory_id']; ?>"> <?php echo $row['catagory_name']; ?> </option>
                        <?php endwhile; ?>
                    </select>
                </td>
                <td>
                    <select class="form-control form-control-lg" aria-label="Default select example" name="medicine" id="medicine">
                        <option selected disabled>Select Medicine</option>
                    </select>
                </td>
                <td> 
                    <input type="text" class="form-control form-control-lg" required="true" name="quantity" placeholder="Medicine Quantity">
                </td>
            </tr>
        </table>
        <div class="button">
            <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
        </div>
    </div>
</form>

<script>
    // Fetch medicine based on selected catagory
    $('#catagory').on('change', function() {
        var catagory_id = $('#catagory').val();
        $.ajax({
            url: 'ajax/medicine.php',
            type: "POST",
            data: {
                catagory_id: catagory_id
            },
            success: function(result) {
                $('#medicine').html(result);
            }
        });
    });
</script>

<?php
if (isset($_POST['submit'])) {
    
    $medicine_id = $_POST['medicine'];
    $quantity = $_POST['quantity'];

    // Insert into order_table
    $query = "INSERT INTO purchase_table (`medicine_id`, `unit`) VALUES ('$medicine_id', '$quantity')";
    $query_run = mysqli_query($conn, $query);
    
    if ($query_run) {
        $_SESSION['status'] = "Inserted Successfully";
    } else {
        $_SESSION['status'] = "Not Inserted";
    }
    echo "<script>window.location='stock.php'</script>";
}
?>

</body>
</html>
