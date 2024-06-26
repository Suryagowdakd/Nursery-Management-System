<?php
// Include the config.php file and start the session
@include 'config.php';
session_start();

// Check if the user is logged in and get the user_id from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// If the user is not logged in, redirect them to the login page
if (!$user_id) {
    header('Location: login.php');
    exit();
}
// Fetch user information from the database using the user_id
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($user_query) > 0) {
    $user = mysqli_fetch_assoc($user_query);
} else {
    $user = array(
        'name' => 'Default Name',
        'number' => '0000000000',
        'email' => 'default@example.com',
        'address' => 'Default Address'
    );

    // Or showing an error message
    $message[] = 'Error: Unable to fetch user information.';
}

// Initialize message array
$message = array();

// Process the order if the form is submitted
if (isset($_POST['order'])) {
    // Get user details from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    // Combine address details into a single address string
    $address = mysqli_real_escape_string($conn, $_POST['city']);

    date_default_timezone_set('Asia/Kathmandu');
    $placed_on = date('d-M-Y H:i:s');

    // Calculate the total price and create a list of products in the cart
    $cart_total = 0;
    $cart_products = array();
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');


    // Initialize an array to store order items for insertion into order_items table
    $order_items = array();

    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {

            // Fetch the product ID from the products table
            $product_id = $cart_item['pid'];

            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;

            // Add cart item details to the order_items array
            $order_items[] = array(
                'pid' => $product_id,
                'productsname' => $cart_item['name'],
                'quantity' => $cart_item['quantity'],
                'price' => $cart_item['price'],
                'image' => $cart_item['image']
            );
        }
    }

    $products_name = implode(', ', $cart_products);

    // Check if the cart is empty
    if ($cart_total == 0) {
        $message[] = 'Your cart is empty!';
    } else {
        // Check if the order already exists in the database
        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND products_name = '$products_name' AND total_price = '$cart_total'") or die('query failed');

        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order has already been placed!';
        } else {
            // Insert the order into the database
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, products_name, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$products_name', '$cart_total', '$placed_on')") or die('query failed');

            // Get the ID of the inserted order
            $order_id = mysqli_insert_id($conn);

            // Insert order items into the order_items table
            foreach ($order_items as $item) {
                $product_id = $item['pid'];
                $productsname = $item['productsname'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $image = $item['image'];

                mysqli_query($conn, "INSERT INTO `order_items`(order_id, pid, productsname, quantity, price, image) VALUES('$order_id', '$product_id', '$productsname', '$quantity', '$price', '$image')") or die('Query failed: ' . mysqli_error($conn));
            }

            // Delete the items from the cart
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

            $message[] = 'Order placed successfully!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <!-- <h3>checkout order</h3> -->
        <p> <a href="home.php">home</a> / checkout </p>
    </section>
    <div class="checkout-container">

        <div class="col-md-6 checkout-form">
            <form action="" method="POST">
                <h2 class="order-title">Delivery Details</h2>
                <!-- Display user information (retrieved from users table) -->
                <div class="inputBox">
                    <span>Name:</span>
                    <input type="text" name="name" value="<?php echo $user['username']; ?>">
                </div>
                <div class="inputBox">
                    <span>Number:</span>
                    <input type="number" name="number" min="0" value="<?php echo $user['number']; ?>">
                </div>
                <div class="inputBox">
                    <span>Email:</span>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>">
                </div>
                <div class="inputBox additional-address">
                    <span>Address:</span>
                    <input type="text" name="flat" placeholder="e.g. Suryabinayak">
                </div>
                <div class="inputBox additional-address">
                    <span>City:</span>
                    <input type="text" name="city" placeholder="e.g. Kathmandu">
                </div>
                <div class="inputBox">
                    <span>Payment Method:</span>
                    <select name="method">
                        <option value="cash on delivery">Cash on Delivery</option>
                    </select>
                </div>
                <input type="submit" name="order" value="Order Now" class="btn">
            </form>
        </div>



        <section class="display-order col-md-6">
            <div class="your-order">
                <h2 class="order-title">Your order</h2>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                            if (mysqli_num_rows($select_cart) > 0) {
                                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                    $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                                    $grand_total += $total_price;
                            ?>
                                    <tr>
                                        <td>
                                            <div class="product-cell">

                                            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
                                                <span class="product-name"><?php echo $fetch_cart['name']; ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo 'Rs.' . $fetch_cart['price'] . '/-'; ?></td>
                                        <td><?php echo $fetch_cart['quantity']; ?></td>
                                        <td><?php echo 'Rs.' . $total_price . '/-'; ?></td>
                                    </tr>
                                <?php
                                }


                                ?>

                            <?php
                            } else {
                                echo '<tr><td colspan="4">Your cart is empty</td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                                <td><strong><?php echo 'Rs.' . $grand_total . '/-'; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>