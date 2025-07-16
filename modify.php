<?php
session_start();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <style>
        .main{width: 80%;margin: 0 auto;text-align: center;}
        h2{font-size:20px}
        h2 a{color:navy;text-decoration:none;margin-right:15px}
        h2 a:last-child {margin-right: 0}
        h2 a:hover{color:brown;text-decoration:underline}
        .current{color:darkgreen}
        .red{color:red}

    </style>
</head>
<body>
<div class="main">
    <?php

    include_once 'conn.php';
    $username=$_GET['username'] ?? '';
    $sql = "select * from userinfo where username ='".$_SESSION['loggedUsername']."'";

    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)){
        $info = mysqli_fetch_array($result);
        $fav = explode(",",$info['fav']);
        print_r($fav);
    }else{
        die("未找到有效用户！");
    }
    ?>
    <form action="postModify.php" method="post" onsubmit="return check()">
        <table align="center" border="1" style="border-collapse: collapse" cellpadding="10" cellspacing="0">
            <tr>
                <td align="right">用户名</td>
                <td align="left"><input name = "username" readonly value="<?php echo $info['userName'];?>"></td>
            </tr>
            <tr>
                <td align="right">密码</td>
                <td align="left"><input type = "password" name="pw" placeholder="不修改密码请留空"></td>
            </tr>
            <tr>
                <td align="right">确认密码</td>
                <td align="left"><input type = "password" name="cpw" placeholder="不修改密码请留空"></td>
            </tr>

            <tr>
                <td align="right">邮箱</td>
                <td align="left"><input name = "email" value="<?php echo $info['email'];?>"></td>
            </tr>

            <tr>
                <td align="right"><input type="submit" value="提交"></td>
                <td align="left">
                    <input type="reset" value="重置">
                </td>
            </tr>

        </table>
    </form>
</div>
<script>
    function check(){

        let pw = document.getElementsByName("pw")[0].value.trim();
        let cpw = document.getElementsByName("cpw")[0].value.trim();
        let email = document.getElementsByName("email")[0].value.trim();

        let pwreg = /^[a-zA-Z0-9]{6,10}$/;
        if(pw.length > 0){
            if(!pwreg.test(pw)){
                alert("密码必填，且只能大小写字符和数字构成，长度为3-10个字符");
                return false;
            }else{
                if(pw!==cpw){
                    alert('密码和确认密码必须相同！');
                    return false;
                }
            }
        }

        let emailReg= /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if(email.length>0){
            if(!emailReg.test(email)){
                alert('邮箱格式不正确');
                return false;
            }
        }


        return true;
    }
</script>
</body>
</html>