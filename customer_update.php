<?php
include 'nav/nav.php';

// Check if user is logged in as admin
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

// Check if updateid is set and numeric
if(isset($_GET['updateid']) && is_numeric($_GET['updateid'])) {
    $id = $_GET['updateid'];

    // Fetch customer data based on ID
    $query = "SELECT * FROM customer WHERE customer_id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);
    } else {
        echo "Customer not found.";
        exit;
    }
} else {
    echo "Invalid customer ID.";
    exit;
}

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve form data
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_number = $_POST['customer_number'];

    // Update customer details in the database
    $update_query = "UPDATE customer SET customer_name='$customer_name', customer_email='$customer_email', customer_number='$customer_number' WHERE customer_id=$id";
    $update_result = mysqli_query($conn, $update_query);

    if($update_result) {
        echo "Customer details updated successfully.";
        // Redirect to customer list page
        header("Location: customer.php");
        exit;
    } else {
        echo "Error updating customer details: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="title">
        <h1 class="font-weight-bold">Edit Customer</h1>
    </div>
    <form method="post" action="">
        <div class="form-group">
            <label for="customer_name">Name:</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $customer['customer_name']; ?>">
        </div>
        <div class="form-group">
            <label for="customer_email">Email:</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?php echo $customer['customer_email']; ?>">
        </div>
        <div class="form-group">
            <label for="customer_number">Phone Number:</label>
            <input type="text" class="form-control" id="customer_number" name="customer_number" value="<?php echo $customer['customer_number']; ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
</div>

<?php include 'nav/footer.php'; ?>
