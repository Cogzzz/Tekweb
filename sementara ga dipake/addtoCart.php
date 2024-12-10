<?php
include 'config.php';
session_start();

// if (!isset($_SESSION['user_id'])) {
//     die(json_encode(['status' => 'error', 'message' => 'User not logged in']));
// }

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$product_id = $data['product_id'];
$quantity = $data['quantity'];

// Validasi input
if (!$product_id || !$quantity || $quantity <= 0) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid input']));
}

// // Cek apakah produk ada dan masih tersedia dalam stok
// $product_query = $conn->query("SELECT * FROM product WHERE product_id = '$product_id'");
// if ($product_query->num_rows === 0) {
//     die(json_encode(['status' => 'error', 'message' => 'Product not found']));
// }

$product = $product_query->fetch_assoc();
if ($product['stock'] < $quantity) {
    die(json_encode(['status' => 'error', 'message' => 'Insufficient stock']));
}

// Cek apakah keranjang sudah ada untuk user
$cart_query = $conn->query("SELECT cart_id FROM cart WHERE user_id = '$user_id'");
if ($cart_query->num_rows > 0) {
    $cart_id = $cart_query->fetch_assoc()['cart_id'];
} else {
    $conn->query("INSERT INTO cart (user_id) VALUES ('$user_id')");
    $cart_id = $conn->insert_id;
}

// Periksa apakah produk sudah ada di cart_items
$item_query = $conn->query("SELECT * FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
if ($item_query->num_rows > 0) {
    $conn->query("UPDATE cart_items SET quantity = quantity + $quantity WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
} else {
    $conn->query("INSERT INTO cart_items (cart_id, product_id, quantity, price, image_url) VALUES ('$cart_id', '$product_id', '$quantity', '{$product['price']}', '{$product['image_url']}')");
}

echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
?>
