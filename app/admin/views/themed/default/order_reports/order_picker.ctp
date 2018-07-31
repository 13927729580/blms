<?php
	/*
		搜索条件:
			date_type:日期类型(year:年,month:月,day:日,week:周),默认按月
			picker:改衣师
			order_date_start:开始时间
			order_date_end:结束时间
	*/

	// pr($order_product_info);
	// pr($operator_list);
?>
<?php echo $form->create('OrderPicker',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li style="margin-bottom:10px;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;">日期类型</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end" style="">
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
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-left" style="font-weight:bold;margin-left:0;">改衣师</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end" style="padding-right:0;">
				<select name="picker" id="">
					<option value="">所有</option>
					<?php foreach ($operator_list as $key => $value) {  ?>
					<option value="<?php echo $key; ?>" <?php echo isset($picker)&&$picker==$key?'selected':''; ?>><?php echo $value; ?></option>';
					<?php }?>
				</select>
			</div>
		</li>
		<li>
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;">下单时间</label>
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
		<li>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<input type="button" onclick="search_order_report()"  class="btn am-btn am-btn-success am-btn-sm am-radius" style="" value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
</div>
<?php echo $form->end();?>
<div id="main" class="main" style="width: 100%;min-height: 400px;"></div>
<div class="am-g" style="border:1px solid #ddd;border-right:none;">
	<div class="am-cf" style="font-weight:700;">
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">订单号</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">改衣师</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">下单日期</div>
		
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品货号</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品名称</div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品数量</div>
	</div>
	<?php foreach ($order_product_info as $key => $value) {?>
	<div class="am-cf" style="border-top:1px solid #ddd;">
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['order_code'] ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php 
			foreach ($operator_list as $key3 => $value3) {
			if($key3 == $value['OrderProduct']['picker']){
				echo $value3;
			}
		}
		 ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['created'] ?></div>
		
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_code'] ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_name'] ?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_quntity'] ?></div>
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
	document.SReportForm.action=admin_webroot+"order_reports/order_picker";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}

var user_list=<?php echo json_encode($operator_list); ?>;
var report_list1=<?php echo json_encode($report_list); ?>;

$.each(report_list1,function(pick,pick_list){
	$('#main').append("<div id='pick_"+pick+"' style='float:left;width:50%;min-height:300px;'></div>");
	var OrderDiv=document.getElementById('pick_'+pick);
	var report_data=[];
	var date_list=[];
	$.each(pick_list,function(date,total){
		date_list.push(date);
		report_data.push({'name':date,'value':total});
	});
	var ReportOption = {
	    title : {
	        text: '改衣师'+pick+'统计',
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
	        data: date_list
	    },
	    series : [
	        {
	            name: '改衣师',
	            type: 'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data:report_data,
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
	var myChart = echarts.init(OrderDiv);
	myChart.setOption(ReportOption);
});

var report_list2=<?php echo json_encode($order_product_pick_list); ?>;
var ProductTotal=[];
var PassrateTotal=[];
var PickList=[];
$.each(report_list2,function(pick,pick_value){
	PickList.push(pick);
	ProductTotal.push(pick_value['total']);
	PassrateTotal.push(pick_value['pass_rate']);
});

$('#main').append("<div id='pick_info' style='clear:both;min-height:300px;'></div>");
var OrderDiv=document.getElementById('pick_info');
var pick_option = {
    title : {
        text: '改衣师统计',
        subtext: ''
    },
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['修改件数','合格率']
    },
    toolbox: {
        show : true,
        feature : {
            dataView : {show: true, readOnly: false},
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: false}
        }
    },
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data :PickList
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'修改件数',
            type:'bar',
            data:ProductTotal
        },
        {
            name:'合格率',
            type:'bar',
            data:PassrateTotal
        }
    ]
};
var myChart = echarts.init(OrderDiv);
myChart.setOption(pick_option);
</script>