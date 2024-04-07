<?php include 'nav/nav.php';
$id = $_SESSION['eid'];
$query = mysqli_query($conn, "SELECT employee.name,employee.email,employee.role,employee.number 
FROM employee 
WHERE employee.id='$id'");
$fetch = mysqli_fetch_array($query);
?>



<div class="container mt-3">
    <h1>Employee Info.</h1>
    <form method="post" action="changepass.php">
        <table class="table table-borderless">
            <tr>
                <th>Name</th>
                <td> <span><?php echo $fetch['name']; ?></span></td>
            </tr>
            <tr>
                <th>Email</th>
                <td> <span><?php echo $fetch['email']; ?></span></td>
            </tr>

            <tr>
                <th>Phone</th>

                <td> <span><?php echo $fetch['number']; ?></span></td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    <?php
                    $rid = $fetch['role'];
                    $rquery = mysqli_query($conn, "SELECT *FROM `role`  WHERE role_id='$rid'");
                    $rfetch = mysqli_fetch_array($rquery);
                    ?>
                    <span><?php echo $rfetch['role_name']; ?></span>
                </td>
            </tr>
        </table>
        <button type="submit">Change Password</button>
    </form>
</div>

</body>

</html>

<?php
include 'nav/footer.php';
