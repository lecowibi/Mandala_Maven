<?php
// Include the database connection
include('../database.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch current delivery days setting (if exists)
$select_settings = mysqli_query($conn, "SELECT * FROM settings WHERE name='delivery_days'");
$settings = mysqli_fetch_assoc($select_settings);
$delivery_days = $settings['value']; // Default or current value of delivery days

// Update delivery days
if (isset($_POST['update_delivery_days'])) {
    $new_delivery_days = mysqli_real_escape_string($conn, $_POST['delivery_days']);
    if (!empty($new_delivery_days)) {
        $update_query = "UPDATE settings SET value='$new_delivery_days' WHERE name='delivery_days'";
        $result = mysqli_query($conn, $update_query);
        if ($result) {
            $message[] = "Delivery days updated successfully!";
        } else {
            $message[] = "Failed to update delivery days: " . mysqli_error($conn);
        }
    } else {
        $message[] = "Please enter a valid number of days.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery Days</title>
</head>
<body>
    <form action="" method="post">
        <label for="delivery_days">Set delivery days for parcel delivery:</label>
        <input type="number" name="delivery_days" value="<?php echo htmlspecialchars($delivery_days); ?>" required>
        <input type="submit" name="update_delivery_days" value="Update Delivery Days">
    </form>

    <p class="empty poppin">
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo htmlspecialchars($msg) . "<br>";
            }
        }
        ?>
    </p>
</body>
</html>
