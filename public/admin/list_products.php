<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    try {
        // Kiểm tra sản phẩm có tồn tại không và lấy hình ảnh của sản phẩm
        $select_delete_image = $pdo->prepare("SELECT image FROM `products` WHERE id = ?");
        $select_delete_image->execute([$delete_id]);
        $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);

        if ($fetch_delete_image) {
            // Tìm tất cả các ID đơn đặt hàng liên quan đến sản phẩm
            $select_order_ids = $pdo->prepare("SELECT DISTINCT orders.id FROM `orders`
                                                INNER JOIN orders_details ON orders.id = orders_details.order_id
                                                WHERE orders_details.pid = ?");
            $select_order_ids->execute([$delete_id]);
            $order_ids = $select_order_ids->fetchAll(PDO::FETCH_COLUMN);

            // Xóa các đơn đặt hàng liên quan trong bảng orders
            if (!empty($order_ids)) {
                $delete_orders = $pdo->prepare("DELETE FROM `orders` WHERE id IN (" . implode(',', $order_ids) . ")");
                $delete_orders->execute();
            }

            // Xóa các bản ghi liên quan trong bảng orders_details
            $delete_orders_details = $pdo->prepare("DELETE FROM `orders_details` WHERE pid = ?");
            $delete_orders_details->execute([$delete_id]);

            // Xóa hình ảnh của sản phẩm
            unlink('uploaded_img/' . $fetch_delete_image['image']);

            // Xóa sản phẩm
            $delete_products = $pdo->prepare("DELETE FROM `products` WHERE id = ?");
            $delete_products->execute([$delete_id]);

            header('location:list_products.php');
        }
    } catch (Exception $e) {
        $message[] = $e->getMessage();
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
                        <h1 class="h3 mb-0 text-gray-800">Danh sách sản phẩm</h1>
                        <a href="add_products.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-solid fa-plus fa-sm text-white-50"></i> Thêm sản phẩm</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Details</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php
                                $i = 1;
                                $show_products = $pdo->prepare("SELECT * FROM `products`");
                                $show_products->execute();
                                if ($show_products->rowCount() > 0) {
                                    while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <tr>
                                            <td class="pt-4">
                                                <b>
                                                    <?= htmlspecialchars($i++); ?>
                                                </b>
                                            </td>

                                            <td>
                                                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                    alt="" style="width:100px; height:120px" />
                                            </td>

                                            <td class="pt-4">
                                                <?= htmlspecialchars($fetch_products['name']); ?>
                                            </td>
                                            <td class="pt-4">
                                                <?php
                                                $category_id = $fetch_products['category_id'];
                                                $category_name = '';

                                                // Thực hiện truy vấn SQL để lấy tên category
                                                $select_category = $pdo->prepare("SELECT name FROM category WHERE id = ?");
                                                $select_category->execute([$category_id]);

                                                if ($select_category->rowCount() > 0) {
                                                    $category = $select_category->fetch(PDO::FETCH_ASSOC);
                                                    $category_name = $category['name'];
                                                }
                                                ?>
                                                <?= htmlspecialchars($category_name); ?>

                                            </td>
                                            <td class="pt-4">
                                                <?= htmlspecialchars($fetch_products['quantity']); ?>
                                            </td>

                                            <td class="pt-4">
                                                <?= htmlspecialchars($fetch_products['price']); ?>$
                                            </td>

                                            <td class="pt-4">
                                                <?= htmlspecialchars($fetch_products['details']); ?>
                                            </td>

                                            <td class="pt-4">
                                                <a class="btn btn-primary my-1 my-lg-0"
                                                    href="edit_products.php?update=<?= htmlspecialchars($fetch_products['id']); ?>"
                                                    class="option-btn">Edit</a>
                                            </td>

                                            <td class="pt-4">
                                                <a class="btn btn-danger my-1 my-lg-0"
                                                    data-id="<?= htmlspecialchars($fetch_products['id']); ?>"
                                                    data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='15'>Không có dữ liệu.</td></tr>";
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>


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
        $(document).ready(function () {
            $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var Id = button.data('id');

                // Set the delete button link with productId
                var deleteLink = 'list_products.php?delete=' + Id;
                $('#deleteLink').attr('href', deleteLink);
            });
        });
    </script>

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
