<?php

include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../user/login.php');
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <!--HEADER START-->
    <header>
        <a href="#" class="logo">
            <img src="asset/logo.png" alt="">
        </a>
        <i class="bx bx-menu" id="menu-icon"></i>
        <ul class="navbar">
            <li><a href="../homepage.php">Home</a></li>
            <li><a href="../homepage.php#about">About Us</a></li>
            <li><a href="../homepage.php#menu">Menu</a></li>
            <li><a href="../shop.php">Shop Now</a></li>
            <li><a href="../homepage.php#customers">Customers</a></li>
            <li><a href="orders.php">My Order</a></li>
        </ul>
        <div class="header-actions">
            <div class="header-icon">
                <?php
                $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $cart_rows_number = mysqli_num_rows($select_cart_number);
                ?>
                <div class="cart">
                    <i class="bx bx-cart-alt" id="cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal"></i>
                    <span>(<?php echo $cart_rows_number; ?>)</span>
                </div>
                <i class="bx bx-search" id="search-icon"></i>
                <div class="dropdown">
                    <i class="bx bx-user" id="dropdown-toggle"></i>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="profile_user/profileuser.php" class="dropdown-item">Profile User</a>
                        <!-- <a href="history/history.html" class="dropdown-item">History Pembelian</a> -->
                        <!-- <a href="profile_user/profile.html" class="dropdown-item">Edit Profil</a> -->
                    </div>
                </div>
            </div>

            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartModalLabel">Shopping Cart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul id="cart-items" class="list-group mb-3">
                                <?php
                                $select_cart_items = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                                if (mysqli_num_rows($select_cart_items) > 0) {
                                    while ($cart_item = mysqli_fetch_assoc($select_cart_items)) {
                                        echo '
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>' . $cart_item['name'] . '</span>
                                        <span>' . $cart_item['quantity'] . ' x IDR ' . $cart_item['price'] . '</span>
                                        </li>';
                                    }
                                } else {
                                    // kenapa ga pakai p atau h aja?
                                    echo '<li class="list-group-item text-center">Your cart is empty!</li>';
                                }
                                ?>
                            </ul>
                            <div class="text-center">
                                <a href="keranjang/cart.php" class="btn w-100 mb-2" <?php echo (mysqli_num_rows($select_cart_items) > 0) ? '' : 'disabled'; ?>>Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--HEADER END-->


    <div class="heading">
        <p> <a href="../homepage.php">home</a> / my order </p>
    </div>

    <section class="placed-orders">
        <h3 class="title">Placed Orders</h3>
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
                                <td><span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') {
                                    echo 'red';
                                } else {
                                    echo 'green';
                                } ?>;"><?php echo $fetch_orders['payment_status']; ?></span>
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










    <!-- custom js file link  -->
    <!-- <script src="js/script.js"></script> -->

</body>

</html>