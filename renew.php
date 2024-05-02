<?php 
include 'nav/nav.php'; 
$id = $_GET['medicine_id']; 
$query = mysqli_query($conn, "SELECT m.medicine_name, s.expiry_date FROM medicine_stock s INNER JOIN medicine m ON s.medicine_id = m.medicine_id WHERE s.medicine_id='$id'"); 
$fetch = mysqli_fetch_array($query); 
?>

<div class="container mt-3"> 
    <h2 class="text-center text-uppercase p-2">Update Medicine Expiry Date</h2> 
    <form method="post"> 
        <table class="table table-borderless"> 
            <tr> 
                <th>Medicine Name</th> 
                <td><?php echo $fetch['medicine_name']; ?></td> 
            </tr> 
            <tr> 
                <th>Expiry Date</th> 
                <td> <input type="date" class=" form-control form-control-lg" name="expiry_date" value="<?php echo $fetch['expiry_date']; ?>" autocomplete="off"></td> 
            </tr> 
        </table> 
        <div class="button"> 
            <button style="border-radius: 24px; background: #03274d; padding: 10px 20px; border: none;" type="submit" name="submit">UPDATE</button> 
        </div> 
    </form> 
</div> 

<?php 
if (isset($_POST['submit'])) { 
    $expiry_date = $_POST['expiry_date']; 
    $query = "UPDATE `medicine_stock` SET `expiry_date`='$expiry_date' WHERE `medicine_id`='$id'"; 
    $data = mysqli_query($conn, $query); 

    if ($data) { 
        echo "<script>alert('Expiry date updated successfully')</script>"; 
        echo "<script>window.location='stock.php'</script>"; 
    } else { 
        echo "<script>alert('Failed to update expiry date')</script>"; 
    } 
} 
?>
