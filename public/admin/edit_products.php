<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;
if (isset($_POST['update_product'])) {

    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $details = $_POST['details'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $old_image = $_POST['old_image'];

    $update_product = $pdo->prepare("UPDATE `products` SET name = ?, quantity = ?, category_id = ?, details = ?, price = ? WHERE id = ?");
    $update_product->execute([$name, $quantity, $category_id, $details, $price, $pid]);

    $message[] = 'product updated successfully!';
    header('location:list_products.php');


    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'image size is too large!';
        } else {

            $update_image = $pdo->prepare("UPDATE `products` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $pid]);

            if ($update_image) {
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('uploaded_img/' . $old_image);
                $message[] = 'image updated successfully!';
            }
        }
    }
}
;

if (isset($message)) {
    foreach ($message as $message) {
        // echo '<script>alert(" ' . $message . ' ");</script>';
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($message) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
    }
}
?>

<title>Edit Products</title>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include_once __DIR__ . "../../../partials/admin_header_column.php";
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include_once __DIR__ . "../../../partials/admin_header.php";
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Cập nhật sản phẩm</h1>
                        <a href="list_products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh sách sản phẩm</a>
                    </div>

                    <section class="">
                        <div class="container text-center">
                            <h2 class="position-relative d-inline-block">Thông tin sản phẩm</h2>
                            <!-- <hr class="mx-auto"> -->
                        </div>

                        <div class="mx-auto container">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <?php
                                    $update_id = $_GET['update'];
                                    $select_products = $pdo->prepare("SELECT * FROM `products` WHERE id = ?");
                                    $select_products->execute([$update_id]);
                                    if ($select_products->rowCount() > 0) {
                                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                                            ?>

                                            <form id="product-form" class="text_center form-horizontal" action="" method="post"
                                                enctype="multipart/form-data">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input class="form-control w-100" type="hidden" name="old_image"
                                                                value="<?= htmlspecialchars($fetch_products['image']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="hidden" name="pid"
                                                                value="<?= htmlspecialchars($fetch_products['id']); ?>">
                                                        </div>

                                                        <div class="form-group text-center">
                                                            <img class="img-fluid" src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="" />
                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="name"
                                                                placeholder="Enter product name" required
                                                                value="<?= htmlspecialchars($fetch_products['name']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="number" name="quantity"
                                                                placeholder="Enter product quantity" required
                                                                value="<?= htmlspecialchars($fetch_products['quantity']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="text" name="price"
                                                                placeholder="Enter product price" required
                                                                value="<?= htmlspecialchars($fetch_products['price']); ?>">
                                                        </div>
                                                        <?php
                                                        $category = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
                                                        ?>
                                                        <div class="form-group">
                                                            <select name="category_id" class="form-control" required>
                                                                <option value="" selected disabled>Select category</option>
                                                                <?php
                                                                $selected_category = $fetch_products['category_id']; // Lấy danh mục hiện tại của sản phẩm
                                                                foreach ($category as $category) {
                                                                    $selected = ($category['id'] == $selected_category) ? 'selected' : ''; // Kiểm tra xem danh mục có phải là danh mục hiện tại không
                                                                    echo "<option value='" . htmlspecialchars($category['id']) . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <textarea class="form-control" name="details" required
                                                                placeholder="Enter product details" cols="30"
                                                                rows="5"><?=  htmlspecialchars($fetch_products['details']); ?></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="file" name="image"
                                                                accept="image/jpg, image/jpeg, image/png">
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="flex-btn">
                                                                <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                                    value="Update product" name="update_product">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <?php
    include_once __DIR__ . '../../../partials/admin_footer.php';