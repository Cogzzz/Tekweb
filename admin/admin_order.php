<?php

include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:../user/login.php');
}
;

if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE order_id = '$order_update_id'") or die('query failed');
   $alert_message[] = 'payment status has been updated!';

}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE order_id = '$delete_id'") or die('query failed');
   $alert_message[] = 'order deleted successfully!';
   header('location:admin_order.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin.css">

</head>

<body>

   <?php
   if (isset($alert_message)) {
      foreach ($alert_message as $message) {
         echo '<div class="message">' . $message . '</div>';
      }
   }
   ?>

   <?php include 'admin_header.php'; ?>

   <section class="orders">
      <h1 class="title">Placed Orders</h1>

      <div class="table-responsive">
         <table class="table">
            <thead>
               <tr>
                  <th>User ID</th>
                  <th>Placed On</th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Total Products</th>
                  <th>Total Price</th>
                  <th>Payment Method</th>
                  <th>Payment Status</th>
                  <th>Actions</th>
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
                        <td><?php echo $fetch_orders['number']; ?></td>
                        <td><?php echo $fetch_orders['email']; ?></td>
                        <td><?php echo $fetch_orders['address']; ?></td>
                        <td><?php echo $fetch_orders['total_products']; ?></td>
                        <td>IDR <?php echo $fetch_orders['total_price']; ?>,00</td>
                        <td><?php echo $fetch_orders['payment_method']; ?></td>
                        <td>
                           <form action="" method="post" class="d-inline">
                              <input type="hidden" name="order_id" value="<?php echo $fetch_orders['order_id']; ?>">
                              <select name="update_payment" class="form-select">
                                 <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                 <option value="pending">Pending</option>
                                 <option value="completed">Completed</option>
                              </select>
                        </td>
                        <td>
                           <input type="submit" value="Update" name="update_order" class="btn btn-blue">
                           </form>
                           <a href="admin_order.php?delete=<?php echo $fetch_orders['order_id']; ?>"
                              onclick="return confirm('Delete this order?');" class="btn btn-red">Delete</a>
                        </td>
                     </tr>
                     <?php
                  }
               } else {
                  echo '<tr><td colspan="11" class="text-center">No orders placed yet!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </section>












   <!-- custom admin js file link  -->
   <script src="js/admin_config.js"></script>

</body>

</html>