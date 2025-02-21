<?php
include('../database.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../signin.php");
    exit();
}

// Store the username from the session and fetch user_id
$username = $_SESSION['username'];
$user_query = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
$user_data = mysqli_fetch_assoc($user_query);
$user_id = $user_data['id']; // Fetch user ID

// Handle order cancellation
if (isset($_GET['cancel'])) {
    $cancel_id = mysqli_real_escape_string($conn, $_GET['cancel']);

    // Fetch the product details of the canceled order
    $order_query = mysqli_query($conn, "SELECT oi.product_id, oi.quantity 
                                        FROM order_items oi
                                        JOIN orders o ON oi.order_id = o.id
                                        WHERE o.id='$cancel_id' AND o.user_id='$user_id' AND o.status='Pending'");

    // Check if the order and items exist
    if ($order_query && mysqli_num_rows($order_query) > 0) {
        // Loop through the products of the canceled order
        while ($order_item = mysqli_fetch_assoc($order_query)) {
            $product_id = $order_item['product_id'];
            $quantity = $order_item['quantity'];

            // Restore visibility for the product (make the product available again)
            $update_product_query = "UPDATE products SET visible = 1 WHERE id = '$product_id'";
            if (!mysqli_query($conn, $update_product_query)) {
                die("Failed to restore product visibility: " . mysqli_error($conn));
            }
        }

        // Update the order status to "Cancelled"
        $update_order_query = "UPDATE orders SET status='Cancelled' WHERE id='$cancel_id' AND user_id='$user_id' AND status='Pending'";
        $cancelOrder = mysqli_query($conn, $update_order_query);

        if (!$cancelOrder) {
            die("Cancel Order Failed: " . mysqli_error($conn));
        } else {
            echo "<script>
                alert('Order has been successfully cancelled.');
                window.location.href = 'order.php';
            </script>";
            exit;
        }
    } else {
        // If no order items are found or order doesn't exist
        echo "<script>
            alert('The order could not be found or is already processed.');
            window.location.href = 'order.php';
        </script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven - My Orders</title>
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>
<?php include('nav.php'); ?>

<section class="display-product">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>City</th>
                <th>Street</th>
                <th>Landmark</th>
                <th>Order</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Estimated Delivery (Days)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch all orders for the current user along with order details
            $select_orders = mysqli_query($conn, "SELECT o.*, od.name, od.number, od.email, od.city, od.street, od.landmark 
                                                  FROM orders o 
                                                  JOIN order_details od ON o.id = od.order_id
                                                  WHERE o.user_id='$user_id' ORDER BY o.id DESC");

            if (!$select_orders) {
                die("Query Failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($select_orders) > 0) {
                while ($row = mysqli_fetch_assoc($select_orders)) {
                    $estimated_delivery = $row['expected_delivery_date']; // Get the estimated delivery date from the database
                    
                    // Calculate the difference in days
                    if ($estimated_delivery) {
                        $current_date = date("Y-m-d"); // Get the current date
                        $estimated_timestamp = strtotime($estimated_delivery);
                        $current_timestamp = strtotime($current_date);
                        $difference = ($estimated_timestamp - $current_timestamp) / (60 * 60 * 24); // Difference in days
                    } else {
                        $difference = "Not Set"; // If no delivery date is set
                    }
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['number']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['street']); ?></td>
                        <td><?php echo htmlspecialchars($row['landmark']); ?></td>
                        <td class="order"><?php 
                            // Fetch and display the ordered products and quantities
                            $order_items_query = mysqli_query($conn, "SELECT oi.quantity, p.name
                                                FROM order_items oi 
                                                JOIN products p ON oi.product_id = p.id
                                                WHERE oi.order_id = '" . $row['id'] . "'");

                            $ordered_products = [];
                            while ($item = mysqli_fetch_assoc($order_items_query)) {
                                $ordered_products[] = $item['name'] . " (" . $item['quantity'] . ")";
                            }
                            echo implode('<br>', $ordered_products);
                        ?></td>
                        <td>Nrs. <?php echo htmlspecialchars($row['total_price']); ?></td>
                        <td>
                            <span class="<?php echo strtolower($row['status']); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td>
                          <p>After</p>  <?php echo $difference; ?> Days
                        </td>
                        <td>
                            <?php if (strtolower($row['status']) === 'pending') { ?>
                                <a href="order.php?cancel=<?php echo $row['id']; ?>" class="delete-btn poppin" 
                                    onclick="return confirm('Are you sure you want to cancel the order?')">
                                    Cancel
                                </a>
                            <?php } else { ?>
                                <span class="disabled poppin">N/A</span>
                            <?php } ?>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='10' class='empty poppin'>You haven't ordered yet!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<script src="https://cdn.lordicon.com/lordicon.js"></script>
<script src="js/index.js"></script>
</body>
</html>
