<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location: login.php');
}

if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
    $message[] = 'payment status has been updated!';
}

// Check if the order_id is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details from the database using the order_id
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('query failed');
    if (mysqli_num_rows($order_query) > 0) {
        $order_details = mysqli_fetch_assoc($order_query);

        // Fetch order items associated with the given order ID from the order_items table
        $select_order_items = mysqli_query($conn, "SELECT * FROM `order_items` WHERE order_id = '$order_id'") or die('Query failed');
    } else {
        // Handle the case where the order does not exist
        $message[] = 'Order not found';
    }
} else {
    // Handle the case where order_id is not set in the URL
    $message[] = 'Order ID not provided';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Details</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>
    <?php @include 'admin_header.php'; ?>
    <h1 class="title">View Details</h1>
    <section class="order-details">



        <div class="order-container-1">
            <!-- Display order details here -->
            <?php if (isset($order_details)) : ?>
                <div class="order-info">
                    <p><strong>Order ID:</strong> <?php echo $order_details['id']; ?></p>
                    <p><strong>Placed On:</strong> <?php echo $order_details['placed_on']; ?></p>
                    <p><strong>Name:</strong> <?php echo $order_details['name']; ?></p>
                    <p><strong>Address:</strong> <?php echo $order_details['address']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order_details['email']; ?></p>
                </div>
                <!-- Update Order Status Form -->
                <div class="update-form">
                    <h2>Update Order Status</h2>

                    <form action="" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order_details['id']; ?>">
                        <select name="update_payment">
                            <option disabled selected><?php echo $order_details['payment_status']; ?></option>
                            <option value="pending">pending</option>
                            <option value="completed">completed</option>
                        </select>
                        <input type="submit" name="update_order" value="update" class="option-btn">
                        <!-- Delete Button -->
                        <a href="admin_orders.php?delete=<?php echo $order_details['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>

                    </form>

                </div>

        </div>

        <!-- Display order items table -->
        <div class="order-container-2">
            <h2 class="order-title">Ordered Products</h2>
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
                    $totalCost = 0; // Initialize the total cost
                    if (mysqli_num_rows($select_order_items) > 0) {
                        while ($order_item = mysqli_fetch_assoc($select_order_items)) {
                            echo '<tr>';
                            echo '<td><img src="uploaded_img/' . $order_item['image'] . '" alt="" class="product-image"></td>';
                            echo '<td>' . $order_item['productsname'] . '</td>';
                            echo '<td>Rs.' . $order_item['price'] . '/-</td>';
                            echo '<td>' . $order_item['quantity'] . '</td>';
                            echo '</tr>';
                            $itemTotal = $order_item['price'] * $order_item['quantity'];
                            $totalCost += $itemTotal;
                        }
                    } else {
                        echo '<tr><td colspan="4">No products found for this order.</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>

                    <tr>
                        <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                        <td><strong>Rs.<?php echo $totalCost; ?>/-</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>


    <?php else : ?>
        <p><?php echo $message[0]; ?></p>
    <?php endif; ?>
    </section>

    <?php @include 'admin_footer.php'; ?>
</body>

</html>