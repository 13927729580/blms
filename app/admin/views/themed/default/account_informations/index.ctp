<div class='am-g'>
    <?php echo $form->create('account_informations',array('action'=>'/',"type"=>"get",'class'=>'am-form am-form-horizontal','id'=>'account_information_search'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
    	<!--
       <li class='am-hide'> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['classification']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="account_category" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:300}">
				<optgroup label="请选择">
					<option value=""><?php echo $ld['all_data']; ?></option>
				</optgroup>
				<?php if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){if(isset($info_resource[$k])&&!empty($info_resource[$k])){ ?>
				<optgroup label="<?php echo $v; ?>">
					<?php foreach($info_resource[$k] as $kk=>$vv){ ?>
					<option value="<?php echo 'user_project_'.$kk; ?>" <?php echo isset($account_category)&&$account_category=='user_project_'.$kk?'selected':''; ?>><?php echo $vv; ?></option>
					<?php } ?>
				</optgroup>
				<?php }}} ?>
			</select>
            </div>
        </li>
        -->
    	<li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['category']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="transaction_category" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>'}">
				<option value=''><?php echo $ld['all_data']; ?></option>
				<?php if(isset($Resource_info['transaction_category'])&&sizeof($Resource_info['transaction_category'])>0){foreach($Resource_info['transaction_category'] as $k=>$v){ ?>
				<option value="<?php echo $k; ?>" <?php echo isset($transaction_category)&&$transaction_category==$k?'selected':''; ?>><?php echo $v; ?></option>
				<?php }} ?>
			</select>
            </div>
        </li>
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['type']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="account_type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>'}">
				<option value=''><?php echo $ld['all_data']; ?></option>
				<option value='0' <?php echo isset($account_type)&&$account_type=='0'?'selected':''; ?>><?php echo '收入'; ?></option>
				<option value='1' <?php echo isset($account_type)&&$account_type=='1'?'selected':''; ?>><?php echo '支出'; ?></option>
			</select>
            </div>
        </li>
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['payment']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="payment_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:300}">
				<option value='0'><?php echo $ld['all_data']; ?></option>
				<?php if(isset($payment_info)&&sizeof($payment_info)>0){foreach($payment_info as $v){ ?>
				<option value="<?php echo $v['Payment']['id']; ?>" <?php echo isset($payment_id)&&$payment_id==$v['Payment']['id']?'selected':''; ?>><?php echo $v['PaymentI18n']['name']; ?></option>
				<?php }} ?>
			</select>
            </div>
        </li>
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">审核<?php echo $ld['operator']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="check_operator" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:300}">
				<option value=''><?php echo $ld['all_data']; ?></option>
				<option value='0'><?php echo $ld['system']; ?></option>
				<?php if(isset($OperatorList)&&sizeof($OperatorList)>0){foreach($OperatorList as $k=>$v){ ?>
				<option value="<?php echo $k; ?>" <?php echo isset($check_operator)&&$check_operator==$k?'selected':''; ?>><?php echo $v; ?></option>
				<?php }} ?>
			</select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php  echo '付款人'; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            	<input type='text' class="am-form-field" name='payer' value="<?php echo isset($payer)?$payer:''; ?>" />
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php  echo '收款人'; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                	<input type='text' class="am-form-field" name='payee' value="<?php echo isset($payee)?$payee:''; ?>" />
            </div>
        </li>
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">状态</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <select name="status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>'}">
                <option value=''><?php echo $ld['all_data']; ?></option>
                <option value='0' <?php echo isset($status)&&$status=='0'?'selected':''; ?>><?php echo '待审核'; ?></option>
                <option value='1' <?php echo isset($status)&&$status=='1'?'selected':''; ?>><?php echo '已审核'; ?></option>
                <option value='2' <?php echo isset($status)&&$status=='2'?'selected':''; ?>><?php echo '已取消'; ?></option>
            </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['amount']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                	<input type='text' class="am-form-field" name='amount_start' value="<?php echo isset($amount_start)?$amount_start:''; ?>" />
            </div>
            <div class="  am-text-center  am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                	<input type='text' class="am-form-field" name='amount_end' value="<?php echo isset($amount_end)?$amount_end:''; ?>" />
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['time_of_payment'];?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="payment_time_start"  class="am-form-field " readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo isset($payment_time_start)?$payment_time_start:''; ?>" />
            </div>
            <div class="  am-text-center  am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="payment_time_end" value="<?php echo isset($payment_time_end)?$payment_time_end:''; ?>" />
            </div>
        </li>
        <li >
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">&nbsp;</label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
		</div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<div>
    <?php if($svshow->operator_privilege("account_add")){ ?>
    <div class="am-g am-other_action  am-text-right am-btn-group-xs am-margin-bottom-sm">
        <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/account_informations/view/0'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
    </div>
    <?php } ?>
    <?php echo $form->create('account_informations',array('action'=>'/batch_operate','class'=>'am-form am-form-horizontal'));?>
    <div class="account_information_list">
    		<table class='am-table'>
    			<thead>
    				<tr>
    					<th><label class="am-checkbox am-success am-padding-top-0"><input type='checkbox' name="checkAll" data-am-ucheck onclick="account_batch_check(this)"/><?php //echo $ld['classification']; ?><?php echo $ld['category']; ?></label></th>
    					<th>付款人</th>
    					<th><?php echo $ld['amount']; ?></th>
    					<th><?php echo $ld['payment']; ?></th>
    					<th><?php echo '收据编号'; ?></th>
    					<th><?php echo $ld['time_of_payment']; ?></th>
    					<th>收款人</th>
    					<th><?php echo $ld['type']; ?></th>
    					<th><?php echo $ld['approval status']; ?></th>
    					<th><?php echo $ld['operate']; ?></th>
    				</tr>
    			</thead>
    			<tbody>
			<?php
					$amount_total=0;
					if(isset($account_information_list)&&sizeof($account_information_list)>0){foreach($account_information_list as $v){
						if($v['AccountInformation']['account_type']=='0')$amount_total+=$v['AccountInformation']['payment_amount'];
						if($v['AccountInformation']['account_type']=='1')$amount_total-=$v['AccountInformation']['payment_amount'];
			?>
				<tr>
					<td>
						<?php
							if(strstr($v['AccountInformation']['account_category'],'user_project')){
								$user_project_id=ltrim($v['AccountInformation']['account_category'],'user_project_');
								$user_project_code=isset($user_project_list[$user_project_id]['project_code'])?$user_project_list[$user_project_id]['project_code']:'';
								$account_category=isset($info_resource['all_user_project'][$user_project_code])?$info_resource['all_user_project'][$user_project_code]:$v['AccountInformation']['account_category'];
							}else{
								$account_category=isset($ld[$v['AccountInformation']['account_category']])?$ld[$v['AccountInformation']['account_category']]:$v['AccountInformation']['account_category'];
							}
							if(!strstr($v['AccountInformation']['account_category'],'user_project')||(strstr($v['AccountInformation']['account_category'],'user_project')&&!isset($modify_project_infos[$user_project_id])&&!isset($old_modify_project_infos[$user_project_id]))){
						?>
						<label class="am-checkbox am-success am-padding-top-0"><input type='checkbox' name="checkbox[]" value="<?php echo $v['AccountInformation']['id']; ?>" data-am-ucheck /><?php isset($Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']])?$Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']]:$v['AccountInformation']['transaction_category'];//echo $account_category; ?></label>
						<?php
							}else if(strstr($v['AccountInformation']['account_category'],'user_project')&&!isset($modify_project_infos[$user_project_id])&&$v['AccountInformation']['account_type']=='0'){
						?>
						<label class="am-checkbox am-success am-padding-top-0"><input type='checkbox' name="checkbox[]" value="<?php echo $v['AccountInformation']['id']; ?>" data-am-ucheck /><?php isset($Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']])?$Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']]:$v['AccountInformation']['transaction_category'];//echo $account_category; ?></label>
						<?php
							}else{
						?>
						<label class="am-checkbox am-success am-padding-top-0"><input type='checkbox' name="checkbox[]" value="<?php echo $v['AccountInformation']['id']; ?>" disabled data-am-ucheck /><?php isset($Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']])?$Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']]:$v['AccountInformation']['transaction_category'];//echo $account_category; ?></label>
						<?php
							}
						?>
					</td>
					<td class='am-hide'><?php //echo isset($Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']])?$Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']]:$v['AccountInformation']['transaction_category']; ?></td>
					<td><?php echo $v['AccountInformation']['payer']; ?></td>
					<td><?php echo floor($v['AccountInformation']['payment_amount'])==$v['AccountInformation']['payment_amount']?floor($v['AccountInformation']['payment_amount']):number_format($v['AccountInformation']['payment_amount'],2,".",""); ?></td>
					<td><?php echo isset($payment_list[$v['AccountInformation']['payment_id']])?$payment_list[$v['AccountInformation']['payment_id']]:$v['AccountInformation']['payment_id']; ?></td>
					<td><?php echo $v['AccountInformation']['transaction']; ?></td>
					<td><?php echo strstr($v['AccountInformation']['payment_time'],'0000')?'':date('Y-m-d',strtotime($v['AccountInformation']['payment_time'])); ?></td>
					<td><?php echo $v['AccountInformation']['payee'];//if(strstr($v['AccountInformation']['account_category'],'user_project'))echo isset($user_project_list[$user_project_id]['manager'])?(isset($OperatorList[$user_project_list[$user_project_id]['manager']])?$OperatorList[$user_project_list[$user_project_id]['manager']]:'-'):'-'; ?></td>
					<td><?php echo $v['AccountInformation']['account_type']=='0'?'收入':'支出'; ?></td>
					<td><?php echo $v['AccountInformation']['status']=='0'?'待审核':($v['AccountInformation']['status']=='1'?'已审核':'已取消'); ?>&nbsp;<?php echo $v['AccountInformation']['status']=='1'?(isset($OperatorList[$v['AccountInformation']['check_operator']])?$OperatorList[$v['AccountInformation']['check_operator']]:$ld['system']):''; ?><br /><?php
							echo $v['AccountInformation']['status']=='1'?date('Y-m-d',strtotime($v['AccountInformation']['modified'])):''; 
								 if($v['AccountInformation']['status']=='0'&&strstr($v['AccountInformation']['account_category'],'user_project')){
								 	if(isset($modify_project_infos[$user_project_id])||(isset($old_modify_project_infos[$user_project_id])&&$v['AccountInformation']['account_type']=='1')){
								 		echo "<span class='project_message'>变更未审核</span>";
								 	}
								 }
						?></td>
					<td>
						<a class="am-btn am-btn-success am-btn-xs  am-seevia-btn-view am-hide" href="<?php echo $html->url('/account_informations/view/'.$v['AccountInformation']['id']); ?>">
							<span class="am-icon-eye"></span> <?php echo $ld['view']; ?>
						</a>
						<?php 
								if($v['AccountInformation']['status']!=1&&strstr($v['AccountInformation']['account_category'],'user_project')){
									if(!strstr($v['AccountInformation']['account_category'],'user_project')||(strstr($v['AccountInformation']['account_category'],'user_project')&&!isset($modify_project_infos[$user_project_id])&&!isset($old_modify_project_infos[$user_project_id]))){
						?>
				                <a class="am-btn am-btn-success am-btn-xs" href="javascript:void(0);" onclick="account_informations_status('<?php echo $v['AccountInformation']['id'] ?>','1');">
				                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['order_check']; ?>
				                </a>
			                	<?php 
			                				}
			                			}else if($v['AccountInformation']['status']==1&&strstr($v['AccountInformation']['account_category'],'user_project')){ ?>
			                	<a class="am-btn am-btn-danger am-btn-xs" href="javascript:void(0);" onclick="account_informations_status('<?php echo $v['AccountInformation']['id'] ?>','0');">
				                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['remove_checked']; ?>
				                </a>
			                	<?php }else if($v['AccountInformation']['status']!=2){ ?>
			                	<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="javascript:void(0);" onclick="account_informations_status('<?php echo $v['AccountInformation']['id'] ?>','2');">
				                    <span class="am-icon-pencil-square-o"></span> 取消
				                </a>
			                	<?php }
			                		if(strstr($v['AccountInformation']['account_category'],'user_project')&&$svshow->operator_privilege("student_edit"))echo $html->link('学生详情','/user_projects/view/'.(isset($user_project_list[$user_project_id]['user_id'])?$user_project_list[$user_project_id]['user_id']:0),array('target'=>'_blank','class'=>'am-btn am-btn-default am-btn-xs am-text-secondary'));
			                	?>
					</td>
				</tr>
			<?php }} ?>
			
			<?php	if(isset($account_information_list)&&sizeof($account_information_list)>0){	?>
				<tr>
					<td class='am-text-center' colspan='3'>当页小计</td>
					<td colspan='8'><?php echo floor($amount_total)==$amount_total?floor($amount_total):number_format($amount_total,2,".",""); ?></td>
				</tr>
			<?php	}	?>
    			</tbody>
    		</table>
    	</div>
	<div id="btnouterlist" class="btnouterlist">
		<div class='am-u-lg-6 am-padding-left-0'>
			<div class='am-u-lg-2 am-u-sm-3 am-padding-left-0'>
				<label class="am-checkbox am-success am-padding-top-0"><input type='checkbox' name="checkAll" data-am-ucheck onclick="account_batch_check(this)"/><?php echo $ld['select_all']; ?></label>
			</div>
			<div class='am-u-sm-3'>
				<select name="batch_operate" onchange="account_batch_operate(this)">
					<option value=""><?php echo $ld['batch_operate']; ?></option>
					<option value="batch_export"><?php echo $ld['batch_export']; ?></option>
					<option value="batch_check"><?php echo '批量审核'; ?></option>
					<option value="batch_uncheck"><?php echo '批量取消审核'; ?></option>
				</select>
			</div>
			<div class='am-u-sm-3' style="display:none;">
				<select name="batch_export_type">
					<option value=""><?php echo $ld['export_type']; ?></option>
					<option value="all_export"><?php echo $ld['all_export']; ?></option>
					<option value="choice_export"><?php echo $ld['choice_export']; ?></option>
					<option value="search_export"><?php echo $ld['search_export']; ?></option>
				</select>
			</div>
			<div class='am-u-sm-3'>
				<button type='button' class='am-btn am-btn-danger am-btn-sm am-radius' onclick="account_batch_confirm(this)"><?php echo $ld['confirm'] ?></button>
			</div>
		</div>
		<div class='am-u-lg-6'>
		<?php if(isset($account_information_list)&&sizeof($account_information_list)>0){echo $this->element('pagers');} ?>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php echo $form->end(); ?>
</div>
<style type='text/css'>
form.am-form>ul>li{margin-bottom:10px;}
div.account_information_list div.am-g{padding:10px 0px;}
div.account_information_list td a.am-btn-default,div.account_information_list td a.am-btn-default:hover,div.account_information_list td a.am-btn-default:visited{color:#3bb4f2;}
span.project_message{color:#ccc;}
</style>
<script type='text/javascript'>
function account_informations_status(id,status){
    if (confirm("确定操作？")){
        $.ajax({ 
            url: admin_webroot+"account_informations/account_informations_status",
            data:{'status':status,'id':id},
            dataType:"json",
            type:"POST",
            success: function(data){
                if(data.code == '1'){
                    window.location.href=admin_webroot+'account_informations/index';
                }
            }
        });
    }
}

function account_batch_check(checkbox){
	var postForm=$(checkbox).parents('form');
	if($(checkbox).prop('checked')){
		$(postForm).find("input[name='checkbox[]']").not(':disabled').uCheck('check');
	}else{
		$(postForm).find("input[name='checkbox[]']").not(':disabled').uCheck('uncheck');
	}
}

function account_batch_operate(select){
	var operate_code=$(select).val();
	var batchDiv=$(select).parent().parent();
	if(operate_code=='batch_export'){
		batchDiv.find("div:eq(2)").show();
	}else{
		batchDiv.find("div:eq(2)").hide();
	}
}

function account_batch_confirm(btn){
	var postForm=$(btn).parents('form');
	var operate_code=$(postForm).find("select[name='batch_operate']").val();
	if(operate_code=='')return;
	var batch_export_type=$(postForm).find("select[name='batch_export_type']").val();
	if(operate_code=='batch_export'&&batch_export_type==''){
		return;
	}
	if(!(operate_code=='batch_export'&&(batch_export_type=='search_export'||batch_export_type=='all_export'))){
		var checkIds=[];
		$(postForm).find("input[name='checkbox[]']:checked").each(function(index,item){
			checkIds.push(item.value);
		});
		if(checkIds.length==0)return;
	}
	var operate_text=$(postForm).find("select[name='batch_operate'] option:selected").text();
	if(confirm(operate_text)){
		if(operate_code=='batch_export'){
			var batch_export_type=$(postForm).find("select[name='batch_export_type']").val();
			if(batch_export_type!='search_export'){
				postForm.submit();
			}else{
				var exportForm=document.getElementById('account_information_search');
				exportForm.action=admin_webroot+"account_informations/batch_operate";
				$(exportForm).append("<input type='hidden' name='batch_operate' value='batch_export' /><input type='hidden' name='batch_export_type' value='search_export' />");
				exportForm.submit();
			}
		}else{
			$(btn).button('loading');
			$.ajax({
				url: admin_webroot+"account_informations/batch_operate",
				data:postForm.serialize(),
				dataType:"json",
				type:"POST",
				success: function(result){
					if(result.code == '1'){
						window.location.reload();
					}
				},complete:function(){
					$(btn).button('reset');
				}
			});
		}
	}
}
</script>