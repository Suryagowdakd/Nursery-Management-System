<?php
session_start(); // Start the session


?>
<header class="header">

    <div class="flex">

        <a href="home.php" class="logo"><i class="fa-solid fa-seedling"></i>nursery.</a>



        <nav class="navbar">
            <ul>
                <li><a href="home.php">home</a></li>
                <li><a href="#">products <i class="fa-solid fa-caret-down"></i></a>
                    <ul>
                        <li><a href="plants.php">plants</a></li>
                        <li><a href="seeds.php">seeds</a></li>
                        <li><a href="fertilizers.php">fertilizers</a></li>
                        <li><a href="pesticides.php">pesticides</a></li>
                        <li><a href="tools.php">tools</a></li>
                    </ul>
                </li>
                <li><a href="shop.php">shop</a></li>
                <li><a href="orders.php">orders</a></li>

                <li><a href="about.php">about</a></li>
                <li><a href="contact.php">contact</a></li>




            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php

            // Display wishlist and cart links if the user is logged in
            $select_wishlist_count = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '{$_SESSION['user_id']}'") or die('query failed');
            $wishlist_num_rows = mysqli_num_rows($select_wishlist_count);
            ?>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>


            <?php
            // Display cart count for both logged-in and non-logged-in users
            $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
            $cart_num_rows = mysqli_num_rows($select_cart_count);
            ?>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
            <?php  ?>

            <?php
            if (isset($_SESSION['user_id'])) {
                // Display username and email if the user is logged in
            ?>
                <div class="account-box">
                    <!-- <p>username : <span><?php echo $_SESSION['username']; ?></span></p> -->
                    <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                    <a href="logout.php" class="delete-btn">logout</a>
                </div>
            <?php } else { ?>
                <!-- Display login and register buttons if the user is not logged in -->
                <div class="account-box">
                    <a href="login.php" class="delete-btn">login</a>
                    <a href="register.php" class="delete-btn">register</a>
                </div>
            <?php } ?>

        </div>

    </div>


</header>
<?php
if (isset($message)) {
    foreach ($message as $messageText) {
        echo '
         <div class="message">
            <span>' . $messageText . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
    }
}
?>