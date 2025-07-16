<?php
session_start();
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    echo "<script> alert('请以管理员身份登录本页面');location.href='login.php';</script>";
    exit;
}
