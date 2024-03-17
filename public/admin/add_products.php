<?php
session_start();
include_once __DIR__ . "../../../partials/admin_boostrap.php";
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}
;

if (isset($_POST['add_product'])) {

    $name = $_POST['name'];    
    $price = $_POST['price'];    
    $category_id = $_POST['category_id'];    
    $quantity = $_POST['quantity'];  
    $details = $_POST['details'];    

    $image = $_FILES['image']['name'];    
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $select_products = $pdo->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        $select_category = $pdo->prepare("SELECT * FROM `category` WHERE id = ?");
        $select_category->execute([$category_id]);

        if ($select_category->rowCount() > 0) {
            $insert_products = $pdo->prepare("INSERT INTO `products`(name, category_id, quantity, details, price, image) VALUES(?,?,?,?,?,?)");
            $insert_products->execute([$name, $category_id, $quantity, $details, $price, $image]);

            if ($insert_products) {
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large!';
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'New product added!';
                }
            } else {
                $message[] = 'Failed to insert product into the database.';
            }
        } else {
            $message[] = 'Category does not exist!';
        }
    }
}
;

if (isset($message)) {
    foreach ($message as $message) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($message) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
    }
}
?>

<title>List Products</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Thêm sản phẩm</h1>
                        <a href="list_products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh sách sản phẩm</a>
                    </div>

                    <section class="">
                        <div class="container text-center">
                            <h2 class="position-relative d-inline-block">Thông tin sản phẩm</h2>
                            <!-- <hr class="mx-auto"> -->
                        </div>

                        <div class="mx-auto container">
                            <div class="card col-md-6 offset-md-3 shadow-sm">
                                <div class="card-body">
                                    <form id="product-form" action="" method="POST" enctype="multipart/form-data"
                                        class="text_center form-horizontal">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter product name" required>
                                        </div>
                                        <div class="form-group">
                                            <select name="category_id" class="form-control" required>
                                                <option value="" selected disabled>Select category</option>
                                                <?php
                                                $category = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($category as $category) {
                                                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="number" name="quantity" class="form-control"
                                                placeholder="Enter product quantity" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="price" class="form-control"
                                                placeholder="Enter product price" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="image" class="form-control" required
                                                accept="image/jpg, image/jpeg, image/png">
                                        </div>
                                        <div class="form-group">
                                            <textarea name="details" class="form-control"
                                                placeholder="Enter product details" cols="30" rows="5"
                                                required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                value="Add product" name="add_product">
                                        </div>
                                    </form>
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