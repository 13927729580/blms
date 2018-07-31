<?php
	/*
		date_start:开始时间
		date_end:结束时间
		date_type:时间类型(month:月，week:周，day:天)
	*/
	//pr($date_type);
	//积分获得
	//pr($point_increase_log_data);
//	
//	//积分使用
	//pr($point_use_log_data);
//	
//	//分享访问
	//pr($share_affiliate_data);
	
	/*
		详情跳转链接
		/user_reports/view/时间对象/时间类型
	*/
	
	//每周一天
	//pr($week_list);
	
	$report_date_list=array_merge(array_keys($point_increase_log_data),array_keys($point_use_log_data),array_keys($share_affiliate_data));
	$report_date_list=array_unique($report_date_list);
	
	$report_data=array();
	foreach($report_date_list as $report_date){
		$report_data[$report_date]=array(
			'point_increase'=>isset($point_increase_log_data[$report_date])?$point_increase_log_data[$report_date]:0,
			'point_use'=>isset($point_use_log_data[$report_date])?$point_use_log_data[$report_date]:0,
			'share_affiliate'=>isset($share_affiliate_data[$report_date])?$share_affiliate_data[$report_date]:0,
		);
	}
	//pr($report_data);
?>
<style>
	.am-tabs>.am-nav > li > a:hover{
		background-color: #eee;
	}
</style>
<?php echo $form->create('UserReport',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li style="margin-bottom:10px;width: 30%;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-center" style="font-weight:bold;">日期类型</label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<select name="date_type" id="date_type" data-am-selected="{noSelectedText:''}">
					<option value=""><?php echo $ld['please_select']?></option>
					<option value="month" <?php echo isset($date_type) && $date_type=='month'?"selected":"";?>>每月<?php //echo $ld['please_select']?></option>
					<option value="week" <?php echo isset($date_type) && $date_type=='week'?"selected":"";?>>每周<?php //echo $ld['please_select']?></option>
					<option value="day" <?php echo isset($date_type) && $date_type=='day'?"selected":"";?>>每日<?php //echo $ld['please_select']?></option>
				</select>
			</div>
		</li>
		<li>
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">报表时间</label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;">
                <div class="am-input-group">
				<input id="start_date" type="text" name="date_start" value="<?php echo $date_start; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;"><em>-</em></label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-left:0;">
                <div class="am-input-group">
				<input type="text" id="end_date" name="date_end" value="<?php echo $date_end; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<input type="button" onclick="search_order_report()"  class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
</div>
<?php echo $form->end();?>
<?php if (constant('Product') == 'AllInOne') { ?>
<div class='am-text-right'>
	<?php echo $html->link('客户订单','/order_reports/order_user',array('class'=>'am-btn am-btn-default am-btn-sm am-seevia-btn-view')); ?>
</div>
<?php } ?>
<!-- 图表 -->
<div id="main" class="main" style="width: 100%;height: 400px;"></div>
<!-- 表单 -->
<div class="am-u-lg-12">
<div class="am-g" style="border:1px solid #ddd;border-bottom:none;border-right:none;">
	<div style="border-bottom:1px solid #ddd;font-weight:600;">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">时间</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">变更总数</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">使用总数</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">访问次数</div>
		<div class="am-cf"></div>
	</div>
	<?php foreach ($report_data as $key => $value) {?>
	<div style="border-bottom:1px solid #ddd;">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;cursor:pointer;" onclick="jump(this)"><?php echo $key ?></div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo current($value);next($value); ?></div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo current($value);next($value); ?></div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo current($value) ?></div>
		<div class="am-cf"></div>
	</div>
	<?php } ?>
</div>
</div>
<script src="<?php echo $webroot; ?>plugins/echarts/echarts.min.js"></script>
<script>
	function search_order_report(){
	var start_date=$("#start_date").val();
	var end_date=$("#end_date").val();
	if(start_date==""){
		alert("开始时间不能为空");
		return false;
	}
	if(end_date==""){
		alert("结束时间不能为空");
		return false;
	}
	document.SReportForm.action=admin_webroot+"user_reports/";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}
//积分获得报表
var report_data=<?php echo json_encode($report_data); ?>;
var key_list=[];
var value_list=[];
var value_list1=[];
var value_list2=[];
var value_list3=[];
var count = 1;
$.each(report_data,function(index,item){
	key_list.push(index);
	$.each(item,function(index1,item1){
		if(index1 == 'point_increase'){
			value_list1.push(item1);
		}else if(index1 == 'point_use'){
			value_list2.push(item1);
		}else if(index1 == 'share_affiliate'){
			value_list3.push(item1);
		}
			

	});
});
console.log(value_list2);
var myChart = echarts.init(document.getElementById('main')); 
	option = {
	    title : {
	        text: '积分获得',
	        subtext: ''
	    },
	    tooltip : {
	        trigger: 'axis'
	    },
	    legend: {
	        data:['变更总数','使用总数','访问次数']
	    },
	    toolbox: {
	        show : true,
	        feature : {
	            mark : {show: true},
	            dataView : {show: true, readOnly: false},
	            magicType : {show: true, type: ['line', 'bar']},
	            restore : {show: true},
	            saveAsImage : {show: true}
	        }
	    },
	    calculable : true,
	    xAxis : [
	        {
	            type : 'category',
	            data : key_list
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value'
	        }
	    ],
	    series : [
	        {
	            name:'变更总数',
	            type:'bar',
	            data:value_list1
	           
	        }, {
	        	name:'使用总数',
	            type:'bar',
	            data:value_list2
	        },{
	        	name:'访问次数',
	            type:'bar',
	            data:value_list3
	        }    
	    ]
	};
  	myChart.setOption(option);

var da1=<?php echo json_encode($week_list); ?>;

function jump(obj){
	//alert($(obj).text());
	var da_type = $("#date_type").val();
	var date = $(obj).text();
	var add = '20';
	//alert(date);
	if(da_type == 'week'){
		$.each(da1,function(index,item){
			if(date == index){
				date = item;
			}
		});
		add='';
	}
	
	window.location.href='/admin/user_reports/view/'+add+date+'/'+da_type;
}
</script>