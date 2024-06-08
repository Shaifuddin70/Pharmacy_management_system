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
    <style>
        .container {}
    </style>
</head>
<div class="home-content">
    <div class="overview-boxes">
        <div class="container">
            <div class="row mt-5 text-center custom">
                <div class="col-5  m-2 p-4 box1 box">
                    <a href="purchase_report.php" style=" color: white;">
                        <h3 style=" color: white;">Purchase Report</h3>
                        <i class='bx bxs-cart-download' style=" color: white;"></i>
                    </a>
                </div>
                <div class="col-5  m-2 p-4 box2 box">
                    <a href="invoices.php" style=" color: white;">
                        <h3 style=" color: white;">Sales Report</h3>
                        <i style=" color: white;" class='bx bx-money'></i>
                    </a>
                </div>
            </div>
        </div>


    </div>

    <?php include 'nav/footer.php'; ?>