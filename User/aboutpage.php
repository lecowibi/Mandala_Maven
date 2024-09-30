<?php
include('../database.php');
session_start(); {
    if (!isset($_SESSION['user_name'])) {
        header("location:../signin.php");
    }
};
if(isset($_GET['search'])){
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);

    // Query to search for products that match the search term in the product name
    $search_query = "SELECT * FROM products WHERE name LIKE '%$search_term%'"; 
    $result = mysqli_query($conn, $search_query);
};
if (isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];
    $productQuantity = 1;


    $selectCart = mysqli_query($conn, "SELECT * FROM cart WHERE name= '$productName'");
    if (mysqli_num_rows($selectCart) > 0) {
        header('location:userpage.php');
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
    header('location:userpage.php');
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
    <h6 class="gmail poppin">siddhantakhanal@gmail.com</h6>
    <h6 class="location poppin">Saraswatinagar-6, Kathmandu</h6>
    <div class="link">
    <a href="#">
        <i class="fa-brands fa-facebook-f" style="color: #000000;"></i>
        </a> 
     <a href="#">
        <i class="fa-brands fa-instagram" style="color: #000000;"></i>
        </a> 
     <a href="#">
        <i class="fa-brands fa-pinterest-p" style="color: #000000;"></i>
        </a>
    </div>
</div>
<!-- form section  -->
<form action="" class="form">
    <div class="name">
        <input type="text" placeholder="First Name">
        <input type="text" placeholder="Last Name">
    </div>
    <input type="number" placeholder="Phone Number">
    <textarea name="Message" placeholder="Message"></textarea>
    <input type="submit" value="Submit" class="submit_btn">
</form>
</section>
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <script src="js/index.js"></script>
</body>
</html>