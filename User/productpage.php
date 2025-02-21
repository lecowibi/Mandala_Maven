<?php
include('../database.php');
session_start(); {
    if (!isset($_SESSION['username'])) {
        header("location:../signin.php");
    }
};
$username=$_SESSION['username'];
if(isset($_GET['search'])){
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);

    // Query to search for products that match the search term in the product name
    $search_query = "SELECT * FROM products WHERE name LIKE '%$search_term%'"; 
    $result = mysqli_query($conn, $search_query);
};
// Add to cart
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
    <link rel="stylesheet" href="css/productpage.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <!-- logo of the project  -->
    <div class="logo">
        <img src="img/logo.png" alt="Mandala Maven logo">
    </div>
    <!-- main navbar  -->
    <div class="navigation">
        <ul>
            <a href="userpage.php">
                <li>Home </li><span class="design"></span>
            </a>
            <a href="productpage.php" >
                <li class="active">Our Product </li><span class="design"></span>
            </a>
            <a href="aboutpage.php">
                <li>About Us </li><span class="design"></span>
            </a>
        </ul>
    </div>
    <!-- extra navbar i.e searchbar,cart,user  -->
    <div class="extra_nav">
        <form class="search" method="get" action="search.php">
            <input type="search" required name="search" placeholder="Search" value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>">
            <button type="submit" class="search_btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

<!-- Cart logo from lordicon -->
<?php
// Assuming $username is already sanitized and contains the username
$select_user_query = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'") or die('query failed');
$user = mysqli_fetch_assoc($select_user_query);
$user_id = $user['id']; // The user ID from the users table

// Now, query the cart table using user_id to get cart items
$select_row = mysqli_query($conn, "SELECT cart.*, products.name, products.price, products.image FROM cart
                                   JOIN products ON cart.product_id = products.id
                                   WHERE cart.user_id = '$user_id'") or die('query failed');
$row_count = mysqli_num_rows($select_row);
?>

<div class="dropdown2">
    <button class="cart2" onclick="toggle()">
        <lord-icon
            src="https://cdn.lordicon.com/odavpkmb.json"
            trigger="hover"
            stroke="bold"
            colors="primary:#121331,secondary:#000000"
            style="width:30px;height:30px">
        </lord-icon>
        <span class="count"><?php echo $row_count; ?></span>
    </button>
    <ul style="display:none;"> <!-- Hide by default -->
        <h3 class="baloo">Cart</h3>
        <?php
        $grand_total = 0;
        if ($row_count > 0) {
            while ($row = mysqli_fetch_assoc($select_row)) {
        ?>
                <li class="cart-item">
                    <div class="cart">
                        <img src="../admin/uploaded_images/<?php echo $row['image']; ?>" width="40px" alt="">
                        <h5 class="poppin"><?php echo $row['name']; ?></h5>
                        <p class="poppin">Nrs. <?php echo $row['price']; ?></p> <!-- Display quantity -->
                        <a href="userpage.php?remove=<?php echo $row['id']; ?>" onclick="return confirm('Remove item')" class="btn-remove"><i class="fas fa-trash"></i></a>
                    </div>
                </li>
        <?php
                // Calculate the grand total (price * quantity)
                $grand_total += $row['price'] * $row['quantity'];
            }
        }
        ?>
        <div class="cart-footer">
            <div class="total">
                <h6 class="poppin">Total</h6>
                <p class="poppin">Nrs. <?php echo $grand_total; ?></p>
            </div>
            <a href="userpage.php?delete_all" onclick="return confirm('Are you sure! You want to Delete All')" class="btn-remove"><i class="fas fa-trash"></i> Delete All</a>
        </div>
        <div class="checkout-btn">
            <!-- Enable the 'Order Now' button only if total is greater than 0 -->
            <a href="checkout.php" class="checkout-btn poppin <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Order Now</a>
        </div>
    </ul>
</div>


        <!-- end of cart section  -->
        <div class="dropdown">
            <lord-icon
                src="https://cdn.lordicon.com/bgebyztw.json"
                trigger="hover"
                stroke="bold"
                state="hover-looking-around"
                colors="primary:#121331,secondary:#000000"
                style="width:30px;height:30px">
            </lord-icon>
            <ul>
                <li> <?php echo $_SESSION['username'] ?></li>
                <li> <a href="order.php">Your order</a></li>
                <li> <a href="../pwdchange.php">Change Details</a></li>
                <li> <a href="../logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- end of navbar  -->
<div class="product">
    <h1 class="title baloo">Our <span>Products</span></h1>
    <div class="wrapper">
    <?php
    $fetch_query=mysqli_query($conn,"SELECT*FROM products");
    if(mysqli_num_rows($fetch_query)>0){
        while($fetch=mysqli_fetch_assoc($fetch_query)){
            ?>
              <form action="" method="post">
                        <div class="card">
                            <!-- image of the card  -->
                            <a href="redirect.php?redirect=<?php echo $fetch['id']; ?>">
                                <img src="../admin/uploaded_images/<?php echo $fetch['image']; ?>" alt="error loading image">
                            </a>
                            <!-- title price of the card  -->
                            <div class="title">
                                <p class="title_name baloo"><?php echo $fetch['name']; ?></p>
                                <p class="price baloo">Nrs. <?php echo $fetch['price']; ?></p>
                            </div>
                            <!-- shop and cart in card  -->
                            <div class="shop">
                                <input type="hidden" name="product_name" value="<?php echo $fetch['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $fetch['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $fetch['image']; ?>">
                                <a href="#"><input type="submit" name="add_to_cart" class="buy_btn poppin" value="Add to Cart"></a>
                                <div class="cart"><lord-icon
                                        src="https://cdn.lordicon.com/odavpkmb.json"
                                        trigger="hover"
                                        stroke="bold"
                                        colors="primary:#ffffff,secondary:#ffffff"
                                        style="width:25px;height:25px">
                                    </lord-icon></div>
                            </div>
                        </div>
                    </form>
            <?php
        }
    }
    ?>
    </div>
</div>
<?php
include('footer.php');
?>
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <script src="js/index.js"></script>
</body>
</html>