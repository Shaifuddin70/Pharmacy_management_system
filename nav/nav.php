<?php
ob_start(); // Start output buffering
session_start();

include 'db_connect.php';
$db = new dbObj();
$conn =  $db->getConnstring();

// stock notification
$query = mysqli_query($conn, "Select *from medicine_stock");
$total = mysqli_num_rows($query);
$c = 0;
if ($total != 0) {
  while ($result =  mysqli_fetch_assoc($query)) {
    if ($result['unit'] < 5) {
      $c++;
    }
  }
}
//Expired Notification

$query = mysqli_query($conn, "SELECT * FROM medicine_stock WHERE expiry_date < CURDATE()");
$e = mysqli_num_rows($query);

// purcahse notification
$pquery = mysqli_query($conn, "Select *from purchase_table");
$ptotal = mysqli_num_rows($pquery);
$p = 0;
if ($ptotal != 0) {
  while ($presult =  mysqli_fetch_assoc($pquery)) {
    if ($presult['status'] == null) {
      $p++;
    }
  }
}
?>
<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!----======== CSS ======== -->
  <link rel="stylesheet" href="nav/style.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!----===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="Jquery/jquery.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="icon" type="image/x-icon" href="image/logo.jpg">
  <title>Pharmacy Management System</title>
</head>

<body>
  <div class="sidebar">
    <div class="logo-details">
      <img src="image/logo.jpg" alt="profileImg" style="height: 40px; width: 40px;     border-radius: 50px; margin-right: 10px; margin-left: 20px;">
      <span class="logo_name">PMS</span>
    </div>
    <?php
    if (isset($_SESSION['admin'])) {
      echo '
            <ul class="nav-links">
            <li>
                <a href="dashboard.php">
                <i class="bx bx-grid-alt"></i>
                <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                <li><a class="link_name" href="dashboard.php">Dashboard</a></li>
                </ul>
            </li>
           
            <li>
                <div class="iocn-link">
                <a href="medicine_details.php">
                    <i class="bx bx-collection"></i>
                    <span class="link_name">Medicine</span>
                </a>
                <i class="bx bxs-chevron-down arrow"></i>
                </div>
                <ul class="sub-menu">
                <li><a class="link_name" href="medicine_details.php">Medicine</a></li>
                <li><a href="catagory.php">Catagory</a></li>
                <li><a href="brand.php">Brand</a></li>
                <li><a href="generic.php">Generic</a></li>
                </ul>
            </li>';
      if ($p != 0) {
        echo '
            <li>
            <a href="purchase_request.php">
            <i class="bx bxs-cart-add" ></i>
            <span class="link_name">Purchase Request</span>
                <span style="position: absolute; top: -0.1px;left: 235px;padding: 0.1px 9px;border-radius: 50%;background: red;color: white;">' . $p . '</span>
       </a>
            <ul class="sub-menu blank">
            <li><a class="link_name" href="purchase_request.php">Purchase Request</a></li>
            </ul>
        </li>';
      }
      echo '
            <li>
            <a href="stock.php">
            <i class="bx bxs-store" ></i>
              <span class="link_name">Stock</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="stock.php">Stock</a></li>
            </ul>
          </li>';
      if ($c != 0) {
        echo '
          <li>
            <a href="outofstock.php">
            <i class="bx bxs-minus-circle" ></i>
              <span class="link_name">Out Of Stock</span>
           
                      
                            <span style="position: absolute; top: -0.1px;left: 160px;padding: 0.1px 9px;border-radius: 50%;background: red;color: white;margin-left:40px;">
                            ' . $c . '</span>
           </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="outofstock.php">Out Of Stock</a></li>
              
            </ul>
          </li>';
      }
      if ($e != 0) {
        echo '
            <li>
            <a href="expired.php">
            <i class="bx bxs-package" ></i>
              <span class="link_name">Expired</span>
           
                       
                            <span style="position: absolute; top: -0.1px;left: 110px;padding: 0.1px 9px;border-radius: 50%;background: red;color: white;margin-left:40px;">
                            ' . $e . '</span>
           </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="expired.php">Out Of Stock</a></li>
              
            </ul>
          </li>

          ';
      }
      echo '
          <li>
            <div class="iocn-link">
              <a href="add_order.php">
                <i class="bx bx-collection"></i>
                <span class="link_name">Create Invoice</span>
              </a>
            </div>
          </li>
          <li>
            <div class="iocn-link">
              <a href="invoices.php">
              <i class="bx bx-money" ></i>
                <span class="link_name">Invoices</span>
              </a>
            </div>
          </li>
          <li>
            <a href="return.php">
            <i class="bx bxs-caret-down-square" ></i>
              <span class="link_name">Return</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="return.php">Return</a></li>
            </ul>
          </li>
          
          <li>
          <a href="supplier.php">
          <i class="bx bxs-user-rectangle" ></i>
            <span class="link_name">Supplier</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="supplier.php">Customer</a></li>
          </ul>
        </li>
          <li>
          <a href="customer.php">
          <i class="bx bxs-user-detail" ></i>
            <span class="link_name">Customer</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="customer.php">Customer</a></li>
          </ul>
        </li>
        <li>
          <a href="employee.php">
          <i class="bx bxs-user-badge"></i>
            <span class="link_name">Employee</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="employee.php">Employee</a></li>
          </ul>
        </li>
        <li>
          <a href="report.php">
          <i class="bx bxs-report"></i>
            <span class="link_name">Reports</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="report.php">Reports</a></li>
          </ul>
        </li>

          <li>
           
            ';
    } else if ($_SESSION['stuff']) {

      echo '
          <ul class="nav-links">
          <li>
              <div class="iocn-link">
              <a href="medicine_details.php">
                  <i class="bx bx-collection"></i>
                  <span class="link_name">Medicine</span>
              </a>
              <i class="bx bxs-chevron-down arrow"></i>
              </div>
              <ul class="sub-menu">
              <li><a class="link_name" href="medicine_details.php">Medicine</a></li>
              <li><a href="catagory.php">Catagory</a></li>
              <li><a href="brand.php">Brand</a></li>
              <li><a href="generic.php">Generic</a></li>
              </ul>
          </li>
          <li>
          <a href="customer.php">
          <i class="bx bxs-user-detail" ></i>
            <span class="link_name">Customer</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="customer.php">Customer</a></li>
          </ul>
        </li>
        <li>
            <a href="stock.php">
            <i class="bx bxs-store" ></i>
              <span class="link_name">Stock</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="stock.php">Stock</a></li>
            </ul>
          </li>
          <li>
            <a href="outofstock.php">
            <i class="bx bxs-minus-circle" ></i>
              <span class="link_name">Out Of Stock</span>';

      if ($c != 0) echo '
                            <span style="position: absolute; top: -0.1px;left: 160px;padding: 0.1px 9px;border-radius: 50%;background: red;color: white;margin-left:40px;">
                            ' . $c . '</span>';
      echo ' </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="outofstock.php">Out Of Stock</a></li>
              
            </ul>
          </li>
          
          <li>
            <div class="iocn-link">
              <a href="add_order.php">
                <i class="bx bx-collection"></i>
                <span class="link_name">Create Invoice</span>
              </a>
            </div>
          </li>
          <li>
            <div class="iocn-link">
              <a href="invoices.php">
              <i class="bx bx-money" ></i>
                <span class="link_name">Invoices</span>
              </a>
            </div>
          </li>
          <li>
            <a href="return.php">
            <i class="bx bxs-caret-down-square" ></i>
              <span class="link_name">Return</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="return.php">Return</a></li>
            </ul>
          </li>
        
        <li>
         
          ';
    }
    ?>


    <div class="profile-details">
      <div class="profile-content">
        <img src="image/user.png" alt="profileImg">
      </div>
      <div class="name-job">
        <div class="profile_name"><a class="link_name text-white text-uppercase" href="profile.php"><?php

                                                                                                    $query = mysqli_query($conn, "SELECT * FROM `employee` WHERE `id`='$_SESSION[eid]'");
                                                                                                    $fetch = mysqli_fetch_array($query);
                                                                                                    echo $fetch['name'];
                                                                                                    ?></a></div>

      </div>
      <a href="logout.php">
        <i class='bx bx-log-out'></i></a>
    </div>
    </li>
    </ul>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
    </div>