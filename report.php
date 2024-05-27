<?php
include 'nav/nav.php';

// Authorization check
if (!isset($_SESSION['stuff']) && !isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit(); // Stop further execution
}
?>

<head>
    <link rel="stylesheet" href="nav/dashboard.css">
</head>
<div class="home-content">
    <div class="overview-boxes">
        <div class="container">
        <div class="row mt-5 text-center custom"> 
            <div class="col-5  m-2 p-2 custom-box">
            <a href="purchase_report.php">  <h3>Purchase Report</h3>
                <i class='bx bxs-cart-download' ></i></a>
            </div>
           <div class="col-5  m-2 p-2 custom-box">
           <a href="invoices.php"> <h3>Sales Report</h3>
                <i class='bx bx-money' ></i></a>
            </div>
        </div>
        </div>
       
        
    </div>

<?php include 'nav/footer.php'; ?>