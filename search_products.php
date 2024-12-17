<?php
include 'config.php';

// Periksa koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Escape input untuk mencegah XSS
$search_query = isset($_POST['search']) ? htmlspecialchars(trim($_POST['search'])) : '';

try {
    // Menyiapkan query SQL
    if (!empty($search_query)) {
        $query = "SELECT * FROM product WHERE name LIKE ?";
        $search_param = "%$search_query%";  // Wildcard untuk pencarian parsial

        // Gunakan prepared statement dengan mysqli
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt === false) {
            throw new Exception("Gagal mempersiapkan statement: " . mysqli_error($conn));
        }

        // Bind parameter
        if (!mysqli_stmt_bind_param($stmt, "s", $search_param)) {
            throw new Exception("Gagal mengikat parameter: " . mysqli_stmt_error($stmt));
        }

        // Eksekusi statement
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal mengeksekusi query: " . mysqli_stmt_error($stmt));
        }

        // Ambil hasil
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            throw new Exception("Gagal mendapatkan hasil: " . mysqli_stmt_error($stmt));
        }
    } else {
        // Jika tidak ada pencarian, tampilkan semua produk
        $query = "SELECT * FROM product";
        $result = mysqli_query($conn, $query);

        if ($result === false) {
            throw new Exception("Gagal mengeksekusi query: " . mysqli_error($conn));
        }
    }

    // Cek apakah ada produk
    if (mysqli_num_rows($result) > 0) {
        while ($product = mysqli_fetch_assoc($result)) {
            // Tambahkan escape untuk mencegah XSS di output
            $name = htmlspecialchars($product['name']);
            $description = htmlspecialchars($product['description']);
            $price = number_format($product['price'], 0, ',', '.');

            echo '
            <div class="product-item">
                <img src="admasset/' . htmlspecialchars($product['image_url']) . '" alt="' . $name . '" class="product-image">
                <h3>' . $name . '</h3>
                <p>' . $description . '</p>
                <span>Price: IDR ' . $price . '</span>
                <form action="" method="POST">
                    <input type="hidden" name="product_name" value="' . $name . '">
                    <input type="hidden" name="product_price" value="' . $product['price'] . '">
                    <input type="hidden" name="product_image" value="' . htmlspecialchars($product['image_url']) . '">
                    <div class="quantity-container">
                        <button type="button" class="decrement">-</button>
                        <input type="number" name="product_quantity" value="1" min="1" class="qty">
                        <button type="button" class="increment">+</button>
                    </div>
                    <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                </form>
            </div>
            ';

            
        }
    } else {
        // Pesan tidak ditemukan dengan desain yang lebih baik
        echo '
        <div class="no-products-found">
            <p>Maaf, tidak ada produk yang ditemukan.</p>
            <p>Coba kata kunci pencarian yang berbeda.</p>
        </div>
        ';
    }

    // Tutup statement jika menggunakan prepared statement
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }

} catch (Exception $e) {
    // Tangani error dengan detail yang lebih baik
    error_log('Database error: ' . $e->getMessage());
    echo '
    <div class="error-message">
        <p>Terjadi kesalahan dalam memuat produk:</p>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
    </div>
    ';
}

// Tidak perlu menutup koneksi karena akan digunakan di halaman lain
?>