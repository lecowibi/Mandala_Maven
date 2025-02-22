<?php
include('../database.php');

// Handle the update form submission
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_price = $_POST['update_p_price'];
    $update_p_description = $_POST['update_p_description'];
    $update_p_image = $_FILES['update_p_image']['name'];
    $update_p_image_temp = $_FILES['update_p_image']['tmp_name'];
    $update_p_image_folder = 'uploaded_images/' . $update_p_image;

    // Check if a new image is uploaded, if not, retain the old image
    if (empty($update_p_image)) {
        $existing_image_query = mysqli_query($conn, "SELECT image FROM products WHERE id = '$update_p_id'");
        $existing_image_data = mysqli_fetch_assoc($existing_image_query);
        $update_p_image = $existing_image_data['image'];  // Use the old image
    }

    // Update the product details in the database
    $update_query = mysqli_query($conn, "UPDATE products SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image', description = '$update_p_description' WHERE id = '$update_p_id'");

    if ($update_query) {
        // If there's a new image, move it to the folder
        if (!empty($update_p_image)) {
            move_uploaded_file($update_p_image_temp, $update_p_image_folder);
        }

        $message[] = 'Product updated successfully';
        header('location: edit.php');
    } else {
        $message[] = 'Failed to update product';
    }
}

// Redirect if the Cancel button is clicked
if (isset($_POST['option_btn'])) {
    header('location: edit.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="admincss/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <section class="edit_container">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM products WHERE id='$edit_id'");
        
            if (mysqli_num_rows($edit_query) > 0) {
                while ($fetch = mysqli_fetch_assoc($edit_query)) {
        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <img src="uploaded_images/<?php echo $fetch['image']; ?>" height="200" alt="Product Image">
                <input type="hidden" name="update_p_id" value="<?php echo $fetch['id']; ?>">
                <input type="text" required name="update_p_name" value="<?php echo $fetch['name']; ?>">
                <input type="number" min="0" name="update_p_price" value="<?php echo $fetch['price']; ?>">
                <input type="text" name="update_p_description" value="<?php echo $fetch['description']; ?>">
                <input type="file" name="update_p_image" accept="image/png, image/jpg, image/jpeg">
                <input type="submit" value="Update the product" name="update_product">
                <input type="submit" value="Cancel" name="option_btn" id="close-edit">
            </form>
        <?php
                }
            }
        }
        ?>
    </section>
</body>
</html>
