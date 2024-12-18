<?php
include 'config.php';

session_start();
$user_id = $_SESSION['user_id'] ?? null;

// Ambil kata kunci dari form
$search_query = $_GET['query'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
a</head>
<body>
    <div class="container mt-4">
        <h1>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h1>

        <div class="products-container">
            <?php
            if ($search_query) {
                // SQL Query menggunakan LIKE untuk mencari produk
                $stmt = $conn->prepare("SELECT * FROM `product` WHERE `name` LIKE ? OR `description` LIKE ?");
                $search_param = "%$search_query%";
                $stmt->bind_param("ss", $search_param, $search_param);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="box">
                            <img src="admasset/' . htmlspecialchars($row['image_url'], ENT_QUOTES) . '" alt="">
                            <div class="name">' . htmlspecialchars($row['name']) . '</div>
                            <p>IDR ' . htmlspecialchars($row['price']) . '</p>
                        </div>';
                    }
                } else {
                    echo '<p>No results found!</p>';
                }
            } else {
                echo '<p>Please enter a search query.</p>';
            }
            ?>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
    </div>
</body>
</html>
