<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}
;
if (isset($_POST['update_staff'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $born = $_POST['born'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/staff/' . $image;
    $old_image = $_POST['old_image'];

    $update_customer = $pdo->prepare("UPDATE `user` SET name = ?, sex = ?, born = ?, address = ?, phone = ?, email = ? WHERE id = ?");
    $update_customer->execute([$name, $sex, $born, $address, $phone, $email, $id]);

    $message[] = 'staffs updated successfully!';
    header('location:list_staffs.php');


    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'image size is too large!';
        } else {

            $update_image = $pdo->prepare("UPDATE `user` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $id]);

            if ($update_image) {
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('uploaded_img/staff/' . $old_image);
                $message[] = 'image updated successfully!';
            }
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

<title>Edit staffs</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Cập nhật thông tin nhân viên</h1>
                        <a href="list_staffs.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-solid fa-list fa-sm text-white-50"></i> Danh sách nhân viên</a>
                    </div>

                    <section class="">
                        <div class="container text-center">
                            <h2 class="position-relative d-inline-block">Thông tin nhân viên</h2>
                        </div>

                        <div class="mx-auto container">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <?php
                                    $update_id = $_GET['update'];
                                    $select_staffs = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
                                    $select_staffs->execute([$update_id]);
                                    if ($select_staffs->rowCount() > 0) {
                                        while ($fetch_staffs = $select_staffs->fetch(PDO::FETCH_ASSOC)) {
                                            ?>

                                            <form id="customer-form" class="text_center form-horizontal" action="" method="post"
                                                enctype="multipart/form-data">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input class="form-control w-100" type="hidden" name="old_image"
                                                                value="<?= htmlspecialchars($fetch_staffs['image']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="hidden" name="id"
                                                                value="<?= htmlspecialchars($fetch_staffs['id']); ?>">
                                                        </div>

                                                        <div class="form-group text-center">
                                                            <?php if ($fetch_staffs['image'] == '') {
                                                                echo '<img class="img-fluid" src="../img/account/user0.png" alt="" width="315px" height="315px" />';
                                                            } else {
                                                                ?>
                                                                <img class="img-fluid"
                                                                    src="uploaded_img/staff/<?= htmlspecialchars($fetch_staffs['image']); ?>"
                                                                    alt="" width="315px" height="315px" />
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="file" name="image"
                                                                accept="image/jpg, image/jpeg, image/png">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="name"
                                                                placeholder="Enter customer name" required
                                                                value="<?= htmlspecialchars($fetch_staffs['name']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <select name="sex" class="form-control" required>
                                                                <option value="" selected disabled>Select Gender

                                                                </option>
                                                                <?php
                                                                $sex = htmlspecialchars($fetch_staffs['sex']);
                                                                $options = array('1' => 'male', '0' => "female");

                                                                foreach ($options as $value => $label) {
                                                                    $selected = ($sex === $value) ? 'selected' : '';
                                                                    echo "<option value='" . htmlspecialchars($value) . "' $selected>" . ucfirst($label) . "</option>";
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="date" name="born"
                                                                placeholder="Enter customer born" required
                                                                value="<?= htmlspecialchars($fetch_staffs['born']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <textarea class="form-control" name="address" required
                                                                placeholder="Enter customer address" cols="30"
                                                                rows="2"><?= htmlspecialchars($fetch_staffs['address']); ?></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="tel" name="phone"
                                                                placeholder="Enter customer phone" required
                                                                value="<?= htmlspecialchars($fetch_staffs['phone']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <input class="form-control" type="email" name="email"
                                                                placeholder="Enter customer email" required
                                                                value="<?= htmlspecialchars($fetch_staffs['email']); ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="flex-btn">
                                                                <input type="submit" class="btn w-100 btn-primary shadow-sm"
                                                                    value="Update staff" name="update_staff">
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