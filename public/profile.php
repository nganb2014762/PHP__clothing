<?php

include_once __DIR__ . '../../partials/boostrap.php';

include_once __DIR__ . '../../partials/header.php';

require_once __DIR__ . '../../partials/connect.php';

$message = [];


?>

<title>My Account</title>
</head>

<section class="my-5 py-5 ">
    <div class="container title text-center mt-3 pt-5">
        <h2 class="position-relative d-inline-block">My Account</h2>
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
                                    src="admin/uploaded_img/user/<?= htmlspecialchars($fetch_profile['image']); ?>" alt=""
                                    width="315px" height="315px" />
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-7 mt-3">
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Name</div>
                            <div class="col-md-9 col-10">
                                <input type="text" class="form-control" name="name" disabled
                                    value="<?= htmlspecialchars($fetch_profile['name']); ?>">
                            </div>
                        </div>
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Sex</div>
                            <div class="col-md-9 col-10">
                                <input type="text" class="form-control" name="sex" disabled
                                    value="<?= htmlspecialchars($fetch_profile['sex']) == '0' ? 'Female' : 'Male' ?>">
                            </div>
                        </div>
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Born</div>
                            <div class="col-md-9 col-10">
                                <input class="form-control" type="text" name="born" disabled
                                    value="<?= htmlspecialchars($fetch_profile['born']); ?>">
                            </div>
                        </div>
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Address</div>
                            <div class="col-md-9 col-10">
                                <textarea class="form-control" name="address" disabled cols="30"
                                    rows="1"><?= htmlspecialchars($fetch_profile['address']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Phone</div>
                            <div class="col-md-9 col-10">
                                <input class="form-control" type="tel" name="phone" disabled
                                    value="<?= htmlspecialchars($fetch_profile['phone']); ?>">
                            </div>
                        </div>
                        <div class="form-group d-flex p-2">
                            <div class="col-md-3 col-12 p-2 fw-bold">Email</div>
                            <div class="col-md-9 col-10">
                                <input class="form-control" type="email" name="email" disabled
                                    value="<?= htmlspecialchars($fetch_profile['email']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group p-2">
                        <div class="flex-btn">
                            <a href="edit_profile.php" class="btn btn-primary shadow-sm w-100">Change Information</a>
                        </div>
                    </div>
                    <div class="form-group p-2">
                        <div class="flex-btn">
                            <a href="change_password.php" class="btn btn-primary shadow-sm w-100">Change
                                Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
include_once __DIR__ . '/../partials/footer.php';