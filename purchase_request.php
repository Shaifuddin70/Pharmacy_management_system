<?php 
include 'nav/nav.php';

if(!isset($_SESSION['admin'])) {
    echo "<script>alert('Unauthorized Access')</script>";
    echo "<script>window.location='index.php'</script>";
    exit; // Stop execution if unauthorized
}

// Pagination variables
$results_per_page = 10; // Number of results per page
$offset = 0; // Starting offset
$page = 1; // Default page

// Get current page number from URL
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
    $offset = ($page - 1) * $results_per_page; // Calculate offset
}

$query = "SELECT * FROM purchase_table WHERE status IS NULL LIMIT $offset, $results_per_page"; // Fetch only pending requests
$data = mysqli_query($conn, $query);
$total_results_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM purchase_table WHERE status IS NULL"); // Query to get total results
$total_results_row = mysqli_fetch_assoc($total_results_query);
$total_results = $total_results_row['total']; // Total number of results

// Calculate total number of pages
$total_pages = ceil($total_results / $results_per_page);
?>

<div class="container">
    <h2 class="text-center text-uppercase p-2">Medicine Purchase Requests</h2>
    <div id="table"> 
       <table id="zctb" class="display table table-bordered table-hover text-center" cellspacing="0" width="100%">
           <thead>
               <tr>
                   <th id="sn">S/N</th>
                   <th>Medicine Name</th>
                   <th>Quantity</th>
                   <th>Operation</th>
               </tr>
           </thead>
           <tbody>
               <?php
               $c = $offset + 1; // Initialize serial number based on offset and page number
               if (mysqli_num_rows($data) > 0) {
                   while ($result = mysqli_fetch_assoc($data)) {
                       // Fetch medicine name based on medicine_id from the order
                       $medicine_id = $result['medicine_id'];
                       $medicine_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM medicine WHERE medicine_id='$medicine_id'"));
                       if ($medicine_info) {
                           echo '
                               <tr>
                                   <td id="sn">' . $c . '</td>
                                   <td>' . $medicine_info['medicine_name'] . '</td>
                                   <td>' . $result['unit'] . '</td>
                                   <td> 
                                       <a href="paccept.php?acceptid=' . $result['id'] . '" class="text-light"><button class="btn btn-primary">Accept</button></a>
                                       <a href="purchase_request.php?rejectid=' . $result['id'] . '" class="text-light"><button class="btn btn-danger">Reject</button></a>
                                   </td>
                               </tr>';
                           $c++;
                       }
                   }
               } else {
                   echo "<tr><td colspan='4'>No records found</td></tr>";
               }
               ?>
           </tbody>
       </table>
       <!-- Pagination links -->
       <div class="pagination">
        <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            </li>
            
            <!-- Page numbers -->
            <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Next button -->
            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            </li>
        </ul>
    </div>
   </div>
 
</div>

<?php 
include 'nav/footer.php'; 

// Reject query
if(isset($_GET['rejectid']) && is_numeric($_GET['rejectid'])) {
    $reject_id = $_GET['rejectid'];
    
    // Update the status of the purchase request to rejected
    $reject_query = "UPDATE purchase_table SET status='0' WHERE id=$reject_id";
    $reject_result = mysqli_query($conn, $reject_query);
    
    if($reject_result) {
        // Redirect back to this page after rejection
        header("Location: ".$_SERVER['PHP_SELF']."?page=".$page);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
