<?php
session_start();
$a = array();
if (!isset($_SESSION['loggedUsername']) || !$_SESSION['loggedUsername']) {
    $a['error'] = 1;
    $a['errMsg'] = '请登录后访问本页面';
    echo json_encode($a);
    exit;
}
include_once 'conn.php';
$id = $_GET['id'] ?? '';
$code = $_GET['code'];

if(strtolower($_SESSION['captcha']) == strtolower($code)){
    $_SESSION['captcha'] = '';
}else{
    $_SESSION['captcha'] = '';
    $a['error'] = 1;
    $a['errMsg'] = '验证码错误';
    echo json_encode($a);
    exit;

}

if(!is_numeric($id) || $id == ''){
    $a['error'] = 1;
    $a['errMsg'] = '参数错误';
    echo json_encode($a);
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
    $a['error'] = 1;
    $a['errMsg'] = '当前用户已经给当前车辆投过5票了';
    echo json_encode($a);
    exit;

}
$sql = "select carID from votedetail where userID = 
".$_SESSION['loggedUserID']." and FROM_UNIXTIME(voteTime,'%Y-%m-%d') = '".date("Y-m-d")."' and carID <> $id group by carID";
$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if($num >= 3){
    $a['error'] = 1;
    $a['errMsg'] = '每人每天最多只能给三辆车投票';
    echo json_encode($a);
    exit;

}
//投票间隔60以上
$sql = "select voteTime from votedetail where userID = " . $_SESSION['loggedUserID']." order by id desc limit 0,1";


$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if(mysqli_num_rows($result)){
    $info = mysqli_fetch_array($result);
    if(time() - $info['voteTime'] <= 60){
    $a['error'] = 1;
    $a['errMsg'] = '两次投票之间必须间隔1分钟。';
    echo json_encode($a);
    exit;

    }
}
//第四  一个ip15票
$sql = "select 1 from votedetail where from_unixtime(voteTime,'%Y-%m-%d') = CURRENT_DATE() and ip = '".getIp()."'";

$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);

if(mysqli_num_rows($result)>= 15){
    $a['error'] = 1;
    $a['errMsg'] = '一个ip地址一天最多投15票';
    echo json_encode($a);
    exit;

}


$sql1 = "update carinfo set carnum = carNum + 1 where id = $id";
$sql2 = "insert into votedetail (userID,carID,voteTime,ip) VALUES ('".$_SESSION['loggedUserID']."','$id','".time()."','".getIp()."')";
mysqli_autocommit($conn,0);
$result1= mysqli_query($conn,$sql1);

$result2= mysqli_query($conn,$sql2);

if($result1 and $result2){
    mysqli_commit($conn);
    $a['error'] = 0;
    echo json_encode($a);

}else{
    mysqli_rollback($conn);
    $a['error'] = 1;
    $a['errMsg'] = '投票失败';
    echo json_encode($a);

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
