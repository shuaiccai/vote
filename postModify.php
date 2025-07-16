<?php
$username = trim($_POST['username']);
$pw = trim($_POST['pw']);
$cpw = trim($_POST['cpw']);
$sex = $_POST['sex'];
$email = $_POST['email'];
$fav = @implode(",",$_POST['fav']);
$source = $_POST['source'];
$page = $_POST['page'];

if(!strlen($username)){
    echo "<script>alert('用户名必须填写');history.back()</script>";
    exit;
}else{
    if(!preg_match('/^[a-zA-Z0-9]{3,10}$/',$username)){
        echo "<script>alert('用户名必填，且只能大小写字符和数字构成，长度为3-10个字符');history.back()</script>";
        exit;
    }
}
if(!empty($pw)){
    if($pw <> $cpw){
        echo "<script>alert('密码和确认密码不同');history.back()</script>";
        exit;
    }else{
        if(!preg_match('/^[a-zA-Z0-9]{6,10}$/',$pw)){
            echo "<script>alert('密码必填，且只能大小写字符和数字构成，长度为3-10个字符');history.back()</script>";
            exit;
        }
    }
}

if(!empty($email)){
    if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$email)){
        echo "<script>alert('邮箱格式不正确');history.back()</script>";
        exit;
    }
}
include_once 'conn.php';

if($pw){
    $sql = "update userinfo set pw='".md5($pw)."',email = '$email' where username = '$username'";
 
}else{
    $sql = "update userinfo set email = '$email' where username = '$username'";


$result = mysqli_query($conn,$sql);
}

if($result){
    echo "<script>alert('更新个人资料成功');window.parent.closeLayer();</script>";

}else{
    echo "<script>alert('更新个人资料失败');history.back()</script>";
}
