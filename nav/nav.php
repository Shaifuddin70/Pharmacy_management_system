<?php
ob_start(); // Start output buffering
session_start();

include 'db_connect.php';
$db = new dbObj();
$conn =  $db->getConnstring();

?>
<!DOCTYPE html>
<!-- Designined by CodingLab | www.youtube.com/codinglabyt -->
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
  <title>Store Management System</title>
</head>

<body>
  <div class="sidebar">
    <div class="logo-details">
      <img src="image/logo.jpg" alt="profileImg" style="height: 40px; width: 40px;     border-radius: 50px; margin-right: 10px; margin-left: 20px;">
      <span class="logo_name">PMS</span>
    </div>
    <ul class="nav-links">
      <!-- <li>
        <a href="#">
          <i class='bx bx-grid-alt'></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="medicine_details.php">Medicine</a></li>
        </ul>
      </li> -->
      <li>
        <a href="purchase_request.php">
          <i class='bx bx-grid-alt'></i>
          <span class="link_name">Purchase Request</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="purchase_request.php">Purchase Request</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="medicine_details.php">
            <i class='bx bx-collection'></i>
            <span class="link_name">Medicine</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="medicine_details.php">Medicine</a></li>
          <li><a href="catagory.php">Catagory</a></li>
          <li><a href="brand.php">Brand</a></li>
          <li><a href="generic.php">Generic</a></li>
        </ul>
      </li>
      <!-- <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt'></i>
            <span class="link_name">Posts</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Posts</a></li>
          <li><a href="#">Web Design</a></li>
          <li><a href="#">Login Form</a></li>
          <li><a href="#">Card Design</a></li>
        </ul>
      </li>-->
      <li>
        <a href="customer.php">
          <i class='bx bx-line-chart'></i>
          <span class="link_name">Customer</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="customer.php">Customer</a></li>
        </ul>
      </li>
      <li>
        <a href="employee.php">
          <i class='bx bx-pie-chart-alt-2'></i>
          <span class="link_name">Employee</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="employee.php">Employee</a></li>
        </ul>
      </li>
      <!-- <li>
        <a href="add_order.php">
          <i class='bx bx-pie-chart-alt-2'></i>
          <span class="link_name">Order</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="add_order.php">Order</a></li>
        </ul>
      </li>
      <li>
        <a href="invoices.php">
          <i class='bx bx-pie-chart-alt-2'></i>
          <span class="link_name">Invoices</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="Invoices.php">Invoices</a></li>
        </ul>
      </li> -->
      <li>
        <div class="iocn-link">
          <a href="add_order.php">
            <i class='bx bx-collection'></i>
            <span class="link_name">Sales</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="add_order.php">Sales</a></li>
          <li><a href="add_order.php">Order</a></li>
          <li><a href="Invoices.php">Invoices</a></li>
        </ul>
      </li>
      <li>
        <a href="stock.php">
          <i class='bx bx-pie-chart-alt-2'></i>
          <span class="link_name">Stock</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="stock.php">Stock</a></li>
        </ul>
      </li>
    
      <!-- <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-plug'></i>
            <span class="link_name">Plugins</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Plugins</a></li>
          <li><a href="#">UI Face</a></li>
          <li><a href="#">Pigments</a></li>
          <li><a href="#">Box Icons</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-compass'></i>
          <span class="link_name">Explore</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Explore</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-history'></i>
          <span class="link_name">History</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">History</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-cog'></i>
          <span class="link_name">Setting</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Setting</a></li>
        </ul>
      </li> -->
      <li>
        <div class="profile-details">
          <div class="profile-content">
            <img src="image/user.png" alt="profileImg">
          </div>
          <div class="name-job">
            <div class="profile_name"><a class="link_name" href="profile.php"><?php

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