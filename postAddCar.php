<?php
$carName = $_POST['carName'];
$carDesc = $_Post['carDesc'];
$fileName = '';
if($_FILES['carPic']['error']){
    echo "<script>alert('图片上传错误');history.back();</script>";
    exit;
}
if(!empty($_FILES['carPic']['name'])){
    if($_FILES['carPic']['size'] > 2048*1024){
        echo "<script>alert('图片文件大小不能超过2mb');history.back();</script>";
        exit;
    }
    $allowType = array("image/gif","image/pjpeg","image/jpeg","image/jpg","image/png");
    if(!in_array($_FILES['carPic']['type'],$allowType)){
        echo "<script>alert('图片类型错误，只能是jpg,png,gif格式。');history.back();</script>";
        exit;
    }
    $allowExt = array("jpg","jpeg","png","gif");
    $nameArray = explode(".",$_FILES['carPic']['name']);
    $nameExt = end($nameArray);
    if(!in_array(strtolower($nameExt),$allowExt)){
        echo "<script>alert(图片文件扩展名错误，只能是jpg,jpeg,png,gif文件。');history.back();</script>";
        exit;
    }
    $fileName = uniqid().".".$nameExt;
    $result = move_uploaded_file($_FILES['carPic']['tmp_name'],"img/".$fileName);
    if(!$result){
        echo "<script>alert('保存文件出错。');history.back();</script>";
        exit;
    }
}
include_once 'conn.php';
$sql = "insert into carinfo (carName, carDesc, carPic,carNum) VALUES ('$carName','$carDesc','$fileName','0')";
$result = mysqli_query($conn,$sql);
if($result){
    echo "<script>alert('车辆添加成功。');location.href='admin.php';</script>";
}else{
    echo "<script>alert('车辆添加失败。');history.back();</script>";
}