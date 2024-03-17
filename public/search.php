<?php

include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

if (isset($_POST['add_to_wishlist'])) {

    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];

    $check_wishlist_numbers = $pdo->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    $check_cart_numbers = $pdo->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'already added to wishlist!';
    } elseif ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'already added to cart!';
    } else {
        $insert_wishlist = $pdo->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
        $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
        $message[] = 'added to wishlist!';
    }

}


if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];
    $p_qty = $_POST['p_qty'];

    $check_cart_numbers = $pdo->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        // Sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
        $update_qty = $pdo->prepare("UPDATE `cart` SET quantity = quantity + ? WHERE name = ? AND user_id = ?");
        $update_qty->execute([$p_qty, $p_name, $user_id]);
        $message[] = 'Quantity updated in cart!';
    } else {
        // Sản phẩm chưa có trong giỏ hàng, thêm mới
        $insert_cart = $pdo->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
        $message[] = 'added to cart!';
    }
}
;
?>


<title>Search</title>
</head>

<section class="my-5">
    <div class="container title text-center mt-3 pt-5">
        <form action="" method="POST" class="d-flex justify-content-center">
            <div class="form-group p-2">
                <input class="form-control" type="text" name="search_box" placeholder="Search products...">

            </div>
            <div class="form-group p-2">
                <input type="submit" name="search_btn" value="Search" class="btn">
            </div>
        </form>
    </div>
</section>


<section class="container">
    <div class="container title text-center">
        <h2 class="position-relative d-inline-block">Result</h2>
    </div>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="alert alert-warning alert-dismissible fade show col-6 offset-3" role="alert" tabindex="-1">
                            ' . htmlspecialchars($message) . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
        }
    }
    ;
    ?>
    <div class="container row row-cols-2 row-cols-md-4 g-4 mt-3">
        <?php
        if (isset($_POST['search_btn'])) {
            $_SESSION['search_btn'] = $_POST['search_btn'];
            $_SESSION['search_btn'] = $_POST['search_box'];
        }
        if (isset($_SESSION['search_btn'])) {
            $search_box = $_SESSION['search_btn'];
            $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);

            $select_products = $pdo->prepare("SELECT products.*, category.name as category_name 
            FROM `products` 
             JOIN category ON products.category_id = category.id 
            WHERE products.name LIKE :search_box OR category.name LIKE :search_box");
            $select_products->execute(['search_box' => "%{$search_box}%"]);

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <form action="" method="POST" onsubmit="return addToWishllist();">
                        <div class="col">
                            <div class="card shadow rounded h-100">
                                <div class="collection-img position-relative">
                                    <img class="rounded-top p-0 card-img-top"
                                        src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="card-text text-capitalize text-truncate fw-bold">
                                                <?= htmlspecialchars($fetch_products['name']); ?>
                                            </p>
                                        </div>

                                        <div class="col-4 text-end"><button class="text-capitalize border-0 bg-white" type="submit"
                                                name="add_to_wishlist"><i
                                                    class="fa-regular fa-heart fa-lg text-dark heart"></i></button>
                                        </div>

                                    </div>

                                    <p class="text-truncate text-capitalize">
                                        <?= htmlspecialchars($fetch_products['details']); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold d-block h5">$
                                            <?= htmlspecialchars($fetch_products['price']); ?>
                                        </span>
                                        <div class="btn-group">
                                            <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>"
                                                class="btn btn-primary">View</a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                                    <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                                    <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                                    <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">

                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
                }
            } else {
                echo '<p class="empty">no result found!</p>';
            }
        }
        ;
        ?>
    </div>
    </div>
</section>



<?php
include_once __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>