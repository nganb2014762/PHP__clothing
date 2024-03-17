<?php
include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

if (isset($_POST['delete_wishlist'])) {
    $user_id = $_SESSION['user_id'];
    $id = $_POST['id'];
    $check_wishlist_numbers = $pdo->prepare("DELETE FROM `wishlist` WHERE id = :id AND user_id = :user_id");
    $check_wishlist_numbers->execute([':id' => $id, ':user_id' => $user_id]);
    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'Delete successfully!!';
    }
}
;
?>

<title>Favorite list</title>
</head>

<body>
    <!-- shop -->
    <section id="collection" class="pt-5">
        <div class="container">
            <div class="title text-center mt-5 pt-5">
                <h2 class="position-relative d-inline-block">My Favorite List</h2>
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
            <div class="row g-0 container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mt-3">
                    <?php
                    $select_products = $pdo->prepare("SELECT * FROM `wishlist` WHERE user_id = :user_id");
                    $select_products->execute([':user_id' => $_SESSION['user_id']]);
                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <form action="" method="POST" onsubmit="return addToWishlist();">
                                <div class="col">
                                    <div class="card shadow rounded h-100">
                                        <div class="collection-img position-relative">
                                            <img class="rounded-top p-0 card-img-top"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="">
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-8">
                                                    <p class="card-text text-capitalize text-truncate fw-bold">
                                                        <?= htmlspecialchars($fetch_products['name']); ?>
                                                    </p>
                                                </div>

                                                <div class="col-4 text-end">
                                                    <button class="text-capitalize border-0 bg-white" type="submit"
                                                        name="delete_wishlist">
                                                        <i class="fa-solid fa-heart fa-lg text-danger heart"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold d-block h5">$
                                                    <?= htmlspecialchars($fetch_products['price']); ?>
                                                </span>
                                                <div class="btn-group">
                                                    <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['pid']); ?>"
                                                        class="btn btn-primary">View</a>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id"
                                                value="<?= htmlspecialchars($fetch_products['id']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="container text-center pt-3">
                            <h6 class="position-relative d-inline-block">No item found </h6>
                            <div>
                                <a type="submit" class="buy-btn text-capitalize text-decoration-none mt-3" name="shop now"
                                    href="shop.php">Shop now</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!-- end of shop -->

    <script>
        function addToWishlist() {
            var loggedIn = <?= htmlspecialchars(isset($_SESSION['user_id']) ? 'true' : 'false'); ?>;

            if (!loggedIn) {
                alert('You need to log in to add products to your wishlist.');
                window.location.href = 'login.php';
                return false;
            }
            return true;
        }
    </script>
    <?php include_once __DIR__ . '/../partials/footer.php';
    ?>
</body>

</html>