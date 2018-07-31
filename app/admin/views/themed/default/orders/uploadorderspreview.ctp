<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
.order_type_id,.order_product_code,.order_product_quntity,.order_product_price,.order_product_price,.order_subtotal,.order_total,.order_money_paid,.order_payment_name,.order_payment_fee,.order_shipping_name,.order_shipping_fee,.order_consignee,.order_mobile,.order_country,.order_province,.order_city,.order_telephone{width:80px;}
</style>

<?php echo $form->create('orders',array('action'=>'/batch_add_orders/','name'=>"theDateForm"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table id="t1" class="am-table  table-main">
		<tr>
			<th>
				<label class="am-checkbox am-success" style="font-weight:bold;">
					<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck/>
					<?php echo $ld['number']?>
				</label>
			</th>
			<?php foreach($profilefiled_info as $thk => $thv){?>
				<th><?php echo $thv['ProfilesFieldI18n']['description'];?></th>
			<?php }?>
		</tr>
		<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
		<tr>
			<td>
				<label class="am-checkbox am-success">
					<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked  checked data-am-ucheck /><?php echo $k;?>
				</label>
			</td>
			<?php foreach($profilefiled_info as $kk => $vv){$fields_kk=explode(".",$vv['ProfileFiled']['code']);?>
			<td>
				<input type='text' class="order_<?php echo $fields_kk[1]?>" name="data[<?php echo $k?>][<?php echo $fields_kk[1]?>]" value="<?php echo isset($v[$fields_kk[1]])?$v[$fields_kk[1]]:"";?>"  />
			</td>
			<?php }?>
		</tr>
		<?php }}?>
	</table>
	<div id="btnouterlist" class="btnouterlist">
		<div>
			<label class="am-checkbox am-success" style="font-weight:bold;">
				<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck />
				<?php echo $ld['select_all']?>
			</label>
			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['d_submit']?>" />
			<input type="reset"  class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php $form->end();?>
<script type="text/javascript">
	$(function(){
		if(document.getElementById('msg')){
			var msg =document.getElementById('msg').value;
            if(msg !=""){
                alert(msg);
                var button=document.getElementById('btnouterlist');
                button.style.display="none";
            }
		}
	});
</script>