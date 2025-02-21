<?php
include('../database.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("location:../signin.php");
    exit();
}

// Initialize $fetch_data as an empty array in case the product is not found
$fetch_data = array(); 

// Fetch product data based on redirect parameter
if (isset($_GET['redirect'])) {
    $redirect_id = $_GET['redirect'];
    
    // Check if the redirect ID is valid
    $redirect_query = mysqli_query($conn, "SELECT * FROM products WHERE id='$redirect_id'");

    // Check if the query returned any result
    if (mysqli_num_rows($redirect_query) > 0) {
        $fetch_data = mysqli_fetch_assoc($redirect_query); // Fetch the product data if it exists
    } else {
        // If no product is found, set $fetch_data as an empty array to avoid errors
        echo "No product found";
    }
}

if(isset($_GET['search'])){
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query to search for products that match the search term in the product name
    $search_query = "SELECT * FROM products WHERE name LIKE '%$search_term%'"; 
    $result = mysqli_query($conn, $search_query);
}

$username = $_SESSION['username'];

/// Add to cart
if (isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];
    $productQuantity = 1;

  // First, fetch the user_id based on the username
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
$userRow = mysqli_fetch_assoc($userQuery);
$userId = $userRow['id'];

// Then, fetch the product_id based on the product name
$productQuery = mysqli_query($conn, "SELECT id FROM products WHERE name = '$productName'");
$productRow = mysqli_fetch_assoc($productQuery);
$productId = $productRow['id'];

$selectCart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'");

    if (mysqli_num_rows($selectCart) > 0) {
        echo "<script>alert('Product is already in the cart!');</script>";
    } else {
       // Fetch the user_id based on the username
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
$userRow = mysqli_fetch_assoc($userQuery);
$userId = $userRow['id'];

// Fetch the product_id based on the product name
$productQuery = mysqli_query($conn, "SELECT id FROM products WHERE name = '$productName'");
$productRow = mysqli_fetch_assoc($productQuery);
$productId = $productRow['id'];

// Now, insert into the cart table with the correct columns
$insertCart = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$userId', '$productId', '$productQuantity')");

        if ($insertCart) {
            echo "<script>alert('Product has been added to the cart successfully!');</script>";
        } else {
            echo "<script>alert('Failed to add product to the cart!');</script>";
        }
    }
}


// Remove selected item from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];

    // Fetch the user_id based on the username
    $userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    $userRow = mysqli_fetch_assoc($userQuery);
    $userId = $userRow['id'];

    // Delete the cart item where the cart id and user_id match
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id' AND user_id = '$userId'");
    header("Location: " . $_SERVER['PHP_SELF']);
}

// Remove all items from cart for the user
if (isset($_GET['delete_all'])) {
    // Fetch the user_id based on the username
    $userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    $userRow = mysqli_fetch_assoc($userQuery);
    $userId = $userRow['id'];

    // Delete all items from the cart for the specific user
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$userId'");
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="css/redirect.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>
<?php
include('nav.php');
?>

<!-- Information section -->
<section class="container">
    <div class="left">
        <?php
        // Check if the fetch_data array is not empty before displaying product image
        if (!empty($fetch_data)) {
            echo '<img src="../admin/uploaded_images/' . $fetch_data['image'] . '" alt="Product Image">';
        } else {
            echo 'Product not found.';
        }
        ?>
    </div>

    <!-- End of left section -->
    
    <form action="" method="post" class="right-form">
        <div class="right">
            <?php
            // Check if the fetch_data array is not empty before displaying product details
            if (!empty($fetch_data)) {
                echo '<h1 class="name baloo">' . $fetch_data['name'] . '</h1>';
                echo '<h6 class="price poppin">Nrs. ' . $fetch_data['price'] . '</h6>';
                echo '<p class="description poppin">' . $fetch_data['description'] . '</p>';
            } else {
                echo 'Product not found.';
            }
            ?>
            <div class="shop">
                <input type="hidden" name="product_name" value="<?php echo isset($fetch_data['name']) ? $fetch_data['name'] : ''; ?>">
                <input type="hidden" name="product_price" value="<?php echo isset($fetch_data['price']) ? $fetch_data['price'] : ''; ?>">
                <input type="hidden" name="product_image" value="<?php echo isset($fetch_data['image']) ? $fetch_data['image'] : ''; ?>">
                <button type="submit" name="add_to_cart" class="cart-btn poppin">Add to Cart<div class="bg"><i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i></div></button>
            </div>
        </div>
    </form>
</section>

<?php
include('footer.php');
?>
<script src="https://cdn.lordicon.com/lordicon.js"></script>
<script src="js/index.js"></script>
</body>
</html>
