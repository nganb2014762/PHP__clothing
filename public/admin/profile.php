<?php
session_start();
include_once __DIR__ . "../../../partials/admin_boostrap.php";

require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
;

$message = [];
if (isset($message)) {
    foreach ($message as $message) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($message) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
    }
}
?>

<title>Profile</title>
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

                    <div class="container title text-center ">
                        <h2 class="position-relative d-inline-block">My Account</h2>
                    </div>
                    <div class="mx-auto container">
                        <div class="card col-md-10 offset-1 shadow-sm rounded-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input class="form-control" type="hidden" name="id"
                                                value="<?= htmlspecialchars($fetch_profile['id']); ?>">
                                        </div>

                                        <div class="form-group text-center">
                                            <?php if ($fetch_profile['image'] == '') {
                                                echo '<img class="img-fluid" src="../img/account/user0.png" alt="" width="315px" height="315px" />';
                                            } else {
                                                ?>
                                                <img class="img-fluid"
                                                    src="uploaded_img/staff/<?= htmlspecialchars($fetch_profile['image']); ?>"
                                                    alt="" width="315px" height="315px" />
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-7 pt-3">
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Name</div>
                                            <div class="col-md-9 col-10">
                                                <input type="text" class="form-control" name="name" disabled
                                                    value="<?= htmlspecialchars($fetch_profile['name']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Sex</div>
                                            <div class="col-md-9 col-10">
                                                <input type="text" class="form-control" name="sex" disabled
                                                    value="<?= htmlspecialchars($fetch_profile['sex']) == '0' ? 'Female' : 'Male' ?>">
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Born</div>
                                            <div class="col-md-9 col-10">
                                                <input class="form-control" type="text" name="born" disabled
                                                    value="<?= htmlspecialchars($fetch_profile['born']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Address</div>
                                            <div class="col-md-9 col-10">
                                                <textarea class="form-control" name="address" disabled cols="30"
                                                    rows="1"><?= htmlspecialchars($fetch_profile['address']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Phone</div>
                                            <div class="col-md-9 col-10">
                                                <input class="form-control" type="tel" name="phone" disabled
                                                    value="<?= htmlspecialchars($fetch_profile['phone']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group d-flex">
                                            <div class="col-md-3 col-12 p-2 fw-bold">Email</div>
                                            <div class="col-md-9 col-10">
                                                <input class="form-control" type="email" name="email" disabled
                                                    value="<?= htmlspecialchars($fetch_profile['email']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="flex-btn">
                                            <a href="edit_profile.php" class="btn btn-primary shadow-sm w-100">Change
                                                Information</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="flex-btn">
                                            <a href="change_password.php" class="btn btn-primary shadow-sm w-100">Change
                                                Password</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    </div>
    <!-- End of Content Wrapper -->

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
