<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
}
;

$profile_query = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
$profile_query->execute([$admin_id]);
$fetch_profile = $profile_query->fetch(PDO::FETCH_ASSOC);
if (isset($_POST['update_password'])) {

   $name = $_POST['name'];
   $email = $_POST['email'];
   $update_password = $pdo->prepare("UPDATE `user` SET name = ?, email = ? WHERE id = ?");
   $update_password->execute([$name, $email, $admin_id]);

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
         $update_pass_query->execute([$confirm_pass, $admin_id]);
         $message[] = 'Mật khẩu đã được cập nhật thành công!';
         header('location:profile.php');
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
;
?>

<title>Change Password</title>
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
                  <h2 class="position-relative d-inline-block">Change Password</h2>

               </div>

               <div class="mx-auto container">
                  <div class="card shadow-sm col-md-10 offset-1 rounded-5">
                     <div class="card-body">

                        <form id="product-form" action="" method="POST" enctype="multipart/form-data"
                           class="text_center form-horizontal">
                           <div class="form-group">
                              <input class="form-control" type="text" name="name"
                                 value="<?= htmlspecialchars($fetch_profile['name']); ?>" placeholder="Update username"
                                 required >
                           </div>

                           <div class="form-group">
                              <input class="form-control" type="email" name="email"
                                 value="<?= htmlspecialchars($fetch_profile['email']); ?>" placeholder="Update email"
                                 required >
                           </div>

                           <div class="form-group">
                              <input type="hidden" name="old_pass"
                                 value="<?= htmlspecialchars($fetch_profile['password']); ?>">
                              <input class="form-control" type="password" name="update_pass"
                                 placeholder="Enter previous password" >
                           </div>

                           <div class="form-group">
                              <input class="form-control" type="password" name="new_pass"
                                 placeholder="Enter new password" >
                           </div>

                           <div class="form-group">
                              <input class="form-control" type="password" name="confirm_pass"
                                 placeholder="Confirm new password" >
                           </div>
                           <div class="form-group">
                              <div class="flex-btn">
                                 <input type="submit" class="btn w-100 btn-primary" value="Update Password" name="update_password">
                              </div>
                           </div>
                        </form>
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