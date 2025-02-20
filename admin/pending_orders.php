<?php
include('../database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Orders - Mandala Maven</title>
    <link rel="stylesheet" href="admincss/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <section class="display-product">
        <h1>Pending Orders</h1>
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
            </thead>
            <tbody>
                <?php
                $select_delivered = mysqli_query($conn, "SELECT * FROM `order` WHERE status='Pending'");
                if (mysqli_num_rows($select_delivered) > 0) {
                    while ($row = mysqli_fetch_assoc($select_delivered)) {
                ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['city']; ?></td>
                            <td><?php echo $row['street']; ?></td>
                            <td><?php echo $row['landmark']; ?></td>
                            <td class="order"><?php echo $row['total_product']; ?></td>
                            <td>Nrs. <?php echo $row['total_price']; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>No delivered orders yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
