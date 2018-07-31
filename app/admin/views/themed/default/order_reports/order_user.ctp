<?php
	/*
		搜索条件:
			date_type:日期类型(year:年,month:月,day:日,week:周),默认按月
			order_date_start:开始时间
			order_date_end:结束时间
	*/
	
	 // pr($order_list);//用户订单金额
?>
<?php echo $form->create('OrderReport/OrderUser',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li style="margin-bottom:10px;width: 30%;display: none;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-center" style="font-weight:bold;">日期类型</label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<select name="date_type" data-am-selected="{noSelectedText:''}">
					<option value=""><?php echo $ld['please_select']?></option>
					<option value="year" <?php echo isset($date_type) && $date_type=='year'?"selected":"";?>>每年<?php //echo $ld['please_select']?></option>
					<option value="month" <?php echo isset($date_type) && $date_type=='month'?"selected":"";?>>每月<?php //echo $ld['please_select']?></option>
					<option value="week" <?php echo isset($date_type) && $date_type=='week'?"selected":"";?>>每周<?php //echo $ld['please_select']?></option>
					<option value="day" <?php echo isset($date_type) && $date_type=='day'?"selected":"";?>>每日<?php //echo $ld['please_select']?></option>
				</select>
			</div>
		</li>
		<li>
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">下单时间</label>
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
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<input type="button" onclick="search_order_report()"  class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
			</div>
		</li>

	</ul>
</div>
<?php echo $form->end();?>
<div id="main1" class="main" style="width: 100%;min-height: 400px;"></div>
<div id="main2" class="main" style="width: 100%;min-height: 400px;"></div>
<div class="am-g" style="border:1px solid #ddd;border-right:none;">
    <div class="am-cf" style="font-weight:700;">
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">订单号</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">用户</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">下单日期</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品货号</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品数量</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;">商品名称</div>
    </div>
    <?php foreach ($order_list as $key => $value) {?>
    <div class="am-cf" style="border-top:1px solid #ddd;">
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['order_code'] ?></div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php 
            foreach ($user_list as $key2 => $value2) {
                if($key2 == $value['Order']['user_id']){
                    echo $value2;
                }
            }
        ?></div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['Order']['created'] ?></div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_code'] ?></div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_quntity'] ?></div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="border-right:1px solid #ddd;padding-top:0.5rem;padding-bottom:0.5rem;"><?php echo $value['OrderProduct']['product_name'] ?></div>
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
	document.SReportForm.action=admin_webroot+"order_reports/order_user";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}

var user_list=<?php echo json_encode($user_list); ?>;
var report_list1=<?php echo json_encode($report_list1); ?>;

var report1_user_list=[],report1_order_total=[],report1_order_count=[];
$.each(report_list1,function(user_id,order_data){
	report1_user_list.push(typeof(user_list[user_id])=='undefined'?user_id:user_list[user_id]);
	report1_order_total.push({'name':typeof(user_list[user_id])=='undefined'?user_id:user_list[user_id],'value':order_data['order_total']});
	report1_order_count.push({'name':typeof(user_list[user_id])=='undefined'?user_id:user_list[user_id],'value':order_data['order_count']});
});
var OrderDiv=document.getElementById('main1');
var ReportOption = {
    title : {
        text: '用户订单金额统计',
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
        data: report1_user_list
    },
    series : [
        {
            name: '用户订单金额',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:report1_order_total,
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

var OrderDiv=document.getElementById('main2');
var ReportOption = {
    title : {
        text: '用户订单数量',
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
        data: report1_user_list
    },
    series : [
        {
            name: '用户订单数量',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:report1_order_count,
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




</script>