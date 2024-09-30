<?php
include('../database.php');
if(isset($_POST['order_btn'])){
    $name=$_POST['name'];
    $number=$_POST['number'];
    $email=$_POST['email'];
    $city=$_POST['city'];
    $street=$_POST['street'];
    $landmark=$_POST['landmark'];
    
    $cart_query=mysqli_query($conn,"SELECT*FROM cart");
    $price_total=0;
    if(mysqli_num_rows($cart_query)>0){
        while($product_item = mysqli_fetch_assoc($cart_query)){
            $product_name[] = $product_item['name'] . ' (Nrs. ' . $product_item['price'] . ')';
            $price_total += $product_item['price'] * $product_item['quantity'];
        };
    };
    
    $total_product= implode('<br> ', $product_name);
    $detail_query = mysqli_query($conn,"INSERT INTO `order`(name,number,email,city,street,landmark,total_product,total_price) VALUES('$name','$number','$email','$city','$street','$landmark','$total_product','$price_total')");

    if($detail_query ){
        $message[]="Ordered Successfully! We will contact you on your delivery";
    }
    else{
        $message[]="Failed to Order";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="container">
        <section class="checkout-form">
            <form action="" method="post">
                <h1 class="baloo">Complete Your Order</h1>

     

                <!-- Input fields start here -->
                <div class="input">
                    <span>Name:</span>
                    <input type="text" name="name" placeholder="Enter your name" required>
                </div>
                <div class="input">
                    <span>Number:</span>
                    <input type="number" name="number" placeholder="Enter your number" required>
                </div>
                <div class="input">
                    <span>Email:</span>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input">
                    <span>City:</span>
                    <input type="text" name="city" placeholder="Eg:Kathmandu " required>
                </div>
                <div class="input">
                    <span>Street:</span>
                    <input type="text" name="street" placeholder="Eg:Boudha " required>
                </div>
                <div class="input">
                    <span>Landmark:</span>
                    <input type="text" name="landmark" placeholder="Eg:Nearby Fulbari Complex " required>
                </div>
                    <?php
                        if(isset($message)){
                            foreach($message as $message){
                                echo $message;
                            }
                        }
                    ?>
                <!-- Buttons -->
                <div class="btn">
                    <input type="button" value="Cancel" id="close">
                    <input type="submit" value="Order now" name="order_btn" class="order-btn">
                </div>
                           <!-- Moved display-order div here -->
                           <div class="display-order">
                            <h1>Your Order</h1> 
                    <?php
                    $select_cart = mysqli_query($conn, "SELECT * FROM cart");
                    $grand_total = 0;
                    $total = 0;

                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($row = mysqli_fetch_assoc($select_cart)) {
                            $grand_total = $total += ($row['price'] * $row['quantity']);
                            ?>
                            <div class="order">
                                <span class="name"><?php echo $row['name']; ?></span>
                                <span class="price">(Nrs. <?php echo $row['price']; ?>)</span>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='display-order'><span>Your cart is empty</span></div>";
                    }
                    ?>
                    <div class="total">Grand Total: <span>Nrs. <?= $grand_total; ?></span> </div>
                </div>
            </form>
            
        </section>
    </div>

    <script>
        document.querySelector('#close').onclick = () => {
            window.location.href='userpage.php';
        }
    </script>
</body>
</html>
