<?php
session_start();
$username = trim($_POST['username']);
$pw = $_POST['pw'];
$code = $_POST['code'];
if(strtolower($_SESSION['captcha']) == strtolower($code)){
    $_SESSION['captcha'] = '';
}else{
    $_SESSION['captcha'] = '';
    echo "<script>alert('验证码错误');location.href='login.php?id=3';</script>";
    exit;
}

if(!strlen($username) || !strlen($pw)){
    echo "<script>alert('用户名和密码必须填写');history.back()</script>";
    exit;
}else{
    if(!preg_match('/^[a-zA-Z0-9]{3,10}$/',$username)){
        echo "<script>alert('用户名必填，且只能大小写字符和数字构成，长度为3-10个字符');history.back()</script>";
        exit;
    }
}
if(!preg_match('/^[a-zA-Z0-9]{6,10}$/',$pw)){
    echo "<script>alert('密码必填，且只能大小写字符和数字构成，长度为3-10个字符');history.back()</script>";
    exit;
}
include_once "conn.php";
$sql = "select * from userinfo where username='$username' and pw='".md5($pw) . "'";

$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);
if($num){
    
    $_SESSION['loggedUsername'] = $username;

    $info = mysqli_fetch_array($result);
    $_SESSION['loggedUserID'] =$info['id'];
    if($info['admin']){
        $_SESSION['isAdmin'] = 1;

    }else{
        $_SESSION['isAdmin'] = 0;
    }
    echo"<script>alert('登录成功');window.parent.location.reload();</script>";

}else{
    unset($_SESSION['isAdmin']);
    unset($_SESSION['loggedUsername']);

    echo "<script>alert('用户名密码错误');window.parent.closeLayer();</script>";
}