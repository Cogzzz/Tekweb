<?php

include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:../user/login.php');
}

if (isset($_POST['update_update_btn'])) {
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];
   $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE cart_id = '$update_id'");
   if ($update_quantity_query) {
      header('location:cart.php');
   }
   ;
}
;

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE cart_id = '$remove_id'");
   $alert_message[] = 'Item telah dihapus dari keranjang.';
   header('location:cart.php');
}
;

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart`");
   $alert_message[] = 'Keranjang kosong!';
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="cart.css">

</head>

<body>
   <div class="container">
      <?php
      if (isset($alert_message)) {
         foreach ($alert_message as $message) {
            echo '<div class="message">' . $message . '</div>';
         }
      }
      ?>
      <section class="shopping-cart">
         <h1 class="heading">Shopping cart</h1>

         <table>
            <thead>
               <th>image</th>
               <th>name</th>
               <th>price</th>
               <th>quantity</th>
               <th>total price</th>
               <th>action</th>
            </thead>

            <tbody>

               <?php

               $select_cart = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
               $grand_total = 0;
               if (mysqli_num_rows($select_cart) > 0) {
                  while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                     ?>

                     <tr>
                        <td><img src="../admasset/<?php echo $fetch_cart['image_url']; ?>" height="50" alt=""></td>
                        <td><?php echo $fetch_cart['name']; ?></td>
                        <td>IDR <?php echo $fetch_cart['price']; ?> ,00</td>
                        <td>
                           <form action="" method="post">
                              <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['cart_id']; ?>">
                              <input type="number" name="update_quantity" min="1"
                                 value="<?php echo $fetch_cart['quantity']; ?>">
                              <input type="submit" value="update" name="update_update_btn">
                           </form>
                        </td>
                        <td>IDR <?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> ,00</td>
                        <td><a href="cart.php?remove=<?php echo $fetch_cart['cart_id']; ?>"
                              onclick="return confirm('remove item from cart?')" class="delete-btn"> <i
                                 class="fas fa-trash"></i> remove</a></td>
                     </tr>
                     <?php
                     $grand_total += $sub_total;
                  }
                  ;
               }
               ;
               ?>
               <tr class="table-bottom">
                  <!-- <td><a href="../homepage.php" class="option-btn" style="margin-top: 0;">continue shopping</a></td> -->
                  <td colspan="4" class="grand-total-label">grand total</td>
                  <td class="grand-value-label">IDR <?php echo $grand_total; ?> ,00</td>
                  <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all?');"
                        class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
               </tr>

            </tbody>

         </table>

         <div class="checkout-btn">
            <a href="../shop.php" class="option-btn" style="margin-top: 0;">continue shopping</a>
            <a href="../checkout/new_checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">procced to
               checkout</a>
         </div>

      </section>

   </div>

   <!-- custom js file link  -->
   <script src="cart.js"></script>

</body>

</html>