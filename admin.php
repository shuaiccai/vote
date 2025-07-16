<?php
include_once 'checkAdmin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我最爱的汽车投票</title>
    <style>
        h1,h2{text-align:center}
        h2{font-size:20px}
        h2 a{text-decoration:none;color: #4476A7;}
        h2 a:hover{text-decoration: underline;color: brown}
        .img{width: 100%;max-width:250px}
        .current{color: blueviolet}
    </style>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="layer/layer.js"></script>
</head>
<body>
<h1>车辆管理</h1>
<h2><a href="index.php">返回首页</a> <a href="admin.php" class="current">车辆管理</a> <a href="show.php">数据查看</a> 
<a href="logout.php">注销</a></h2>
<?php
include_once 'conn.php';
include_once 'page.php';
$sql = "select count(id) as total from carinfo";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_array($result);
$total = $info['total'];
$perPage = 4;
$page = $_GET['page'] ?? 1;
paging($total,$perPage);
$sql = "select * from carinfo order by id desc limit $firstCount,$displayPG";
$result = mysqli_query($conn,$sql);
?>
    <table border="0" width= "100%" align="center">
        <tr><td>
            <table align="center" width= "100%" border="1" bordercolor="black" 
            cellspacing="0" cellpadding="10" style="border-collapse: collapse">
                <tr>
                    <td align="center" width="8%">序号</td>
                    <td align="center" width="20%">车辆名称</td>
                    <td align="center" width="39%">车辆描述</td>
                    <td align="center" width="10%">车辆图片</td>
                    <td align="center" width="8%">当前票数</td>
                    <td align="center" width="15%">操作</td>
                </tr>
                <?php
                $i = ($page - 1) * $perPage + 1;
                while($info = mysqli_fetch_array($result)){
                ?>
                <tr>
                    <td align="center"><?php echo $i;?></td>
                    <td align="center"><?php echo $info['carName'];?></td>
                    <td align="center"><?php echo $info['carDesc'];?></td>
                    <td align="center"><img class="img" src="img/<?php echo $info['carPic'];?>"></td>
                    <td align="center"><?php echo $info['carNum'];?></td>
                    <td align="center"><a href="modifyCar.php?id=<?php echo $info['id'];?>">修改资料</a> 
                    <a href="javascript:del('<?php echo $info['carName'];?>',<?php echo $info['id'];?>)">删除资料</a></td>
                </tr>
                <?php
                    $i++;
                }
                ?>
            </table>
        </td></tr>
        <tr>
            <td align="right">
                    <?php
                    echo $pageNav;
                    ?>
            </td>
        </tr>
        <tr>
            <td>
                <h2>车辆添加</h2>
                <form onsubmit="return check()" enctype="multipart/form-data" method="post" action="postAddCar.php">
                    <table width="70%" align="center style="border-collapse: collapse;" border="1" bordercolor="gray" 
                    cellpadding="10" cellspacing="0">
                        <tr>
                            <td align="right">车辆名称</td>
                            <td align="left"><input name="carName" id="carName"></td>
                        </tr>
                        <tr>
                            <td align="right">车辆描述</td>
                            <td align="left"><textarea name="carDesc" id="carDesc"></textarea></td>
                        </tr>
                        <tr>
                            <td align="right">车辆图片</td>
                            <td align="left"><input type="file" name="carPic" id="carPic"></td>
                        </tr>
                        <tr>
                            <td align="right"><input type="submit" value="添加"></td>
                            <td align="left"><input type="reset" value="重置"></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
<script>
            function del(name,id){
                layer.confirm('您确定要删除车辆' + name + ' ?', {icon: 3, title:'提示'}, 
    function(index){
        location.href = 'delCar.php?id='+id;
        //do something
        
        layer.close(index);
        });
    }
    function check(){
        let carName = $("#carName").val().trim();
        let carDesc = $("#carDesc").val().trim();
        let carPic= $("#carPic").val().trim();
        if(carName == '' || carDesc == '' || carPic== ''){
            alert('车辆名称，车辆描述，车辆图片都不能为空');
            return false;
        }
        return ture;
    }
</script>   
</body>
</html>