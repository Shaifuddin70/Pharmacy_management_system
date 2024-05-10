<?php
include 'nav/nav.php';

// Check if user is logged in as admin
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Check if updateid is set and numeric
if (isset($_GET['updateid']) && is_numeric($_GET['updateid'])) {
    $id = $_GET['updateid'];

    // Fetch supplier data based on ID
    $query = "SELECT * FROM supplier WHERE supplier_id=$id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
} else {
    // If updateid is not set or not numeric, redirect to supplier.php
    header("location:supplier.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_number = $_POST['supplier_number'];

    // Update query
    $update_query = "UPDATE supplier SET supplier_name='$supplier_name', supplier_email='$supplier_email', supplier_number='$supplier_number' WHERE supplier_id=$supplier_id";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Redirect to supplier.php after successful update
        header("location:supplier.php");
        exit();
    } else {
        // Handle update error
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="title">
        <h2 class="text-center text-uppercase p-2">Update Supplier</h2>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
        <div class="form-group">
            <label for="supplier_name">Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo $row['supplier_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="supplier_email">Email</label>
            <input type="email" class="form-control" id="supplier_email" name="supplier_email" value="<?php echo $row['supplier_email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="supplier_number">Phone Number</label>
            <input type="text" class="form-control" id="supplier_number" name="supplier_number" value="<?php echo $row['supplier_number']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Supplier</button>
    </form>
</div>

<?php
include 'nav/footer.php';
?>
