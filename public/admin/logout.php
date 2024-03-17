<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    // Đăng xuất admin
    unset($_SESSION['admin_id']);
    session_unset();
    session_destroy();
    header('Location: login.php'); // Chuyển hướng đến trang đăng nhập admin
    exit();
} else {
    htmlspecialchars('Không đăng xuất thành công!');
}