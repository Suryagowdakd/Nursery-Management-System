<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
};
// // Check if a message is set (product name already exists)
$message = '';
if (isset($_SESSION['product_exists_message'])) {
   $message = $_SESSION['product_exists_message'] = array('Product name already exists!');
   unset($_SESSION['product_exists_message']); // Clear the message to avoid showing it again on refresh
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
   <?php include 'admin_header.php'; ?>
<

   <section class="add-products">
      <form id="addProductForm" action="admin_products.php" method="POST" enctype="multipart/form-data">
         <h3>Add New Product</h3>
         <input type="text" class="box" required placeholder="Enter product name" name="name">
         <select class="box" required name="category">
            <option value="" disabled selected>Select Category</option>
            <option value=" plants">Plants</option>
            <option value="seeds">Seeds</option>
            <option value="fertilizers">Fertilizers</option>
            <option value="pesticides">Pesticides</option>
            <option value="tools">Tools</option>
         </select>

         <input type="number" min="0" class="box" required placeholder="Enter product price" name="price">
         <input type="number" min="0" class="box" required placeholder="Enter stock quantity" name="stock">
         <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
         <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
         <input type="submit" value="Add Product" name="add_product" class="btn">
      </form>
   </section>
 
</body>

</html>