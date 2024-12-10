<?php

include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:../user/login.php');
}

// Ambil data user dari database
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
$user_data = mysqli_fetch_assoc($user_query);

if (isset($_POST['order_btn'])) {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $payment_method = $_POST['method'];
   $address = $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['kecamatan'] . ', ' . $_POST['kabupaten'] . ' - ' . $_POST['postal_code'];
   $placed_on = date('Y-m-d H:i:s');

   $cart_total = 0;
   $cart_products[] = '';
   $total_products_quantity = 0; // Tambahkan variabel untuk menyimpan total quantity

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
         $total_products_quantity += $cart_item['quantity']; // Tambahkan quantity ke total_products
      }
   }

   $total_products = implode(', ', $cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND payment_method = '$payment_method' AND address = '$address' AND total_products = '$total_products_quantity' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $alert_message[] = 'Keranjang kosong!';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $alert_message[] = 'Pemesanan sudah dibuat.';
      } else {
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, payment_method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$payment_method', '$address', '$total_products_quantity', '$cart_total', '$placed_on')") or die('query failed');
         $alert_message[] = 'Pemesanan berhasil!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="new_co.css">

</head>

<body>
   <?php
   if (isset($alert_message)) {
      foreach ($alert_message as $message) {
         echo '<div class="message">' . $message . '</div>';
      }
   }
   ?>

   <div class="heading">
      <h3>checkout</h3>
      <p> <a href="../homepage.php">home</a> / checkout </p>
   </div>

   <section class="display-order">
      <ul class="order-list">
         <h3>Your Order:</h3>
         <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
               $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
               $grand_total += $total_price;
               ?>
               <li>
                  <?php echo $fetch_cart['name']; ?>
                  <span>(<?php echo 'IDR ' . $fetch_cart['price'] . ',00' . ' x ' . $fetch_cart['quantity']; ?>)</span>
               </li>
               <?php
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </ul>
      <div class="grand-total">
         GRAND TOTAL: <span>IDR <?php echo $grand_total; ?>,00</span>
      </div>
   </section>


   <section class="checkout">

      <form action="" method="post">
         <h3>place your order</h3>
         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" value="<?php echo $user_data['nama']; ?>" readonly>
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="text" name="number" required placeholder="enter your number">
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" value="<?php echo $user_data['email']; ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Delivery Address :</span>
               <input type="text" name="address" required placeholder="Enter your full address">
            </div>
            <div class="inputBox">
               <span>Kota :</span>
               <input type="text" name="city" required placeholder="e.g. Surabaya">
            </div>
            <div class="inputBox">
               <span>Kecamatan :</span>
               <input type="text" name="kecamatan" required placeholder="e.g. Wonocolo">
            </div>
            <div class="inputBox">
               <span>Kabupaten :</span>
               <input type="text" name="kabupaten" required placeholder="e.g. Kota Surabaya">
            </div>
            <div class="inputBox">
               <span>Kode Pos :</span>
               <input type="number" min="0" name="postal_code" required placeholder="e.g. 123456">
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method">
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="e-wallet">e-wallet</option>
                  <option value="bank transfer">bank transfer</option>
               </select>
            </div>
         </div>
         <input type="submit" value="order now" class="btn" name="order_btn">
      </form>

   </section>

   <!-- custom js file link  -->
   <script src="checkout.js"></script>

</body>

</html>