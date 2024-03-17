<?php

include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
;

if (isset($_POST['update_password'])) {

   $name = $_POST['name'];
   $email = $_POST['email'];
   $update_password = $pdo->prepare("UPDATE `user` SET name = ?, email = ? WHERE id = ?");
   $update_password->execute([$name, $email, $user_id]);

   $update_pass = md5($_POST['update_pass']);
   $new_pass = md5($_POST['new_pass']);
   $confirm_pass = md5($_POST['confirm_pass']);

   if (!empty($update_pass) && !empty($new_pass) && !empty($confirm_pass)) {
      if ($update_pass != $fetch_profile['password']) {
         $message[] = 'Mật khẩu cũ không khớp!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } else {
         $update_pass_query = $pdo->prepare("UPDATE `user` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $user_id]);
         $message[] = 'Mật khẩu đã được cập nhật thành công!';
      }
   }

}
;

?>

<title>Change Password</title>
</head>

<body id="change_password">
   <section class="container my-5 pt-5">
      <div class="container title text-center my-5 pt-5">
         <h2 class="position-relative d-inline-block">Change Password</h2>
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
      <div class="mx-auto container">
         <div class="card col-md-6 offset-md-3 bg-transparent">
            <div class="card-body">
               <form id="product-form" action="" method="POST" enctype="multipart/form-data"
                  class="text_center form-horizontal">
                  <div class="form-group p-1">
                     <input class="form-control" type="text" name="name"
                        value="<?= htmlspecialchars($fetch_profile['name']); ?>" placeholder="update username" required
                        >
                  </div>

                  <div class="form-group p-1">
                     <input class="form-control" type="email" name="email"
                        value="<?= htmlspecialchars($fetch_profile['email']); ?>" placeholder="Update email" required
                        >
                  </div>

                  <div class="form-group p-1">
                     <input type="hidden" name="old_pass" value="<?= htmlspecialchars($fetch_profile['password']); ?>">
                     <input class="form-control" type="password" name="update_pass"
                        placeholder="Enter previous password" >
                  </div>

                  <div class="form-group p-1">
                     <input class="form-control" type="password" name="new_pass" placeholder="Enter new password"
                        >
                  </div>

                  <div class="form-group p-1">
                     <input class="form-control" type="password" name="confirm_pass" placeholder="Confirm new password"
                        >
                  </div>
                  <div class="form-group p-1">
                     <div class="flex-btn">
                        <input type="submit" class="btn w-100" value="Update Password" name="update_password">
                     </div>
                  </div>

                  <div class="form-group p-1">
                     <div class="flex-btn">
                        <a href="profile.php" class="btn w-100 text-decoration-none">View Profile</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>

   </section>

   <?php
   include_once __DIR__ . '../../partials/footer.php';