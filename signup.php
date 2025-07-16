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
        .green{color:green}
        .black{color:black}
        #loading{width:30px;display: none}
    </style>
</head>
<body>
<div class="main">

    <form action="postReg.php" method="post" onsubmit="return check()">
        <table align="center" border="1" style="border-collapse: collapse" cellpadding="10" cellspacing="0">
            <tr>
                <td align="right">用户名</td>
                <td align="left"><input name = "username" onblur="checkUsername()">
                    <span class="red">*</span> <span id="usernameMsg"></span><img src="img/loading.gif" id = "loading"></td>
            </tr>
            <tr>
                <td align="right">密码</td>
                <td align="left"><input type = "password" name="pw"><span class="red">*</span></td>
            </tr>
            <tr>
                <td align="right">确认密码</td>
                <td align="left"><input type = "password" name="cpw"><span class="red">*</span></td>
            </tr>

            <tr>
                <td align="right">邮箱</td>
                <td align="left"><input name = "email"></td>
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
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script>
    function checkUsername(){
        let username = document.getElementsByName("username")[0].value.trim();
        let usernameReg= /^[a-zA-Z0-9]{3,10}$/;
        if(!usernameReg.test(username)){
            alert("用户名必填，且只能由大小写字符和数字构成，长度为3-10个字符");
            $("#usernameMsg").text('');
            return false;
        }
        $.ajax({
            url:"checkUsername.php",
            type:'post',
            dataType:'json',
            data:{username:username},
            beforeSend:function(){
                $("#usernameMsg").text('');
                $("#loading").show();
            },
            success:function(data){

                $("#loading").hide();
                if(data.code === 0){
                    $("#usernameMsg").text(data.msg).removeClass('black').addClass('green');
                }else if(data.code === 2){
                    $("#usernameMsg").text(data.msg).removeClass('green').addClass('black');
                }
            },
            error:function(){
                $("#loading").hide();

                alert('网络错误');
            }
        })
    }
    function check(){
        let username = document.getElementsByName("username")[0].value.trim();
        let pw = document.getElementsByName("pw")[0].value.trim();
        let cpw = document.getElementsByName("cpw")[0].value.trim();
        let email = document.getElementsByName("email")[0].value.trim();
        let sex = document.getElementsByName("sex")[0];
        if(sex){
            console.log(sex);
        }


        let usernameReg= /^[a-zA-Z0-9]{3,10}$/;
        if(!usernameReg.test(username)){
            alert("用户名必填，且只能you大小写字符和数字构成，长度为3-10个字符");
            return false;
        }
        let pwreg = /^[a-zA-Z0-9]{6,10}$/;
        if(!pwreg.test(pw)){
            alert("密码必填，且只能大小写字符和数字构成，长度为3-10个字符");
            return false;
        }else{
            if(pw!==cpw){
                alert('密码和确认密码必须相同！');
                return false;
            }
        }
        let emailReg= /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        // /^[a-zA-Z0-9_\-] + @[a-zA-Z0-9]+ \.(com|cn|net|org)$/;
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