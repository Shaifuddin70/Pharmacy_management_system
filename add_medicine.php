<?php
include 'nav/nav.php';

if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $medicine_name = $_POST['medicine_name'];
    $catagory_id = $_POST['catagory_id'];
    $brand_id = $_POST['brand_id'];
    $generic_id = $_POST['generic_id'];
 

    // Perform database insertion
    $query = "INSERT INTO medicine (medicine_name, catagory_id, brand_id, generic_id) VALUES ('$medicine_name',' $catagory_id', '$brand_id', '$generic_id')";
    $query_run = mysqli_query($conn, $query);

    // Check if insertion was successful
    if ($query_run) {
        $_SESSION['status'] = "Inserted Successfully";
        header("location:medicine_details.php");
        exit();
    } else {
        $_SESSION['status'] = "Not Inserted";
        header("location:medicine_details.php");
        exit();
    }
}
?>
<div class="container">
    <form method="post">
        <h2>Add New Medicine</h2>
        <div class="container mt-3">
            <table id="zctb"class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
                <tr>
                    <td><label for="medicine_name">Medicine Name:</label></td>
                    <td><input type="text" class="form-control form-control-lg" required="true" name="medicine_name" id="medicine_name" placeholder="Medicine Name"></td>
                </tr>
                <tr>
                    <td><label for="brand_id">Brand:</label></td>
                    <td>
                        <select class="form-control" name="brand_id" id="brand_id" required>
                            <option value="">Select Brand</option>
                            <?php
                            $brand_query = "SELECT * FROM medicine_brand";
                            $brand_result = mysqli_query($conn, $brand_query);
                            while ($brand_row = mysqli_fetch_assoc($brand_result)) {
                                echo '<option value="' . $brand_row['brand_id'] . '">' . $brand_row['brand_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="generic_id">Generic:</label></td>
                    <td>
                        <select class="form-control" name="generic_id" id="generic_id" required>
                            <option value="">Select Generic</option>
                            <?php
                            $generic_query = "SELECT * FROM medicine_generic";
                            $generic_result = mysqli_query($conn, $generic_query);
                            while ($generic_row = mysqli_fetch_assoc($generic_result)) {
                                echo '<option value="' . $generic_row['generic_id'] . '">' . $generic_row['generic_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="catagory_id">Catagory:</label></td>
                    <td>
                        <select class="form-control" name="catagory_id" id="catagory_id" required>
                            <option value="">Select Catagory</option>
                            <?php
                            $catagory_query = "SELECT * FROM medicine_catagory";
                            $catagory_result = mysqli_query($conn, $catagory_query);
                            while ($catagory_row = mysqli_fetch_assoc($catagory_result)) {
                                echo '<option value="' . $catagory_row['catagory_id'] . '">' . $catagory_row['catagory_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
               
            </table>
            <div class="button">
                <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
            </div>
        </div>
    </form>
</div>


<?php include 'nav/footer.php'; ?>
