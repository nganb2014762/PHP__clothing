<?php

include_once __DIR__ . '../../partials/boostrap.php';

include_once __DIR__ . '../../partials/header.php';

require_once __DIR__ . '../../partials/connect.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
}
;

$user_profile_query = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
$user_profile_query->execute([$user_id]);
$fetch_profile = $user_profile_query->fetch(PDO::FETCH_ASSOC);


if ($fetch_profile) {
  $user_name = isset($fetch_profile['name']) ? $fetch_profile['name'] : '';
  $user_phone = isset($fetch_profile['phone']) ? $fetch_profile['phone'] : '';
  $user_email = isset($fetch_profile['email']) ? $fetch_profile['email'] : '';
  $user_address = isset($fetch_profile['address']) ? $fetch_profile['address'] : '';
} else {
  echo htmlspecialchars("Không thể tìm thấy thông tin hồ sơ người dùng.");
}


if (isset($_POST['order'])) {

  $method = $_POST['method'];
  $address = $_POST['address'];
  $placed_on = date('Y-m-d', strtotime('now'));

  $cart_grand_total = 0;
  $orderDetails = array();

  $cart_query = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
  $cart_query->execute([$user_id]);

  if ($cart_query->rowCount() > 0) {
    while ($fetch_cart_items = $cart_query->fetch(PDO::FETCH_ASSOC)) {
      // Lấy product id và quantity
      $pid = $fetch_cart_items['pid'];
      $quantity = $fetch_cart_items['quantity'];

      // Lưu thông tin sản phẩm vào mảng
      $orderDetails[] = array('pid' => $pid, 'quantity' => $quantity);

      // Cập nhật tổng giá trị đơn hàng
      $cart_total_price = ($fetch_cart_items['price'] * $quantity);
      $cart_grand_total += $cart_total_price;
    }
    ;
  }
  ;

  $total_products = '';
  foreach ($orderDetails as $orderDetail) {
    $total_products .= 'ID(' . $orderDetail['pid'] . '),SL(' . $orderDetail['quantity'] . '); ';
  }

  try {
    // Cập nhật địa chỉ trong bảng `user`
    $update_user_address = $pdo->prepare("UPDATE `user` SET address = ? WHERE id = ?");
    $update_user_address->execute([$address, $user_id]);
    if ($cart_grand_total > 0) {
      // Thêm đơn hàng vào bảng orders
      $insert_order = $pdo->prepare("INSERT INTO `orders`(user_id, method, total_products, total_price, placed_on) VALUES(?,?,?,?,?)");
      $insert_order->execute([$user_id, $method, $total_products, $cart_grand_total, $placed_on]);
      // Lấy id của đơn hàng vừa chèn
      $order_id = $pdo->lastInsertId();

      // Giảm số lượng sản phẩm trong bảng product
      foreach ($orderDetails as $orderDetail) {
        $product_id = $orderDetail['pid'];
        $quantity = $orderDetail['quantity'];

        // Lấy số lượng sản phẩm hiện tại từ bảng product
        $product_query = $pdo->prepare("SELECT quantity FROM `products` WHERE id = ?");
        $product_query->execute([$product_id]);
        $current_quantity = $product_query->fetchColumn();

        // Kiểm tra số lượng sản phẩm đủ để giảm không
        if ($current_quantity >= $quantity) {
          // Giảm số lượng sản phẩm trong bảng product
          $update_product_quantity = $pdo->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
          $update_product_quantity->execute([$current_quantity - $quantity, $product_id]);
          $insert_orders_details = $pdo->prepare("INSERT INTO orders_details (order_id, pid, quantity) VALUES (?, ?, ?)");
          $insert_orders_details->execute([$order_id, $orderDetail['pid'], $orderDetail['quantity']]);
          // Xóa các sản phẩm trong giỏ hàng
          $delete_cart = $pdo->prepare("DELETE FROM `cart` WHERE user_id = ?");
          $delete_cart->execute([$user_id]);

          $message[] = 'Order placed successfully!';
        } else {
          // Xử lý trường hợp không đủ sản phẩm trong kho
          $message[] = "Bạn đã đặt số lượng vượt quá số lượng sản phẩm trong kho.Số lượng sản phẩm trong kho còn $current_quantity";
          $delete_order = $pdo->prepare("DELETE FROM `orders` WHERE id = ?");
          $delete_order->execute([$user_id]);
        }
        // Kiểm tra xem cập nhật đã thành công hay không và hiển thị thông báo
        if ($update_user_address->rowCount() > 0) {
          echo "Địa chỉ đã được cập nhật thành công!";
        } else {
          echo "Có lỗi xảy ra khi cập nhật địa chỉ.";
        }
      }
    }
  } catch (PDOException $e) {
    $message[] = 'Failed to place order. Error: ' . $e->getMessage();

  }
}
;

?>

<title>Checkout</title>
</head>

<div class="container">
  <main>
    <div class="my-5 pt-5 text-center">
      <div class="container title text-center mt-3 pt-5">
        <h2 class="position-relative d-inline-block">Checkout form</h2>
      </div>
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
    <div class="row g-5">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary ">Your cart</span>
        </h4>
        <?php
        $cart_grand_total = 0;
        $select_cart_items = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart_items->execute([$user_id]);
        if ($select_cart_items->rowCount() > 0) {
          while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
            ?>
            <div>
              <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <div>
                    <h6 class="my-0">Product name</h6>
                    <small class="text-muted">
                      <?= htmlspecialchars($fetch_cart_items['name']); ?>
                    </small>
                  </div>
                  <span class="text-muted">
                    <?= htmlspecialchars($fetch_cart_items['price']) . '$' ?>
                  </span>
                </li>

                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <div>
                    <h6 class="my-0">Quantity</h6>
                  </div>
                  <span class="text-muted">
                    <?= htmlspecialchars($fetch_cart_items['quantity']); ?>
                  </span>
                </li>

              </ul>
            </div>

            <?php
          }
        } else {
          echo '<p class="empty"> Your bill is  empty!</p>';
        }
        ?>

        <li class="list-group-item d-flex justify-content-between lh-sm">
          <span><b>Total: </b></span>
          <strong>
            <?= htmlspecialchars($cart_grand_total); ?>$
          </strong>
        </li>
        <button class="w-100 btn btn-sm mt-3" name="go_to_shop" type="submit"><a href="shop.php"
            class="text-decoration-none text-dark">Go to shop</a>
        </button>
        <button class="w-100 btn btn-sm mt-3" name="Go to cart" type="submit"><a href="cart.php"
            class="text-decoration-none text-dark">Go to Cart</a>
        </button>
        <button class="w-100 btn btn-sm mt-3" name="Go to cart" type="submit"><a href="my_order.php"
            class="text-decoration-none text-dark">My order</a>
        </button>
      </div>

      <div class="col-md-7 col-lg-8 border-end">
        <h4 class="mb-3 text-primary">Billing address</h4>
        <form class="needs-validation" validate method="POST">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Your name</label>
              <input type="text" class="form-control" name="name"
                value="<?= isset($fetch_profile['name']) ? htmlspecialchars($fetch_profile['name']) : ''; ?>" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>

            <div class="col-sm-6">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" name="phone"
                value="<?= htmlspecialchars($fetch_profile['phone']); ?>" required>
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email"
                value="<?= htmlspecialchars($fetch_profile['email']); ?>" required>
              <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
              </div>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" name="address" required><?= htmlspecialchars($fetch_profile['address']);
              ?></textarea>
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>
            <h4 class="text-primary">Payment</h4>
            <select name="method" class="form-control" required>
              <option value="cash on delivery">Cash on delivery</option>
              <option value="credit card">Credit card</option>
              <option value="MoMo">MoMo</option>
              <option value="Zalo Pay">Zalo Pay</option>
            </select>
            <button class="w-100 btn btn-primary btn-lg mt-3 <?= ($cart_grand_total > 1) ? '' : 'disabled'; ?>"
              name="order" type="submit">Accept Payment</button>
        </form>
      </div>
    </div>
  </main>
</div>
<?php
include_once __DIR__ . '../../partials/footer.php';