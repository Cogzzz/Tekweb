<?php

include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:../user/login.php');
 };

 if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    
    // Jika admin yang sedang login menghapus akunnya sendiri
    if($delete_id == $admin_id){
        mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
        
        // Hapus sesi dan arahkan ke login
        session_unset();
        session_destroy();
        header('location:../user/login.php');
        exit; // Berhenti eksekusi script setelah redirect
    } else {
        // Jika bukan akun admin yang sedang login
        mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
        header('location:admin_users.php');
        exit; // Berhenti eksekusi script setelah redirect
    }
 }
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title"> user accounts </h1>

   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <p> user id : <span><?php echo $fetch_users['id']; ?></span> </p>
         <p> name : <span><?php echo $fetch_users['nama']; ?></span> </p>
         <p> username : <span><?php echo $fetch_users['username']; ?></span> </p>
         <p> email : <span><?php echo $fetch_users['email']; ?></span> </p>
         <p> user type : <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--orange)'; } elseif($fetch_users['user_type'] == 'user'){ echo 'var(--blue)'; } ?>"><?php echo $fetch_users['user_type']; ?></span> </p>
         <a href="admin_account.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
      </div>
      <?php
         };
      ?>
   </div>

</section>









<!-- custom admin js file link  -->
<script src="js/admin_config.js"></script>

</body>
</html>