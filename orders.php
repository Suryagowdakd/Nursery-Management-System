<?php

@include 'config.php';

session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // If user is not logged in, you might choose to handle this differently
    
    $user_id = null; 
}

if (isset($_GET['delete'])) {
    $orderToDelete = $_GET['delete'];

    // Fetch order items associated with the given order ID
    $fetchOrderItemsQuery = "SELECT pid, quantity FROM order_items WHERE order_id = '$orderToDelete'";
    $fetchOrderItemsResult = mysqli_query($conn, $fetchOrderItemsQuery);

    if ($fetchOrderItemsResult) {
        // Iterate through each order item and update product stock quantity
        while ($orderItemData = mysqli_fetch_assoc($fetchOrderItemsResult)) {
            $productId = $orderItemData['pid'];
            $orderedQuantity = $orderItemData['quantity'];

            // Fetch the current stock quantity of the product
            $fetchProductQuery = "SELECT stock_quantity FROM products WHERE id = '$productId'";
            $fetchProductResult = mysqli_query($conn, $fetchProductQuery);
            $productData = mysqli_fetch_assoc($fetchProductResult);
            $currentStockQuantity = $productData['stock_quantity'];

            // Calculate the new stock quantity after adding the ordered quantity back
            $newStockQuantity = $currentStockQuantity + $orderedQuantity;

            // Update the product stock in the products table
            mysqli_query($conn, "UPDATE products SET stock_quantity = '$newStockQuantity' WHERE id = '$productId'");
        }

        // Delete order items associated with the given order ID
        mysqli_query($conn, "DELETE FROM order_items WHERE order_id = '$orderToDelete'");

        // Proceed to delete the order
        $deleteQuery = "DELETE FROM orders WHERE id = '$orderToDelete'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Redirect back to the orders page after deletion
            header('Location: orders.php');
            exit;
        } else {
            echo "Error deleting order: " . mysqli_error($conn);
        }
    } else {
        echo "Error fetching order items: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <!-- <h3>your orders</h3> -->
        <p> <a href="home.php">home</a> / order </p>
    </section>

    <section class="placed-orders">
        <h1 class="title">Placed Orders</h1>
        <?php
        $select_orders = mysqli_query($conn, "SELECT o.id, o.placed_on, o.products_name, o.total_price, o.payment_status, c.pid, c.image AS product_image FROM `orders` o
       LEFT JOIN `cart` c ON o.pid = c.id
       WHERE o.user_id = '$user_id' ORDER BY o.placed_on ASC") or die('Query failed');

        if (mysqli_num_rows($select_orders) > 0) {
        ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Placed On</th>
                        <th>Product</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Your PHP loop to display orders here
                    while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                    ?>
                        <tr>
                            <td><?php echo $fetch_orders['id']; ?></td>
                            <td><?php echo $fetch_orders['placed_on']; ?></td>
                            <td>
                                <div class="product-info">
                                    <span><?php echo $fetch_orders['products_name']; ?></span>
                                </div>
                            </td>
                            <td>Rs.<?php echo $fetch_orders['total_price']; ?>/-</td>
                            <td style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'tomato' : 'green'; ?>"><?php echo $fetch_orders['payment_status']; ?></td>
                            <td>
                                <button class="option-btn" data-order-id="<?php echo $fetch_orders['id']; ?>">
                                    <a href="user_order_details.php?order_id=<?php echo $fetch_orders['id']; ?>">View Details</a>
                                </button>
                                <button>
                                    <a href="orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">Delete</a>
                                </button>
                            </td>
                        </tr>
                    <?php
                    }

                    ?>
                </tbody>
            </table>
        <?php
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>

    </section>











    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>