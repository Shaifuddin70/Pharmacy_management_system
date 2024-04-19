<?php
include 'nav/nav.php';
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
}
?>
<div class="container">
    <form method="post">
        <h1>Add New Brand</h1>
        <div class="container mt-3">
            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <tr>
                    <td> <input type="text" class=" form-control form-control-lg" required="true" name="bname" placeholder="Brand Name"></td>
                </tr>
            </table>
            <div class="button">
                <button class="btn btn-primary" type="submit" name="submit">SUBMIT</button>
            </div>
        </div>
    </form>
</div>
<?php
include 'nav/footer.php';
if (isset($_POST['submit'])) {
    $bname = $_POST['bname'];
    $query = "INSERT INTO `medicine_brand` (`brand_name`)VALUES('$bname')";
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        $_SESSION['status'] = "Inserted Succesfully";
        echo "<script>window.location='brand.php'</script>";
    } else {
        $_SESSION['status'] = "Not Inserted";
        echo "<script>window.location='brand.php'</script>";
    }
}
