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
    $delete_users = $pdo->prepare("DELETE FROM `user` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    header('location:list_staffs.php');
}
?>


<title>List Staffs</title>
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
                    <h1 class="h3 mb-4 text-gray-800">Danh sách tài khoản nhân viên</h1>

                    <!-- Success mess -->
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="mb-4 text-success" id="flash-message">
                            <?= $_SESSION['flash_message'] ?>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>

                    <!-- False mess -->
                    <?php if (isset($_SESSION['false_message'])): ?>
                        <div class="mb-4 text-danger" id="false-message">
                            <?= $_SESSION['false_message'] ?>
                        </div>
                        <?php unset($_SESSION['false_message']); ?>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Sex</th>
                                    <th scope="col">Born</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody class="table-group-divider">
                                <?php
                                $i = 1;
                                $select_users = $pdo->prepare("SELECT * FROM `user` WHERE role = '1' ");
                                $select_users->execute();
                                while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <div style="<?php if ($fetch_users['id'] == $admin_id) {
                                        echo 'display:none';
                                    }
                                    ;
                                    ?>">
                                        <tr>
                                            <td class="pt-4">
                                                <b>
                                                    <?= $i++; ?>
                                                </b>
                                            </td>

                                            <td>
                                                <?php if ($fetch_users['image'] == '') {
                                                    echo '<div class="col-2"><img src="../img/account/user0.png" width="70" height="70" ></div>';
                                                } else {
                                                    ?>
                                                    <div class="col-2"><img
                                                            src="uploaded_img/staff/<?= htmlspecialchars($fetch_users['image']); ?>"
                                                            width="70" height="70"></div>
                                                    <?php
                                                }
                                                ?>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?= htmlspecialchars($fetch_users['name']); ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?php if ($fetch_users['sex'] == '0') {
                                                        echo htmlspecialchars('female');
                                                    } else {
                                                        echo htmlspecialchars('male');
                                                    }
                                                    ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?= $fetch_users['born']; ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?php if ($fetch_users['address'] == '') {
                                                        echo htmlspecialchars('No address');
                                                    }
                                                    ;
                                                    ?>
                                                    <?= htmlspecialchars($fetch_users['address']); ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?= htmlspecialchars($fetch_users['phone']); ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?= htmlspecialchars($fetch_users['email']); ?>
                                                </span>
                                            </td>

                                            <td class="pt-4">
                                                <span>
                                                    <?php if ($fetch_users['role'] == '1') {
                                                        echo htmlspecialchars('admin');
                                                    } else {
                                                        echo htmlspecialchars('user');
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td class="pt-4">
                                                <a class="btn btn-primary my-1 my-lg-0"
                                                    href="edit_staffs.php?update=<?= htmlspecialchars($fetch_users['id']); ?>"
                                                    class="option-btn">Edit</a>
                                            </td>
                                            <td class="pt-4">
                                                <a class="btn btn-danger my-1 my-lg-0"
                                                    data-id="<?= htmlspecialchars($fetch_users['id']); ?>"
                                                    data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a>
                                            </td>
                                        </tr>
                                </tbody>
                                <?php
                                }
                                ?>
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
        $(document).ready(function () {
            $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var Id = button.data('id');

                // Set the delete button link with productId
                var deleteLink = 'list_customers.php?delete=' + Id;
                $('#deleteLink').attr('href', deleteLink);
            });
        });
    </script>

    <?php
    include_once __DIR__ . '../../../partials/admin_footer.php';