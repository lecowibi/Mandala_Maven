<?php
include('../database.php');
session_start();

// Check if the user is logged in and store data from session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Use session user_id to identify the logged-in user
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $city = $_SESSION['city'];
    $street = $_SESSION['street'];
    $landmark = $_SESSION['landmark'];
    $number = $_SESSION['number'];
}

// Handle form submission for placing the order
if (isset($_POST['order_btn'])) {
    // Capture form data
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $landmark = $_POST['landmark'];
    $username = $_POST['username']; // Retrieve the hidden username

    // Retrieve cart items for the logged-in user
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
    $price_total = 0;
    $order_items = [];

    // Check if the user has items in the cart
    if (mysqli_num_rows($cart_query) > 0) {
        // Loop through each cart item
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            // Fetch the product details (name, price)
            $product_query = mysqli_query($conn, "SELECT name, price FROM products WHERE id = '" . $product_item['product_id'] . "'");
            $product = mysqli_fetch_assoc($product_query);

            // Calculate the total price
            $price_total += $product['price'] * $product_item['quantity'];

            // Add the product details to the order_items array
            $order_items[] = [
                'product_id' => $product_item['product_id'],
                'quantity' => $product_item['quantity'],
                'price' => $product['price'],
            ];
        }

        // Insert the order into the orders table
        $order_query = mysqli_query($conn, "INSERT INTO orders (user_id, total_price, status) VALUES ('$user_id', '$price_total', 'Pending')");

        // Get the inserted order ID
        $order_id = mysqli_insert_id($conn);

        // Insert the products into the order_items table
        foreach ($order_items as $item) {
            $insert_order_item = mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')");
        }

        // Insert the updated order details into the order_details table
        $insert_order_details = mysqli_query($conn, "INSERT INTO order_details (order_id, user_id, name, number, email, city, street, landmark) 
                                                    VALUES ('$order_id', '$user_id', '$name', '$number', '$email', '$city', '$street', '$landmark')");

        // If the order was successfully placed, delete items from the cart
        if ($order_query && $insert_order_details && count($order_items) > 0) {
            $delete_cart_query = mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

            if ($delete_cart_query) {
                echo "<script>
                    alert('Order placed successfully! We will contact you for delivery.');
                    window.location.href = 'userpage.php';
                </script>";
            } else {
                echo "<script> alert('Error clearing cart.'); </script>";
            }
        } else {
            echo "<script> alert('Error placing the order.'); </script>";
        }
    } else {
        echo "<script> alert('Your cart is empty.'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400..900&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <section class="checkout-form">
        <form action="" method="post">
            <h1 class="baloo">Complete Your Order</h1>

            <!-- Input fields start here -->
            <div class="input">
                <span>Name:</span>
                <input type="text" name="name" placeholder="Enter your name" value="<?php echo $username ?? ''; ?>" required>
            </div>
            <div class="input">
                <span>Number:</span>
                <input type="number" name="number" placeholder="Enter your number" value="<?php echo $number ?? ''; ?>" required>
            </div>
            <div class="input">
                <span>Email:</span>
                <input type="email" name="email" placeholder="Enter your email" value="<?php echo $email ?? ''; ?>" required>
            </div>
            <div class="input">
                <span>City:</span>
                <input type="text" name="city" placeholder="City" value="<?php echo $city ?? ''; ?>" required>
            </div>
            <div class="input">
                <span>Street:</span>
                <input type="text" name="street" placeholder="Street" value="<?php echo $street ?? ''; ?>" required>
            </div>
            <div class="input">
                <span>Landmark:</span>
                <input type="text" name="landmark" placeholder="Landmark" value="<?php echo $landmark ?? ''; ?>" required>
            </div>

            <!-- Hidden field for username -->
            <input type="hidden" name="username" value="<?php echo $username; ?>">

            <!-- Buttons -->
            <div class="btn">
                <input type="button" value="Cancel" id="close">
                <input type="submit" value="Order now" name="order_btn" class="order-btn">
            </div>

            <!-- Display order summary -->
            <div class="display-order">
                <h1>Your Order</h1>
                <?php
                // Query to get the user's ID based on their username
                $select_cart = mysqli_query($conn, "SELECT cart.quantity, products.name, products.price FROM cart 
                                                    JOIN products ON cart.product_id = products.id 
                                                    WHERE cart.user_id = '$user_id'");

                $grand_total = 0;

                if (mysqli_num_rows($select_cart) > 0) {
                    while ($row = mysqli_fetch_assoc($select_cart)) {
                        $grand_total += $row['price'] * $row['quantity'];
                        ?>
                        <div class="order">
                            <span class="name"><?php echo $row['name']; ?></span>
                            <span class="price">(Nrs. <?php echo $row['price']; ?>)</span>
                        </div>
                        <?php
                    }
                    // Display total price
                    echo "<div class='total'>Total: Nrs. $grand_total</div>";
                } else {
                    echo "<span>Your cart is empty</span>";
                }
                ?>

            </div>
        </form>
    </section>
</div>

<script>
    // Redirect to user page on cancel button click
    document.querySelector('#close').onclick = () => {
        window.location.href = 'userpage.php';
    };
</script>

</body>
</html>
