<?php
include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../user/login.php');
}

if (isset($_POST['submit_change'])) {

    $old_pass = $_POST['old_pass'];
    $update_pass = md5($_POST['update_pass']);
    $new_pass = md5($_POST['new_pass']);
    $confirm_pass = md5($_POST['confirm_pass']);

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($update_pass != $old_pass) {
            $alert_message[] = 'Password lama tidak sesuai!';
        } elseif ($new_pass != $confirm_pass) {
            $alert_message[] = 'Konfirmasi password tidak cocok!';
        } else {
            mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
            $alert_message[] = 'Password berhasil dirubah!';
        }
    } else {
        $alert_message[] = 'Field tidak boleh kosong!';
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
                <h2>Update Profile - Ubah Kata Sandi</h2>
                <form>
                    <div class="form-group mb-3">
                        <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                        <label>Old Password</label>
                        <input type="password" name="update_pass" placeholder="enter previous password"
                            class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" name="new_pass" placeholder="enter new password" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_pass" placeholder="confirm new password"
                            class="form-control">
                    </div>
                    <button type="submit" name="submit_change" class="btn-save">Ganti Password</button>
                </form>
                <div class="account-section">
                    <button class="btn">Ubah Kata Sandi</button>
                    <!-- <button class="btn">Perbarui Alamat Email</button> -->
                    <!-- <button class="btn">Perbarui Nomor Telefon</button> -->
                    <a href="../homepage.php" class="btn">Kembali ke Homepage</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>