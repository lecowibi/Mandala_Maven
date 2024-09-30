<?php
include("../database.php");
session_start(); {
    if (!isset($_SESSION['user_name'])) {
        header("location:../signin.php");
    }
};
// add to cart  
if (isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];
    $productQuantity = 1;


    $selectCart = mysqli_query($conn, "SELECT * FROM cart WHERE name= '$productName'");
    if (mysqli_num_rows($selectCart) > 0) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        $insertCart = mysqli_query($conn, "INSERT INTO cart(name, price, image,quantity) VALUES ('$productName','$productPrice','$productImage','$productQuantity')");
    }
};
// remove selected item from cart 
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id'");
};
// remove all the item from cart  
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM cart");
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="css/userpage.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <!-- starting header  -->
    <header>
        <!-- starting navbar  -->
        <nav>
            <!-- logo of the project  -->
            <div class="logo">
                <img src="img/logo.png" alt="Mandala Maven logo">
            </div>
            <!-- main nabvar  -->
            <div class="navigation">
                <ul>
                    <a href="userpage.php">
                        <li class="active">Home </li><span class="design"></span>
                    </a>
                    <a href="productpage.php">
                        <li>Our Product </li><span class="design"></span>
                    </a>
                    <a href="aboutpage.php">
                        <li>About Us </li><span class="design"></span>
                    </a>
                </ul>
            </div>
            <!-- extra navbar i.e searchbar,cart,user  -->
            <div class="extra_nav">
                <form class="search" method="get" action="search.php">
                    <input type="search" required name="search" placeholder="Search" value="<?php if (isset($_GET['search'])) {
                                                                                                echo $_GET['search'];
                                                                                            } ?>">
                    <button type="submit" class="search_btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>

                <!-- cart logo from lordicon  -->
                <?php
                $select_row = mysqli_query($conn, "SELECT * FROM cart") or die('query failed');
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
                        $select = mysqli_query($conn, "SELECT * FROM cart");
                        $grand_total = 0;
                        if (mysqli_num_rows($select) > 0) {
                            while ($row = mysqli_fetch_assoc($select)) {
                        ?>
                                <li class="cart-item">
                                    <div class="cart">
                                        <img src="../admin/uploaded_images/<?php echo $row['image']; ?>" width="40px" alt="">
                                        <h5 class="poppin"><?php echo $row['name']; ?></h5>
                                        <p class="poppin">Nrs.<?php echo $row['price']; ?></p>
                                        <a href="userpage.php?remove=<?php echo $row['id']; ?> " onclick="return confirm('Remove item')" class="btn-remove"><i class="fas fa-trash"></i></a>
                                    </div>
                                </li>
                        <?php
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
                            <a href="checkout.php" class="checkout-btn poppin <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Order Now</a>
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
                        <li> <?php echo $_SESSION['user_name'] ?></li>
                        <li> <a href="order.php">Your order</a></li>
                        <li><a href="../logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end of navbar -->
        <!-- main body of the project  -->
        <section class="header_main">
            <div class="left">
                <h1 class="quote baloo">Enjoy your <span>mandala</span> shopping here</h1>
                <p class="poppin">Decorate your room with the art of Mandala</p>
                <a href="productpage.php"><button class="btn baloo">Explore Now <i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i></button></a>
            </div>
            <div class="right">
                <img src="img/main.png" alt="">
            </div>
        </section>
    </header>
    <!-- header ended and main section started  -->
    <main>
        <!-- popular trend  -->

        <div class="pop-trend">
            <h1 class="trend baloo">Popular <span>Art</span></h1>
            <div class="wrapper">
                <!-- card section  -->
                <?php
                // Fetch random products
                $query = "SELECT * FROM products ORDER BY RAND() LIMIT 3";
                $result = mysqli_query($conn, $query);

                // Display the fetched data
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <form action="" method="post">
                        <div class="card">
                            <!-- image of the card  -->
                            <a href="redirect.php?redirect=<?php echo $row['id']; ?>">
                                <img src="../admin/uploaded_images/<?php echo $row['image']; ?>" alt="error loading image">
                            </a>
                            <!-- title price of the card  -->
                            <div class="title">
                                <p class="title_name baloo"><?php echo $row['name']; ?></p>
                                <p class="price baloo">Nrs. <?php echo $row['price']; ?></p>
                            </div>
                            <!-- shop and cart in card  -->
                            <div class="shop">
                                <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
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
                ?>



            </div>
            <!-- end of wrapper  -->
        </div>
        </div>
        </div>
    </main>
    <!-- end of main  -->
    <section class="delivery-section">
        <h1 class="baloo">How to use Delivery Service</h1>
        <div class="delivery">
            <!-- card section  -->
            <div class="card">
                    <img src="img/choose.png" alt="Error loading image" >
                    <h4 class="poppin">Choose your Mandala</h4>
                    <p class="poppin">There are 20+ Mandala for you</p>
            </div>
            <div class="card">
                    <img src="img/delivery.png" alt="Error loading image" >
                    <h4 class="poppin">We deliver it to you</h4>
                    <p class="poppin">Wait for your delivery</p>
            </div>
            <div class="card">
                    <img src="img/decorate.jpg" alt="Error loading image">
                    <h4 class="poppin">Decorate your room</h4>
                    <p class="poppin">Enjoy our services</p>
            </div>
        </div>
    </section>
    <!-- End of delivery section  -->
     <!-- about us section  -->
     <section class="about-us">
<div class="img"><img src="img/main.png" alt=""></div>
<div class="about">
    <h1 class="baloo">About <span>Us</span></h1>
    <h4 class="poppin">We provide quality mandala for you and ready to deliver</h4>
    <p class="poppin"> We create beautifully handcrafted mandalas designed to inspire balance and harmony. Our mission is to deliver high-quality pieces that elevate your space and bring a sense of calm to your life.</p>
    <a href="productpage.php"><button class="baloo">Get your Mandala</button></a>
</div>
     </section>
     <!-- end of about us  -->
     <section class=" about-product">
    <h1 class="product-title baloo">Special offer for <span>you</span></h1>
    <div class="product-wrapper">
    <?php
    $fetch_query=mysqli_query($conn,"SELECT*FROM products LIMIT 6");
    if(mysqli_num_rows($fetch_query)>0){
        while($fetch=mysqli_fetch_assoc($fetch_query)){
            ?>
              <form action="" method="post">
                        <div class="product-card">
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
</section>
<a class="more" href="productpage.php"><button href="productpage.php" class="more_btn poppin">Explore More</button></a>
<?php
include('footer.php');
?>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="js/index.js"></script>

</body>

</html>