<?php
include 'nav/nav.php';

// Check if user is authorized
if (!isset($_SESSION['admin']) && !isset($_SESSION['stuff'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='employeelogin.php'</script>";
    exit; // Stop execution if unauthorized
}

// Check if medicine ID is provided
if (!isset($_GET['updateid']) || empty($_GET['updateid'])) {
    echo "<script>alert('Invalid request')</script>";
    echo "<script>window.location='medicine_details.php'</script>";
    exit; // Stop execution if ID is not provided
}

// Get the medicine ID from the URL
$updateid = $_GET['updateid'];

// Fetch medicine details from the database
$query = "SELECT * FROM medicine WHERE medicine_id = $updateid";
$result = mysqli_query($conn, $query);
$medicine = mysqli_fetch_assoc($result);

if (!$medicine) {
    echo "<script>alert('Medicine not found')</script>";
    echo "<script>window.location='medicine_details.php'</script>";
    exit; // Stop execution if medicine not found
}
?>

<div class="container">
    <form method="post">
        <h1>Update Medicine Details</h1>
        <div class="container mt-3">
            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <tr>
                    <td><label for="medicine_name">Medicine Name:</label></td>
                    <td><input type="text" class="form-control " required="true" name="medicine_name" id="medicine_name" value="<?php echo $medicine['medicine_name']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="brand_id">Brand:</label></td>
                    <td>
                        <select class="form-control" name="brand_id" id="brand_id" required>
                            <?php
                            // Fetch and display brand options
                            $brand_query = "SELECT * FROM medicine_brand";
                            $brand_result = mysqli_query($conn, $brand_query);
                            while ($brand_row = mysqli_fetch_assoc($brand_result)) {
                                $selected = ($brand_row['brand_id'] == $medicine['brand_id']) ? 'selected' : '';
                                echo '<option value="' . $brand_row['brand_id'] . '" ' . $selected . '>' . $brand_row['brand_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="generic_id">Generic:</label></td>
                    <td>
                        <select class="form-control" name="generic_id" id="generic_id" required>
                            <?php
                            // Fetch and display generic options
                            $generic_query = "SELECT * FROM medicine_generic";
                            $generic_result = mysqli_query($conn, $generic_query);
                            while ($generic_row = mysqli_fetch_assoc($generic_result)) {
                                $selected = ($generic_row['generic_id'] == $medicine['generic_id']) ? 'selected' : '';
                                echo '<option value="' . $generic_row['generic_id'] . '" ' . $selected . '>' . $generic_row['generic_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="catagory_id">Catagory:</label></td>
                    <td>
                        <select class="form-control" name="catagory_id" id="catagory_id" required>
                            <?php
                            // Fetch and display generic options
                            $catagory_query = "SELECT * FROM medicine_catagory";
                            $catagory_result = mysqli_query($conn, $catagory_query);
                            while ($catagory_row = mysqli_fetch_assoc($catagory_result)) {
                                $selected = ($catagory_row['catagory_id'] == $medicine['catagory_id']) ? 'selected' : '';
                                echo '<option value="' . $catagory_row['catagory_id'] . '" ' . $selected . '>' . $catagory_row['catagory_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="button">
                <button class="btn btn-primary" type="submit" name="update">UPDATE</button>
            </div>
        </div>
    </form>
</div>

<?php
include 'nav/footer.php';

// Handle form submission for updating medicine details
if (isset($_POST['update'])) {
    // Retrieve form data
    $medicine_name = $_POST['medicine_name'];
    $catagory_id = $_POST['catagory_id'];
    $brand_id = $_POST['brand_id'];
    $generic_id = $_POST['generic_id'];

    // Update query
    $update_query = "UPDATE medicine SET medicine_name = '$medicine_name',catagory_id='$catagory_id', brand_id = $brand_id, generic_id = $generic_id WHERE medicine_id = $updateid";

    // Execute update query
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $_SESSION['status'] = "Medicine details updated successfully";
        echo "<script>window.location='medicine_details.php'</script>";
    } else {
        $_SESSION['status'] = "Failed to update medicine details";
        echo "<script>window.location='update.php?updateid=$updateid'</script>";
    }
}
?>
