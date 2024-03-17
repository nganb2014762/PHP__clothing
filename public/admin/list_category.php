<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
;

// Thêm danh mục sản phẩm
if (isset($_POST['add_category'])) {

    $name = $_POST['name'];
    $select_categorys = $pdo->prepare("SELECT * FROM `category` WHERE name = ?");
    $select_categorys->execute([$name]);

    if ($select_categorys->rowCount() > 0) {
        $message[] = 'category name already exist!';
    } else {

        $insert_categorys = $pdo->prepare("INSERT INTO `category`(name) VALUES(?)");
        $insert_categorys->execute([$name]);
        $message[] = "new category added!";
    }
}
;

// Xóa danh mục sản phẩm
$product_count = 0;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $check_product = $pdo->prepare("SELECT COUNT(*) FROM `products` WHERE category_id = ?");


    $check_product->execute([$delete_id]);

    $product_count = $check_product->fetchColumn();

    if ($product_count > 0) {
        // Nếu sản phẩm có mối quan hệ với các bản ghi trong các bảng, thông báo
        $message[] = 'Không thể xóa category vì nó đã có sản phẩm.';
    } else {
        // Nếu không có mối quan hệ, thực hiện xóa category
        $delete_category = $pdo->prepare("DELETE FROM `category` WHERE id = ?");
        $delete_category->execute([$delete_id]);

        // Điều hướng trở lại trang list_products.php sau khi xóa
        header('location:list_category.php');
    }
}

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
                    <div class="row mb-3 text-center">
                        <div class="col-md-6 themed-grid-col">
                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Danh mục sản phẩm</h1>
                            </div>

                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">STT</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Edit</th>
                                            <th scope="col">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <?php
                                        $i = 1;
                                        $show_category = $pdo->prepare("SELECT * FROM `category`");
                                        $show_category->execute();
                                        if ($show_category->rowCount() > 0) {
                                            while ($fetch_category = $show_category->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                        <tr>
                                            <td class="pt-4">
                                                <b>
                                                    <?= htmlspecialchars($i++); ?>
                                                </b>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?= htmlspecialchars($fetch_category['name']); ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <a class="btn btn-primary my-1 my-lg-0"
                                                    href="edit_category.php?update=<?= htmlspecialchars($fetch_category['id']); ?>"
                                                    class="option-btn">edit</a>
                                            </td>

                                            <td class="pt-4">
                                                <a class="btn btn-danger my-1 my-lg-0"
                                                    data-id="<?= htmlspecialchars($fetch_category['id']); ?>"
                                                    data-toggle="modal"
                                                    data-target="#deleteConfirmationModal">delete</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php
                                            }
                                        }
                                        ?>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6 themed-grid-col">
                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Thêm danh mục sản phẩm</h1>
                                <!-- <a href="list_products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh mục sản phẩm</a> -->
                            </div>

                            <section class="">
                                <div class="mx-auto container">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <form id="product-form" action="" method="POST"
                                                enctype="multipart/form-data" class="text_center form-horizontal">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Enter category name" required>
                                                </div>

                                                <div class="form-group">
                                                    <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                        value="Add category" name="add_category">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>



                </div>
                <!-- /.container-fluid -->
                <!-- Begin Page Content -->
                <div class="container-fluid">



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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa dòng này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <a id="deleteLink" href="" class="btn btn-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>


    <script>
    // JavaScript code to handle delete from modal
    $(document).ready(function() {
        $('#deleteConfirmationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var Id = button.data('id');

            // Set the delete button link with productId
            var deleteLink = 'list_category.php?delete=' + Id;
            $('#deleteLink').attr('href', deleteLink);
        });
    });
    </script>

    <?php

    include_once __DIR__ . '../../../partials/admin_footer.php';