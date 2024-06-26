<?php
@include 'config.php';
session_start();

// Initialize $user_id as null
$user_id = null;

// Check if the user is logged in and if the user_id is set in the session
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
}

if (isset($_POST['add_to_wishlist'])) {

   // Check if the user is logged in
   if (!isset($_SESSION['user_id'])) {
      // Redirect the user to the login page or show a message
      header('Location: login.php');
      exit();
   }

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];

   $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_wishlist_numbers) > 0) {
      $message[] = 'already added to wishlist';
   } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart';
   } else {
      mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
      $message[] = 'product added to wishlist';
   }
}

if (isset($_POST['add_to_cart'])) {
   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart';
   } else {
      $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_wishlist_numbers) > 0) {
         mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
      }

      // Fetch the current stock quantity of the product
      $fetch_product = mysqli_query($conn, "SELECT stock_quantity FROM `products` WHERE id = '$product_id'") or die('query failed');
      $product_data = mysqli_fetch_assoc($fetch_product);
      $current_stock_quantity = $product_data['stock_quantity'];

      if ($current_stock_quantity >= $product_quantity) {
         // Update cart and product stock quantity
         mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');

         // Calculate the new stock quantity after subtracting the ordered quantity
         $new_stock_quantity = $current_stock_quantity - $product_quantity;

         // Update the product stock in the products table
         mysqli_query($conn, "UPDATE products SET stock_quantity = '$new_stock_quantity' WHERE id = '$product_id'") or die('query failed');

         $message[] = 'product added to cart';
      } else {
         $message[] = 'Ordered quantity exceeds available stock';
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
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php @include 'header.php'; ?>

   <!-- <section class="home">

      <div class="content">
         <p><b>Find Your Perfect Plants for a Vibrant Garden</b></p>
        
         <a href="shop.php" class="btn">shop now</a>
      </div>

   </section> -->

   <section class="products">

      <h1 class="title">latest products</h1>

      <div class="box-container">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
               $product_id = $fetch_products['id'];
               $product_name = $fetch_products['name'];
               $product_price = $fetch_products['price'];
               $product_image = $fetch_products['image'];
               $stock_quantity = $fetch_products['stock_quantity'];

               // Check if the product is out of stock
               $out_of_stock = $stock_quantity <= 0;
         ?>
               <form action="" method="POST" class="box">
                  <a href="view_page.php?pid=<?php echo $product_id; ?>" class="fas fa-eye"></a>
                  <div class="price">Rs.<?php echo $product_price; ?>/-</div>
                  <img src="uploaded_img/<?php echo $product_image; ?>" alt="" class="image">
                  <div class="name"><?php echo $product_name; ?></div>
                  <p class="stock-quantity">In Stock: <?php echo $stock_quantity; ?></p> <!-- Display stock quantity -->
                  <input type="number" name="product_quantity" value="1" min="1" max="<?php echo $stock_quantity; ?>" class="qty">
                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                  <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $product_price; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $product_image; ?>">

                  <!-- Display appropriate button based on stock availability -->
                  <?php if ($out_of_stock) { ?>
                     <p class="out-of-stock">Out of Stock</p>
                  <?php } else { ?>
                     <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn">
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                  <?php } ?>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>
      </div>


      <div class="more-btn">
         <a href="shop.php" class="option-btn">load more</a>
      </div>

   </section>

   <section class="home-contact">

      <div class="content">
         <h3>have any questions?</h3>

         <a href="contact.php" class="btn">contact us</a>
      </div>

   </section>




   <?php @include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>