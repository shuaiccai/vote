<?php
session_start();
if (!isset($_SESSION['loggedUsername']) || !$_SESSION['loggedUsername']) {
    echo "<script> alert('请登陆后访问本页面');location.href='login.php';</script>";
    exit;
}
