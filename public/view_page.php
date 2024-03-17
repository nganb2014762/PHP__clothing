<?php
ob_start();
include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

if (isset($_POST['add_to_wishlist'])) {
   $pid = $_POST['pid'];
   $p_name = $_POST['p_name'];
   $p_price = $_POST['p_price'];
   $p_image = $_POST['p_image'];

   $check_wishlist_numbers = $pdo->prepare("SELECT * FROM `wishlist` WHERE name = :p_name AND user_id = :user_id");
   $check_wishlist_numbers->execute([':p_name' => $p_name, ':user_id' => $user_id]);
   if ($check_wishlist_numbers->rowCount() > 0) {
      $message[] = 'already added to wishlist!';
   } else {
      $insert_wishlist = $pdo->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }
}
;

if (isset($_POST['add_to_cart'])) {
   $pid = $_POST['pid'];
   $p_name = $_POST['p_name'];
   $p_price = $_POST['p_price'];
   $p_image = $_POST['p_image'];
   $p_qty = $_POST['p_qty'];

   $check_cart_numbers = $pdo->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if ($check_cart_numbers->rowCount() > 0) {
      $update_qty = $pdo->prepare("UPDATE `cart` SET quantity = quantity + ? WHERE name = ? AND user_id = ?");
      $update_qty->execute([$p_qty, $p_name, $user_id]);
      $message[] = 'Quantity updated in cart!';
   } else {
      $insert_cart = $pdo->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }
}
;


if (isset($_POST['send'])) {
   if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      $comment = $_POST['comment'];
      $pid = $_GET['pid'];
      $image = null;

      if (isset($_GET['pid'])) {
         $pid = $_GET['pid'];
         try {
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
               $image_name = $_FILES['image']['name'];
               $temp_image_path = $_FILES['image']['tmp_name'];
               $uploads_directory = 'admin/uploaded_img/reviews/';

               $image = $uploads_directory . $image_name;

               move_uploaded_file($temp_image_path, $image);

               $insert_comment = $pdo->prepare("INSERT INTO `reviews` (user_id, pid, comment, image) VALUES (?, ?, ?, ?)");
               $insert_comment->execute([$user_id, $pid, $comment, $image_name]);
            } else {
               $insert_comment = $pdo->prepare("INSERT INTO `reviews` (user_id, pid, comment) VALUES (?, ?, ?)");
               $insert_comment->execute([$user_id, $pid, $comment]);
            }


            header('Location:view_page.php?pid=' . $pid);
            exit();
         } catch (PDOException $e) {
            $message[] = "Lỗi khi thực hiện truy vấn: " . $e->getMessage();
         }
      } else {
         $message[] = "Không tìm thấy sản phẩm!";
      }
   } else {
      $_SESSION['comment'] = 'Bạn cần đăng nhập để đánh giá sản phẩm.';
   }
}
;
?>

<title>Features Product</title>
</head>

<body>
   <!-- quick-view -->
   <section id="quick-view" class="pt-5">
      <div class="container">
         <div class="title text-center mt-5 pt-5">
            <h2 class="position-relative d-inline-block">Features Product</h2>
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
         <div class="container align-text-center">
            <div class="row">
               <?php
               $pid = $_GET['pid'];
               $select_products = $pdo->prepare("SELECT products.*, category.name as category_name FROM `products` 
               JOIN category ON products.category_id = category.id WHERE products.id = ?");
               $select_products->execute([$pid]);
               if ($select_products->rowCount() > 0) {
                  while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                     ?>
                     <div class="col-lg-4 mt-5 offset-1">
                        <div class="card mb-3">
                           <img class="card-img img-fluid"
                              src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="Card image cap"
                              id="product-detail">
                        </div>
                        <div class="row">
                           <!--Start Controls-->
                           <div class="col-1 align-self-center">
                              <a href="#multi-item-example" role="button" data-bs-slide="prev">
                                 <i class="text-dark fas fa-chevron-left"></i>
                                 <span class="sr-only">Previous</span>
                              </a>
                           </div>
                           <!--End Controls-->
                           <!--Start Carousel Wrapper-->
                           <div id="multi-item-example" class="col-10 carousel slide carousel-multi-item pointer-event"
                              data-bs-ride="carousel">
                              <!--Start Slides-->
                              <div class="carousel-inner product-links-wap" role="listbox">

                                 <!--First slide-->
                                 <div class="carousel-item active">
                                    <div class="row">
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 1">
                                          </a>
                                       </div>
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 2">
                                          </a>
                                       </div>
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 3">
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                                 <!--/.First slide-->

                                 <!--Second slide-->
                                 <div class="carousel-item">
                                    <div class="row">
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 4">
                                          </a>
                                       </div>
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 5">
                                          </a>
                                       </div>
                                       <div class="col-4">
                                          <a href="#">
                                             <img class="card-img img-fluid"
                                                src="admin/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>"
                                                alt="Product Image 6">
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                                 <!--/.Second slide-->
                              </div>
                              <!--End Slides-->
                           </div>
                           <!--End Carousel Wrapper-->
                           <!--Start Controls-->
                           <div class="col-1 align-self-center">
                              <a href="#multi-item-example" role="button" data-bs-slide="next">
                                 <i class="text-dark fas fa-chevron-right"></i>
                                 <span class="sr-only">Next</span>
                              </a>
                           </div>
                           <!--End Controls-->
                        </div>
                     </div>
                     <!-- col end -->
                     <div class="col-lg-6 mt-5">
                        <div class="card">
                           <div class="card-body">
                              <form action="" method="POST" onsubmit="return addToCart();" onsubmit="return addToWishlist();">
                                 <div class="row">
                                    <div class="col-11">
                                       <h2 class="card-text text-capitalize fw-bold">
                                          <?= htmlspecialchars($fetch_products['name']); ?>
                                       </h2>
                                    </div>
                                    <div class="col-1 text-end">
                                       <button class="text-capitalize border-0 bg-white" type="submit"
                                          name="add_to_wishlist"><i
                                             class="fa-regular fa-heart fa-lg text-dark heart"></i></button>
                                    </div>
                                 </div>
                                 <p class="h3">$
                                    <?= htmlspecialchars($fetch_products['price']); ?>
                                 </p>
                                 <p class="">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <span class="list-inline-item text-dark">Rating 4.8 | 36 Comments</span>
                                 </p>
                                 <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                       <h6>Category:</h6>
                                    </li>
                                    <li class="list-inline-item">
                                       <p class="text-muted text-capitalize">
                                          <?= htmlspecialchars($fetch_products['category_name']); ?>
                                       </p>
                                    </li>
                                 </ul>
                                 <h6>Description:</h6>
                                 <p class="text-capitalize">
                                    <?= htmlspecialchars($fetch_products['details']); ?>
                                 </p>

                                 <h6>Care Instruction:</h6>
                                 <ul class="list-unstyled">
                                    <li><img src="img/shop/care_instruction/do_not_bleach.png" alt="" width="25px"
                                          class="p-1"> do not bleach</li>
                                    <li><img src="img/shop/care_instruction/iron_on_low_heat.png" alt="" width="25px"
                                          class="p-1"> iron on low heat</li>
                                    <li><img src="img/shop/care_instruction/dry_flat.png" alt="" width="25px" class="p-1"> dry
                                       flat</li>
                                    <li><img src="img/shop/care_instruction/handwash.png" alt="" width="25px" class="p-1">
                                       handwash</li>
                                 </ul>
                                 <ul class="list-inline">
                                    <li class="list-inline-item text-right h6">
                                       Quantity:
                                    </li>
                                    <input type="number" min="1" max="<?= htmlspecialchars($fetch_products['quantity']); ?>"
                                       value="1" name="p_qty" class="qty" style="width: 100px;" />
                                    <button class="buy-btn text-capitalize" type="submit" name="add_to_cart">
                                       Add To Cart</button>
                                 </ul>
                                 <ul class="list-inline mt-3">
                                    <a href="shop.php"
                                       class="buy-btn text-capitalize col-5 text-decoration-none text-dark">Continue
                                       Shopping</a>

                                    <a href="cart.php" class="buy-btn text-capitalize col-5 text-decoration-none text-dark">Go
                                       to Cart</a>

                                 </ul>

                                 <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                                 <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
                                 <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
                                 <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
                              </form>
                           </div>
                        </div>
                     </div>
                     <?php
                  }
               } else {
                  echo '<p class="empty text-capitalize">no products added yet!</p>';
               }
               ?>
            </div>
         </div>
   </section>
   <!-- end of quick-view -->


   <!-- Reviews -->
   <section id="reviews" class="">
      <div class="container">
         <div class="title text-center mt-5 pt-5">
            <h2 class="position-relative d-inline-block">Reviews</h2>
         </div>

         <div class="container pt-5">
            <div class="row d-flex">

               <?php
               $loggedIn = isset($_SESSION['user_id']);

               if ($loggedIn) {
                  if (isset($_GET['pid'])) {
                     $pid = $_GET['pid'];

                     $select_comment = $pdo->prepare('SELECT reviews.comment, reviews.image, reviews.review_time, user.name FROM `reviews` 
                                 INNER JOIN `user` ON user.id = reviews.user_id 
                                 WHERE reviews.pid = ?');
                     $select_comment->execute([$pid]);

                     if ($select_comment && $select_comment->rowCount() > 0) { ?>
                        <div class="col-8">
                           <div class="card border-2">
                              <div class="card-body p-2">
                                 <?php
                                 while ($fetch_comments = $select_comment->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <div class="row">
                                       <div class="col-12 row">
                                          <div class="col-4 px-5 pb-3">
                                             <img src="admin/uploaded_img/reviews/<?= $fetch_comments['image']; ?>" alt=""
                                                width="70%">
                                          </div>
                                          <div class="col-8 px-3">
                                             <p class="card-text text-capitalize text-truncate fw-bold">
                                                <?= $fetch_comments['name']; ?>
                                             </p>

                                             <p class="text-capitalize">
                                                <?= $fetch_comments['comment']; ?>
                                             </p>
                                             <p class="text-capitalize text-mute">
                                                <?= $fetch_comments['review_time']; ?>
                                             </p>

                                          </div>
                                          <hr class="d-block mx-2">
                                       </div>
                                    </div>
                                    <?php
                                 } ?>
                              </div>
                           </div>
                        </div>
                        <?php
                     } else {
                        echo '<div class="col-8 text-center pt-3">
                                    <h6 class="position-relative d-inline-block">Chưa có đánh giá</h6>
                                 </div>';
                     }
                  } else {
                     $message[] = "Không tìm thấy ID sản phẩm!";
                  }
                  ?>


                  <div class="col-4">
                     <div class="card border-2">
                        <div class="card-body">
                           <form action="" method="POST" enctype="multipart/form-data" class="col-sm-12">
                              <?php if (isset($_SESSION['comment'])): ?>
                                 <div>
                                    <?= $_SESSION['comment'] ?>
                                 </div>
                                 <?php unset($_SESSION['comment']) ?>
                              <?php endif ?>
                              <label class="fs-3 fw-bold py-3 text-primary">Comment</label>
                              <br>

                              <label for="image" class="mx-1">Chọn ảnh:</label>
                              <input type="file" name="image" id="image">
                              <textarea name="comment" id="comment" class="form-control mt-3" placeholder="Content reviews"
                                 rows="7" oninput="checkComment()"></textarea>
                              <br>
                              <button value="send" id="sendButton" name="send" type="submit" class="btn"
                                 disabled>Send</button>
                           </form>
                        </div>
                     </div>
                  </div>
               <?php } ?>


               <!-- Hiển thị bình luận -->
               <?php
               if (!$loggedIn) {
                  if (isset($_GET['pid'])) {
                     $pid = $_GET['pid'];

                     $select_comment = $pdo->prepare('SELECT reviews.comment, reviews.image, reviews.review_time, user.name FROM `reviews` 
                                    INNER JOIN `user` ON user.id = reviews.user_id 
                                    WHERE reviews.pid = ?');
                     $select_comment->execute([$pid]);

                     if ($select_comment && $select_comment->rowCount() > 0) {
                        ?>
                        <div class="col-8 offset-2">
                           <div class="card border-2">
                              <div class="card-body p-2">
                                 <?php
                                 while ($fetch_comments = $select_comment->fetch(PDO::FETCH_ASSOC)) {
                                    ?>

                                    <div class="row">
                                       <div class="col-12 row">
                                          <div class="col-4 px-5 pb-3">
                                             <img src="admin/uploaded_img/reviews/<?= $fetch_comments['image']; ?>" alt=""
                                                width="70%">
                                          </div>
                                          <div class="col-8 px-3">
                                             <p class="card-text text-capitalize text-truncate fw-bold">
                                                <?= $fetch_comments['name']; ?>
                                             </p>

                                             <p class="text-capitalize">
                                                <?= $fetch_comments['comment']; ?>
                                             </p>
                                             <p class="text-capitalize text-mute">
                                                <?= $fetch_comments['review_time']; ?>
                                             </p>

                                          </div>
                                          <hr class="d-block mx-2">

                                       </div>
                                    </div>
                                    <?php
                                 }
                                 ?>
                              </div>
                           </div>
                        </div>
                        <?php
                     } else {
                        echo '<div class="text-center pt-3">
                              <h6 class="position-relative d-inline-block">Chưa có đánh giá</h6>
                           </div>';
                     }
                  } else {
                     $message[] = "Không tìm thấy ID sản phẩm!";
                  }
               }
               ?>

            </div>
         </div>
   </section>

   <!-- // <-- Related items section -->
   <section id="collection" class="">
      <div class="container">
         <div class="title text-center mt-5 pt-5">
            <h2 class="position-relative d-inline-block">You May Also Like</h2>
         </div>

         <div class="row g-0 container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mt-3">
               <?php
               $select_products = $pdo->prepare("SELECT * FROM `products`");
               $select_products->execute();
               if ($select_products->rowCount() > 0) {
                  while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                     ?>
                     <div class="col">
                        <div class="card shadow rounded h-100">
                           <div class="collection-img position-relative">
                              <img class="rounded-top p-0 card-img-top"
                                 src="admin/uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                           </div>

                           <div class="card-body">
                              <div class="row">
                                 <div class="col-8">
                                    <p class="card-text text-capitalize text-truncate fw-bold">
                                       <?= htmlspecialchars($fetch_products['name']); ?>
                                    </p>
                                 </div>
                                 <div class="col-4 text-end"><a href="#"><i
                                          class="fa-regular fa-heart fa-lg text-dark heart"></i></a></div>
                              </div>
                              <p class="text-truncate text-capitalize">
                                 <?= htmlspecialchars($fetch_products['details']); ?>
                              </p>
                              <div class="d-flex justify-content-between align-items-center">
                                 <span class="fw-bold d-block h5">$
                                    <?= htmlspecialchars($fetch_products['price']); ?>
                                 </span>
                                 <div class="btn-group">
                                    <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>"
                                       class="btn btn-primary">View</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <?php
                  }
                  ?>
               </div>

               <?php
               } else {
                  echo '<div class="text-center pt-3">
                           <h6 class="position-relative d-inline-block">No products added yet! </h6>
                     </div>';
               }
               ?>
         </div>
      </div>
   </section>

   <script>
      function addToWishlist() {
         var loggedIn = <?php echo htmlspecialchars(isset($_SESSION['user_id']) ? 'true' : 'false'); ?>;

         if (!loggedIn) {
            alert('You need to log in to add products to your wishlist.');
            window.location.href = 'login.php';
            return false;
         }
         return true;
      }
   </script>

   <script>
      function addToCart() {
         var loggedIn = <?php echo htmlspecialchars(isset($_SESSION['user_id']) ? 'true' : 'false'); ?>;

         if (!loggedIn) {
            alert('You need to log in to add products to your cart.');
            window.location.href = 'login.php';
            return false;
         }
         return true;
      }
   </script>

   <script>
      function checkComment() {
         var comment = document.getElementById("comment").value.trim();

         if (comment === "") {
            document.getElementById("sendButton").disabled = true;
         } else {
            document.getElementById("sendButton").disabled = false;
         }
      }
   </script>

   <?php
   include_once __DIR__ . '../../partials/footer.php';