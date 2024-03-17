<?php
include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location: login.php');
    exit();
}




if (isset($_POST['send'])) {
    $msg = $_POST['msg'];

    $select_message = $pdo->prepare("SELECT * FROM `message` WHERE  user_id = ?");
    $select_message->execute([$msg]);

    if ($select_message->rowCount() > 0) {
        $message[] = 'already sent message!';
    } else {

        $insert_message = $pdo->prepare("INSERT INTO `message`(user_id, message) VALUES(?,?)");
        $insert_message->execute([$user_id, $msg]);

        $message[] = 'Sent message successfully!<br>We will email or call you as soon as possible.<br>Thank you for your understanding.';
    }
}
;

?>

<title>Contact</title>
</head>

<body id="contact">
    <!-- Contact -->
    <section class="my-5 py-5">

        <div class="container title text-center mt-5 pt-5 col-md-6 offset-md-6">
            <h2 class="position-relative d-inline-block">Contact Us</h2>
        </div>
        <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '<div class="alert alert-warning alert-dismissible fade show col-4 offset-7" role="alert" tabindex="-1">
                            ' . $message . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
            }
        }
        ;
        ?>

        <div class="mx-auto container mt-3">
            <div class="col-md-5 offset-md-7">
                <div class="card-body">
                    <form id="register-form" class="text_center form-horizontal" action="" method="post">
                        <div class="form-group">
                            <textarea name="msg" class="form-control rounded-3 fs-5" required
                                placeholder="Enter your message" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Send message" class="btn w-100 mt-3 fs-5" name="send">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- End contact -->

    <?php
    include_once __DIR__ . '../../partials/footer.php';