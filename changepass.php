<?php include 'nav/nav.php'?>
<div class="container mt-3">
    <form method="post">
        <h1>Change Password</h1>
        <table class="table table-borderless">
            <tr>
                <th>Old Password</th>
                <td>
                    <input type="password" class="form-control form-control-lg" required="true" name="opass" placeholder="Old Password" autocomplete="current-password" maxlength="8" minlength="6">
                </td>
            </tr>
            <tr>
                <th>New Password</th>
                <td>
                    <input type="password" class="form-control form-control-lg" required="true" name="npass" id="npass" placeholder="New Password" autocomplete="new-password" maxlength="8" minlength="6">
                </td>
            </tr>
            <tr>
                <th>Confirm New Password</th>
                <td>
                    <input type="password" class="form-control form-control-lg" required="true" name="cnpass" id="cnpass" placeholder="Confirm New Password" autocomplete="new-password" maxlength="8" minlength="6">
                </td>
            </tr>
        </table>
        <div class="button">
            <button type="submit" name="submit">SUBMIT</button>
        </div>
    </form>
</div>

<?php include 'nav/footer.php';

if (isset($_POST['submit'])) {
    $id = $_SESSION['eid'];
    $opass = $_POST['opass'];
    $npass = $_POST['npass'];
    $cnpass = $_POST['cnpass'];

    // Check if new password and confirm new password match
    if ($npass !== $cnpass) {
        echo "<script>alert('New password and confirm password do not match.')</script>";
        echo "<script>window.location='changepass.php'</script>";
        exit(); // Stop further execution
    }

    // Hash the new password
    $npass_hashed = password_hash($npass, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM `employee` WHERE `id` = '$id'";
    $result = mysqli_query($conn, $sql);
    $check = mysqli_fetch_array($result);
    $hashed_pass = $check['password'];

    if (password_verify($opass, $hashed_pass)) {
        $query = "UPDATE `employee` SET `password`='$npass_hashed'  WHERE `id`='$id'";
        $data = mysqli_query($conn, $query);
        echo "<script>alert('Password changed successfully.')</script>";
        echo "<script>window.location='profile.php'</script>";
    } else {
        echo "<script>alert('Wrong Credentials')</script>";
        echo "<script>window.location='profile.php'</script>";
    }
}
?>
