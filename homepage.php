<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

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

</head>

<body>
    <header>
        <a href="#" class="logo">
            <img src="asset/logo.png" alt="">
        </a>
        <i class="bx bx-menu" id="menu-icon"></i>
        <ul class="navbar">
            <li><a href="#">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="shop.php">Shop Now</a></li>
            <li><a href="#customers">Customers</a></li>
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
                                        <span>' . htmlspecialchars($cart_item['name'], ENT_QUOTES, 'UTF-8') . '</span>
                                        <span>' . $cart_item['quantity'] . ' x IDR ' . $cart_item['price'] . '</span>
                                        </li>';
                                    }
                                } else {
                                    echo '<li class="list-group-item text-center">Your cart is empty!</li>';
                                }
                                ?>
                            </ul>
                            <div class="text-center">
                                <a href="keranjang/cart.php" class="btn btn-primary w-100 mb-2">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-box">
                <input type="search" id="" placeholder="What Coffee Do You Want ?">
            </div>
        </div>
    </header>

    <section class="home" id="home">
        <div class="home-text">
            <h1>Start your day <br> With Coffee</h1>
            <p> Nikmati hari Anda dengan secangkir kopi terbaik yang kami tawarkan. Dari biji kopi pilihan hingga
                racikan penuh cinta, temukan kenikmatan sempurna di setiap tegukan. Jadikan setiap pagi lebih
                bersemangat bersama kami!</p>
            <a href="shop.php" class="btn"> Shop Now</a>
        </div>
        <div class="home-img">
            <img src="asset/main.png" alt="">
        </div>
    </section>
    <section class="about" id="about">
        <div class="about-img">
            <img src="asset/about.jpg" alt="About Image">
        </div>
        <div class="about-text">
            <h2>Our History</h2>
            <p>Kami memulai perjalanan kami dengan visi untuk menghadirkan kopi berkualitas terbaik kepada setiap
                pecinta kopi. Berawal dari kecintaan pada rasa dan aroma kopi, kami tumbuh menjadi sebuah komunitas yang
                menjunjung tinggi nilai tradisi dan inovasi.</p>
            <p>Setiap biji kopi yang kami pilih bercerita tentang perjalanan panjang dari petani lokal hingga menjadi
                secangkir kopi istimewa. Kami bangga mendukung petani kopi lokal, menjaga keberlanjutan, dan menyajikan
                kopi dengan cinta dan keahlian.</p>
            <p>Bergabunglah dengan kami untuk menjelajahi dunia kopi yang penuh dengan cerita, rasa, dan pengalaman tak
                terlupakan.</p>
            <a href="history_coffee/historycoffe.html" class="btn"> Learn More</a>
        </div>
    </section>

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
                    <form action="" class="box" data-category="<?php echo htmlspecialchars($fetch_products['category'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img class="image" 
                            src="admasset/<?php echo $fetch_products['image_url']; ?>" 
                            alt="" 
                            data-name="<?php echo htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-description="<?php echo htmlspecialchars($fetch_products['description'], ENT_QUOTES, 'UTF-8'); ?>" 
                            data-price="<?php echo $fetch_products['price']; ?>" 
                            onclick="showProductModal(this)">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                    </form>

                    <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <section class="customers" id="customers">
        <div class="heading">
            <h2>Customers Testimonials</h2>
        </div>

        <div class="customers-container d-flex overflow ```html
-auto" style="gap: 1rem; white-space: nowrap;">
            <!-- Contoh Testimoni -->
            <div class="box d-inline-block" style="min-width: 300px;">
                <div class="stars">
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
                <h2>Steven Oentoro</h2>
                <img src="asset/rev1.jpg" alt="">
            </div>
            <!-- Tambahkan lebih banyak box testimoni -->
        </div>

        <!-- Tombol untuk memunculkan modal -->
        <div class="text-center mt-4">
            <button class="btn" data-bs-toggle="modal" data-bs-target="#testimonialModal">Add Testimonial</button>
        </div>
    </section>

    <!-- Modal untuk Input Testimonial -->
    <div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testimonialModalLabel">Add Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="testimonialForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="stars" class="form-label">Rating</label>
                            <select class="form-select" id="stars" required>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <button type="submit" class="btn1">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            <span><i class='bx bxs-phone-call'></i>+62 009898791123 </span>
            <hr>
            <span><i class='bx bxs-envelope'></i>coffe@gmail.com</span>
        </div>
    </section>

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
        function showProductModal(element) {
            // Ambil data dari atribut data-* pada gambar
            const name = element.getAttribute('data-name');
            const description = `Discover the unique taste of ${name}!`; // Mengubah deskripsi sesuai permintaan
            const price = element.getAttribute('data-price');
            const imageUrl = element.src; // Ambil URL gambar

            // Isi modal dengan data produk
            document.getElementById('productModalLabel').innerText = name;
            document.getElementById('modal-description').innerText = description; // Menggunakan deskripsi baru
            document.getElementById('modal-price').innerText = 'Rp.' + price;
            document.getElementById('modal-img').src = imageUrl;

            // Tampilkan modal
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        }
    </script>
    <script src="main.js"></script>
</body>

</html>