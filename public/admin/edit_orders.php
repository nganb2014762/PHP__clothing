<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit(); // Đảm bảo ngừng việc thực thi mã ngay sau lệnh header
}

if (isset($_POST['update_order'])) {
    $update_id = $_POST['update_id'];
    $check_date = $_POST['check_date'];
    $cancel_date = $_POST['cancel_date'];
    $received_date = $_POST['received_date'];
    if(isset($_POST['payment_status'])) {
        $payment_status = $_POST['payment_status'];
    } else {
        $payment_status = 'pending';
    }
    
    $update_order = $pdo->prepare("UPDATE orders SET check_date = :check_date, cancel_date = :cancel_date, received_date = :received_date, payment_status = :payment_status WHERE id = :update_id");

    $update_order->bindParam(':check_date', $check_date);
    $update_order->bindParam(':cancel_date', $cancel_date);
    $update_order->bindParam(':received_date', $received_date);
    $update_order->bindParam(':update_id', $update_id);
    $update_order->bindParam(':payment_status', $payment_status);
    $update_order->execute();
    header('location: list_orders.php');
    exit();
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
;
?>

<title>Edit Orders</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Cập nhật trạng thái đơn hàng</h1>
                        <a href="list_orders.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh sách đơn hàng</a>
                    </div>

                    <section class="">
                        <div class="mx-auto container">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <?php
                                    $update_id = $_GET['update'];
                                    $select_orders = $pdo->prepare("SELECT * FROM `orders` WHERE id = ?");
                                    $select_orders->execute([$update_id]);
                                    if ($select_orders->rowCount() > 0) {
                                        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <form id="order-form" class="text_center form-horizontal" action="" method="post"
                                        enctype="multipart/form-data">

                                        <div class="form-group">
                                            <input class="form-control" type="hidden" name="update_id"
                                            value=<?= htmlspecialchars($fetch_orders['id']); ?>>
                                        </div>
                                        <div class="form-group">
                                                    <select name="payment_status" class="form-control" required>
                                                        <option selected disabled>
                                                            <?= htmlspecialchars($fetch_orders['payment_status']); ?>
                                                        </option>
                                                        <option value="pending">pending</option>
                                                        <option value="cancel">cancel</option>
                                                        <option value="checked">checked</option>
                                                        <option value="transport">transport</option>
                                                        <option value="completed">completed</option>
                                                    </select>
                                                </div>

                                        <div class="form-group">
                                            <label for="check_date">Check Date:</label>
                                            <input type="date" class="form-control" id="check_date" name="check_date"
                                                value="<?= htmlspecialchars( $fetch_orders['check_date']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="cancel_date">Cancel Date:</label>
                                            <input type="date" class="form-control" id="cancel_date" name="cancel_date"
                                                value="<?= htmlspecialchars($fetch_orders['cancel_date']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="received_date_date">Received Date:</label>
                                            <input type="date" class="form-control" id="received_date_date" name="received_date"
                                                value="<?= htmlspecialchars($fetch_orders['received_date']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <div class="flex-btn">
                                                <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                    value="Update" name="update_order">
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