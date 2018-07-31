<?php
	$is_wechat=true;
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		$is_wechat=false;
	}
	if($action_flag==1&&$payment_info['Payment']['is_online']=='1'){
	 	echo $form->create('balances',array('action'=>'/balance_deposit2','id'=>'payform','name'=>'payform','type'=>'POST','style'=>'overflow:hidden'));?>
	 		<input name='amount_num' type='hidden' value="<?php echo $order_info['Order']['need_paid'];?>">
			<input type='hidden' value="<?php echo $order_info['Order']['payment_id'];?>">
			<input type='hidden' name='cmd' value='_xclick'/>
			<input type='hidden' name='business' value='order@idealhere.com'/>
			<input type='hidden' name='item_name' value='<?php echo $order_info['Order']['order_code'];?>'/>
			<input type='hidden' name='amount' value='<?php echo $order_info['Order']['total'];?>'/>
			<input type='hidden' name='currency_code' value='<?php echo $order_info['Order']['order_currency'];?>'/>
			<input type='hidden' name='return' value='<?php echo $server_host;?>/'/>
			<input type='hidden' name='invoice' value='<?php echo $order_info['Order']['id'];?>'/>
			<input type='hidden' name='charset' value='utf-8'/>
			<input type='hidden' name='no_shipping' value='1'/>
			<input type='hidden' name='no_note' value='1' />
			<input type='hidden' name='notify_url' value='<?php echo $server_host;?>/'/>
			<input type='hidden' name='rm' value='2'/>
			<input type='hidden' name='cancel_return' value='<?php echo $server_host;?>/'/>
			<div class="am-g am-form am-form-horizontal">
				<div class="am-form-group am-margin-bottom-sm">
					<div class="am-u-sm-12 am-text-center am-text-danger am-text-xl"><?php echo $svshow->price_format($order_info['Order']['need_paid'],$configs['price_format']);?></div>
					<div class='am-cf'></div>
					<hr class='am-margin-top-sm am-margin-bottom-0'>
				</div>
				<div class="am-form-group am-margin-bottom-sm">
					<div class="am-form-label am-u-sm-12 am-text-left"><?php echo $ld['fill_payment_method']; ?>:</div>
					<div class='am-cf'></div>
				</div>
				<?php if(isset($sub_paylist)&&sizeof($sub_paylist)>0){ ?>
				<div class="am-form-group">
					<div class="am-u-sm-12 am-btn-group radio-btn payment_btn am-text-center" data-am-button>
					<?php foreach($sub_paylist as $k=>$v){ ?>
						<label class="am-btn am-btn-default <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?' am-active':''; ?> am-u-lg-5 am-u-md-5 am-u-sm-12" onclick="topay(this)">
							<input type="radio" class="payments  pay_<?php echo $v['Payment']['code']; ?>" name="payment_id" <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?" checked='checked'":''; ?> value="<?php echo $v['Payment']['id']; ?>"/><img alt="<?php echo $v['PaymentI18n']['name']; ?>" src="<?php echo $v['Payment']['logo'] ?>">
						</label>
					<?php } ?>
	                		</div>
	                		<div class='am-cf'></div>
				</div>
				<?php } ?>
			</div>
<?php echo $form->end();?>
<style type="text/css">
@media only screen and (max-width:641px){
#am-pay{
   margin-left:24px;margin-right;
}}
#td-pay{text-align:center;}
@media only screen and (max-width:641px){
	#td-pay{padding:0}
}

</style>
<script type="text/javascript">
var is_wechat="<?php echo $is_wechat; ?>";
function topay(btn){
	var $radios = $('[name="payment_id"]');
	var pay=$(btn).find('input').val();
	$(btn).find('input').attr('checked','checked');
	
	if(typeof(pay) == 'undefined'){
		alert("<?php echo $ld['fill_payment_method']?>");
		return false;
	}else{
		var payment_code=$(btn).find('input').is(".pay_weixinpay");
		if(payment_code&&is_wechat){
			try{
				wechat_ajax_payaction();
			}catch(Error){
				alert(Error);
				return false;
			}
		}else{
			document.getElementById('payform').submit();
		}
	}
}
</script>
<?php }else if($action_flag==1&&$payment_info['Payment']['is_online']=='0'){?>
	<?php if(isset($sub_paylist)&&sizeof($sub_paylist)>0){ ?>
            <?php 
        				foreach($sub_paylist as $v){
        		?>
				<p><?php echo $v['PaymentI18n']['description']; ?></p>
        		<?php
        				}
        	?>
	<?php } ?>
<?php }else{ ?>
<div class="am-g" style="padding:10px 0;"><?php echo $ld['invalid_operation']; ?></div>
<script type="text/javascript">
window.location.reload();
</script>
<?php } ?>