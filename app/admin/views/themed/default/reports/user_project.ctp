<div class="am-g user_project_list">
	<?php echo $form->create('reports',array('action'=>'/user_project','type'=>'get','class'=>'am-form am-form-horizontal')); ?>
	<div>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<?php if($admin['actions']=='all'||$svshow->operator_privilege("account_check")||!empty($department_managers)){ ?>
			<li>
				<label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-text-center am-margin-left-0"><?php echo $ld['department'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-left-0 am-padding-right-0">
					<select name="department_id" data-am-selected="{maxHeight:300}" onchange="ajax_user_project_manager(this)">
						<option value='0'><?php echo $ld['all']; ?></option>
						<?php if(isset($DepartmentInfos)&&sizeof($DepartmentInfos)>0){foreach($DepartmentInfos as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo isset($department_id)&&$department_id==$k?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<?php } ?>
			<li>
				<label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-text-center am-margin-left-0">课程顾问</label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-left-0 am-padding-right-0">
					<select name="manager_id" data-am-selected="{maxHeight:300}" data-manager_id="<?php echo isset($manager_id)?$manager_id:0; ?>">
						<option value='0'><?php echo $ld['all']; ?></option>
						<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo isset($manager_id)&&$manager_id==$k?'selected':''; ?>><?php echo $v; ?></option>
						<?php }}else{ ?>
						<option value="<?php echo $admin['id']; ?>" <?php echo isset($manager_id)&&$manager_id==$admin['id']?'selected':''; ?>><?php echo $admin['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-text-center am-margin-left-0"><?php echo $ld['payment_time'] ?></label>
				<div class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-padding-left-0 am-padding-right-0">
					<div class="am-input-group">
						<input type="text" name="payment_time_start" value="<?php echo isset($payment_time_start)?$payment_time_start:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center am-padding-left-0 am-padding-right-0"><em>-</em></label>
				<div class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-padding-left-0 am-padding-right-0">
					<div class="am-input-group">
						<input type="text" name="payment_time_end" value="<?php echo isset($payment_time_end)?$payment_time_end:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-text-center am-margin-left-0"><?php echo $ld['category'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-left-0 am-padding-right-0">
					<select name="user_project_fee[]" multiple data-am-selected="{noSelectedText:'<?php echo $ld['all']; ?>'}">
						<?php if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo isset($user_project_fee)&&in_array($k,$user_project_fee)?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-center am-margin-left-0"><?php echo $ld['type']; ?></label>
				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-left-0">
					<select name="account_type" data-am-selected="{noSelectedText:'<?php echo $ld['all']; ?>'}">
						<option value=' '><?php echo $ld['all']; ?></option>
						<option value='0' <?php echo isset($account_type)&&$account_type=='0'?'selected':''; ?>>收入</option>
						<option value='1' <?php echo isset($account_type)&&$account_type=='1'?'selected':''; ?>>支出</option>
					</select>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-center am-margin-left-0">&nbsp;</label>
				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-left-0">
					<button class="btn am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['search']; ?></button>
				</div>
			</li>
		</ul>
	</div>
	<?php
			$project_default_colspan=isset($project_fee_types)&&!empty($project_fee_types)?sizeof($project_fee_types):(isset($info_resource['user_project_fee'])?sizeof($info_resource['user_project_fee']):1);
			if(isset($operator_project_datas)&&sizeof($operator_project_datas)>0){
	?>
	<div class='am-text-right am-margin-top-lg'>
		<button class="btn am-btn am-btn-warning am-btn-sm am-radius" name='export' value='1'><?php echo $ld['export']; ?></button>
	</div>
	<table class="am-table am-table-bordered am-margin-top-lg">
		<thead>
		        <tr>
				<th><?php echo $ld['real_name']; ?></th>
				<?php if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){if(isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k]))continue; ?>
				<th class="am-text-center" colspan="<?php echo isset($project_code_fee[$k])?sizeof($project_code_fee[$k]):$project_default_colspan; ?>"><?php echo $v; ?></th>
				<?php }} ?>
				<th class="am-text-center" colspan="<?php echo isset($project_fee_types)?sizeof($project_fee_types):(isset($info_resource['user_project_fee'])?sizeof($info_resource['user_project_fee']):1); ?>">合计</th>
		        </tr>
		        <tr>
				<th>&nbsp;</th>
				<?php if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){
							if(isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k]))continue;
				?>
					<?php if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k][$kkk]))continue; ?>
					<th class="am-text-center"><?php echo $vvv; ?></th>
					<?php }} ?>
				<?php }} ?>
				<?php if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_fee_types)&&!empty($project_fee_types)&&!in_array($kkk,$project_fee_types))continue; ?>
				<th class="am-text-center"><?php echo $vvv; ?></th>
				<?php }} ?>
		        </tr>
		</thead>
		<tbody>
			<?php
					$projects_user_list=array();
					$user_project_fee_list=array();
					$projects_user_fee_list=array();
					
					$user_project_fees=array();
				       foreach($operator_project_datas as $kk=>$vv){$user_project_fee_group=array(); ?>
			<tr>
				<td><?php echo isset($operator_infos[$kk])?$operator_infos[$kk]:' - ';$projects_user_list[]=isset($operator_infos[$kk])?$operator_infos[$kk]:$kk; ?></td>
				<?php if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){
							if(isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k]))continue;
							
							if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k][$kkk]))continue; ?>
					<td class='am-text-center'><?php $project_fee_info=isset($vv[$k][$kkk])?$vv[$k][$kkk]:0;
														 $user_project_fee_group[$kkk][]=$project_fee_info;
														 $user_project_fees[$k][$kkk][]=$project_fee_info;
						echo floor($project_fee_info); ?></td>
					<?php }} ?>
				<?php }}?>
				<?php if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_fee_types)&&!empty($project_fee_types)&&!in_array($kkk,$project_fee_types))continue; ?>
				<td class='am-text-center'><?php
					if(!isset($user_project_fee_list[$kk]))$user_project_fee_list[$kk]=0;
					$user_project_fee_list[$kk]+=isset($user_project_fee_group[$kkk])&&!empty(array_sum($user_project_fee_group[$kkk]))?array_sum($user_project_fee_group[$kkk]):'0';
					echo isset($user_project_fee_group[$kkk])&&!empty(array_sum($user_project_fee_group[$kkk]))?number_format(array_sum($user_project_fee_group[$kkk]),0,'.', ''):'0';
					$projects_user_fee_list[$kk][$kkk]=isset($user_project_fee_group[$kkk])&&!empty(array_sum($user_project_fee_group[$kkk]))?array_sum($user_project_fee_group[$kkk]):0;
					?></td>
				<?php }} ?>
		        </tr>
		       <?php } ?>
		       <tr>
				<td>小计</td>
				<?php 
				   		$user_project_fees_total=array();
						if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){
							if(isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k]))continue;
							
							if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_code_fee)&&!empty($project_code_fee)&&!isset($project_code_fee[$k][$kkk]))continue; ?>
					<td class='am-text-center'><?php
							$user_project_fees_total[$kkk][]=isset($user_project_fees[$k][$kkk])?array_sum($user_project_fees[$k][$kkk]):0;
						echo isset($user_project_fees[$k][$kkk])&&!empty(array_sum($user_project_fees[$k][$kkk]))?number_format(array_sum($user_project_fees[$k][$kkk]),0,'.', ''):'0'; ?></td>
					<?php }} ?>
				<?php }} ?>
				<?php if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){foreach($info_resource['user_project_fee'] as $kkk=>$vvv){if(isset($user_project_fee)&&!in_array($kkk,$user_project_fee)||isset($project_fee_types)&&!empty($project_fee_types)&&!in_array($kkk,$project_fee_types))continue; ?>
				<td class='am-text-center'><?php echo isset($user_project_fees_total[$kkk])&&array_sum($user_project_fees_total[$kkk])>0?number_format(array_sum($user_project_fees_total[$kkk]),0,'.', ''):'0'; ?></td>
				<?php }} ?>
		        </tr>
		</tbody>
	</table>
	<?php } ?>
	<?php echo $form->end(); ?>
	<div class='user_project_eachat am-margin-top-lg'>
		<?php
				if(isset($operator_project_datas)&&!empty($operator_project_datas)){
					$projects_fee_type=isset($info_resource['user_project_fee'])?$info_resource['user_project_fee']:array();
					$project_operators=array();
					$projects_fee_type_list=array();
					
					foreach($projects_user_fee_list as $k=>$v){
						$project_operators[]=isset($operator_infos[$k])?$operator_infos[$k]:$k;
						$operator_fee_total=array();
						foreach($projects_fee_type as $kk=>$vv){
							$projects_fee_type_list[$kk][]=isset($v[$kk])?number_format($v[$kk],2,'.', ''):0;
							$operator_fee_total[]=isset($v[$kk])?number_format($v[$kk],2,'.', ''):0;
						}
						$projects_fee_type_list[sizeof($projects_fee_type)][]=number_format(array_sum($operator_fee_total),2,'.', '');
					}
					$projects_fee_type[]='合计';
					$report_data=array(
						'legend'=>array_values($projects_fee_type),
						'xAxis'=>array_values($project_operators),
						'series'=>array_values($projects_fee_type_list)
					);
		?>
			<textarea readonly class='am-hide'><?php echo json_encode($report_data); ?></textarea>
			<div class='eachat_table' id='eachat_table'></div>
		<?php	}	?>
	</div>
</div>

<style type='text/css'>
.user_project_list table thead tr:nth-child(2) th{border-top:none;border-bottom:none;}
.user_project_eachat{}
.eachat_table{width:95%;height:300px;margin:0 auto;}
</style>
<script src="<?php echo $webroot; ?>plugins/echarts/dist/echarts.js"></script>
<script type='text/javascript'>
function ajax_user_project_manager(sel){
	var manager_id=$(sel).val();
	var operator_sel=$(".user_project_list select[name='manager_id']");
	var default_manager=$(operator_sel).attr("data-manager_id");
	$.ajax({
		type: "POST",
		url:admin_webroot+"reports/ajax_user_project_manager",
		dataType: 'json',
		data: {manager_id:manager_id},
		success: function (result) {
			operator_sel.find("option[value!='0']").remove();
	    		if(result.length>0){
	    			$.each(result,function(index,item){
	    				operator_sel.append("<option value='"+item.Operator.id+"' "+(default_manager==item.Operator.id?'selected':'')+">"+item.Operator.name+"</option>");
		    		});
	    		}
	    		operator_sel.trigger('changed.selected.amui');
	    	}
	});
}

if(document.getElementById('eachat_table')){
	var JSONDataTxt=$('#eachat_table').prev('textarea').val().trim();
	var JSONData=JSON.parse(JSONDataTxt);
	var echartSeries=[];
	$.each(JSONData.series,function(index,item){
		echartSeries.push({
			name:JSONData.legend[index],
			type:'bar',
			data:item,
			itemStyle:{
				normal:{
					label:{
						formatter : "{b} {c}",
					}
				}
			}
		});
	});
	require.config({
			paths: {
				echarts: '<?php echo $webroot; ?>plugins/echarts/dist'
			}
		});
		require(
			[
			'echarts',
			'echarts/theme/macarons',
	        	'echarts/chart/bar'
	        ],
		function (ec,theme) {
			var myChart = ec.init(document.getElementById('eachat_table'),theme);
			var option = {
				title: {
					text: '业绩汇总表',
					textStyle:{
						align:'center'
					}
				},
				tooltip : {
					trigger: 'axis'
				},
				color:['#3bb4f2','#5eb95e','#F37B1D','#dd514c','#808080'],
				legend: {
					data:JSONData.legend
				},
			    	toolbox: {
			        show : true,
			        feature : {
			            mark : {show: true},
			            dataView : {show: false, readOnly: false},
			            magicType : {show: true, type: ['bar']},
			            restore : {show: true},
			            saveAsImage : {show: true}
			        }
			    },
			    calculable : true,
			    xAxis : [
			        {
			            type : 'category',
			            data : JSONData.xAxis
			        }
			    ],
			    yAxis : [
			        {
			            type : 'value'
			        }
			    ],
			    series : echartSeries
			};
	            myChart.setOption(option);
	            setTimeout(function (){
		            window.onresize = function () {
		            		myChart.resize();
		            }
	            },200)
		}
	);
}
</script>