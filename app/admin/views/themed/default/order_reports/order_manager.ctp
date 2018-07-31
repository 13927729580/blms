<?php
	/*
		搜索条件:
			date_type:日期类型(year:年,month:月,day:日,week:周),默认按月
			order_manager:销售顾问
			order_date_start:开始时间
			order_date_end:结束时间
	*/
	//pr($operator_list);
	// pr($order_info);
	//pr($user_list);
	//pr($report_list);
?>
<?php echo $form->create('OrderManager',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-2 am-avg-sm-1">
		<li style="margin-bottom:10px;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;">日期类型</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end">
				<select name="date_type" >
					<option value=""><?php echo $ld['please_select']?></option>
					<option value="year" <?php echo isset($date_type) && $date_type=='year'?"selected":"";?>>每年<?php //echo $ld['please_select']?></option>
					<option value="month" <?php echo isset($date_type) && $date_type=='month'?"selected":"";?>>每月<?php //echo $ld['please_select']?></option>
					<option value="week" <?php echo isset($date_type) && $date_type=='week'?"selected":"";?>>每周<?php //echo $ld['please_select']?></option>
					<option value="day" <?php echo isset($date_type) && $date_type=='day'?"selected":"";?>>每日<?php //echo $ld['please_select']?></option>
				</select>
			</div>
		</li>
		<li>
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;margin-left:0;">销售顾问</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end" style="padding-right:0;">
			<select name="order_manager" id="" >
				<option value="">所有</option>
				<?php foreach ($operator_list as $key => $value) {  ?>
				<option value="<?php echo $key; ?>" <?php echo isset($order_manager)&&$order_manager==$key?'selected':''; ?>><?php echo $value; ?></option>';
				<?php }?>
			</select>
		</div>
		</li>
		<li>
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;margin-top:0.3rem;">下单时间</label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;">
                <div class="am-input-group">
				<input id="start_date" type="text" name="order_date_start" value="<?php echo $order_date_start; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;"><em>-</em></label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-left:0;">
                <div class="am-input-group">
				<input type="text" id="end_date" name="order_date_end" value="<?php echo $order_date_end; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
		</li>
		<li style="margin-top:0.4rem;">
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<input type="button" onclick="search_order_report()"  class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
</div>
<?php echo $form->end();?>
<?php if (constant('Product') == 'AllInOne') { ?>
<div class='am-text-right'>
	<?php echo $html->link('改衣师业绩','/order_reports/order_picker',array('class'=>'am-btn am-btn-default am-btn-sm am-seevia-btn-view')); ?>
</div>
<?php } ?>
<div id="main" class="main" style="width: 100%;min-height: 400px;"></div>
<div class="am-g" style="border:1px solid #ddd;border-right:none;">
	<div class="am-cf" style="font-weight:700;">
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">订单号</div>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">销售顾问</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">下单日期</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">用户</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品货号</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品名称</div>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品数量</div>
	</div>
	<?php foreach ($order_info as $key => $value) {?>
	<div class="am-cf" style="border-top:1px solid #ddd;">
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['order_code'] ?></div>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php 
			foreach ($operator_list as $key3 => $value3) {
			if($key3 == $value['Order']['order_manager']){
				echo $value3;
			}
		}
		 ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['created'] ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php 
		foreach ($user_list as $key2 => $value2) {
			if($key2 == $value['Order']['user_id']){
				echo $value2['name'];
			}
		}
		?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_code'] ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_name'] ?></div>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_quntity'] ?></div>
	</div>
	<?php } ?>
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
	document.SReportForm.action=admin_webroot+"order_reports/order_manager";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}

var user_list=<?php echo json_encode($operator_list); ?>;
var report_list=<?php echo json_encode($report_list); ?>;
console.log(user_list)

//操作员
var OperatorList=[];
var TimeList=[];
var ReportData=[];

$.each(report_list,function(operator_name,report_data){
	OperatorList.push(operator_name);
	$.each(report_data,function(time){
		if($.inArray(time,TimeList)==-1){
			TimeList.push(time);
			ReportData[time]={
				'OrderTotal':[],
				'ProductTotal':[],
				'UserTotal':[]
			};
		}
	});
});
TimeList=TimeList.sort();

$.each(report_list,function(operator_name,report_data){
	$.each(TimeList,function(time_key,time){
		var report_info=typeof(report_data[time])!='undefined'?report_data[time]:null;
		if(report_info!=null){
			ReportData[time]['OrderTotal'].push({'name':operator_name,'value':report_info['order_total']});
			ReportData[time]['ProductTotal'].push({'name':operator_name,'value':report_info['product_total']});
			ReportData[time]['UserTotal'].push({'name':operator_name,'value':report_info['user_total']});
		}else{
			ReportData[time]['OrderTotal'].push({'name':operator_name,'value':0});
			ReportData[time]['ProductTotal'].push({'name':operator_name,'value':0});
			ReportData[time]['UserTotal'].push({'name':operator_name,'value':0});
		}
	});
});
loadReport();
function loadReport(){
	var MainDiv = $('#main');
	for(var time in ReportData){
		var report_data=ReportData[time];
		if(typeof(report_data)=='undefined')return;
		$('#main').append("<div id='"+time+"_order_total' style='float:left;width:50%;min-height:300px;'></div>");
		var OrderDiv=document.getElementById(time+'_order_total');
		var myChart = echarts.init(OrderDiv);
		OrderTotalOption = {
		    title : {
		        text: time+'订单金额统计',
		        subtext: '',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: OperatorList
		    },
		    series : [
		        {
		            name: '销售顾问',
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:report_data['OrderTotal'],
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
		myChart.setOption(OrderTotalOption);
		
		$('#main').append("<div id='"+time+"_product_total' style='float:left;width:50%;min-height:300px;'></div>");
		OrderDiv=document.getElementById(time+'_product_total');
		myChart = echarts.init(OrderDiv);
		ProductTotalOption = {
		    title : {
		        text: time+'商品数量统计',
		        subtext: '',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: OperatorList
		    },
		    series : [
		        {
		            name: '销售顾问',
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:report_data['ProductTotal'],
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
		myChart.setOption(ProductTotalOption);
		
		$('#main').append("<div id='"+time+"_user_total' style='float:left;width:50%;min-height:300px;'></div>");
		OrderDiv=document.getElementById(time+'_user_total');
		myChart = echarts.init(OrderDiv);
		UserTotalOption = {
		    title : {
		        text: time+'用户数量统计',
		        subtext: '',
		        x:'center'
		    },
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: OperatorList
		    },
		    series : [
		        {
		            name: '销售顾问',
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:report_data['UserTotal'],
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
		myChart.setOption(UserTotalOption);
		
		$('#main').append("<div class='am-cf'></div>");
	}
}
</script>