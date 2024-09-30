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
};
if (isset($_GET['cancel'])) { 
    $delete_id = $_GET['cancel'];
    mysqli_query($conn, "DELETE FROM `order` WHERE id='$delete_id'");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="css/nav.css">
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
<section class="display-product">
            <table>
                <thead>
                    <th>Name</th>
                    <th>Number</th>
                    <th>E-mail</th>
                    <th>City</th>
                    <th>Street</th>
                    <th>Landmark</th>
                    <th>Order</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $select_product = mysqli_query($conn, "SELECT * FROM `order`");
                    if (mysqli_num_rows($select_product) > 0) {
                        while ($row = mysqli_fetch_assoc($select_product)) {
                    ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['city']; ?></td>
                                <td><?php echo $row['street']; ?></td>
                                <td><?php echo $row['landmark']; ?></td>
                                <td class="order"><?php echo $row['total_product']; ?></td>
                                <td>Nrs. <?php echo $row['total_price']; ?></td>
                                <td>
                                    <a href="order.php?cancel=<?php echo $row['id']; ?>" class="delete-btn poppin" onclick="return confirm('Are you sure you want to cancel the order?')">
                                         Cancel
                                    </a>
                                </td>
                                
                            </tr>
                    <?php
                        }
                    } else {
                        $message[]="You haven't ordered yet!";
                    }
                    ?>
                </tbody>
            </table>
                <p class="empty poppin">
                        <?php 
                        if(isset($message)){
                            foreach($message as $empty){
                                echo $empty;
                            }
                        }
                     ?>
                     </p>
</section>
        <!-- end of navbar -->
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <script src="js/index.js"></script>
</body>
</html>