<?php

include_once __DIR__ . '../../partials/boostrap.php';
include_once __DIR__ . '../../partials/header.php';
require_once __DIR__ . '../../partials/connect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    $select_email = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $select_email->execute([$email]);

    $select_phone = $pdo->prepare("SELECT * FROM user WHERE phone= ?");
    $select_phone->execute([$phone]);

    if (($select_email->rowCount() > 0) && ($select_phone->rowCount() > 0)) {
        $message[] = 'Email and phone already exist!';
    } elseif ($select_phone->rowCount() > 0) {
        $message[] = 'Phone already exist!';
    } elseif ($select_email->rowCount() > 0) {
        $message[] = 'Email already exist!';
    } else {
        if ($password != $cpassword) {
            $message[] = 'Confirm password not matched!';
        } else {
            $insert = $pdo->prepare("INSERT INTO `user`(name, phone, email, password) VALUES(?, ?, ?, ?)");
            $insert->execute([$name, $phone, $email, $password]);
            $message[] = 'registered successfully!';
            header('Location:login.php');
        }
    }
}
;

?>
<title>Register</title>
</head>

<body id="register">
    <!-- Register -->
    <section class="my-5 py-5">
        <div class="container title text-center mt-3 pt-5">
            <h2 class="position-relative d-inline-block">Register</h2>
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
        <div class="mx-auto container mt-3">
            <div class="card col-md-6 offset-md-3 bg-transparent">
                <div class="card-body">
                    <form action="register.php" id="register-form" method='post' class="text_center form-horizontal">
                        <div class="form-group">
                            <input type="text" class="form-control" id="register-name" name="name" placeholder="Name"
                                for="name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="register-phone" name="phone" placeholder="Phone"
                                for="phone">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="register-email" name="email"
                                placeholder="Email" for="email">
                        </div>
                        <div class="d-flex flex-row input-group rounded">
                            <div class="col-11">
                                <input type="password" class="form-control flex-fill" id="register-password"
                                    name="password" placeholder="Password" for="password">
                            </div>
                            <div class="col-1">
                                <span class="fas fa-eye flex-fill m-3 position-relative" type="button"
                                    id="btnPassword"></span>
                            </div>
                        </div>
                        <div class="form-group d-flex rounded">
                            <div class="col-11">
                                <input type="password" class="form-control flex-fill" id="register-confirm-password"
                                    name="cpassword" placeholder="Confirm Password" for="cpassword">
                            </div>
                            <div class="col-1">
                                <span class="fas fa-eye flex-fill m-3 position-relative" type="button"
                                    id="btnPassword1"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-check col-md-6">
                                <input class="form-check-input" type="checkbox" id="agree" name="agree" value="agree" />
                                <label class="form-check-label" for="agree"> Agree to our regulations</label>
                            </div>
                            <div class="col-md-6">
                                <a href="login.php" id="login-url" class=""> Do you have an account ? Login</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn w-100" id="register-btn" value="Register" name="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>// step 1
        const ipnElement = document.querySelector('#register-password')
        const btnElement = document.querySelector('#btnPassword')

        // step 2
        btnElement.addEventListener('click', function () {
            // step 3
            const currentType = ipnElement.getAttribute('type')
            // step 4
            ipnElement.setAttribute(
                'type',
                currentType === 'password' ? 'text' : 'password'
            )
        })

        const ipnElement1 = document.querySelector('#register-confirm-password')
        const btnElement1 = document.querySelector('#btnPassword1')
        btnElement1.addEventListener('click', function () {
            // step 3
            const currentType1 = ipnElement1.getAttribute('type')
            // step 4
            ipnElement1.setAttribute(
                'type',
                currentType1 === 'password' ? 'text' : 'password'
            )
        })


    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#register-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        minlength: 10
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    cpassword: {
                        required: true,
                        minlength: 8,
                        equalTo: '#register-password',
                    },
                    agree: 'required',
                },
                messages: {
                    name: {
                        required: 'You have not entered your username',
                        minlength: 'Username must have at least 8 characters',
                    },
                    phone: {
                        required: 'You have not entered your phone',
                        minlength: 'Phone must have  10 numbers',
                    },
                    email: 'Invalid email box',
                    password: {
                        required: 'You have not entered a password',
                        minlength: 'Password must have at least 10 characters',
                    },
                    cpassword: {
                        required: 'You have not entered a password',
                        minlength: 'Password must have at least 10 characters',
                        equalTo: 'The password does not match the entered password'
                    },

                    agree: 'You must agree to our regulations'
                },
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    if (element.prop('type') === 'checkbox') {
                        error.insertAfter(element.siblings('label'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element)
                        .addClass('is-invalid')
                        .removeClass('is-valid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element)
                        .addClass('is-valid')
                        .removeClass('is-invalid');
                },
            });
        });
    </script>
    <?php
    include_once __DIR__ . '/../partials/footer.php';
    ?>
</body>
</html>