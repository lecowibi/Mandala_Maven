<?php
include('../database.php');
session_start(); {
    if (!isset($_SESSION['username'])) {
        header("location:../signin.php");
    }
};
$username = $_SESSION['username'];
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
    <link rel="stylesheet" href="css/aboutpage.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
              <!-- starting navbar  -->
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
            <a href="productpage.php">
                <li>Our Product </li><span class="design"></span>
            </a>
            <a href="aboutpage.php">
                <li class="active">About Us </li><span class="design"></span>
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
  <!-- end of navbar -->
        <!-- new section of the about us  -->
         <section class="about_us">
            <!-- left side of the div which is information  -->
            <div class="left">
                <h3 class="baloo">About <span>Us</span></h3>
                <h6 class="poppin">We provide you a best Mandala art with great quality</h6>
                <p class="poppin">Decorate your room with art of Mandala</p>
                <a href="productpage.php"><button class="btn baloo">Explore Now <i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i></button></a>
            </div>

            <!-- right side of the div which is image  -->
            <div class="right">
                <img src="img/main.png" alt="">
            </div>
         </section>
    </header>
    <!-- end of the header section  -->
     <!-- start of the objective  -->
<section class="objective">
    <div class="left">
        <img src="img/main.png" alt="">
    </div>
    <div class="right">
        <h1>Our Objective</h1>
        <div class="obj">
            <div class="obj-title">
                <img src="img/arrow.png" alt="">
                <h3 class="title">Showcase diverse art collection:</h3>
            </div>
            <p>Mandala Maven aims to feature a wide range of mandala designs, blending traditional and modern forms, ensuring a rich variety for customers to explore.</p>
        </div>


        <div class="obj">
            <div class="obj-title">
                <img src="img/arrow.png" alt="">
                <h3 class="title">Educate and engage customers:</h3>
            </div>
            <p> The platform will inform customers about the cultural and historical significance of mandalas, with a special focus on Nepali art, creating a deeper connection between the art and its audience.</p>
        </div>


        <div class="obj">
            <div class="obj-title">
                <img src="img/arrow.png" alt="">
                <h3 class="title">Facilitate a secure art-selling platform:</h3>
            </div>
            <p>By integrating reliable and secure purchasing options, Mandala Maven will provide a trustworthy marketplace where customers can buy art confidently while supporting artists globally.</p>
        </div>
    </div>
</section>
<!-- end of objective  -->
<!-- start of section  -->
<section class="about_artist">
    <h1 class="baloo">About the Artist</h1>
    <div class="artist_info">
<div class="intro">
<p class="poppin">I'm Siddhanta Khanal, a 20-year-old freelance mandala artist. I have been passionately engaged in creating mandala art since 2019. 
        </p>
        <p class="poppin">For me, the intricate process of designing mandalas brings a deep sense of peace and patience, allowing for both creative expression and personal tranquillity. For me, mandala art has become not only a profession but also a source of inner balance and fulfilment.</p>
</div>
        <img src="img/artist.jpg" alt="error loading image">
    </div>
</section>
<!-- end of artist section  -->
 <!-- start of direct contact section  -->
<section class="direct_contact">
<div class="info">
    <h1 class="contact poppin">Contact Artist</h1>
    <h3 class="name poppin">Siddhanta Khanal</h3>
    <p class="phone poppin">9876543210,9865743201</p>
    <h6 class="gmail poppin">siddhankhanal3@gmail.com</h6>
    <h6 class="location poppin">Saraswatinagar-6, Kathmandu</h6>
    <div class="link">
    <a href="https://www.facebook.com/siddhantha.khanal?rdid=BPQSFqI5Arl8vxSk&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F1AKXTSFynh%2F">
        <i class="fa-brands fa-facebook-f" style="color: #000000;"></i>
        </a> 
     <a href="https://www.instagram.com/artbysiddhanta/profilecard/">
        <i class="fa-brands fa-instagram" style="color: #000000;"></i>
        </a> 
     <a href="https://www.pinterest.com/siddhankhanal3/?invite_code=a2beeec181c744eca7376c63d6f2225d&sender=656188745615447373">
        <i class="fa-brands fa-pinterest-p" style="color: #000000;"></i>
        </a>
    </div>
</div>
<!-- form section  -->
<form action="https://api.web3forms.com/submit" method="POST"" class="form">
    <input type="hidden" name="access_key" value="8801734a-c2be-4913-a03a-7849fa9359b2">
    <div class="name">
        <input type="text" placeholder="First Name" name="first-name" required>
        <input type="text" placeholder="Last Name" name="last-name" required>
    </div>
    <input type="number" name="number" placeholder="Phone Number" required>
    <textarea name="Message" name="message" placeholder="Message" required></textarea>
    <input type="checkbox" name="botcheck" class="hidden" style="display: none;">
    <input type="submit" value="Submit" class="submit_btn">
</form>
</section>
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <script src="js/index.js"></script>
</body>
</html>