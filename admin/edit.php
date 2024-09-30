<?php
include('../database.php');
if (isset($_GET['delete'])) { 
    $delete_id = $_GET['delete'];

    // Execute the delete query
    $delete_query = mysqli_query($conn, "DELETE FROM products WHERE id='$delete_id'");
if($delete_query){
$message[]="Product Deleted Successfully";
}
else{
$message[]="Failed to Delete Product";
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
    include('navbar.php');
    ?>
     <section class="display-product">
            <table>
                <thead>
                    <th>product image</th>
                    <th>product name</th>
                    <th>product price</th>
                    <th>action</th>
                </thead>
                <tbody>
                    <?php
                    $select_product = mysqli_query($conn, "SELECT * FROM products");
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
                        echo "<p>No product added</p>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

</body>
</html>