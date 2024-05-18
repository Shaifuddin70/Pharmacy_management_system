<?php
include 'nav/nav.php';
if (isset($_SESSION['stuff'])) {
} elseif (isset($_SESSION['admin'])) {
} else {
    echo "<script>alert('Unautorized Access')</script>";
    echo "<script>window.location='employeelogin.php'</script>";
}
?>

<div class="container">
    <form method="post">
        <h2>Update Genereic</h2>
        <div class="container mt-3">

            <table class="table table-borderless">
                <tr>

                    <th>Genereic Name</th>
                </tr>
                <tr>
                    <td> <input type="text" class=" form-control form-control-lg" required="true" name="cname" placeholder="Genereic Name"></td>
                </tr>
            </table>
            <div class="button">
                <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
            </div>
        </div>
    </form>
</div>



</html>
<?php
include 'nav/footer.php';
if (isset($_POST['submit'])) {
    $id = $_GET['updateid'];
    $cname = $_POST['cname'];
    $query = "UPDATE medicine_generic SET `generic_id`='$id',`generic_name`='$cname' WHERE generic_id='$id'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        echo "<script>success</script>";
        echo "<script>window.location='generic.php'</script>";
    } else {
        echo "fail";
        header("location:generic.php");
    }
}


?>