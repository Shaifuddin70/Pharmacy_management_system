<?php include 'nav/nav.php'; ?>

<div class="container">
    <div class="title">
        <h1>Items</h1>
        <a href="add_medicine.php">
            <button class="col-2 btn btn-primary add_button"> Add Item</button>
        </a>
    </div>

    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Medicine</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Generic Name</th>
                <th>Expiry Date</th>
                <th>Unit Price</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Pagination variables
            $results_per_page = 10; // Number of results per page

            // Get current page number from URL
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $results_per_page; // Calculate offset

            // Get selected category, brand, or generic ID
            $id = isset($_GET['selectid']) ? $_GET['selectid'] : null;

            // Fetch data with pagination
            $query = "SELECT medicine.medicine_id, medicine.medicine_name, medicine.expiry_date, medicine.unit_price, medicine_catagory.catagory_name, medicine_brand.brand_name, medicine_generic.generic_name 
                      FROM medicine
                      JOIN medicine_brand ON medicine_brand.brand_id = medicine.brand_id
                      JOIN medicine_catagory ON medicine_catagory.catagory_id = medicine.catagory_id
                      JOIN medicine_generic ON medicine_generic.generic_id = medicine.generic_id";

            // Append WHERE condition based on selected ID
            if ($id !== null) {
                $query .= " WHERE medicine.catagory_id = $id";
            }

            $query .= " LIMIT $offset, $results_per_page";

            $data = mysqli_query($conn, $query);
            $total_results = mysqli_num_rows($data);

            if ($total_results != 0) {
                $c = $offset + 1; // Initialize serial number based on offset
                while ($result = mysqli_fetch_assoc($data)) {
                    echo '
                    <tr>
                        <td>' . $c . '</td>
                        <td>' . $result['medicine_name'] . '</td>
                        <td>' . $result['brand_name'] . '</td>
                        <td>' . $result['catagory_name'] . '</td>
                        <td>' . $result['generic_name'] . '</td>
                        <td>' . date('Y-m-d', strtotime($result['expiry_date'])) . '</td>
                        <td>' . $result['unit_price'] . '</td>
                        <td>
                            <a href="update.php?updateid=' . $result['medicine_id'] . '" class="text-light">
                                <button class="btn btn-primary">Update</button>
                            </a>
                            <a href="delete.php?deleteid=' . $result['medicine_id'] . '" class="text-light">
                                <button class="btn btn-danger">Delete</button>
                            </a>
                        </td>
                    </tr>';
                    $c++;
                }
            } else {
                echo "<tr><td colspan='8'>No records Found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Display pagination links
    echo '<div class="pagination">';
    echo '<ul class="pagination">';

    // Previous button
    if ($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">&laquo; Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= ceil($total_results / $results_per_page); $i++) {
        if ($i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($page < ceil($total_results / $results_per_page)) {
        echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next &raquo;</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
    }

    echo '</ul>';
    echo '</div>';
    ?>

</div>

<?php include 'nav/footer.php'; ?>
