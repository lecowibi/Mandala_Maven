<nav>
     <!-- logo of the project  -->
     <div class="logo">
        <img src="img/logo.png" alt="Mandala Maven logo">
    </div>
    <!-- main navbar  -->
    <div class="navigation">
        <ul>
            <a href="userpage.php">
                <li>Home </li><span class="design"></span>
            </a>
            <a href="productpage.php" >
                <li>Our Product </li><span class="design"></span>
            </a>
            <a href="aboutpage.php">
                <li>About Us </li><span class="design"></span>
            </a>
        </ul>
    </div>
    <!-- extra navbar i.e searchbar,cart,user  -->
    <div class="extra_nav">
        <form class="search" method="get" action="search.php">
            <input type="search" required name="search" placeholder="Search" value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>">
            <button type="submit" class="search_btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <!-- cart logo from lordicon  -->
        <?php
        $select_row = mysqli_query($conn, "SELECT * FROM cart") or die('query failed');
        $row_count = mysqli_num_rows($select_row);
        ?>
        <div class="dropdown2">
            <button class="cart2" onclick="toggle()">
                <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="hover"
                    stroke="bold"
                    colors="primary:#121331,secondary:#000000"
                    style="width:30px;height:30px">
                </lord-icon>
                <span class="count"><?php echo $row_count; ?></span>
            </button>
            <ul style="display:none;"> <!-- Hide by default -->
                <h3 class="baloo">Cart</h3>
                <?php
                $select = mysqli_query($conn, "SELECT * FROM cart");
                $grand_total = 0;
                if (mysqli_num_rows($select) > 0) {
                    while ($row = mysqli_fetch_assoc($select)) {
                ?>
                        <li class="cart-item">
                            <div class="cart">
                                <img src="../admin/uploaded_images/<?php echo $row['image']; ?>" width="40px" alt="">
                                <h5 class="poppin"><?php echo $row['name']; ?></h5>
                                <p class="poppin">Nrs.<?php echo $row['price']; ?></p>
                                <a href="userpage.php?remove=<?php echo $row['id']; ?> " onclick="return confirm('Remove item')" class="btn-remove"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>
                <?php
                        $grand_total += $row['price'] * $row['quantity'];
                    }
                }
                ?>
                <div class="cart-footer">
                    <div class="total">
                        <h6 class="poppin">Total</h6>
                        <p class="poppin">Nrs. <?php echo $grand_total; ?></p>
                    </div>
                    <a href="userpage.php?delete_all" onclick="return confirm('Are you sure! You want to Delete All')" class="btn-remove"><i class="fas fa-trash"></i> Delete All</a>
                </div>
                <div class="checkout-btn">
                    <a href="checkout.php" class="checkout-btn poppin <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Order Now</a>
                </div>

            </ul>
        </div>

        <!-- end of cart section  -->
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
                <li> <?php echo $_SESSION['user_name'] ?></li>
                <li> <a href="order.php">Your order</a></li>
                <li> <a href="../logout.php">Log out</a></li>
            </ul>
        </div>
    </div>
</nav>
