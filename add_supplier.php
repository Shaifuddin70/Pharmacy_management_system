<?php 
include 'nav/nav.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}

if (isset($_POST['submit'])) {
    // Prepare and bind parameters to avoid SQL injection
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $query = "INSERT INTO supplier (supplier_name, supplier_email, supplier_number) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $number);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location='supplier.php'</script>";
    } else {
        echo "<script>alert('Failed to add supplier')</script>";
    }

    mysqli_stmt_close($stmt);
}

?>

<div class="container mt-3">
    <form method="post">
        <h2>Add New Supplier</h2>

        <table class="table table-borderless">
            <tr>
                <th>Name</th>
                <td> <input type="text" class="form-control form-control-lg" required="true" name="name" autocomplete="off" placeholder="Name"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td> <input type="text" class="form-control form-control-lg" required="true" name="email" placeholder="Email" autocomplete="off"></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td> <input type="text" class="form-control form-control-lg" required="true" name="number" autocomplete="off" placeholder="Phone Number"></td>
            </tr>
        </table>
        <div class="button">
            <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
        </div>
    </form>
</div>

<?php include 'nav/footer.php'; ?>
