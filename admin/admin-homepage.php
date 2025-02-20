<?php
include('../database.php');  // Include your database connection file


// Fetch the admin username from the session
$admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';

// Fetch the total number of products
$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = mysqli_query($conn, $product_query);
$product_data = mysqli_fetch_assoc($product_result);
$total_products = $product_data['total_products'];

// Fetch the total number of orders
$order_query = "SELECT COUNT(*) AS total_orders FROM `order`";
$order_result = mysqli_query($conn, $order_query);
$order_data = mysqli_fetch_assoc($order_result);
$total_orders = $order_data['total_orders'];

// Fetch the total number of users
$user_query = "SELECT COUNT(*) AS total_users FROM user_form";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$total_users = $user_data['total_users'];

// Fetch recent activities (Example: last 5 products added)
$recent_products_query = "SELECT name, price FROM products ORDER BY id DESC LIMIT 9";
$recent_products_result = mysqli_query($conn, $recent_products_query);
$recent_products = mysqli_fetch_all($recent_products_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mandala Maven</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="admincss/homepage.css">
</head>

<body>
    <?php include("navbar.php"); ?>

    <div class="admin-container">
        <!-- Dashboard Section -->
        <section class="dashboard">
            <h1>Welcome Back, <?php echo $_SESSION['username']; ?>!</h1> <!-- Displaying dynamic admin username -->
            <p>Manage your store seamlessly with the following options and updates.</p>
            <div class="stats">
                <div class="stat">
                    <h3>Total Products</h3>
                    <p class="num"><?php echo $total_products; ?></p>
                </div>

                <div class="stat">
                    <h3>Total Orders</h3>
                    <p class="num"><?php echo $total_orders; ?></p>
                </div>

                <div class="stat">
                    <h3>Registered Users</h3>
                    <p class="num"><?php echo $total_users; ?></p>
                </div>
            </div>
        </section>

        <!-- Recent Activities Section -->
        <section class="recent-activities">
            <h2>Recent Activities</h2>
            <ul>
                <?php foreach ($recent_products as $product): ?>
                <li>
                    <i class="fas fa-box"></i> New Product "<?php echo $product['name']; ?>" added.<br>Nrs. <?php echo substr($product['price'], 0, 50); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>

</html>
