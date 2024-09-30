<?php
include('../database.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="admincss/style.css">
    <link rel="stylesheet" href="admincss/product.css">
</head>
<body>
    <?php
    include("navbar.php");
    ?>
    <section class="productContainer">
        <?php
        $fetchProduct=mysqli_query($conn,"SELECT*FROM products");
        if(mysqli_num_rows($fetchProduct)>0){
            while($row= mysqli_fetch_assoc($fetchProduct) ){
              
        ?>
        <div class="card">
            <img src="uploaded_images/<?php  echo $row['image']?>" height="200" alt="">
            <div class="p-detail">
                    <h6><?php echo $row['name'];  ?></h6>
                    <h6>Price:   Nrs.<?php echo $row['price'];  ?></h6>
            </div>

        </div>
        <?php
            }
        }
        else{
            echo "<p>No product added<h1>";
        }

        ?>
    </section>
</body>
</html>