<?php
include('../database.php');

if (isset($_POST['add_product'])) {
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_description = $_POST['p_description'];
    $p_image = $_FILES['p_image']['name'];
    $p_image_tmp = $_FILES['p_image']['tmp_name'];
    $p_image_folder = 'uploaded_images/' . $p_image;

    // Backend validation to ensure description doesn't exceed 255 characters
    if (strlen($p_description) > 255) {
        $message[] = "Description can't be more than 255 characters.";
    } else {
        // Insert product data into the database
        $insert = "INSERT INTO products(name, price, image, description) VALUES('$p_name', '$p_price', '$p_image','$p_description')";
        $insert_query = mysqli_query($conn, $insert) or die('Query failed');

        if ($insert_query) {
            move_uploaded_file($p_image_tmp, $p_image_folder);
            $message[] = "Inserted successfully";
        } else {
            $message[] = "Insertion unsuccessful";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="admincss/style.css">
</head>

<body>
    <?php include("navbar.php"); ?>
    <div class="container">
        <section>
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Add a new product</h3>
                <br><br><input type="text" name="p_name" placeholder="Enter the product name" required>
                <br><br><input type="number" name="p_price" min="0" placeholder="Enter the product price" required>
                <br><br><textarea name="p_description" placeholder="Enter the Description" maxlength="255" required></textarea>
                <br><br><input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" required>
                <?php
                if (isset($message)) {
                    foreach ($message as $messages) {
                        echo "<h6>$messages</h6>";
                    }
                }
                ?>
                <br><br><input type="submit" value="Add the product" name="add_product">
            </form>
        </section>
    </div>
    <script src="index.js"></script>
</body>

</html>