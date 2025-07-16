<?php
include_once 'checkAdmin.php'
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
        .current{color: blueviolet}
        #main{margin: 40px auto}
    </style>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/echarts.js"></script>
</head>
<body>
<h1>车辆管理</h1>
<h2><a href="index.php">返回首页</a> <a href="admin.php" >车辆管理</a> <a href="show.php" class="current">数据查看</a> 
<a href="logout.php">注销</a></h2>
<div id="main" style="width: 800px;height:500px;"></div>
<script type="text/javascript">
        var myChart = echarts.init(document.getElementById('main'));
        // 显示标题，图例和空的坐标轴
        myChart.setOption({
            title: {
            text: '车辆票数柱状图'
            },
            tooltip: {},
            legend: {
            data: ['票数']
            },
            xAxis: {
            data: []
            },
            yAxis: {},
            series: [
            {
            name: '票数',
            type: 'bar',
            data: []
            }
            ]
        });
        $.ajax({
            url:'getData.php',
            dataType:'json',
            success:function (data){
                myChart.setOption({
                    xAxis: {
                    data: data.categories
                    },
                    series: [
                        {
                            // 根据名字对应到相应的系列
                            name: '票数',
                            data: data.data
                        }
                    ]
                });
            },
            error:function (){
                alert('获取数据出错');
            }
        })

      // 使用刚指定的配置项和数据显示图表。
      myChart.setOption(option);
    </script>
</body>
</html>