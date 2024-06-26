<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};

if (isset($_POST['update_order'])) {
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'payment status has been updated!';
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
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'admin_header.php'; ?>
   <section class="placed-orders">
      <h1 class="title">placed orders</h1>

      <table class="order-table">
         <thead>
            <tr>
               <th>User ID</th>
               <th>Placed On</th>
               <th>Name</th>
               <th>Email</th>
               <th>Phone Number</th>
               <th>Address</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            if (mysqli_num_rows($select_orders) > 0) {
               while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>
                  <tr>
                     <td><?php echo $fetch_orders['user_id']; ?></td>
                     <td><?php echo $fetch_orders['placed_on']; ?></td>
                     <td><?php echo $fetch_orders['name']; ?></td>
                     <td><?php echo $fetch_orders['email']; ?></td>
                     <td><?php echo $fetch_orders['number']; ?></td>
                     <td><?php echo $fetch_orders['address']; ?></td>
                     <td><?php echo $fetch_orders['payment_status']; ?></td>
                     <td>
                        <button class="view-details-btn" data-order-id="<?php echo $fetch_orders['id']; ?>">
                           <a href="admin_order_details.php?order_id=<?php echo $fetch_orders['id']; ?>">View Details</a>
                        </button>
                        <!-- Delete Button -->
                        <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                     </td>
                  </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="8" class="empty">no orders placed yet!</td></tr>';
            }
            ?>
         </tbody>
      </table>


   </section>


</body>

</html>