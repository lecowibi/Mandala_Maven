<?php
include('../database.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("location:../signin.php");
    exit();
}

// Fetch product data based on redirect parameter
if (isset($_GET['redirect'])) {
    $redirect_id = $_GET['redirect'];
    
    // Check if the redirect ID is valid
        $redirect_query = mysqli_query($conn, "SELECT * FROM products WHERE id='$redirect_id'");

        // Check if the query returned any result
        if (mysqli_num_rows($redirect_query) > 0) {
            $fetch_data = mysqli_fetch_assoc($redirect_query);
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
         <img src="../admin/uploaded_images/<?php echo $fetch_data['image']; ?>" alt="Product Image">
    </div>
    <!-- End of left section -->
    
    <form action="" method="post" class="right-form">
        <div class="right">
                <h1 class="name baloo"><?php echo $fetch_data['name']; ?></h1>
                <h6 class="price poppin">Nrs. <?php echo $fetch_data['price']; ?></h6>
                <p class="description poppin"><?php echo $fetch_data['description']; ?></p>
                <div class="shop">
                    <input type="hidden" name="product_name" value="<?php echo $fetch_data['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_data['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_data['image']; ?>">
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
