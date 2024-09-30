<?php
include('database.php');

if (isset($_POST["submit"])) {
    // Escaping user inputs
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $user_type = mysqli_real_escape_string($conn, $_POST["user_type"]); // Escape user type

    // Checking if the email already exists
    $email_check = "SELECT * FROM user_form WHERE email='$email'";
    $username_check = "SELECT * FROM user_form WHERE username='$username'";
    
    $email_check_query = mysqli_query($conn, $email_check);
    $username_check_query = mysqli_query($conn, $username_check);

    if(mysqli_num_rows($email_check_query) > 0) {
        $error[] = 'email already exists!';
    } 
    elseif (mysqli_num_rows($username_check_query) > 0) {
        $error[] = 'Username already exists!';
    } 
    
    else {
        // Checking if passwords match
        if ($password != $cpassword) {
            $error[] = 'Passwords do not match';
        } else {
            // Hashing the password securely
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user_form(username, password, email, user_type) VALUES('$username', '$hash', '$email', '$user_type')";
            
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
</head>
<body>
<div class="sign">
<div class="wrapper">
            <img src="img/main.png" alt="">
        </div>
    <form action="" method="post">
        <h1>Sign Up</h1>
        <div class="input">
        <i class="fa-solid fa-user"></i>
            <input type="text" name="username" required placeholder="Username">
        </div>
        <div class="input">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" required placeholder="Email">
        
        </div>
        <div class="input">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" required placeholder="Password">
        </div>
        <div class="input">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="cpassword" required placeholder="Confirm Password">
        </div>
       <div class="input">
       <i class="fa-solid fa-users"></i>
       <select name="user_type" >
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
       </div>
        <?php
        if(isset($error)){
            foreach($error as $errors){
            echo "<span class='errormsg'>" .$errors. "</span>";
            }
        }
        ?>
        <input type="submit" value="Sign Up" name="submit" class="btn">
        <p>Already have an account?  <a href="signin.php">Sign in</a></p>
    </form>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        let password = document.querySelector('input[name="password"]').value;
        let cpassword = document.querySelector('input[name="cpassword"]').value;
        let errorMessage = '';

        // Regular expression for validating the password
        let passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (password !== cpassword) {
            errorMessage = 'Passwords do not match.';
        } else if (!passwordRegex.test(password)) {
            errorMessage = 'Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.';
        }

        // If there's an error, prevent form submission and show the error
        if (errorMessage) {
            e.preventDefault(); 
            alert(errorMessage); 
        }
    });
</script>

</body>

</html>
