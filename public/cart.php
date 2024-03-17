<?php
include_once __DIR__ . '../../partials/boostrap.php';

include_once __DIR__ . '../../partials/header.php';

require_once __DIR__ . '../../partials/connect.php';

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}
;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $pdo->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
}
;

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $pdo->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}
;

if (isset($_POST['update_qty'])) {
    $new_quantities = $_POST['p_qty'];

    foreach ($new_quantities as $cart_id => $new_quantity) {
        if (is_numeric($new_quantity) && $new_quantity > 0) {
            $update_qty = $pdo->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
            $update_qty->execute([$new_quantity, $cart_id]);
        } else {
            $message[] = "Update fail";
        }
    }
}
;
?>

<title>Cart</title>
</head>

<body>

    <!-- Cart -->
    <section id="cart" class="pt-5">
        <div class="container">
            <div class="title text-center mt-5 pt-5">
                <h2 class="position-relative d-inline-block">Your Cart</h2>
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
            <?php
            $total = 0.00;
            $sub_total = 0.00;
            $select_cart = $pdo->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            ?>
            <?php if ($select_cart->rowCount() > 0) { ?>
                <table class="mt-5 pt-5 table-responsive">
                    <tr>
                        <th class="col-3">Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $product_subtotal = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $sub_total += $product_subtotal;
                        ?>
                        <form method="POST" action="cart.php">
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="admin/uploaded_img/<?= htmlspecialchars($fetch_cart['image']); ?>" alt=""
                                            class="w-50">
                                    </div>
                                </td>
                                <td>
                                    <div class="product-name">
                                        <div class=" name text-capitalize">
                                            <?= htmlspecialchars($fetch_cart['name']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-price">
                                        <div class="price">$
                                            <?= htmlspecialchars($fetch_cart['price']); ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="quantity">
                                        <input type="number" name="p_qty[<?= htmlspecialchars($fetch_cart['id']); ?>]"
                                            value="<?= htmlspecialchars($fetch_cart['quantity']); ?>" min="1">
                                    </div>
                                    <button class="bg-white border-0 text-primary" type="submit"
                                        name="update_qty">Update</button>
                                </td>
                                <td>
                                    <span class="product-price">$
                                        <?= htmlspecialchars($product_subtotal); ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="text-capitalize text-align"
                                        href="cart.php?delete=<?= htmlspecialchars($fetch_cart['id']); ?>"
                                        onclick="return confirm('delete this from cart?');">delete</a>
                                </td>
                            </tr>
                        </form>
                        <?php
                    }
                    ?>
                </table>


                <div class="cart-total">
                    <table>
                        <tr>
                            <td>Total</td>
                            <td>$
                                <?= $sub_total; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Subtotal</td>
                            <td><span>$
                                    <?= $sub_total; ?>
                                </span></td>
                        </tr>
                    </table>
                </div>

                <div class="checkout-container">
                    <a class="btn checkout-btn" href="checkout.php">Checkout</a>
                </div>

                <?php
            } else {
                ?>
                <div class="text-center pt-3">
                    <h6 class="position-relative d-inline-block">No item found </h6>
                    <div>
                        <a type="submit" class="buy-btn text-capitalize text-decoration-none mt-3" name="shop now"
                            href="shop.php">shop now</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
    <?php
    include_once __DIR__ . '../../partials/footer.php';