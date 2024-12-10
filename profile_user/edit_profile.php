<?php
include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../user/login.php');
}

if (isset($_POST['save_changes'])) {

    $update_uname = $_POST['update_uname'];
    $update_email = $_POST['update_email'];

    $query = "UPDATE `users` SET username = '$update_uname', email = '$update_email' WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $alert_message[] = "Data berhasil diperbarui!"; // Pesan sukses
    } else {
        die("Query failed");
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile User</title>
    <!--Link CSS-->
    <link rel="stylesheet" href="edit_profile.css">
    <!--Boxicon-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Tambahkan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
     <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <form action="" method="POST">
            <div class="profile-box">
                <?php
                $select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
                ?>
                <h2>Update Profile</h2>
                <form>
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="update_uname" value="<?php echo $fetch['username']; ?>"
                            class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>"
                            class="form-control">
                    </div>

                    <!-- <span>old password :</span>
                <input type="password" name="update_pass" placeholder="enter previous password" class="box">
                <span>new password :</span>
                <input type="password" name="new_pass" placeholder="enter new password" class="box">
                <span>confirm password :</span>
                <input type="password" name="confirm_pass" placeholder="confirm new password" class="box"> -->
                    <!-- <div class="form-group mb-3">
                    <label>Nomor Ponsel</label>
                    <div class="input-group">
                        <span class="input-group-text">+62</span>
                        <input type="text" class="form-control" placeholder="Nomor Ponsel" required>
                    </div>
                </div> -->
                    <!-- <h2>Alamat Pengiriman</h2>
                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <input type="text" class="form-control" placeholder="Alamat Lengkap" required>
                </div>
                <div class="form-group mb-3">
                    <label>Kecamatan</label>
                    <input type="text" class="form-control" placeholder="Kecamatan" required>
                </div>
                <div class="form-group mb-3">
                    <label>Kota</label>
                    <input type="text" class="form-control" placeholder="Kota" required>
                </div>
                <div class="form-group mb-3">
                    <label>Kabupaten</label>
                    <input type="text" class="form-control" placeholder="Kabupaten" required>
                </div> -->
                    <button type="submit" name="save_changes" class="btn-save">Simpan</button>
                </form>
                <div class="account-section">
                    <a href="change_pass.php" class="btn">Ubah Kata Sandi</a>
                    <!-- <button class="btn">Perbarui Alamat Email</button> -->
                    <!-- <button class="btn">Perbarui Nomor Telefon</button> -->
                    <!-- <button class="btn btn-delete">Hapus Akun</button> -->
                </div>
            </div>
        </form>

    </div>
</body>

</html>