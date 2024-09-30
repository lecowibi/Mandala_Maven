<?php
include("../database.php");
session_start(); {
    if (!isset($_SESSION['admin_name'])) {
        header("location:../signin.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mandala Maven</title>
    <link rel="stylesheet" href="admincss/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
      <!-- starting navbar  -->
      <nav>
            <!-- logo of the project  -->
            <div class="logo">
                <img src="img/logo.png" alt="Mandala Maven logo">
            </div>
            <!-- main nabvar  -->
            <div class="navigation">
                <ul>
                    <a href="admin.php">
                        <li >Add Products</li><span class="design"></span>
                    </a>
                    <a href="product.php">
                        <li>View Product</li><span class="design"></span>
                    </a>
                    <a href="edit.php">
                        <li>Edit Product</li><span class="design"></span>
                    </a>
                    <a href="order.php">
                        <li>Order Detail</li><span class="design"></span>
                    </a>
                </ul>
            </div>
            <!-- extra navbar i.e searchbar,cart,user  -->
            <div class="extra_nav">
              

                <div class="dropdown">
                    <lord-icon
                        src="https://cdn.lordicon.com/bgebyztw.json"
                        trigger="hover"
                        stroke="bold"
                        state="hover-looking-around"
                        colors="primary:#121331,secondary:#000000"
                        style="width:30px;height:30px">
                    </lord-icon>
                    <ul>
                        <li> <?php echo $_SESSION['admin_name'] ?></li>
                        <li> <a href="../logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end of navbar -->
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
</body>
</html>