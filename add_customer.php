<?php include 'nav/nav.php';

if (isset($_SESSION['admin'])) {
} else {
    echo "<script>alert('Unautorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
} ?>

<div class="container mt-3">
    <form method="post">
        <h2>Add New Customer</h2>

        <table class="table table-borderless">
            <tr>
                <th>Name</th>
                <td> <input type="text" class=" form-control form-control-lg" required="true" name="name" autocomplete="off" placeholder="Name"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td> <input type="text" class=" form-control form-control-lg" required="true" name="email" placeholder="Email" autocomplete="off"></td>
            </tr>
            <tr>
                <th>Phone</th>

                <td> <input type="text" class=" form-control form-control-lg" required="true" name="number" autocomplete="off" placeholder="Phone Number"></td>
            </tr>
        </table>
        <div class="button">
            <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
        </div>
    </form>
</div>


<?php
include 'nav/footer.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $query = "INSERT INTO customer(`customer_name`,`customer_email`,`customer_number`)VALUES('$name','$email','$number')";
    $data = mysqli_query($conn, $query);

    if ($data) {

        echo "<script>window.location='customer.php'</script>";
    } else {
        echo "<script>alert('Invalid username or password')</script>";
        header("location:customer.php");
    }
}
