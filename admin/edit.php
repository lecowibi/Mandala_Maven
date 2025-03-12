<?php
include('../database.php');
include('navbar.php');

// Check if the admin is logged in
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
if (!$admin_id) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) { 
    $delete_id = $_GET['delete'];

    // Check if the product is in an order
    $check_order = mysqli_query($conn, "SELECT * FROM order_items WHERE product_id='$delete_id'");
    
    if (mysqli_num_rows($check_order) > 0) {
        $message[] = "Cannot delete product. It has been ordered.";
    } else {
        // Execute the delete query
        $delete_query = mysqli_query($conn, "DELETE FROM products WHERE id='$delete_id' AND admin_id = '$admin_id'");
        
        if($delete_query){
            $message[] = "Product Deleted Successfully";
        } else {
            $message[] = "Failed to Delete Product";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="admincss/style.css">
</head>
<body>
    <?php 
    // Display message if available
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "<p>$msg</p>";
        }
    }
    ?>

    <section class="display-product">
        <table>
            <thead>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                // Fetch products based on the logged-in admin
                $select_product = mysqli_query($conn, "SELECT * FROM products WHERE admin_id = '$admin_id'");
                if (mysqli_num_rows($select_product) > 0) {
                    while ($row = mysqli_fetch_assoc($select_product)) {
                ?>
                    <tr>
                        <td><img src="uploaded_images/<?php echo $row['image']; ?>" height="90" alt=""></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>Nrs.<?php echo $row['price']; ?></td>
                        <td>
                            <a href="edit.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            <a href="editProduct.php?edit=<?php echo $row['id']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No products added</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>