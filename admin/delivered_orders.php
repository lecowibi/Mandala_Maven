<?php
// Include your connection and other necessary files here
include('navbar.php');

// Fetch only "Delivered" orders for the current admin
$admin_id = $_SESSION['admin_id']; // Assuming admin_id is stored in session
$select_query = "SELECT od.*, o.total_price, o.status, p.name AS product_name
                 FROM order_details od
                 LEFT JOIN orders o ON od.order_id = o.id
                 LEFT JOIN order_items oi ON oi.order_id = o.id
                 LEFT JOIN products p ON p.id = oi.product_id
                 WHERE o.admin_id = ? AND o.status = 'Delivered'"; // Filter orders by admin_id and 'Delivered' status

$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, 'i', $admin_id);
mysqli_stmt_execute($stmt);
$select_product = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven - Delivered Orders</title>
    <link rel="stylesheet" href="admincss/style.css">
</head>
<body>

    <section class="display-product">
        <h2>Delivered Orders</h2>
        <table>
            <thead>
                <th>Name</th>
                <th>Number</th>
                <th>Email</th>
                <th>City</th>
                <th>Street</th>
                <th>Landmark</th>
                <th>Order</th>
                <th>Total Price</th>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($select_product) > 0) {
                    while ($row = mysqli_fetch_assoc($select_product)) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['number']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                            <td><?php echo htmlspecialchars($row['landmark']); ?></td>
                            <td>
                                <?php 
                                // Display the product name from the joined products table
                                if (isset($row['product_name'])) {
                                    echo htmlspecialchars($row['product_name']);
                                } else {
                                    echo "Product is no longer available"; // In case no product name is available
                                }
                                ?>
                            </td>
                            <td>Nrs. <?php echo htmlspecialchars($row['total_price']); ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>No delivered orders right now.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
