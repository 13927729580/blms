<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
.order_type_id,.order_product_code,.order_product_quntity,.order_product_price,.order_product_price,.order_subtotal,.order_total,.order_money_paid,.order_payment_name,.order_payment_fee,.order_shipping_name,.order_shipping_fee,.order_consignee,.order_mobile,.order_country,.order_province,.order_city,.order_telephone{width:80px;}
</style>
<!-- <input type="hidden" id="eval_id" value="<?php echo $eval_id ?>"> -->
<?php echo $form->create('evaluations',array('action'=>'/batch_delivery_orders/','name'=>"theDateForm",'class'=>'am-form'));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12" style="padding:0;">
	<table id="t1" class="am-table  table-main">
		<tr>
			<th style="border:none;width:18%;">
				<label class="am-checkbox am-secondary" style="font-weight:bold;margin:0;padding-top:0;padding-bottom:0;margin-top:0.4rem;">
					<input onclick='batch_chose(this)' type="checkbox"  checked />
					<span class="am-hide-sm-only"><?php echo '所有'; ?></span>
				</label>
			</th>
			
			<?php foreach ($uploads_list[0] as $k => $v) { ?>
				<th style="border:none;"><?php echo $v ?></th>
			<?php } ?>
		</tr>
		<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
		<tr>
			<td style="border:none;">
				<label class="am-checkbox am-secondary" style="margin:0;">
					<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked class="update_chose" /><?php echo ' ';?>
				</label>
			</td>
			<?php foreach($v as $kk => $vv){?>
			<td style="border:none;">
				<input type='text' class="order_<?php echo $kk?>" name="data[<?php echo $k?>][<?php echo $kk?>]" value="<?php echo isset($vv)?$vv:"";?>"  style="margin-bottom:0.5rem;width:95%;" />
			</td>
			<?php }?>
		</tr>
		<?php }}?>
	</table>
	<div id="btnouterlist" class="btnouterlist">
		<div style="padding-left:6px;">
			<label class="am-checkbox am-secondary" style="font-weight:bold;display:none;">
				<input onclick='' type="checkbox" checked  checked data-am-ucheck />
				选择所有
			</label>
			<input type="button" class="am-btn am-btn-secondary am-radius am-btn-sm"  value="<?php echo '确定' ?>" onclick="ajax_batch_share(this)" style="min-width:130px;margin-right:10px;" />
			<input type="button"  class="am-btn am-btn-warning am-radius am-btn-sm"  value="<?php echo '重新上传' ?>" onclick="cancel_upload()" style="min-width:130px;" />
		</div>
	</div>
</div>
<?php $form->end();?>