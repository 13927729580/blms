<?php
	$voucher_id=isset($voucher_data['Voucher']['id'])?$voucher_data['Voucher']['id']:0;
?>
<div class="entity_card_list">
	<div class="am-form-detail am-form am-form-horizontal">
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin:0 0 10px 0;">
				<label class="am-u-lg-2 am-u-md-3  am-u-sm-3 am-form-label-text"><?php echo $ld['keyword']; ?></label>
				<div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="entity_card_keywords" value="<?php echo isset($entity_card_keywords)?$entity_card_keywords:''; ?>" /></div>
			</li>
			<li style="margin:0 0 10px 0;">
				<label class="am-u-lg-2  am-u-md-3  am-u-sm-3 am-form-label-text"><?php echo $ld['status']; ?></label>
				<div class="am-u-lg-9  am-u-md-8 am-u-sm-8"><select id="entity_card_status">
						<option value=""><?php echo $ld['please_select']; ?></option>
						<?php if(isset($entity_card_status_resource)&&sizeof($entity_card_status_resource)>0){foreach($entity_card_status_resource as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php  echo isset($entity_card_status)&&$entity_card_status==$k?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<li style="margin:0 0 10px 0;">
				<label class="am-u-lg-3  am-u-md-3  am-u-sm-3 am-form-label-text"><?php echo '卡号区间'; ?></label>
				<div class="am-u-lg-4  am-u-md-4 am-u-sm-4">
					<input type="text" id="entity_card_sn_start" value="<?php echo isset($entity_card_sn_start)?$entity_card_sn_start:''; ?>" />
				</div>
				<label class="am-fl"><?php echo '-'; ?></label>
				<div class="am-u-lg-4  am-u-md-4 am-u-sm-4">
					<input type="text" id="entity_card_sn_end" value="<?php echo isset($entity_card_sn_end)?$entity_card_sn_end:''; ?>" />
				</div>
			</li>
			<li style="margin:0 0 10px 0;">
				<label class="am-u-lg-2 am-u-md-3  am-u-sm-3 am-form-label-text">&nbsp;</label>
				<div class="am-u-lg-6  am-u-md-8 am-u-sm-8"><input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="entity_card_load()" value="<?php echo $ld['search']?>" /></div>
			</li>
		</ul>
	</div>
	<?php echo $form->create('vouchers',array('action'=>'/entity_card_batch/'.$voucher_id,'name'=>'EntityCardForm','id'=>'EntityCardForm',"type"=>"post",'onsubmit'=>"return false;"));?>
		<input type="hidden" name="batch_sn" value="<?php echo isset($voucher_data['Voucher']['batch_sn'])?$voucher_data['Voucher']['batch_sn']:''; ?>" />
	<div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"entity_card_checkboxes[]")' type="checkbox" />&nbsp; <?php echo $ld['card'];?></label></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['password']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['denomination']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['product']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_030']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['z_operation']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<div>
			<div class="listtable_div_top am-panel-body">
			<?php if(isset($voucher_entity_card_list)&&sizeof($voucher_entity_card_list)>0){foreach($voucher_entity_card_list as $v){ ?>
					<div class="am-panel-bd">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
								<label class="am-checkbox am-success" style="display:inline-block;"><input type="checkbox"  name="entity_card_checkboxes[]" value="<?php echo $v['VoucherEntityCard']['id']; ?>" ><?php echo $v['VoucherEntityCard']['card_sn']; ?></label>&nbsp;<?php if(trim($v['VoucherEntityCard']['remark'])!=""){echo $html->image('/admin/skins/default/img/note.png',array('alt'=>htmlspecialchars($v['VoucherEntityCard']['remark']),'title'=>htmlspecialchars($v['VoucherEntityCard']['remark'])));} ?>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['VoucherEntityCard']['card_password']; ?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $v['VoucherEntityCard']['amount']; ?>&nbsp;</div>
						<div class="am-u-lg-2 am-show-lg-only" style="word-wrap: break-word; word-break: normal"><?php echo isset($product_list[$v['VoucherEntityCard']['product_code']])?$product_list[$v['VoucherEntityCard']['product_code']]:$v['VoucherEntityCard']['product_code']; ?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo isset($entity_card_status_resource[$v['VoucherEntityCard']['status']])?$entity_card_status_resource[$v['VoucherEntityCard']['status']]:''; ?>&nbsp;</div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $v['VoucherEntityCard']['use_time']; ?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2  am-btn-group-xs am-action">
							<?php if($svshow->operator_privilege("voucher_edit")){ ?>
							<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/vouchers/entity_card_view/'.$v['VoucherEntityCard']['id'].'/'.$voucher_id); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a><?php } if($svshow->operator_privilege("voucher_edit")){ ?>
                					<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-text-danger am-seevia-btn-edit" href="javascript:void(0);" onclick="entity_card_remove(admin_webroot+'vouchers/entity_card_remove/<?php echo $v['VoucherEntityCard']['id'] ?>');"><span class="am-icon-trash-o"></span><?php echo $ld['remove']; ?></a><?php } ?>
						</div>
						<div style="clear:both;"></div>
					</div>
			<?php }}else{ ?>
					<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php } ?>
			</div>
		</div>
	</div>
	<?php if(isset($voucher_entity_card_list) && sizeof($voucher_entity_card_list)>0){ ?>
		<div class="am-div-pages">
			<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-hide-sm-only"  style="margin-left:13px;">
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
					<label class="am-checkbox am-success" style="vertical-align:middle;display: inline-block;">
						<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'entity_card_checkboxes[]');" data-am-ucheck />
						<?php echo $ld['select_all']?>
					</label>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
					<select id="entity_card_batch_type" name="entity_card_batch_type">
						<option value="0"><?php echo $ld['please_select']; ?></option>
						<?php if($svshow->operator_privilege("voucher_remove")){ ?>
						<option value="batch_remove"><?php echo $ld['batch_delete']; ?></option>
						<?php } ?>
						<?php if($svshow->operator_privilege("voucher_edit")){ ?>
						<option value="batch_status"><?php echo $ld['log_batch_change_status']; ?></option>
						<option value="batch_edit"><?php echo '批量修改信息'; ?></option>
						<?php } ?>
						<option value="batch_export"><?php echo $ld['batch_export']; ?></option>
					</select>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4" style="display:none;">
					<select id="entity_card_batch_status" name="entity_card_batch_status">
						<option value="-1"><?php echo $ld['please_select']; ?></option>
						<?php if(isset($entity_card_status_resource)&&sizeof($entity_card_status_resource)>0){foreach($entity_card_status_resource as $k=>$v){ ?>
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-12">
					<input type="button" class="am-btn am-radius am-btn-danger am-btn-sm"  onclick="entity_card_batch()" value="<?php echo $ld['submit']?>" />
				</div>
				<div class="am-cf"></div>
			</div>
			<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
				<?php echo $this->element('pagers')?>
			</div>
			<div class="am-cf"></div>
		</div>
	<?php } ?>
	<?php echo $form->end()?>
</div>

<!-- 批量修改信息 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="entity_card_batch_update">
  <div class="am-modal-dialog" style="overflow-y:auto;">
    <div class="am-modal-hd"><?php echo '批量修改';?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-form-detail am-form am-form-horizontal">
        <table class="am-table">
    		<tr>
                	<th width="30%"><?php echo $ld['denomination']; ?></th>
                	<td width="70%" class="am-text-left"><input type="text" id="entity_card_batch_amount" maxlength="8"/></td>
            </tr>
    		<tr>
                	<th width="30%"><?php echo $ld['exchange_item']; ?></th>
                	<td width="30%" class="am-text-left"><input type="text" class="product_search" /></td>
    		   	<td width="30%"><button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="product_search(this)"><?php echo $ld['search']; ?></button></td>
            </tr>
    		<tr>
    			<th width="30%">&nbsp;</th>
    			<td width="70%" class="am-text-left">
    				<select id="entity_card_batch_product" class="product_search_list">
    					<option value=""><?php echo $ld['please_select']; ?></option>
    				</select>
    			</td>
    		</tr>
    		<tr>
    			<th width="30%"><?php echo $ld['variable_start_time'];?></th>
    			<td width="70%" class="am-text-left">
    				<input type="text" id="entity_card_batch_start_time" class="am-form-date" value="" />
    			</td>
    		</tr>
    		<tr>
    			<th width="30%"><?php echo $ld['variable_end_time'];?></th>
    			<td width="70%" class="am-text-left">
    				<input type="text" id="entity_card_batch_end_time"  class="am-form-date"  value="" />
    			</td>
    		</tr>
    		<tr>
    			<th width="30%"><?php echo $ld['remarks_notes'];?></th>
    			<td width="70%" class="am-text-left">
    				<textarea id="entity_card_batch_remark" maxlength="250"></textarea>
    			</td>
    		</tr>
            <tr>
            	<th width="30%">&nbsp;</th>
                	<td class="am-text-left"><button type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="entity_card_batch_update()"><?php echo $ld['submit'];?></button></td>
            </tr>
        </table>
    </div>
  </div>
</div>
<!-- 批量修改信息 -->
<style type="text/css">
.entity_card_list .am-panel-group .am-checkbox{margin-top:0px;}
.entity_card_list .am-form-group{margin:0 auto;margin-bottom:10px;}
.entity_card_list .am-form-group > div > div.am-selected.am-dropdown{margin-top:0px;}
</style>
<script type="text/javascript">
$(function(){
	$("#entity_card_list input[type='checkbox']").uCheck();
	$(".entity_card_list select").selected({btnWidth:'100%'});
	$("#entity_card_list .am-form-date").datepicker({theme: 'success',locale:'<?php echo $backend_locale; ?>'});
	
	$(".entity_card_list .pages a").click(function(){
		var linkurl=$(this).prop('href');
		$.ajax({
	 		url:linkurl,
	 		data:{},
	 		type:'get',
	 		dataType:'html',
	 		success:function(data){
				$("#entity_card_list").html(data);
	 		}
	 	});
		return false;
	});
	
	$("#entity_card_batch_type").change(function(){
		var batch_type=$(this).val();
		if(batch_type=="batch_status"){
			$("#entity_card_batch_status").parent().show();
		}else{
			$("#entity_card_batch_status").parent().hide();
		}
	});
})

function entity_card_remove(link_url){
	if(confirm(j_confirm_delete)){
		$.ajax({
	 		url:link_url,
	 		data:{},
	 		type:'post',
	 		dataType:'json',
	 		success:function(data){
				if(data.flag=='1'){
					entity_card_load();
				}else{
					alert(data.message);
				}
	 		}
	 	});
	}
}

function entity_card_batch(){
	var entity_card_ids=document.getElementsByName('entity_card_checkboxes[]');
	var check_flag=0;
	for( i=0;i<=parseInt(entity_card_ids.length)-1;i++ ){
		if(entity_card_ids[i].checked){
		        check_flag++;
	       }
	}
	if(check_flag>=1 ){
		var batch_type=$("#entity_card_batch_type").val();
		var batch_status=$("#entity_card_batch_status").val();
		if(batch_type=='0'){
			return false;
		}else if(batch_type=='batch_status'){
			if(batch_status=="-1"){
				alert('请选择状态');
				return false;
			}
		}else if(batch_type=='batch_edit'){
			$("#entity_card_batch_update").modal()
			return false;
		}
		if(confirm(confirm_operation)){
			ajax_entity_card_batch();
		}
	}
}

function ajax_entity_card_batch(){
		$.ajax({
	 		url:admin_webroot+"vouchers/entity_card_batch",
	 		data:$("#EntityCardForm").serialize(),
	 		type:'post',
	 		dataType:'html',
	 		success:function(data){
	 			entity_card_load();
	 		}
	 	});
}

function entity_card_batch_update(){
	var PostData=$("#EntityCardForm").serialize();
	var entity_card_batch_amount=$("#entity_card_batch_amount").val().trim();
 	var entity_card_batch_product=$("#entity_card_batch_product").val().trim();
 	var entity_card_batch_start_time=$("#entity_card_batch_start_time").val().trim();
 	var entity_card_batch_end_time=$("#entity_card_batch_end_time").val().trim();
 	var entity_card_batch_remark=$("#entity_card_batch_remark").val().trim();
 	
 	var amount_exp = /^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
 	var number_exp=/^[0-9]*$/;
 	
  	if(entity_card_batch_amount==""||!amount_exp.test(entity_card_batch_amount)){
 		alert('请正确填写实体卡的有效面额');
 		return false;
 	}else if(entity_card_batch_product=="0"||entity_card_batch_product==""){
 		alert('请选择实体卡的商品');
 		return false;
 	}else if(entity_card_batch_start_time==""||entity_card_batch_end_time==""){
 		alert('请选择实体卡的有效期');
 		return false;
 	}else{
 		if(confirm(confirm_operation)){
 			PostData+="&entity_card_batch_amount="+entity_card_batch_amount;
 			PostData+="&entity_card_batch_product="+entity_card_batch_product;
 			PostData+="&entity_card_batch_start_time="+entity_card_batch_start_time;
 			PostData+="&entity_card_batch_end_time="+entity_card_batch_end_time;
 			PostData+="&entity_card_batch_remark="+entity_card_batch_remark;
			$.ajax({
		 		url:admin_webroot+"vouchers/entity_card_batch",
		 		data:PostData,
		 		type:'post',
		 		dataType:'html',
		 		success:function(data){
					window.location.reload();
		 		}
		 	});
		}
 	}
}
</script>