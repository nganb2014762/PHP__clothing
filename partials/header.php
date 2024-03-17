<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <button class="navbar-toggler btn-primary d-lg-none border-0" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="offcanvasResponsive"
            aria-labelledby="offcanvasResponsiveLabel">
            <div class="offcanvas-header">
                <a class="offcanvas-title nav-link text-uppercase fw-bold fs-4 px-3" href="index.php"
                    id="offcanvasResponsiveLabel">
                    home
                </a>

                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                    data-bs-target="#offcanvasResponsive" aria-label="Close">
                </button>
            </div>
            <hr>
            <div class="offcanvas-body">
                <ul class="navbar-nav my-auto">
                    <li class="nav-item dropdown px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="shop.php">shop</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="blog.php">blogs</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="about.php">about us</a>
                    </li>
                    <li class="nav-item px-2 py-2">
                        <a class="nav-link text-uppercase text-dark" href="contact.php">contact us</a>
                    </li>
                </ul>
            </div>
        </div>

        <a class="navbar-brand d-flex order-lg-1 justify-content-between align-content-center" href="index.php">
            <img src="../img/logo.png" alt="site icon">
        </a>

        <div class="order-lg-2">
            <div class="navbar-collapse nav-btns">
                <div class="d-flex justify-content-between align-items-center">
                    <span id="search" class="d-flex align-items-center me-2">
                        <a href="search.php">
                            <i class="fa fa-search" style="color:black;"></i>
                        </a>
                    </span>

                    <?php
                    require_once __DIR__ . '../../partials/connect.php';
                    session_start();
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];

                        $select_profile = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
                        $select_profile->execute([$user_id]);
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

                        if ($fetch_profile !== false) {
                            echo ' <div class="dropdown">
                                    <a  style="color:#222; text-decoration: none;" role="button" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-user"></i> <span>' . htmlspecialchars($fetch_profile['name']) . '</span>
                                    </a>
                        
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="profile.php">My Account</a></li>
                                        <li><a class="dropdown-item" href="my_order.php">My Orders</a></li>
                                        <li><a class="dropdown-item" href="wishlist.php">My Favorite List</a></li>
                                        <li><a id="logout" class="dropdown-item" href="logout.php">Log Out</a></li>
                                    </ul>
                                </div> ';
                        } else {
                            header('location:index.php');
                        }
                    } else {
                        echo '<button type="button" class="btn position-relative">
                                <a href="login.php" style="color:#222;"><i class="fa fa-user"></i></a>
                            </button>';
                    }
                    ?>
                    <button class="btn position-relative" type="button">
                        <a href="cart.php" style="color:#222;"><i class="fa fa-shopping-cart"></i></a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- end of navbar -->