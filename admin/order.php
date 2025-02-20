<?php
// Include the database connection
include('../database.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle delete operation
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = mysqli_query($conn, "DELETE FROM `order` WHERE id='$delete_id'");
    if ($delete_query) {
        $message[] = "Product Deleted Successfully";
    } else {
        $message[] = "Failed to Delete Product: " . mysqli_error($conn);
    }
}

// Handle status update with estimated delivery date
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $estimated_date = mysqli_real_escape_string($conn, $_POST['estimated_date']);  // New field for estimated date

    $delivery_date = $status === 'Delivered' ? date('Y-m-d') : null;

    if (!empty($order_id) && !empty($status)) {
        // Update query for the status and estimated delivery date
        $update_query = $status === 'Delivered'
            ? "UPDATE `order` SET status='$status', delivery_time='$delivery_date' WHERE id='$order_id'"
            : "UPDATE `order` SET status='$status', expected_delivery_date='$estimated_date' WHERE id='$order_id'";

        $result = mysqli_query($conn, $update_query);

        if ($result) {
            $message[] = "Order status updated successfully!";
        } else {
            $message[] = "Failed to update order status: " . mysqli_error($conn);
        }
    } else {
        $message[] = "Invalid input for updating the status.";
    }
}

// Handle cancel operation
if (isset($_GET['cancel'])) {
    $cancel_id = mysqli_real_escape_string($conn, $_GET['cancel']);
    $cancel_query = mysqli_query($conn, "UPDATE `order` SET status='Cancelled' WHERE id='$cancel_id'");
    if ($cancel_query) {
        $message[] = "Order Cancelled Successfully";
    } else {
        $message[] = "Failed to Cancel Order: " . mysqli_error($conn);
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
    <style>
        .cancel-btn {
            color: red;
            border: 1px solid red;
            padding: 3px 12px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

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
                <th>Estimated Delivery</th> <!-- New column for estimated delivery -->
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                // Fetch all orders
                $select_product = mysqli_query($conn, "SELECT * FROM `order`");
                if (mysqli_num_rows($select_product) > 0) {
                    while ($row = mysqli_fetch_assoc($select_product)) {
                        // Calculate the difference in days for the estimated delivery
                        $order_date = new DateTime($row['created_at']);
                        $delivery_date = new DateTime($row['expected_delivery_date'] ?? date('Y-m-d'));
                        $days_diff_estimated = $order_date->diff($delivery_date)->days;
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                            <td><?php echo htmlspecialchars($row['landmark']); ?></td>
                            <td class="order"><?php echo htmlspecialchars($row['total_product']); ?></td>
                            <td>Nrs. <?php echo htmlspecialchars($row['total_price']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Delivered" <?php echo $row['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="Cancelled" <?php echo $row['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <br>
                                    <label for="estimated_date">Est. Delivery:</label>
                                    <input type="date" name="estimated_date" value="<?php echo htmlspecialchars($row['expected_delivery_date']); ?>" onchange="this.form.submit()">
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <?php echo $days_diff_estimated; ?> days (Estimated)
                            </td>
                            <td>
                                <a href="order.php?delete=<?php echo htmlspecialchars($row['id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                <?php if ($row['status'] != 'Cancelled') { ?>
                                    <a href="order.php?cancel=<?php echo htmlspecialchars($row['id']); ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='11'>No orders right now.</td></tr>";
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
