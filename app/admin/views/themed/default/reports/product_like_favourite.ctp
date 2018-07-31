<?php
	//pr($user_like_data);
	//pr($user_favourite_data);
	//pr($product_data);
	//pr($view_stat_data);	// 导出 /reports/export_product_view
?>


<div class="am-g">
<div class="am-u-lg-6 am-u-sm-12 am-u-md-6" id="main8" style="width: 50%;height:500px;"></div>
<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
<h2 class="am-text-center">收藏排行榜</h2>
<div class="am-u-sm-12 am-padding-0">
    <a href="<?php echo $html->url('/reports/export_product_favourite') ?>" class="am-fr am-btn am-btn-warning am-seevia-btn-add am-btn-sm">导出</a>
</div>
<table class="am-table am-table-bordered">
<thead>
        <tr>
            <th>名称</th>
            <th class="am-text-center">收藏数量</th>
        </tr>
</thead>
<tbody>
<?php foreach ($user_favourite_data as $k => $v) {?>
<tr>
    <td><?php echo $product_data[$v['product_id']] ?></td>
    <td class="am-text-center"><?php echo $v['count'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
<div class="am-g am-margin-top-lg am-padding-top-lg" style="border-top:1px solid #ddd">
<div class="am-u-lg-6 am-u-md-6 am-u-sm-12" id="main9" style="width: 50%;height:500px;"></div>
<div class="am-u-lg-6 am-u-sm-12 am-u-md-6">
<h2 class="am-text-center">点赞排行榜</h2>
<div class="am-u-sm-12 am-padding-0">
    <a href="<?php echo $html->url('/reports/export_product_like') ?>" class="am-fr am-btn am-btn-warning am-seevia-btn-add am-btn-sm">导出</a>
</div>
<table class="am-table am-table-bordered">
<thead>
        <tr>
            <th>名称</th>
            <th class="am-text-center">点赞数量</th>
        </tr>
</thead>
<tbody>
<?php foreach ($user_like_data as $k => $v) {?>
<tr>
    <td><?php echo $product_data[$v['product_id']] ?></td>
    <td class="am-text-center"><?php echo $v['count'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>


</div>

<div class="am-g am-margin-top-lg am-padding-top-lg" style="border-top:1px solid #ddd">
<div class="am-u-lg-6 am-u-md-6 am-u-sm-12" id="main10" style="width: 50%;height:500px;"></div>
<div class="am-u-lg-6 am-u-sm-12 am-u-md-6">
<h2 class="am-text-center">浏览量排行榜</h2>
<div class="am-u-sm-12 am-padding-0">
    <a href="<?php echo $html->url('/reports/export_product_view') ?>" class="am-fr am-btn am-btn-warning am-seevia-btn-add am-btn-sm">导出</a>
</div>
<table class="am-table am-table-bordered">
<thead>
        <tr>
            <th>名称</th>
            <th class="am-text-center">浏览量</th>
        </tr>
</thead>
<tbody>
<?php foreach ($view_stat_data as $k => $v) {?>
<tr>
    <td><?php echo $product_data[$v['product_id']] ?></td>
    <td class="am-text-center"><?php echo $v['count'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>


</div>
    <?php 
        $like_data = array();
        $favourite_data = array();
        $browse_data = array();
        $product_like_data = array();
        $product_favourite_data = array();
        $product_browse_data = array();
        foreach ($user_like_data as $k => $v) {
        $like_data[] = $v['count'];
        $product_like_data[] = $product_data[$v['product_id']];
        }  
        foreach ($user_favourite_data as $kk => $vv) {
        $favourite_data[] = $vv['count'];
        $product_favourite_data[] = $product_data[$vv['product_id']];
        }
        foreach ($view_stat_data as $sk => $sv) {
        $browse_data[] = $sv['count'];
        $product_browse_data[] = $product_data[$sv['product_id']];
        }
    ?>


<script src="<?php echo $webroot; ?>plugins/echarts/dist/echarts.js"></script>
<script type="text/javascript">

    var like_data = <?php echo json_encode($like_data) ?>;
    var favourite_data = <?php echo json_encode($favourite_data) ?>;
    var browse_data = <?php echo json_encode($browse_data) ?>;
    var product_like_data = <?php echo json_encode($product_like_data) ?>;
    var product_favourite_data = <?php echo json_encode($product_favourite_data) ?>;
    var product_browse_data = <?php echo json_encode($product_browse_data) ?>;
    var product=new Array();
    // for (var i = 0; i <= 9; i++) {
    // product[i] = product_like_data[i]+'/'+product_favourite_data[i];
    // }
	require.config({
		paths: {
			echarts: '<?php echo $webroot; ?>plugins/echarts/dist'
		}
	});
	require(
		[
		'echarts',
		'echarts/theme/macarons',
		'echarts/chart/line',
        	'echarts/chart/bar'
        ],
	function (ec,theme) {
	var myChart = ec.init(document.getElementById('main8'),theme);
	var option = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['收藏']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: false, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data : ['第一名','第二名','第三名','第四名','第五名','第六名','第七名','第八名','第九名','第十名',]
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'收藏',
            type:'bar',
            data:favourite_data,
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    // {type : 'average', name: '平均值'}
                ]
            }
        },
        
    ]
};
            myChart.setOption(option);
            setTimeout(function (){
            window.onresize = function () {
            myChart.resize();
            }
            },200)
}
);


</script>

<script>
require.config({
        paths: {
            echarts: '<?php echo $webroot; ?>plugins/echarts/dist'
        }
    });
    require(
        [
        'echarts',
        'echarts/theme/default',
        'echarts/chart/line',
            'echarts/chart/bar'
        ],
    function (ec1,theme1) {
    var myChart1 = ec1.init(document.getElementById('main9'),theme1);
    var option = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['点赞']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: false, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data :  ['第一名','第二名','第三名','第四名','第五名','第六名','第七名','第八名','第九名','第十名',]
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'点赞',
            type:'bar',
            data:like_data,
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    // {type : 'average', name : '平均值'}
                ]
            }
        },
    ]
};
            myChart1.setOption(option);
            setTimeout(function (){
            window.onresize = function () {
            myChart1.resize();
            }
            },200)
}
)
</script>

<script>
require.config({
        paths: {
            echarts: '<?php echo $webroot; ?>plugins/echarts/dist'
        }
    });
    require(
        [
        'echarts',
        'echarts/theme/infographic',
        'echarts/chart/line',
            'echarts/chart/bar'
        ],
    function (ec2,theme2) {
    var myChart2 = ec2.init(document.getElementById('main10'),theme2);
    var option = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['浏览量']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: false, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data :  ['第一名','第二名','第三名','第四名','第五名','第六名','第七名','第八名','第九名','第十名',]
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'浏览量',
            type:'bar',
            data:browse_data,
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    // {type : 'average', name : '平均值'}
                ]
            }
        },
    ]
};
            myChart2.setOption(option);
            setTimeout(function (){
            window.onresize = function () {
            myChart1.resize();
            }
            },200)
}
)
</script>