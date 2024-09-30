<?php
include('../database.php');
if (isset($_GET['delete'])) { 
    $delete_id = $_GET['delete'];

    // Execute the delete query
    $delete_query = mysqli_query($conn, "DELETE FROM `order` WHERE id='$delete_id'");
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
                    <th>Name</th>
                    <th>Number</th>
                    <th>E-mail</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Landmark</th>
                    <th>Order</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $select_product = mysqli_query($conn, "SELECT * FROM `order`");
                    if (mysqli_num_rows($select_product) > 0) {
                        while ($row = mysqli_fetch_assoc($select_product)) {
                    ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['city']; ?></td>
                                <td><?php echo $row['street']; ?></td>
                                <td><?php echo $row['landmark']; ?></td>
                                <td class="order"><?php echo $row['total_product']; ?></td>
                                <td>Nrs. <?php echo $row['total_price']; ?></td>
                                <td>
                                    <a href="order.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        $message[]="No order right now";
                    }
                    ?>
                </tbody>
            </table>
            <p class="empty poppin">
                        <?php 
                        if(isset($message)){
                            foreach($message as $empty){
                                echo $empty;
                            }
                        }
                     ?>
                     </p>
        </section>

</body>
</html>