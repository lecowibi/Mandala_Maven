<?php
include('database.php');
session_start();

// Process form submission (no backend validation but check if email/username already exists)
if (isset($_POST["submit"])) {
    // Saving the data (no validation on the server side)
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Check if email or username already exists in the database
    $email_check_query = "SELECT * FROM admin WHERE email='$email'";
    $username_check_query = "SELECT * FROM admin WHERE username='$username'";

    $email_check_result = mysqli_query($conn, $email_check_query);
    $username_check_result = mysqli_query($conn, $username_check_query);

    if (mysqli_num_rows($email_check_result) > 0) {
        echo "<script>alert('Email already exists. Please choose a different email.');</script>";
    } elseif (mysqli_num_rows($username_check_result) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        // Inserting the user with 'approved' status as 0 (not approved yet)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admin (username, password, email, approved) VALUES ('$username', '$hashed_password', '$email', 0)";

        if (mysqli_query($conn, $sql)) {
            // Success: Show alert
            echo "<script>alert('Registration Successful! Please wait for admin approval.'); window.location.href = 'admin-register.php';</script>";
            exit();
        } else {
            // Failed to insert
            echo "<script>alert('Error: Could not register. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="css/form.css">
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
    <form id="registerForm" action="" method="post" onsubmit="return validateForm()">
        <h1>Sign Up</h1>
        <div class="input">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="username" id="username" required placeholder="Username">
            <span class="errormsg" id="username-error"></span>
        </div>
        <div class="input">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" id="email" required placeholder="Email">
            <span class="errormsg" id="email-error"></span>
        </div>
        <div class="input">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" required placeholder="Password">
            <span class="errormsg" id="password-error"></span>
        </div>
        <div class="input">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm Password">
            <span class="errormsg" id="cpassword-error"></span>
        </div>

        <input type="submit" value="Sign Up" name="submit" class="btn">
        <p>Already have an account? <a href="admin-signin.php">Sign in</a></p>
    </form>
</div>

<script>
    function validateForm() {
        let valid = true;

        // Get form elements
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const cpassword = document.getElementById('cpassword');
        
        // Error spans
        const usernameError = document.getElementById('username-error');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        const cpasswordError = document.getElementById('cpassword-error');

        // Reset error messages
        usernameError.textContent = '';
        emailError.textContent = '';
        passwordError.textContent = '';
        cpasswordError.textContent = '';

        // Validate username
        if (username.value.length < 3) {
            usernameError.textContent = 'Username must be at least 3 characters long.';
            valid = false;
        }

        // Validate email (basic format check)
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!email.value.match(emailPattern)) {
            emailError.textContent = 'Please enter a valid email address.';
            valid = false;
        }

        // Validate password (min 8 characters, 1 uppercase, 1 number, 1 special char)
        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!password.value.match(passwordPattern)) {
            passwordError.textContent = 'Password must be at least 8 characters, 1 uppercase, 1 number, and 1 special character.';
            valid = false;
        }

        // Validate confirm password (must match password)
        if (password.value !== cpassword.value) {
            cpasswordError.textContent = 'Passwords do not match.';
            valid = false;
        }

        return valid;
    }
</script>

</body>
</html>
