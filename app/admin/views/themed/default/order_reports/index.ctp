<?php
	/*
		搜索条件:
			date_type:日期类型(year:年,month:月,day:日,week:周),默认按月
			order_date_start:开始时间
			order_date_end:结束时间
	*/
	//pr($report_list);
?>
<?php echo $form->create('OrderReport',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li style="margin-bottom:10px;width: 30%;">
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
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">财务时间</label>
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
<div id="main" class="main" style="width: 100%;height: 400px;"></div>
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
	document.SReportForm.action=admin_webroot+"order_reports/";
	document.SReportForm.onsubmit= "";
	document.SReportForm.submit();
}

var report_list=<?php echo json_encode($report_list); ?>;

var key_list=[];
var value_list=[];
$.each(report_list,function(index,item){
	key_list.push(index);
	value_list.push(item);
});


	var myChart = echarts.init(document.getElementById('main')); 
	option = {
	    title : {
	        text: '财务收入',
	        subtext: ''
	    },
	    tooltip : {
	        trigger: 'axis'
	    },
	    legend: {
	        data:['月收入']
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
	            name:'月收入',
	            type:'bar',
	            data:value_list
	           
	        },       
	    ]
	};
  	myChart.setOption(option);


</script>