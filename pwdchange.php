<?php
include('database.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    // Capture and sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $landmark = mysqli_real_escape_string($conn, $_POST['landmark']);
    $number = $_POST['number'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current user data
    $query = mysqli_query($conn, "SELECT * FROM user_form WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($query);

    // Validate current password
    if (!password_verify($current_password, $user['password'])) {
        $error[] = 'Current password is incorrect';
    } else {
        // Update password if needed
        if (!empty($new_password) && $new_password === $confirm_password) {
            if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE user_form SET password='$hashed_password' WHERE id='$user_id'";
                mysqli_query($conn, $update_query);
            } else {
                $error[] = 'New password does not meet requirements';
            }
        } elseif ($new_password !== $confirm_password) {
            $error[] = 'New passwords do not match';
        }

        // Update other details
        $update_details_query = "UPDATE user_form SET username='$username', email='$email', city='$city', street='$street', landmark='$landmark', phone='$number' WHERE id='$user_id'";
        if (mysqli_query($conn, $update_details_query)) {
            $success = 'Profile updated successfully';
            header('location:signin.php');
        } else {
            $error[] = 'Failed to update profile';
        }
    }
}

// Fetch user data for the form
$user_query = mysqli_query($conn, "SELECT * FROM user_form WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/form.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
        .success-message {
            color: green;
            font-size: 0.9em;
        }
        form{
            height: 85vh;
            width: 28vw;
        }
        .navigation ul a:hover {
    color: var(--primary);
    transition: .6s;
}

.navigation {
    background-color: white;
    width: 350px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 0px 0px 50px 50px;
    height: 60px;
    position: absolute;
    top: 0px;
    left: 40%;
}

.navigation ul {
    display: flex;
    list-style: none;
    gap: 30px;
    position: relative;
}

.navigation ul a {
    text-decoration: none;
    font-family: var(--text);
    letter-spacing: 2px;
    color: black;
    font-size: 18px;
    font-weight: 600;
    position: relative;
}

.navigation ul a .design {
    position: absolute;
    top: 0;
    left: -5px;
    width: 110%;
    height: 100%;
    z-index: -1;
    border-bottom: 2px solid var(--primary);
    border-radius: 16px;
    transform: scale(0) translateY(50px);
    opacity: 0;
    transition: .6s;
}

.navigation ul a:hover .design {
    opacity: 1;
    z-index: 2;
    transform: scale(1) translateY(0);
}
    </style>
</head>
<body>
<div class="navigation">
        <ul>
            <a href="User/userpage.php">
                <li>Home </li><span class="design"></span>
            </a>
            <a href="User/productpage.php" >
                <li>Our Product </li><span class="design"></span>
            </a>
            <a href="User/aboutpage.php">
                <li>About Us </li><span class="design"></span>
            </a>
        </ul>
    </div>
<div class="sign">
<div class="wrapper">
            <img src="img/main.png" alt="">
        </div>
    <form action="" method="post">
        <h1>Update Profile</h1>

        <div class="input">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $user_data['username']; ?>" required>
        </div>

        <div class="input">
            <label>Email</label><br>
            <input type="email" name="email" value="<?php echo $user_data['email']; ?>" required>
        </div>

        <div class="input">
            <label>Phone</label><br>
            <input type="number" name="number" value="<?php echo $user_data['phone']; ?>" required>
        </div>

        <div class="input">
            <label>City</label><br>
            <input type="text" name="city" value="<?php echo $user_data['city']; ?>" required>
        </div>

        <div class="input">
            <label>Street</label><br>
            <input type="text" name="street" value="<?php echo $user_data['street']; ?>" required>
        </div>

        <div class="input"><br>
            <label>Landmark</label>
            <input type="text" name="landmark" value="<?php echo $user_data['landmark']; ?>" required>
        </div>

        <h2>Change Password</h2>

        <div class="input">
            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password" required>
        </div>

        <div class="input">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password">
        </div>

        <div class="input">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password">
        </div>

        <?php
        if (isset($error)) {
            foreach ($error as $errors) {
                echo "<span class='error-message'>" . $errors . "</span><br>";
            }
        }
        if (isset($success)) {
            echo "<span class='success-message'>" . $success . "</span><br>";
        }
        ?>

        <input type="submit" value="Update" name="update" class="btn">
    </form>
</div>
</body>
</html>