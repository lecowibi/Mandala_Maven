<?php
include('database.php');
session_start();

if (isset($_POST["submit"])) {
    // Escaping user inputs
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Fetching user based on the email
    $select = "SELECT * FROM user_form WHERE email='$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        
        // Verifying the password
        if (password_verify($password, $row['password'])) {
            // Checking user type and setting session variables
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['username']; // Storing username for admin
                header('location:admin/admin.php');
                exit(); // Exit after redirect
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['username']; // Storing username for user
                header('location:user/userpage.php');
                exit(); // Exit after redirect
            }
        } else {
            $error[] = "Incorrect Email or Password";
        }
    } else {
        $error[] = "User doesn't exist";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="css/form.css">
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="sign">
    <div class="wrapper">
            <img src="img/main.png" alt="">
        </div>

        <form action="" method="post">
            <h1>Sign In</h1>
            <div class="input">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" required placeholder="Email">
            </div>
            <div class="input">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" required placeholder="Password">
            </div>
            <?php
            if (isset($error)) {
                foreach ($error as $errors) {
                    echo "<span class='errormsg'>" . $errors . "</span>";
                }
            }
            ?>
            <input type="submit" value="Sign in" name="submit" class="btn">
            <p>Don't have an account yet? <a href="registerform.php">Sign up</a></p>
        </form>
    </div>
</body>

</html>