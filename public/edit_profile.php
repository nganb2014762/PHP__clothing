<?php
include_once __DIR__ . '../../partials/boostrap.php';

include_once __DIR__ . '../../partials/header.php';

require_once __DIR__ . '../../partials/connect.php';

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}
;

if (isset($_POST['update_profile'])) {
    if (!empty($_POST['name']) || !empty($_POST['sex']) || !empty($_POST['born']) || !empty($_POST['email']) || !empty($_POST['phone']) || !empty($_POST['address'])) {

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
        $image_folder = 'admin/uploaded_img/user/' . $image;
        $old_image = $_POST['old_image'];

        $update_profile = $pdo->prepare("UPDATE user SET name = ?, sex = ?, born = ?, address = ?, phone = ?, email = ?  WHERE id = ?");
        $update_success = $update_profile->execute([$name, $sex, $born, $address, $phone, $email, $id]);

        if (!empty($image)) {
            if ($image_size > 2000000) {
                $message[] = 'image size is too large!';
            } else {
                $update_image = $pdo->prepare("UPDATE `user` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $id]);

                if ($update_image) {
                    if (!empty($old_image) && file_exists('admin/uploaded_img/user/' . $old_image)) {
                        if (!unlink('admin/uploaded_img/user/' . $old_image)) {
                            $message[] = 'Không thể xóa tập tin ảnh cũ.';
                        } else {
                            move_uploaded_file($image_tmp_name, $image_folder);
                            $message[] = 'Tập tin ảnh cũ đã được xóa thành công và tập tin mới đã được cập nhật.';
                        }
                    } else {
                        $message[] = 'Không tìm thấy tập tin ảnh cũ hoặc tập tin không tồn tại.';
                    }
                } else {
                    $message[] = 'Không thể cập nhật tập tin trong cơ sở dữ liệu.';
                }
            }
        }

        if ($update_success) {
            $message[] = 'Profile updated successfully!';
            header('location:profile.php');
        } else {
            $message[] = 'Profile update failed: ' . $pdo->errorInfo()[2];
        }
    } else {
        $message[] = 'All fields are required.';
    }
}
;

?>

<title>Edit Profile</title>
</head>

<section class="my-5 py-5">
    <div class="container title text-center mt-3 pt-5">
        <h2 class="position-relative d-inline-block">Edit Profile</h2>
        <hr class="mx-auto">
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
    <div class="mx-auto container my-5">
        <div class="card shadow-sm col-md-10 offset-1 rounded-5">
            <div class="card-body">

                <form id="profile-form" class="text_center form-horizontal" action="" method="post"
                    enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group d-flex">
                                <input class="form-control w-100" type="hidden" name="old_image"
                                    value="<?= htmlspecialchars($fetch_profile['image']); ?>">
                            </div>

                            <div class="form-group d-flex">
                                <input class="form-control" type="hidden" name="id"
                                    value="<?= htmlspecialchars($fetch_profile['id']); ?>">
                            </div>

                            <div class="form-group text-center">
                                <?php if ($fetch_profile['image'] == '') {
                                    echo '<img class="img-fluid" src="../img/account/user0.png" alt="" width="315px" height="315px" />';
                                } else {
                                    ?>
                                    <img class="img-fluid"
                                        src="admin/uploaded_img/user/<?= htmlspecialchars($fetch_profile['image']); ?>"
                                        alt="" width="315px" height="315px" />
                                    <?php
                                }
                                ?>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Avatar</div>
                                <div class="col-md-9 col-10">
                                    <input class="form-control" type="file" name="image"
                                        accept="image/jpg, image/jpeg, image/png">
                                </div>
                            </div>

                            <div class="form-group p-2">
                                <div class="flex-btn">
                                    <a href="profile.php" class="btn text-decoration-none w-100">View Profile</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 mt-3">
                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Name</div>
                                <div class="col-md-9 col-10">
                                    <input type="text" class="form-control" name="name" placeholder="Update name"
                                        required value="<?= htmlspecialchars($fetch_profile['name']); ?>">
                                </div>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Sex</div>
                                <div class="col-md-9 col-10">
                                    <select name="sex" class="form-control" required>
                                        <option value="" selected disabled>Select Gender </option>
                                        <?php
                                        $sex = htmlspecialchars($fetch_profile['sex']);
                                        $options = array('1' => 'male', '0' => "female");

                                        foreach ($options as $value => $label) {
                                            $selected = ($sex === $value) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($value) . "' $selected>" . ucfirst($label) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Born</div>
                                <div class="col-md-9 col-10">
                                    <input class="form-control" type="date" name="born" placeholder="Update born"
                                        required value="<?= htmlspecialchars($fetch_profile['born']); ?>">
                                </div>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Address</div>
                                <div class="col-md-9 col-10">
                                    <textarea class="form-control" name="address" required placeholder="Update address"
                                        cols="30"
                                        rows="2"><?= htmlspecialchars($fetch_profile['address']); ?></textarea>
                                </div>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Phone</div>
                                <div class="col-md-9 col-10">
                                    <input class="form-control" type="tel" name="phone" placeholder="Update phone"
                                        required value="<?= htmlspecialchars($fetch_profile['phone']); ?>">
                                </div>
                            </div>

                            <div class="form-group d-flex p-2">
                                <div class="col-md-3 col-12 p-2 fw-bold">Email</div>
                                <div class="col-md-9 col-10">
                                    <input class="form-control" type="email" name="email" placeholder="Update email"
                                        required value="<?= htmlspecialchars($fetch_profile['email']); ?>">
                                </div>
                            </div>

                            <div class="form-group p-2">
                                <div class="flex-btn">
                                    <input type="submit" class="btn  btn-primary shadow-sm w-100" value="Update profile"
                                        name="update_profile">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

<?php
include_once __DIR__ . '../../partials/footer.php';
?>
</body>

</html>