<?php

@include 'config.php';

if (isset($_POST['submit'])) {

   $filter_username = filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
   $username = mysqli_real_escape_string($conn, $filter_username);
   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $email = mysqli_real_escape_string($conn, $filter_email);
   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
   $pass = mysqli_real_escape_string($conn, md5($filter_pass));
   $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
   $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));
   $filter_address = filter_var($_POST['address'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
   $address = mysqli_real_escape_string($conn, $filter_address);
   $filter_phone = filter_var($_POST['number'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
   $phone = mysqli_real_escape_string($conn, $filter_phone);

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {
      $message[] = 'User already exists!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Confirm password does not match!';
      } else {
         mysqli_query($conn, "INSERT INTO `users`(username, email, password, address, number) VALUES('$username', '$email', '$pass', '$address', '$number')") or die('query failed');
         $message[] = 'Registered successfully!';
         header('location:login.php');
         exit;
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
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '
         <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>Register Now</h3>
         <input type="text" name="username" class="box" placeholder="Enter your username" required>
         <input type="email" name="email" class="box" placeholder="Enter your email" required>
         <input type="text" name="address" class="box" placeholder="Enter your address" required>
         <input type="number" name="number" class="box" placeholder="Enter your phone number" required>
         <input type="password" name="pass" class="box" placeholder="Enter your password" required>
         <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
         
         <input type="submit" class="btn" name="submit" value="Register Now">
         <p>Already have an account? <a href="login.php">Login Now</a></p>
      </form>

   </section>

</body>

</html>
