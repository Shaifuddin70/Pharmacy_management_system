<?php include 'nav/nav.php';
$id = $_GET['updateid'];
$query = mysqli_query($conn, "SELECT employee.name,employee.email,employee.role,employee.number
 FROM employee 
WHERE employee.id='$id'");
$fetch = mysqli_fetch_array($query);
?>



<div class="container mt-3">
    <h2 class="text-center text-uppercase p-2">Update Employee Info.</h2>
    <form method="post">

        <table class="table table-borderless">
            <tr>
                <th>Name</th>
                <td> <input type="text" class=" form-control form-control-lg" name="name" autocomplete="off" value="<?php echo $fetch['name']; ?>"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td> <input type="text" class=" form-control form-control-lg" name="email" value="<?php echo $fetch['email']; ?>" autocomplete="off"></td>
            </tr>
            <tr>

                <th>Password</th>
                <td>
                    <input type="password" class=" form-control form-control-lg" name="password" value="Password" autocomplete="new-password" maxlength="8" minlength="6">
                </td>

            </tr>
            <tr>
                <th>Phone</th>

                <td> <input type="text" class=" form-control form-control-lg" name="number" autocomplete="off" value="<?php echo $fetch['number']; ?>"></td>
            </tr>
            <tr>
                <th>Role</th>
                <td> <?php
                        $rid = $fetch['role'];
                        $rquery = mysqli_query($conn, "SELECT *FROM `role`  WHERE role_id='$rid'");
                        $rfetch = mysqli_fetch_array($rquery);
                        $catagory = "SELECT * FROM role";
                        $result = mysqli_query($conn, $catagory);
                        ?>
                    <select class="form-control form-control-lg" aria-label="Default select example" value="<?php echo $row['role_id']; ?>" required="true" name="role" id="role">
                        <option selected disabled><?php echo $rfetch['role_name']; ?></option>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <option value="<?php echo $row['role_id']; ?>"> <?php echo $row['role_name']; ?> </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
        </table>
        <div class="button">
            <button style="border-radius: 24px;
    background: #03274d;
    padding: 10px 20px;
    border: none;" type="submit" name="submit">UPDATE</button>
        </div>
</div>
</form>
</body>

</html>

<?php

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $pass = $_POST['password'];
    $role = $_POST['role'];
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $query = "UPDATE `employee` SET `name`='$name',`email`='$email',`number`='$number',`password`='$pass',`role`='$role' WHERE `id`='$id'";
    $data = mysqli_query($conn, $query);

    if ($data) {

        echo "<script>window.location='employee.php'</script>";
    } else {
        echo "<script>alert('Invalid username or password')</script>";
        header("location:add_employee.php");
    }
}
