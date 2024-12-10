<?php
include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../user/login.php');
}

// Query untuk mengambil data pengguna yang sedang login
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="profileuser.css">
  <title>User Profile</title>
</head>
<body>
  <div class="profile-container">
    <header class="profile-header">
      <button class="back-button" onclick="window.location.href='../homepage.php'">←</button>
      <div class="welcome-text">
        <h1>Welcome, <?php echo $user['username']; ?></h1>
      </div>
    </header>

    <main class="profile-content">
      <h2>Informasi Pribadi</h2>
      <form>
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <p> <?php echo $user['nama']; ?></p>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <p> <?php echo $user['email']; ?></p>
        </div>
        <div class="form-group">
          <label for="phone">Nomor Ponsel</label>
          <div class="input-group">
            <span class="input-group-text">+62</span>
            <input type="text" id="phone" placeholder="Nomor Ponsel" required>
          </div>
        </div>

        <!-- <h2>Alamat Pengiriman</h2>
        <div class="form-group">
          <label for="address">Alamat</label>
          <input type="text" id="address" placeholder="Alamat Lengkap" required>
        </div>
        <div class="form-group">
          <label for="district">Kecamatan</label>
          <input type="text" id="district" placeholder="Kecamatan" required>
        </div>
        <div class="form-group">
          <label for="city">Kota</label>
          <input type="text" id="city" placeholder="Kota" required>
        </div>
        <div class="form-group">
          <label for="regency">Kabupaten</label>
          <input type="text" id="regency" placeholder="Kabupaten" required>
        </div> -->
      </form>
    </main>

    <footer class="profile-footer">
      <a href="../logout.php" class="logout-button">Logout</a>
      <a href="edit_profile.php" class="logout-button">Edit Profile</a>
    </footer>
  </div>
</body>
</html>
