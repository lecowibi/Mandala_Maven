<?php
include('../database.php');
session_start();

// Check if the user is logged in and store data from session
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $city = $_SESSION['city'];
    $street = $_SESSION['street'];
    $landmark = $_SESSION['landmark'];
    $number = $_SESSION['number'];
}

if (isset($_POST['order_btn'])) {
    // Capture form data
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $landmark = $_POST['landmark'];
    $username = $_POST['username']; // Retrieve the hidden username

    // Query to get items in the cart
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE username='$username'");
    $price_total = 0;
    $product_name = [];

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['name'] . ' (Nrs. ' . $product_item['price'] . ')';
            $price_total += $product_item['price'] * $product_item['quantity'];
        }
    }

    $total_product = implode('<br>', $product_name);

    // Insert order details into the database
    $detail_query = mysqli_query($conn, "INSERT INTO `order` (username, name, phone, email, city, street, landmark, total_product, total_price) 
        VALUES ('$username', '$name', '$number', '$email', '$city', '$street', '$landmark', '$total_product', '$price_total')");

    if ($detail_query) {
        // Delete cart items after successful order
        $cart_items_query = mysqli_query($conn, "SELECT * FROM cart WHERE username='$username'");
        if (mysqli_num_rows($cart_items_query) > 0) {
            while ($cart_item = mysqli_fetch_assoc($cart_items_query)) {
                $product_name = mysqli_real_escape_string($conn, $cart_item['name']);
                mysqli_query($conn, "DELETE FROM products WHERE name='$product_name'");
            }
        }

        mysqli_query($conn, "DELETE FROM cart WHERE username='$username'");

        echo "<script>
            alert('Ordered Successfully! We will contact you on your delivery');
            window.location.href = 'userpage.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Failed to place the order. Please try again.');</script>";
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
                <input type="number" name="number" placeholder="Enter your number" value="<?php echo $_SESSION['number'] ?? ''; ?>" required>
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

            <!-- Display confirmation or error message -->
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<span class='message'>$msg</span>";
                }
            }
            ?>

            <!-- Buttons -->
            <div class="btn">
                <input type="button" value="Cancel" id="close">
                <input type="submit" value="Order now" name="order_btn" class="order-btn">
            </div>

            <!-- Display order summary -->
            <div class="display-order">
                <h1>Your Order</h1>
                <?php
                $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE username='$username'");
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
                } else {
                    echo "<span>Your cart is empty</span>";
                }
                ?>
                <div class="total">Grand Total: <span>Nrs. <?= $grand_total; ?></span></div>
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
