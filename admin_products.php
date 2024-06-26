<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};


if (isset($_POST['add_product'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $stock = mysqli_real_escape_string($conn, $_POST['stock']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $_SESSION['product_exists_message'] = 'Product name already exists!';
      header('location:admin_add_product.php'); // Redirect back to the add product page
      exit();
   } else {
      $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, category, details, price, stock_quantity, image) VALUES('$name', '$category', '$details', '$price', '$stock', '$image')") or die('query failed');

      if ($insert_product) {
         if ($image_size > 2000000) {
            $message = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message = 'Product added successfully!';
         }
      }
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">


</head>

<body>

   <?php @include 'admin_header.php'; ?>

   <section class="add-products">
      <a href="admin_add_product.php" class="btn">Add Product</a>
   </section>

   <section class="show-products">

      <table class="products-table" style="border: 1px solid red;">
         <thead>
            <tr>
               <th>Name</th>
               <th>Category</th>
               <th>Price</th>
               <th>Stock Quantity</th>
               <th>Image</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                  <tr>
                     <td><?php echo $fetch_products['name']; ?></td>
                     <td><?php echo $fetch_products['category']; ?></td>
                     <td>Rs.<?php echo $fetch_products['price']; ?>/-</td>
                     <td><?php echo $fetch_products['stock_quantity']; ?></td> <!-- Display stock quantity -->
                     <td><img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="product-image"></td>
                     <td>
                        <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
                     </td>
                  </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="5" class="empty">No products added yet!</td></tr>';
            }
            ?>
         </tbody>
      </table>

   </section>


   <script src="js/admin_script.js"></script>

</body>

</html>