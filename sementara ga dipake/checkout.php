<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'User not logged in']));
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang
$cart_query = $conn->query("SELECT cart_id FROM cart WHERE user_id = '$user_id'");
if ($cart_query->num_rows === 0) {
    die(json_encode(['status' => 'error', 'message' => 'Cart is empty']));
}

$cart_id = $cart_query->fetch_assoc()['cart_id'];
$cart_items_query = $conn->query("SELECT * FROM cart_items WHERE cart_id = '$cart_id'");
if ($cart_items_query->num_rows === 0) {
    die(json_encode(['status' => 'error', 'message' => 'Cart is empty']));
}

// Proses checkout
$total_price = 0;
while ($item = $cart_items_query->fetch_assoc()) {
    $total_price += $item['price'] * $item['quantity'];

    // Kurangi stok produk
    $conn->query("UPDATE product SET stock = stock - {$item['quantity']} WHERE product_id = '{$item['product_id']}'");
}

// Simpan ke tabel orders
$conn->query("INSERT INTO orders (user_id, total_price, created_at) VALUES ('$user_id', '$total_price', NOW())");
$order_id = $conn->insert_id;

// Simpan ke tabel orders_item
$cart_items_query->data_seek(0); // Reset pointer
while ($item = $cart_items_query->fetch_assoc()) {
    $conn->query("INSERT INTO orders_item (order_id, product_id, quantity, price) VALUES ('$order_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')");
}

// Kosongkan keranjang
$conn->query("DELETE FROM cart_items WHERE cart_id = '$cart_id'");

echo json_encode(['status' => 'success', 'message' => 'Checkout successful', 'order_id' => $order_id]);
?>
