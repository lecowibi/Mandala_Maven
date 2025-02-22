<?php
include('../database.php');
include("navbar.php");
// Assuming the admin_id is stored in the session after login
$admin_id = $_SESSION['admin_id']; // Make sure this is set after login

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
    <section class="productContainer">
        <?php
        // Modify the SQL query to fetch products based on the admin_id
        $fetchProduct = mysqli_query($conn, "SELECT * FROM products WHERE admin_id = '$admin_id'");

        if (mysqli_num_rows($fetchProduct) > 0) {
            while ($row = mysqli_fetch_assoc($fetchProduct)) {
        ?>
        <div class="card">
            <img src="uploaded_images/<?php echo $row['image']; ?>" height="200" alt="">
            <div class="p-detail">
                    <h6><?php echo $row['name']; ?></h6>
                    <h6>Price: Nrs. <?php echo $row['price']; ?></h6>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p>No products added by this admin.</p>";
        }
        ?>
    </section>
</body>
</html>
