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

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_page.php">Home</a>
         <a href="admin_product.php">Products</a>
         <a href="admin_order.php">Orders</a>
         <a href="admin_account.php">Account Manage</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p>username : <span><?php echo $_SESSION['admin_username']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="../logout.php" class="delete-btn">logout</a>
         <!-- <div>new <a href="login.php">login</a> | <a href="register.php">register</a></div> -->
      </div>

   </div>

   <!-- custom admin js file link  -->
<script src="admin_config.js"></script>

</header>