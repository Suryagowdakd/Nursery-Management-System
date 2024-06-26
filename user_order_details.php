<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Check if 'order_id' parameter is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the database
    $select_order = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id = '$order_id'") or die('Query failed');

    if (mysqli_num_rows($select_order) > 0) {
        $order_details = mysqli_fetch_assoc($select_order);
    } else {
        // Redirect back to orders page if the order doesn't belong to the user or doesn't exist
        header('Location: orders.php');
        exit;
    }
} else {
    // Redirect back to orders page if 'order_id' is not set
    header('Location: orders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <!-- <h3>order details</h3> -->
        <p><a href="home.php">home</a> / <a href="orders.php">orders</a> / Order Details</p>
    </section>

    <section class="order-details">
        <h3 class="title">Order ID: <?php echo $order_details['id']; ?> <span>
                <!-- <h4 class="title">Ordered Products</h4> -->
            </span>
        </h3>

        <!-- Display order products in a table format -->
       
<div class="order-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch order items associated with the given order ID
            $select_order_items = mysqli_query($conn, "SELECT * FROM `order_items` WHERE order_id = '$order_id'") or die('Query failed');

            if (mysqli_num_rows($select_order_items) > 0) {
                while ($order_item = mysqli_fetch_assoc($select_order_items)) {
                    echo '<tr>';
                    echo '<td><img src="uploaded_img/' . $order_item['image'] . '" alt="" class="product-image"></td>';
                    echo '<td>' . $order_item['productsname'] . '</td>';
                    echo '<td>Rs.' . $order_item['price'] . '/-</td>';
                    echo '<td>' . $order_item['quantity'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No products found for this order.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

    </section>


    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>