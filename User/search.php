<?php
include('../database.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("location:../signin.php");
    exit();
}
$username = $_SESSION['username'];

if(isset($_GET['search'])){
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query to search for products that match the search term in the product name
    $search_query = "SELECT * FROM products WHERE name LIKE '%$search_term%'"; 
    $result = mysqli_query($conn, $search_query);
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];
    $productQuantity = 1;

    // Fetch the user_id based on the username
    $userQuery = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    $userRow = mysqli_fetch_assoc($userQuery);
    $userId = $userRow['id'];

    // Fetch the product_id based on the product name
    $productQuery = mysqli_query($conn, "SELECT id FROM products WHERE name = '$productName'");
    $productRow = mysqli_fetch_assoc($productQuery);
    $productId = $productRow['id'];

    // Check if the product is already in the cart
    $selectCart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'");

    if (mysqli_num_rows($selectCart) > 0) {
        echo "<script>alert('Product is already in the cart!');</script>";
    } else {
        // Insert into the cart table with the correct columns
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
    exit();
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
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="css/nav.css"> 
    <link rel="stylesheet" href="css/search.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<?php
include('nav.php');
?>

<h1 class="search-title baloo">Search Results for "<?php echo $search_term; ?>"</h1>
<div class="wrapper">
    <?php 
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <form action="" method="post">
                    <!-- Image of the card -->
                    <a href="redirect.php?redirect=<?php echo $row['id']; ?>">
                        <img src="../admin/uploaded_images/<?php echo $row['image']; ?>" alt="error loading image">
                    </a>
                    <!-- Title and price of the card -->
                    <div class="title">
                        <p class="title_name baloo"><?php echo $row['name']; ?></p>
                        <p class="price baloo">Nrs. <?php echo $row['price']; ?></p>
                    </div>
                    <!-- Shop and Cart in card -->
                    <div class="shop">
                        <!-- Hidden fields to pass product details to cart -->
                        <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">

                        <!-- Add to Cart Button -->
                        <input type="submit" name="add_to_cart" class="buy_btn poppin" value="Add to Cart">
                        
                        <!-- Cart Icon -->
                        <div class="cart">
                            <lord-icon
                                src="https://cdn.lordicon.com/odavpkmb.json"
                                trigger="hover"
                                stroke="bold"
                                colors="primary:#ffffff,secondary:#ffffff"
                                style="width:25px;height:25px">
                            </lord-icon>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
    } else {
        echo "<p class='error poppin'>No products found.</p>";
    }
    ?>
</div>

<script src="https://cdn.lordicon.com/lordicon.js"></script>
<script src="js/index.js"></script>
</body>
</html>
