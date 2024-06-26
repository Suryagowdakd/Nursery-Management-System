<?php
@include 'config.php';
session_start();

// If the user is not logged in, create a temporary user ID
if (!isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['temp_user_id'])) {
        $_SESSION['temp_user_id'] = uniqid('temp_user_');
    }
    $user_id = $_SESSION['temp_user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Retrieve the product quantity from the cart
    $getCartProductQuery = "SELECT * FROM `cart` WHERE id = '$delete_id'";
    $getCartProductResult = mysqli_query($conn, $getCartProductQuery);
    if ($getCartProductResult && mysqli_num_rows($getCartProductResult) > 0) {
        $cartProductData = mysqli_fetch_assoc($getCartProductResult);
        $product_id = $cartProductData['pid'];
        $product_quantity = $cartProductData['quantity'];

        // Get the current stock quantity of the product
        $getProductStockQuery = "SELECT stock_quantity FROM `products` WHERE id = '$product_id'";
        $getProductStockResult = mysqli_query($conn, $getProductStockQuery);
        if ($getProductStockResult && mysqli_num_rows($getProductStockResult) > 0) {
            $productStockData = mysqli_fetch_assoc($getProductStockResult);
            $current_stock_quantity = $productStockData['stock_quantity'];

            // Calculate the new stock quantity after adding the cart quantity back
            $new_stock_quantity = $current_stock_quantity + $product_quantity;

            // Update the product stock in the products table
            mysqli_query($conn, "UPDATE products SET stock_quantity = '$new_stock_quantity' WHERE id = '$product_id'") or die('query failed');
        }
    }

    // Delete the cart item
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
    exit();
}



if (isset($_GET['delete_all'])) {
    // Retrieve all cart items for the user
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
        $product_id = $fetch_cart['pid'];
        $product_quantity = $fetch_cart['quantity'];

        // Get the current stock quantity of the product
        $getProductStockQuery = "SELECT stock_quantity FROM `products` WHERE id = '$product_id'";
        $getProductStockResult = mysqli_query($conn, $getProductStockQuery);

        if ($getProductStockResult && mysqli_num_rows($getProductStockResult) > 0) {
            $productStockData = mysqli_fetch_assoc($getProductStockResult);
            $current_stock_quantity = $productStockData['stock_quantity'];

            // Calculate the new stock quantity after adding the cart quantity back
            $new_stock_quantity = $current_stock_quantity + $product_quantity;

            // Update the product stock in the products table
            mysqli_query($conn, "UPDATE products SET stock_quantity = '$new_stock_quantity' WHERE id = '$product_id'") or die('query failed');
        }
    }

    // Delete all items from the cart for logged-in users
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

    header('location:cart.php');
    exit();
}
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    // Retrieve the previous cart quantity and product ID
    $getCartProductQuery = "SELECT * FROM `cart` WHERE id = '$cart_id'";
    $getCartProductResult = mysqli_query($conn, $getCartProductQuery);
    if ($getCartProductResult && mysqli_num_rows($getCartProductResult) > 0) {
        $cartProductData = mysqli_fetch_assoc($getCartProductResult);
        $product_id = $cartProductData['pid'];
        $prev_cart_quantity = $cartProductData['quantity'];
    }

    // Update the cart quantity
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');

    // Get the current stock quantity of the product
    $getProductStockQuery = "SELECT stock_quantity FROM `products` WHERE id = '$product_id'";
    $getProductStockResult = mysqli_query($conn, $getProductStockQuery);
    if ($getProductStockResult && mysqli_num_rows($getProductStockResult) > 0) {
        $productStockData = mysqli_fetch_assoc($getProductStockResult);
        $current_stock_quantity = $productStockData['stock_quantity'];

        // Calculate the difference in quantities and adjust stock quantity
        $quantity_difference = $prev_cart_quantity - $cart_quantity;
        $new_stock_quantity = $current_stock_quantity + $quantity_difference;

        // Update the product stock in the products table
        mysqli_query($conn, "UPDATE products SET stock_quantity = '$new_stock_quantity' WHERE id = '$product_id'") or die('query failed');
    }

    $message[] = 'cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping cart</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <!-- <h3>shopping cart</h3> -->
        <p> <a href="home.php">home</a> / cart </p>
    </section>

    <section class="shopping-cart">

        <h1 class="title">products added</h1>

        <div class="box-container">

            <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    // Fetch stock quantity for each product in the cart
                    $product_id = $fetch_cart['pid'];
                    $getProductStockQuery = "SELECT stock_quantity FROM `products` WHERE id = '$product_id'";
                    $getProductStockResult = mysqli_query($conn, $getProductStockQuery);

                    if ($getProductStockResult && mysqli_num_rows($getProductStockResult) > 0) {
                        $productStockData = mysqli_fetch_assoc($getProductStockResult);
                        $stock_quantity = $productStockData['stock_quantity'];
                    }
            ?>
                    <div class="box">
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
                        <div class="name"><?php echo $fetch_cart['name']; ?></div>
                        <div class="price">Rs.<?php echo $fetch_cart['price']; ?>/-</div>
                        <form action="" method="post">
                            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
                            <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" max="<?php echo $stock_quantity; ?>" name="cart_quantity" class="qty">
                            <input type="submit" value="update" class="option-btn" name="update_quantity">
                        </form>
                        <div class="sub-total"> sub-total : <span>Rs.<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
                    </div>
            <?php
                    $grand_total += $sub_total;
                }
            } else {
                echo '<p class="empty">your cart is empty</p>';
            }
            ?>
        </div>

        <div class="more-btn">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>Rs.<?php echo $grand_total; ?>/-</span></p>
            <a href="shop.php" class="option-btn">continue shopping</a>
            <?php
            // Add a condition to display the checkout button only if the user is logged in
            if (isset($_SESSION['user_id'])) {
                echo '<a href="check-out.php" class="btn  ' . ($grand_total > 1 ? '' : 'disabled') . '">proceed to checkout</a>';
            } else {
                echo '<a href="login.php" class="btn">Log in to Proceed to Checkout</a>';
            }
            ?>
        </div>

    </section>






    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>