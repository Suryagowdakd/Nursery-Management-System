<?php
include 'config.php'; // Check the path to your config.php file.

session_start();

if (!isset($_SESSION['admin_id'])) {
   header('location:login.php');
   exit(); // Terminate script after redirection.
}

if (isset($_GET['delete'])) {
   $delete_id = mysqli_real_escape_string($conn, $_GET['delete']); // Sanitize input to prevent SQL injection.
   $query = "DELETE FROM `users` WHERE id = '$delete_id'";
   if (mysqli_query($conn, $query)) {
       header('location:admin_users.php');
       exit(); // Terminate script after redirection.
   } else {
       die('Query failed: ' . mysqli_error($conn)); // Proper error handling.
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dashboard</title>

  <!-- font awesome cdn link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- custom admin css file link -->
  <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
  <?php include 'admin_header.php'; ?> <!-- Check the path to your admin_header.php file. -->

  <section class="users">
    <h1 class="title">users account</h1>

    <table class="users-table">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>User Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('Query failed'); // Proper error handling.
          if (mysqli_num_rows($select_users) > 0) {
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
        ?>
        <tr>
          <td><?php echo htmlspecialchars($fetch_users['id']); ?></td> <!-- Sanitize output to prevent XSS. -->
          <td><?php echo htmlspecialchars($fetch_users['username']); ?></td>
          <td><?php echo htmlspecialchars($fetch_users['email']); ?></td>
          <td style="color:<?php echo ($fetch_users['user_type'] == 'admin') ? 'var(--orange)' : ''; ?>"><?php echo htmlspecialchars($fetch_users['user_type']); ?></td>
          <td>
            <a href="admin_users.php?delete=<?php echo htmlspecialchars($fetch_users['id']); ?>" onclick="return confirm('Delete this user?');" class="delete-btn">delete</a>
          </td>
        </tr>
        <?php
            }
          }
        ?>
      </tbody>
    </table>
  </section>

  <script src="js/admin_script.js"></script>
</body>
</html>
