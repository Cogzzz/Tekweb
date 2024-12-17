<?php

include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../user/login.php');
}

// Handle AJAX request to fetch products
if (isset($_GET['action']) && $_GET['action'] === 'get_products') {
    header('Content-Type: application/json');

    $query = "SELECT * FROM `product`";
    $result = mysqli_query($conn, $query);

    $products = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    echo json_encode($products);
    exit; // Prevent further execution of the script
}

if (isset($_POST['add_product'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../admasset/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `product` WHERE name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        //update
        echo json_encode(['status' => 'error', 'message' => 'Product already exists']);
        exit;
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO `product`(name, price, image_url) VALUES('$name', '$price', '$image')") or die('query failed');

        if ($add_product_query) {
            if ($image_size > 2000000) {
                $alert_message[] = 'image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                // Return the updated product list in JSON format (update ajax)
                $query = "SELECT * FROM `product`";
                $result = mysqli_query($conn, $query);

                $products = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $products[] = $row;
                }

                echo json_encode($products);
                exit;
            }
        } else {
            //update
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image_url FROM `product` WHERE product_id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('../admasset/' . $fetch_delete_image['image_url']);
    mysqli_query($conn, "DELETE FROM `product` WHERE product_id = '$delete_id'") or die('query failed');
    header('location:admin_product.php');
}

if (isset($_POST['update_product'])) {

    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE `product` SET name = '$update_name', price = '$update_price' WHERE product_id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = '../admasset/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $alert_message[] = 'image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `product` SET image_url = '$update_image' WHERE product_id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('../admasset/' . $update_old_image);
        }
    }

    header('location:admin_product.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="admin.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <!-- product CRUD section starts  -->

    <section class="add-products">

        <h1 class="title">manage products</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>add product</h3>
            <input type="text" name="name" class="box" placeholder="enter product name" required>
            <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add product" name="add_product" class="btn">
        </form>

    </section>

    <!-- product CRUD section ends -->

    <!-- show products  -->

    <section class="show-products">

        <div class="box-container">

            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <div class="box">
                        <img src="../admasset/<?php echo $fetch_products['image_url']; ?>" alt="">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="price">IDR <?php echo $fetch_products['price']; ?>,00</div>
                        <a href="admin_product.php?update=<?php echo $fetch_products['product_id']; ?>"
                            class="option-btn">update</a>
                        <a href="admin_product.php?delete=<?php echo $fetch_products['product_id']; ?>" class="delete-btn"
                            onclick="return confirm('delete this product?');">delete</a>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>

    </section>

    <section class="edit-product-form">

        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `product` WHERE product_id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['product_id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image_url']; ?>">
                        <img src="uploaded_img/<?php echo $fetch_update['image_url']; ?>" alt="">
                        <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required
                            placeholder="enter product name">
                        <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box"
                            required placeholder="enter product price">
                        <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                        <input type="submit" value="update" name="update_product" class="btn">
                        <input type="reset" value="cancel" id="close-update" class="option-btn">
                    </form>
                    <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>

    </section>







    <!-- custom admin js file link  -->
    <script src="js/admin_config.js"></script>

</body>

</html>