<?php
include '../config.php';


if (isset($_POST['submit'])) {

  $name = $_POST['fullname'];
  $uname = $_POST['uname'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $confirm_password = md5($_POST['confirm_password']);
  $user_type = $_POST['user_type'];


  $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('query failed');

  if (mysqli_num_rows($select_users) > 0) {
    $alert_message[] = 'user already exist!';
  } else {
    if ($password != $confirm_password) {
      $alert_message[] = 'confirm password not matched!';
    } else {
      mysqli_query($conn, "INSERT INTO `users`(nama, username, email, password, user_type) VALUES('$name','$uname', '$email', '$confirm_password', '$user_type')") or die('query failed');
      $alert_message[] = 'registered successfully!';
      header('location:login.php');
    }
  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="signup.css">
</head>

<body>

<?php
if(isset($alert_message)){
   foreach($alert_message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

  <div class="form-container">
    <div class="form-box">
      <h1>Sign Up</h1>
      <form action="#" method="post">
        <div class="input-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" required>
        </div>
        <div class="input-group">
          <label for="uname">Username</label>
          <input type="text" id="uname" name="uname" required>
        </div>
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <select name="user_type" class="input-group">
          <option value="user">customer</option>
          <option value="admin">admin</option>
        </select>
        <button type="submit" name="submit" class="btn">Sign Up</button>
      </form>
      <p>Already have an account? <a href="login.php">Login Now</a></p>
    </div>
  </div>
</body>

</html>