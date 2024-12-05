<?php
include '../config.php';

session_start();

if (isset($_POST['submit'])) {


  $uname = $_POST['uname'];
  $password = md5($_POST['password']);

  $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$uname' AND password = '$password'") or die('query failed');

  if (mysqli_num_rows($select_users) > 0) {

    $row = mysqli_fetch_assoc($select_users);

    if ($row['user_type'] == 'admin') {

      $_SESSION['admin_username'] = $row['username'];
      $_SESSION['admin_email'] = $row['email'];
      $_SESSION['admin_id'] = $row['id'];
      header('location:../admin/admin_page.php');

    } elseif ($row['user_type'] == 'user') {

      $_SESSION['user_username'] = $row['username'];
      $_SESSION['user_email'] = $row['email'];
      $_SESSION['user_id'] = $row['id'];
      header('location:../homepage.php');

    }

  } else {
    $alert_message[] = 'incorrect username or password!';
  }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="log.css">
</head>

<body>

  <?php
  if (isset($alert_message)) {
    foreach ($alert_message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
  }
  ?>

  <div class="login-container">
    <div class="login-box">
      <h1>Login</h1>
      <form action="#" method="post">
        <div class="input-group">
          <label for="uname">Username</label>
          <input type="text" id="uname" name="uname" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="submit" class="login-btn">Login</button>
      </form>
      <p>Don't have an account? <a href="signup.php">Sign Up Now</a></p>
    </div>
  </div>
</body>

</html>