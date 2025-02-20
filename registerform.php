<?php
include('database.php');

if (isset($_POST["submit"])) {
    // Escaping user inputs for security
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $city = mysqli_real_escape_string($conn, $_POST["city"]);
    $street = mysqli_real_escape_string($conn, $_POST["street"]);
    $landmark = mysqli_real_escape_string($conn, $_POST["landmark"]);
    $number = $_POST["number"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    // Checking if the email or username already exists
    $email_check_query = mysqli_query($conn, "SELECT * FROM user_form WHERE email='$email'");
    $username_check_query = mysqli_query($conn, "SELECT * FROM user_form WHERE username='$username'");

    if (mysqli_num_rows($email_check_query) > 0) {
        $error[] = 'Email already exists!';
    } elseif (mysqli_num_rows($username_check_query) > 0) {
        $error[] = 'Username already exists!';
    } else {
        // Checking if passwords match
        if ($password !== $cpassword) {
            $error[] = 'Passwords do not match';
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $error[] = 'Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.';
        } else {
            // Hashing the password securely
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user_form(username, password, email, city, street, landmark, phone) VALUES('$username', '$hash', '$email', '$city', '$street', '$landmark', '$number')";

            if (mysqli_query($conn, $sql)) {
                header('Location: signin.php');
                exit(); // Exit after redirect
            } else {
                $error[] = 'Failed to create account. Please try again.';
            }
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
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .error-message {
            color: red;
            width: 350px;
            font-size: 0.9em;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="sign">
        <div class="wrapper">
            <img src="img/main.png" alt="">
        </div>
        <form action="" method="post" id="registration-form">
            <h1>Sign Up</h1>

            <div class="input">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" required placeholder="Username">
                <span class="error-message" id="username-error"></span>
            </div>

            <div class="input">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" required placeholder="Email">
                <span class="error-message" id="email-error"></span>
            </div>

            <div class="input">
                <i class="fa-solid fa-phone"></i>
                <input type="number" name="number" required placeholder="Phone Number" minlength="10" maxlength="10" oninput="validatePhoneNumber(this)">
                <span class="error-message" id="number-error"></span>
            </div>

            <div class="input">
                <i class="fa-solid fa-city"></i>
                <input type="text" name="city" required placeholder="City">
            </div>

            <div class="input">
                <i class="fa-solid fa-road"></i>
                <input type="text" name="street" required placeholder="Street">
            </div>

            <div class="input">
                <i class="fa-solid fa-landmark"></i>
                <input type="text" name="landmark" required placeholder="Landmark">
            </div>

            <div class="input">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" required placeholder="Password">
                <span class="error-message" id="password-error"></span>
            </div>

            <div class="input">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="cpassword" required placeholder="Confirm Password">
                <span class="error-message" id="cpassword-error"></span>
            </div>

            <?php
            // Display error messages if there are any
            if (isset($error)) {
                foreach ($error as $errors) {
                    echo "<span class='error-message'>" . $errors . "</span><br>";
                }
            }
            ?>

            <input type="submit" value="Sign Up" name="submit" class="btn">
            <p>Already have an account? <a href="signin.php">Sign in</a></p>
        </form>
    </div>
    <script>
    function validatePhoneNumber(input) {
        const numberError = document.getElementById('number-error');

        // Check if the input value is exactly 10 digits
        if (input.value.length !== 10) {
            numberError.textContent = 'Phone number must be exactly 10 digits.';
        } else {
            numberError.textContent = '';
        }
    }
</script>
</body>

</html>


