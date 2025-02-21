<?php
include('database.php');
session_start();

if (isset($_POST["submit"])) {
    // Escaping user inputs
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Backend validation (just to be safe)
    if (empty($email) || empty($password)) {
        $error[] = "Email and Password are required.";
    } else {
        // Fetching user based on the email
        $select = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            // Verifying the password
            if (password_verify($password, $row['password'])) {
                // Set session for logged-in user
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['city'] = $row['city'];
                $_SESSION['street'] = $row['street'];
                $_SESSION['landmark'] = $row['landmark'];
                $_SESSION['number'] = $row['phone'];

                // Redirect to user page
                header('Location: user/userpage.php');
                exit();
            } else {
                $error[] = "Incorrect email or password.";
            }
        } else {
            $error[] = "User does not exist.";
        }
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
    <style>
        .errormsg {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="sign">
        <div class="wrapper">
            <img src="img/main.png" alt="">
        </div>

        <form action="" method="post" id="signin-form" onsubmit="return validateForm()">
            <h1>Sign In</h1>

            <div class="input">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" id="email" required placeholder="Email">
                <span class="errormsg" id="email-error"></span>
            </div>
            <div class="input">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" required placeholder="Password" minlength="8">
                <span class="errormsg" id="password-error"></span>
            </div>

            <span class="errormsg">
                <?php
                if (isset($error)) {
                    foreach ($error as $errors) {
                        echo $errors;
                    }
                }
                ?>
            </span>

            <input type="submit" value="Sign in" name="submit" class="btn">
            <p>Don't have an account yet? <a href="registerform.php">Sign up</a></p>
        </form>
    </div>

    <script>
        function validateForm() {
            let valid = true;

            // Get form elements
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            emailError.textContent = '';
            passwordError.textContent = '';

            // Validate email (basic format check)
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!email.value.match(emailPattern)) {
                emailError.textContent = 'Please enter a valid email address.';
                valid = false;
            }

            // Validate password (min 8 characters)
            if (password.value.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters long.';
                valid = false;
            }

            return valid; 
        }
    </script>

</body>

</html>
