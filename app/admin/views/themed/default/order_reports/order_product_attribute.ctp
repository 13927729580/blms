<script src="<?php echo $webroot; ?>plugins/echarts/echarts.min.js"></script>
<?php echo $form->create('order_reports',array('action'=>'/order_product_attribute','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
<div style="margin-bottom: 50px;">
	<ul class="am-avg-lg-2 am-avg-md-2 am-avg-sm-1">
		<li style="margin-bottom:10px;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;"><?php echo $ld['attribute'] ?></label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-u-end">
				<select name="attribute_id" data-am-selected>
					<option value="0"><?php echo $ld['all']?></option>
					<?php if(isset($attribute_list)&&sizeof($attribute_list)>0){foreach($attribute_list as $k=>$v){ ?>
					<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
		</li>
		<li>
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="font-weight:bold;margin-top:0.3rem;">下单时间</label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;">
				<div class="am-input-group">
					<input id="start_date" type="text" name="date_start" value="<?php echo isset($date_start)?$date_start:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
					<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
						<i class="am-icon-remove"></i>
					</span>
				</div>
			</div>
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;"><em>-</em></label>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-left:0;">
				<div class="am-input-group">
					<input type="text" id="end_date" name="date_end" value="<?php echo isset($date_end)?$date_end:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
					<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
						<i class="am-icon-remove"></i>
					</span>
				</div>
			</div>
		</li>
		<li style="margin-top:0.4rem;">
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<input type="submit"  class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
</div>
<?php 	echo $form->end(); //pr($order_product_attribute_data);
?>
<div id="main" class="main" style="width: 100%;min-height: 400px;"></div>
<div class='report_data'>
	<table class='am-table'>
		<tr>
			<th><?php echo $ld['attribute']; ?></th>
			<th><?php echo $ld['option']; ?></th>
			<th><?php echo $ld['count']; ?></th>
		</tr>
		<?php foreach($order_product_attribute_data as $attribute_id=>$attribute_value_list){ ?>
		<tr>
			<td rowspan="<?php echo sizeof($attribute_value_list)+1; ?>"><?php echo $attribute_list[$attribute_id]; ?></td>
		</tr>
		<?php if(is_array($attribute_value_list)&&sizeof($attribute_value_list)>0){foreach($attribute_value_list as $kk=>$vv){ ?>
		<tr>
			<td><?php echo isset($attribute_option_list[$attribute_id][$kk])?$attribute_option_list[$attribute_id][$kk]:$kk; ?></td>
			<td><?php echo $vv; ?></td>
		</tr>
		<?php }} ?>
		<?php } ?>
	</table>
</div>
<script type='text/javascript'>
var report_list=<?php echo json_encode($order_product_attribute_data); ?>;
var attribute_list=<?php echo json_encode($attribute_list); ?>;
var attribute_option_list=<?php echo json_encode($attribute_option_list); ?>;
$.each(report_list,function(attribute_id,attribute_value_list){
	var resport_fields=[];
	var resport_field_values=[];
	$.each(attribute_value_list,function(attribute_value,attribute_value_total){
		if(typeof(attribute_option_list[attribute_id])!='undefined'&&typeof(attribute_option_list[attribute_id][attribute_value])!='undefined'){
			attribute_value=attribute_option_list[attribute_id][attribute_value];
		}
		resport_fields.push(attribute_value);
		resport_field_values.push({'name':attribute_value,'value':attribute_value_total});
	});
	var attribute_name=typeof(attribute_list[attribute_id])!='undefined'?attribute_list[attribute_id]:attribute_id;
	$('#main').append("<div id='report_"+attribute_id+"' style='float:left;width:50%;min-height:300px;'></div>");
	var OrderDiv=document.getElementById('report_'+attribute_id);
	var myChart = echarts.init(OrderDiv);
	var report_option={
		    title : {
		        text: attribute_name,
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
		        data: resport_fields
		    },
		    series : [
		        {
		            name: attribute_name,
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:resport_field_values,
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
	myChart.setOption(report_option);
});
</script>