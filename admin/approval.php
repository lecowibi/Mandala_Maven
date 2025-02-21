<?php
include('../database.php');
session_start();

// Only allow admin to access
if (!isset($_SESSION['username'])) {
    header("Location: admin-signin.php");
    exit();
}

// Approve user if the button is clicked
if (isset($_GET['approve_id'])) {
    $id = intval($_GET['approve_id']);
    $update_query = "UPDATE admin SET approved = 1 WHERE admin_id = $id";
    mysqli_query($conn, $update_query);
    header("Location: admin-approval.php"); 
    exit();
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
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <h2>Pending User Approvals</h2>
    <table border="1">
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
</body>
</html>
