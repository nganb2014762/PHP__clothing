<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
;
// Sửa danh mục sản phẩm
if (isset($_POST['update_category'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $update_category = $pdo->prepare("UPDATE `category` SET name = ? WHERE id = ?");
    $update_category->execute([$name, $id]);
    $message[] = 'category updated successfully!';
    header('location:list_category.php');
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

<title>Edit Category</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Cập nhật danh mục sản phẩm</h1>
                        <!-- <a href="list_products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh mục sản phẩm</a> -->
                    </div>

                    <section class="">
                        <div class="mx-auto container">
                            <div class="card col-md-6 offset-md-3 shadow-sm">
                                <div class="card-body">
                                    <?php
                                    $update_id = $_GET['update'];
                                    $select_category = $pdo->prepare("SELECT * FROM `category` WHERE id = ?");
                                    $select_category->execute([$update_id]);
                                    if ($select_category->rowCount() > 0) {
                                        while ($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                    <form id="product-form" action="" method="POST" enctype="multipart/form-data"
                                        class="text_center form-horizontal">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="id"
                                                value="<?= htmlspecialchars($fetch_category['id']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Enter category name"
                                                value="<?= htmlspecialchars($fetch_category['name']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                value="Update category" name="update_category">
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

    <!-- Delete Modal -->
    <!-- <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
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
        $(document).ready(function () {
            $('button.btn-danger').on('click', function () {
                var id = $(this).data('id');
                var deleteLink = '/deleteticket/' + id;
                $('#deleteLink').attr('href', deleteLink);
            });
        });
    </script> -->
    <?php
    include_once __DIR__ . '../../../partials/admin_footer.php';