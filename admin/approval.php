<?php
include('../database.php');
include('navbar.php');

// Only allow admin to access
if (!isset($_SESSION['username'])) {
    header("Location: ../admin-signin.php");
    exit();
}

// Check if the pin is entered and validate it
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_pin'])) {
    // Fetch the stored pin from the database
    $pin_query = "SELECT * FROM admin_security WHERE id = 1"; // Assuming there's only one pin in the database
    $pin_result = mysqli_query($conn, $pin_query);
    $pin_row = mysqli_fetch_assoc($pin_result);

    // Verify the entered pin (no hashing, just plain text comparison)
    if ($_POST['admin_pin'] === $pin_row['pin']) {
        $_SESSION['pin_authenticated'] = true; // Set session variable to indicate pin is authenticated
    } else {
        $error_message = "Incorrect pin. Please try again.";
    }
}

// Change pin logic
if (isset($_POST['change_pin'])) {
    $new_pin = $_POST['new_pin'];

    // Update the pin in the database
    $update_query = "UPDATE admin_security SET pin = '$new_pin' WHERE id = 1";
    mysqli_query($conn, $update_query);
    $success_message = "Pin updated successfully!";
}

// Fetch unapproved users
$fetch_users = "SELECT * FROM admin WHERE approved = 0";
$users = mysqli_query($conn, $fetch_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="admin-approval.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .message {
        color: red;
        font-size: 14px;
        text-align: center;
    }

    input[type="password"], input[type="text"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    button {
        padding: 12px 20px;
        background-color: #FF902B;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    button:hover {
        background-color:rgb(255, 169, 88);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #FF902B;
        color: #fff;
    }

    td a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    td a:hover {
        text-decoration: underline;
    }

    .change-pin-btn {
        margin-top: 20px;
        background-color:#FF902B;
        padding: 12px 20px;
        width: 100%;
        cursor: pointer;
        color: white;
        border: none;
        font-size: 16px;
        border-radius: 5px;
    }

    .change-pin-btn:hover {
        background-color:#FF902B;
    }

    .pin-change-form {
        display: none;
        margin-top: 20px;
    }

    .pin-change-form input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .pin-change-form button {
        background-color: #FF902B;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 12px 20px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    .pin-change-form button:hover {
        background-color: #e65c00;
    }
</style>
<body>

    <?php if (!isset($_SESSION['pin_authenticated'])): ?>
    <div class="container">
        <h2>Please Enter Admin Pin</h2>
        <?php if (isset($error_message)) { echo "<p class='message'>$error_message</p>"; } ?>
        <form method="POST">
            <input type="password" name="admin_pin" placeholder="Enter pin" required>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php else: ?>

    <div class="container">
        <h2>Pending User Approvals</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td><?php echo $row['admin_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><a href="?approve_id=<?php echo $row['admin_id']; ?>">Approve</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Button to show change pin form -->
    <div class="container">
        <button class="change-pin-btn" onclick="togglePinChangeForm()">Change Admin Pin</button>
    </div>

    <!-- Form to change the pin -->
    <div class="container pin-change-form" id="pinChangeForm">
        <h2>Change Admin Pin</h2>
        <?php if (isset($success_message)) { echo "<p class='message'>$success_message</p>"; } ?>
        <form method="POST">
            <label for="new_pin">New Pin:</label>
            <input type="text" name="new_pin" required>
            <button type="submit" name="change_pin">Change Pin</button>
        </form>
    </div>

    <?php endif; ?>

    <script>
        // Toggle the visibility of the pin change form
        function togglePinChangeForm() {
            var form = document.getElementById('pinChangeForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</body>
</html>
