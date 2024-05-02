<?php
include 'nav/nav.php';

if (isset($_SESSION['admin'])) {
} else {
    echo "<script>alert('Unautorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit(); // Stop further execution
}

?>

<div class="container mt-3">
    <form method="post">
        <h2>Add New Employee</h2>
       
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
                <th>Password</th>
                <td>
                    <input type="password" class="form-control form-control-lg" required="true" name="password" placeholder="Password" autocomplete="new-password" maxlength="8" minlength="6">
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td> <input type="text" class="form-control form-control-lg" required="true" name="number" autocomplete="off" placeholder="Phone Number"></td>
            </tr>
            <tr>
                <th>Role</th>
                <td> 
                    <?php
                    $query = "SELECT * FROM role";
                    $result = mysqli_query($conn, $query);
                    ?>
                    <select class="form-control form-control-lg" aria-label="Default select example" name="role" id="role">
                        <option selected disabled>Select Role</option>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <option value="<?php echo $row['role_id']; ?>"> <?php echo $row['role_name']; ?> </option>
                        <?php endwhile; ?>
                    </select>
                </td>
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
    $pass = $_POST['password'];
    $role = $_POST['role'];
    
    // Check if email or number already exists
    $check_query = "SELECT * FROM employee WHERE email='$email' OR number='$number'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email or phone number already exists')</script>";
        exit(); // Stop further execution
    }
    
    // Hash the password
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    
    // Insert new employee
    $query = "INSERT INTO employee(`name`,`email`,`number`,`password`,`role`) VALUES('$name','$email','$number','$pass','$role')";
    $data = mysqli_query($conn, $query);

    if ($data) {
        echo "<script>window.location='employee.php'</script>";
    } else {
        echo "<script>alert('Failed to add employee')</script>";
    }
}
?>
