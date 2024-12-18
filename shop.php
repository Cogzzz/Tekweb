<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:user/login.php');
}

// Ambil username dari database jika user_id ada
$username = null;
if ($user_id) {
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Menggunakan prepared statement untuk keamanan
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("query failed");
    }
}

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        // Jika produk sudah ada, update quantity
        $cart_item = mysqli_fetch_assoc($check_cart_numbers);
        $new_quantity = $cart_item['quantity'] + $product_quantity;
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        $alert_message[] = 'Product quantity updated in cart!';
    } else {
        // Jika produk belum ada, tambahkan produk ke keranjang
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image_url) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $alert_message[] = 'Product added to cart!';
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffe Shop</title>
    <!--Boxicon-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Link custom CSS-->
    <link rel="stylesheet" href="style.css">
    <!-- Tambahkan Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
    <!--HEADER START-->
    <header>
        <a href="#" class="logo">
            <img src="asset/logo.png" alt="">
        </a>
        <i class="bx bx-menu" id="menu-icon"></i>
        <ul class="navbar">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="homepage.php#about">About Us</a></li>
            <li><a href="homepage.php#menu">Menu</a></li>
            <li><a href="shop.php">Shop Now</a></li>
            <li><a href="homepage.php#customers">Customers</a></li>
            <li><a href="cek_order/orders.php">My Order</a></li>
        </ul>
        <div class="header-actions">
            <div class="header-icon">
                <?php
                $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $cart_rows_number = mysqli_num_rows($select_cart_number);
                ?>
                <div class="cart">
                    <i class="bx bx-cart-alt" id="cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal"></i>
                    <span><?php echo $cart_rows_number; ?></span>
                </div>
                <i class="bx bx-search" id="search-icon"></i>
                <div class="dropdown">
                    <i class="bx bx-user" id="dropdown-toggle"></i>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <li class="dropdown-item" id="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?></li>
                        <a href="profile_user/profileuser.php" class="dropdown-item">Profile User</a>
                        <li><a href="logout.php" class="dropdown-item" id="logout">Logout</a></li>
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


    <!-- CONTENT START -->

    <!-- MENAMPILKAN PRODUK -->
    <section class="product" id="menu">
        <div class="heading">
            <h2>Our Popular Products</h2>
        </div>
        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <div class="btn-group" role="group" aria-label="Product Filters">
                <button type="button" class="btn btn-outline-secondary" onclick="filterProducts('Strong')">Strong</button>
                <button type="button" class="btn btn-outline-secondary" onclick="filterProducts('Mild')">Mild</button>
                <button type="button" class="btn btn-outline-secondary" onclick="filterProducts('Light')">Light</button>
                <button type="button" class="btn btn-outline-secondary" onclick="filterProducts()">All</button>
            </div>
        </div>
        <div class="products-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <form action="" method="post" class="box" data-category="<?php echo htmlspecialchars($fetch_products['category'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img class="image" src="admasset/<?php echo $fetch_products['image_url']; ?>" alt="">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="price">IDR <?php echo $fetch_products['price']; ?>,00</div>
                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image_url']; ?>">
                        <div class="product-action">
                            <div class="quantity-container">
                                <button type="button" class="decrement">-</button>
                                <input type="text" name="product_quantity" value="1" class="qty" readonly>
                                <button type="button" class="increment">+</button>
                            </div>
                            <input type="submit" value="Add to cart" name="add_to_cart" class="btn">
                        </div>
                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <!-- CONTENT END -->



    <!-- FOOTER -->
    <section class="footer">
        <div class="footer-box">
            <h2>Coffe Shoop</h2>
            <p>Nikmati hari Anda dengan secangkir kopi terbaik yang kami tawarkan. Dari biji kopi pilihan hingga racikan
                penuh cinta, temukan kenikmatan sempurna di setiap tegukan. Jadikan setiap pagi lebih bersemangat
                bersama kami!</p>
            <div class="social">
                <a href="#"><i class='bx bxl-facebook'></i></a>
                <a href=""><i class='bx bxl-twitter'></i></a>
                <a href=""><i class='bx bxl-instagram'></i></a>
                <a href=""><i class='bx bxl-tiktok'></i></a>
            </div>
        </div>

        <div class="footer-box">
            <h2>Support</h2>
            <li><a href="#menu">Product</a></li>
            <li><a href="#">Help & Support</a></li>
            <li><a href="#">Return Policy</a></li>
            <li><a href="#">Terms of Use</a></li>
        </div>

        <div class="footer-box">
            <h2>View Guides</h2>
            <li><a href="#">Features</a></li>
            <li><a href="#">Carrers</a></li>
            <li><a href="#customers">Blog Post</a></li>
            <li><a href="#">Our Branches</a></li>
        </div>
        <div class="footer-box">
            <h2>Contact</h2>
            <span> <i class='bx bxs-map'></i> Jl.Sekawan Anggrek 100, Sidoarjo</span>
            <hr>
            <span><i class='bx bxs-phone-call'></i>+62 009898791123</span>
            <hr>
            <span><i class='bx bxs-envelope'></i>coffe@gmail.com</span>
        </div>
    </section>
    <!-- FOOTER END -->

    <!-- Modal Product -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modal-img" src="" class="img-fluid mb-3" alt="Product Image">
                    <p id="modal-description">Product Description</p>
                    <p><strong>Price:</strong> <span id="modal-price">Rp.0</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.quantity-container').forEach(container => {
        const decrementBtn = container.querySelector('.decrement');
        const incrementBtn = container.querySelector('.increment');
        const qtyInput = container.querySelector('.qty');

        decrementBtn.addEventListener('click', () => {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });

        incrementBtn.addEventListener('click', () => {
            let currentValue = parseInt(qtyInput.value);
            qtyInput.value = currentValue + 1;
        });
    });
    </script>

        <script>
        // Fungsi untuk menangani increment dan decrement quantity
        document.querySelectorAll('.quantity-container').forEach(container => {
            const decrementBtn = container.querySelector('.decrement');
            const incrementBtn = container.querySelector('.increment');
            const qtyInput = container.querySelector('.qty');

            decrementBtn.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value);
                if (currentValue > 1) {
                    qtyInput.value = currentValue - 1;
                }
            });

            incrementBtn.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value);
                qtyInput.value = currentValue + 1;
            });
        });

        // Fungsi untuk menampilkan/menyembunyikan search input
        document.getElementById("search-icon").addEventListener("click", function () {
            const searchInput = document.getElementById("search-input");
            searchInput.classList.toggle("show");
            searchInput.focus();
        });

        // Fungsi untuk memuat produk menggunakan AJAX
        function loadProducts(searchQuery = '') {
            fetch('search_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: search=${encodeURIComponent(searchQuery)}
            })
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.products-container').innerHTML = data;

                    // Re-attach quantity event listeners
                    document.querySelectorAll('.quantity-container').forEach(container => {
                        const decrementBtn = container.querySelector('.decrement');
                        const incrementBtn = container.querySelector('.increment');
                        const qtyInput = container.querySelector('.qty');

                        decrementBtn.addEventListener('click', () => {
                            let currentValue = parseInt(qtyInput.value);
                            if (currentValue > 1) {
                                qtyInput.value = currentValue - 1;
                            }
                        });

                        incrementBtn.addEventListener('click', () => {
                            let currentValue = parseInt(qtyInput.value);
                            qtyInput.value = currentValue + 1;
                        });
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.querySelector('.products-container').innerHTML =
                        '<div class="error-message">Gagal memuat produk. Silakan coba lagi.</div>';
                });
        }

        // Memuat semua produk saat halaman pertama kali dimuat
        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();

            // Event listener untuk pencarian saat ikon search diklik
            document.getElementById('search-icon').addEventListener('click', () => {
                const searchInput = document.getElementById('search-input');
                const searchQuery = searchInput.value.trim();
                loadProducts(searchQuery);
            });

            // Event listener untuk pencarian saat mengetik
            document.getElementById('search-input').addEventListener('input', (e) => {
                const searchQuery = e.target.value.trim();
                loadProducts(searchQuery);
            });
        });
    </script>
    <script src="main.js"></script>
</body>

</html>