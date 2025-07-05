<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <title>自由站点投票</title>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <style>
        .login{text-align:right;margin:30px;}
        .img{position:relative}
        .row img{width: 100%;}
        .img .row{position: absolute;bottom: 0;left: 15px;background-color: rgba(0,0,0,0.5);width:100%;color:white;}
        p{margin: 10px 0 !important;}
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">我最爱的车辆投票</h1>
    <p class="login">登录 注册</p>
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

                <a href="vote.php?id=<?php echo $info['id'];?>"><img src="img/<?php echo $info['carPIC'];?>"></a>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-6">
                        <p class="text-center"><?php echo $info['carName'];?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6">
                        <p class="text-center">当前票数：<?php echo $info['carNum'];?></p>
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
</body>
</html>