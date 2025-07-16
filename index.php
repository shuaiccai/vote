<?php
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <title>自由站点投票</title>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="layer/layer.js"></script>
    <style>
        .login{text-align:right;margin:30px;}
        .img{position:relative}
        .row img{width: 100%;}
        .img .row{position: absolute;bottom: 0;left: 15px;background-color: rgba(0,0,0,0.5);width:100%;color:white;}
        p{margin: 10px 0 !important;}
        /* .code{display:none} */
        .code td{padding: 10 !important;}
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">我最爱的车辆投票</h1>
    <p class="login">
        <?php
        if(isset($_SESSION['loggedUsername']) and $_SESSION['loggedUsername'] !=''){
            ?>
            当前登陆者：<?php echo $_SESSION['loggedUsername'] ?> <a href="logout.php">注销</a> 
        <a href="javascript:open('signup.php','用户注册')">注册</a> 
        <a href="javascript:open('modify.php','用户注册')">修改资料</a>
        
        <?php if($_SESSION['isAdmin']){?><a href="admin.php">后台管理</a><?php }?>
        <?php
        }else{
            ?>
            <a href="javascript:open('login.php','用户登录')">登录</a> 
        <a href="javascript:open('signup.php','用户注册')">注册</a>
        <?php
        }
        ?>
    </p>
    <div class="row">
        <?php
        include_once 'conn.php';
        $sql = "select * from carinfo order by id desc";
        $result = mysqli_query($conn,$sql);
        $i = 1;
        while($info = mysqli_fetch_array($result)){

        ?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
            <div class="img">
                <?php
                if(isset($_SESSION['loggedUsername']) and $_SESSION['loggedUsername'] !=''){
                ?>

                <a href="javascript:showCode(<?php echo $info['id'];?>)"><img src="img/<?php echo $info['carPic'];?>"></a>
                <?php
                }
                else{
                    ?>
                     <img src="img/<?php echo $info['carPic'];?>">
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-6">
                        <p class="text-center"><?php echo $info['carName'];?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6">
                        <p class="text-center">当前票数：<span id="num<?php echo $info['id'];?>"><?php echo $info['carNum'];?></span></p>
                    </div>
                </div>
            </div>
            <p><?php echo $info['carDesc'];?></p>
        </div>
        <?php
            if($i % 2 == 0){
                echo '<div class="clearfix visible-sm-block"></div>';
            }
            if($i % 3 == 0){
                echo '<div class="clearfix visible-md-block"></div>';
            }
            if($i % 4 == 0){
                echo '<div class="clearfix visible-lg-block"></div>';
            }
            $i++;
        }
        ?>

    </div>
</div>

<script>
    function showCode(id){
        let str = '';
        str += '<div class="code">';
        // str += '<form action="vote.php" method="GET">';
        str += '<table style="border-collapse: collapse" border="1" bordercolor = "gray" cellspacing="0">';
        str += '<tr>';
        str += '<td align="right">验证码</td>';
        str += '<td align="left"><input name="code" id="code"><img src="code.php" id="code1">';
        str += '<input type="hidden" name="id" id="carID"> </td>';
        str += '<tr>';
        str += '<tr>';
        str += '<td align="right"><input type="button" id="postVote" value="提交"></td>';
        str += '<td align="left"><input type="reset" value="重置"></td>';
        str += '</tr>';
        str += '</table>';
        // str += '</form>';
        str += '</div>';
        layer.open({
            type: 1,
            title: '请输入验证码',
            shadeClose: false,
            closeBtn :2,
            content: str,
            success: function(layero,index){
                $("#postVote").click(function (){
            $.ajax({
                url:'ajaxVote.php',
                data:{id:id,code:$("#code").val().trim()},
                dataType:'json',
                type:'GET',
                success:function(d){
                    if(d.error == 1){
                        layer.alert(d.errMsg, {icon: 2});
                        layer.closeAll();
                    }else{
                        let num =parseInt($("#num" +id).text());
                        $("#num" +id).text(num + 1);
                        layer.alert('投票成功',{icon: 1});
                        layer.closeAll();
                    }
                },
                error:function(){
                    layer.alert(d.errMsg, {icon: 3});
                    layer.closeAll();
                }
            })
        })
        $("#code1").click(function (){
        $(this).attr('src','code.php?id='+new Date());
        })
            }
            });

        
    }
    function open(url,title){
        layer.open({
            type: 2,
            title:title,
            area: ['700px', '450px'],
            fixed: false, //不固定
            maxmin: true,
            content: url
        });
    }

    function closeLayer(){
        layer.closeAll();
    }
</script>
</body>
</html>