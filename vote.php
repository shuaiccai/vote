<?php

include_once 'checkLogin.php';
include_once 'conn.php';
$id = $_GET['id'] ?? '';
$code = $_GET['code'];

if(strtolower($_SESSION['captcha']) == strtolower($code)){
    $_SESSION['captcha'] = '';
}else{
    $_SESSION['captcha'] = '';
    echo "<script>alert('验证码错误');location.href='index.php';</script>";
    exit;
}

if(!is_numeric($id) || $id == ''){
    echo '<script>alert("参数错误");history.back();</script>';
    exit;
}
// $sql = "select 1 from votedetail where userID = ".$_SESSION['loggedUserID']." and carID =$id and voteTime = '".date("Y-m-d")."'";
// $result = mysqli_query($conn,$sql);
// $num = mysqli_num_rows($result);

// if($num >= 5){
//     echo "<script>alert('当前用户已经给当前车辆投过5票了');history.back();</script>";
//     exit;
// }
$sql = "select count(1) as num from votedetail where userID = 
".$_SESSION['loggedUserID']." and carID =$id and FROM_UNIXTIME(voteTime,'%Y-%m-%d') = '".date("Y-m-d")."'";
$result = mysqli_query($conn,$sql);

$info = mysqli_fetch_array($result);

if($info['num'] >= 5){
    echo "<script>alert('当前用户已经给当前车辆投过5票了');history.back();</script>";
    exit;
}
$sql = "select carID from votedetail where userID = 
".$_SESSION['loggedUserID']." and FROM_UNIXTIME(voteTime,'%Y-%m-%d') = '".date("Y-m-d")."' and carID <> $id group by carID";
$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if($num >= 3){
    echo "<script>alert('每人每天最多只能给三辆车投票');history.back();</script>";
    exit;
}
//投票间隔60以上
$sql = "select voteTime from votedetail where userID = " . $_SESSION['loggedUserID']." order by id desc limit 0,1";


$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if(mysqli_num_rows($result)){
    $info = mysqli_fetch_array($result);
    if(time() - $info['voteTime'] <= 60){
        echo "<script>alert('两次投票之间必须间隔1分钟。');history.back();</script>";
        exit;
    }
}
//第四  一个ip15票
$sql = "select 1 from votedetail where from_unixtime(voteTime,'%Y-%m-%d') = CURRENT_DATE() and ip = '".getIp()."'";

$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if(mysqli_num_rows($result)>= 15){
    echo "<script>alert('一个ip地址一天最多投15票');history.back();</script>";
    exit;
}


$sql1 = "update carinfo set carnum = carNum + 1 where id = $id";
$sql2 = "insert into votedetail (userID,carID,voteTime,ip) VALUES ('".$_SESSION['loggedUserID']."','$id','".time()."','".getIp()."')";
mysqli_autocommit($conn,0);
$result1= mysqli_query($conn,$sql1);

$result2= mysqli_query($conn,$sql2);

if($result1 and $result2){
    mysqli_commit($conn);
    echo "<script>alert('投票成功');location.href='index.php';</script>";
}else{
    mysqli_rollback($conn);
    //echo "<script>alert('投票失败');history.back();</script>";
}

function getIp()
{
    if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } else {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } else {
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                        "unknown")
                ) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = "unknown";
                }
            }
        }
    }
    return ($ip);
}
