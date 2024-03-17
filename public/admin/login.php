<?php

session_start();
include_once __DIR__ . "../../../partials/admin_boostrap.php";

require_once __DIR__ . '../../../partials/connect.php';



if (isset($_POST['submit'])) {

    $email = $_POST['email'];

    $password = md5($_POST['password']);
    $sql = "SELECT * FROM `user` WHERE email = :email AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':password' => $password
    ]);
    
    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['role'] == '1') {
            $_SESSION['admin_id'] = $row['id'];
            header('Location: index.php');
            exit();
        } else {
            $message[] = 'No user found or incorrect email or password!';
        }
    } else {
        $message[] = 'No user found or incorrect email or password!';
    }

};

if (isset($message)) {
    foreach ($message as $message) {
        echo '<script>alert(" ' . $message . ' ");</script><alert>';
    }
}
?>

<title>Admin Login</title>
</head>

<body class="bg-light" >
    
    <!-- Login -->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <img src="../img/account/user0.png" alt="" width="100">
        </div>
        <div class="container text-center">
            <h2 class="position-relative d-inline-block">Login</h2>
        </div>
        <div class="mx-auto container mt-3">
            <div class="card col-md-6 offset-md-3">
                <div class="card-body">
                    <form action="login.php" id="login-form" method="post" class="text_center form-horizontal">
                        <div class="form-group">
                            <input type="text" class="form-control" id="login-email" name="email" placeholder="Email"
                                for="email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="login-password" name="password"
                                placeholder="Password" for="password" required>
                        </div>
                        <div class="form-group row">
                            <div class="form-check col-md-6">
                                <a href="#" id="register-url" class="">Forgot password</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn w-100 btn-primary shadow-sm" id="login-btn"
                                value="Login" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End Login -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2020</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    

    