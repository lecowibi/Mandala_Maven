<?php
// Include your connection and other necessary files here
include('navbar.php');

// Update status and estimated delivery date when the form is submitted
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $estimated_date = $_POST['estimated_date'];

    // SQL query to update status and expected delivery date
    $update_query = "UPDATE orders SET status = ?, expected_delivery_date = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssi', $status, $estimated_date, $order_id);

    if (mysqli_stmt_execute($stmt)) {
        $message[] = 'Order updated successfully!';
    } else {
        $message[] = 'Failed to update order.';
    }
}

// Fetch orders for the current admin, ordered by most recent (descending)
$admin_id = $_SESSION['admin_id']; // Assuming admin_id is stored in session
$select_query = "SELECT od.*, o.total_price, o.status, o.expected_delivery_date, p.name AS product_name
                 FROM order_details od
                 LEFT JOIN orders o ON od.order_id = o.id
                 LEFT JOIN order_items oi ON oi.order_id = o.id
                 LEFT JOIN products p ON p.id = oi.product_id
                 WHERE o.admin_id = ? 
                 ORDER BY o.created_at DESC"; // Order by the most recent created_at
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
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="admincss/style.css">
    <style>
        .cancel-btn {
            color: red;
            border: 1px solid red;
            padding: 3px 12px;
            text-decoration: none;
        }
        .disabled-input {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

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
                <th>Status</th>
                <th>Estimated Delivery</th>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($select_product) > 0) {
                    while ($row = mysqli_fetch_assoc($select_product)) {
                        $order_status = $row['status'];
                        $order_id = $row['order_id'];

                        // Handle the order date and estimated delivery date
                        $order_date = isset($row['created_at']) ? new DateTime($row['created_at']) : new DateTime();
                        $delivery_date = isset($row['expected_delivery_date']) ? new DateTime($row['expected_delivery_date']) : new DateTime();
                        
                        $days_diff_estimated = $order_date->diff($delivery_date)->days;

                        // Get the last estimated date, if the form hasn't been submitted
                        $last_estimated_date = isset($row['expected_delivery_date']) ? $row['expected_delivery_date'] : '';

                        // Handle the case where estimated date is not set in the form
                        if (isset($_POST['estimated_date'])) {
                            $last_estimated_date = $_POST['estimated_date'];
                        }

                        // Disable inputs if the status is "Delivered" or "Cancelled"
                        $is_disabled = ($order_status == 'Delivered' || $order_status == 'Cancelled') ? 'disabled-input' : '';
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
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                    <select name="status" class="<?php echo $is_disabled; ?>" onchange="this.form.submit()" <?php echo $order_status == 'Delivered' || $order_status == 'Cancelled' ? 'disabled' : ''; ?>>
                                        <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Delivered" <?php echo $row['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <br>
                                    <!-- Show calendar only if the status is not "Delivered" or "Cancelled" -->
                                    <?php if ($order_status != 'Delivered' && $order_status != 'Cancelled') { ?>
                                        <label for="estimated_date">Est. Delivery:</label>
                                        <input type="date" name="estimated_date" value="<?php echo htmlspecialchars($last_estimated_date); ?>"
                                               min="<?php echo date('Y-m-d'); ?>" class="<?php echo $is_disabled; ?>" onchange="this.form.submit()" <?php echo $order_status == 'Delivered' || $order_status == 'Cancelled' ? 'disabled' : ''; ?>>
                                    <?php } ?>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <?php echo $days_diff_estimated; ?> days (Estimated)
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='10'>No orders right now.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="empty poppin">
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo htmlspecialchars($msg) . "<br>";
                }
            }
            ?>
        </p>
    </section>
</body>
</html>
