<?php
	$is_wechat=true;
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		$is_wechat=false;
	}
?>
<div class="am-cf am-container cart-done" >
	<ul class="am-list am-list-static " style="font-size:15px;margin-top:2rem">
		<li style="border:none"><?php echo $_SESSION['User']['User']['name'];?>,<?php echo $ld['thank_order']?></li>
		<li style="border:none"><?php echo $ld['your_order_number']?><span> <?php echo $order_code;?></span><?php echo $html->link($ld['view_order'],'/orders/view/'.$order_info['Order']['id'],array("target"=>"_blank","style"=>"margin:0 10px;text-decoration:underline;"))?></li>
		<?php if($order_info['Order']['payment_status']=='0'){ ?>
		<li style="border:none"><?php echo $ld['amount_to_be_paid']?><span> <?php echo $svshow->price_format($need_pay,$configs['price_format']);?></span></li>
		<?php } ?>
		<?php if(isset($sub_paylist)&&!empty($sub_paylist)){ ?>
		<li style="border:none;margin-top:2.4rem;margin-bottom:0.5rem;"><h2 style="margin-bottom:0px;"> <?php echo $ld['payment_method'] ?></h2></li>
			<?php if($payment_info['Payment']['is_online']=='1'){ ?>
		<li style="border-bottom:none">
<?php echo $form->create('balances',array('action'=>'/balance_deposit2','name'=>'payform','id'=>'payform','type'=>'POST','target'=>'_blank'));?>
			<input name='amount_num' type='hidden' value="<?php echo $need_pay; ?>">
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
			<div class="am-form am-form-horizontal">
				<div class="am-form-detail">
                    <div class="am-form-group">
                        <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn-group radio-btn am-padding-0" data-am-button>
                        <?php foreach($sub_paylist as $k=>$v){ ?>
                            <label class="am-u-sm-6 am-u-lg-2 am-u-md-4 am-btn am-btn-default <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?" am-active":''; ?>">
                                <input type="radio" class="payments pay_<?php echo $v['Payment']['code'] ?>" name="payment_id" <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?" checked='checked'":''; ?> value="<?php echo $v['Payment']['id']; ?>"/><img alt="<?php echo $v['PaymentI18n']['name']; ?>" src="<?php echo $v['Payment']['logo'] ?>" >
                            </label>
                        <?php } ?>
                        	<div class="am-cf"></div>
                        </div>
	    			</div>
				</div>
				<div class="am-form-detail payinfo">
					<div class="am-form-group">
						<a class="am-u-sm-12 am-btn am-btn-block am-btn-warning" href="javascript:void(0);" onclick="topay()"><?php echo $ld['pay_now'];?></a>
					</div>
				</div>
			</div>
			<?php echo $form->end();?>
		</li>
		<?php }else{
				foreach($sub_paylist as $v){
		?>
		<li>
			<label><?php echo $v['PaymentI18n']['name']; ?></label>
			<p><?php echo $v['PaymentI18n']['description']; ?></p>
		</li>
		<?php
				}
			} ?>
		<?php }else if(isset($pay_message)&&$pay_message!=""){ ?>
		<li><?php echo $pay_message; ?></li>
		<?php }else if($order_info['Order']['payment_status']=='2'){ ?>
		<li><?php echo $ld['success_has_bee']; ?></li>
		<?php } ?>
	</ul>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
    </div>
  </div>
</div>

<style type="text/css">
.am-modal-hd .am-close{top:4px;}
.am-list-static li{padding:7px;min-height:auto;}
.cart-done .am-list > li{height:auto;}
.cart-done .am-list > li > a{display: inline;}
#payform .am-form-group {margin-bottom: 5px;}
#payform .am-form-group label{font-weight:500;box-shadow:none;}
.payinfo{margin-top:10px;}
.am-form-detail .am-form-group .radio-btn .am-btn{margin-right:1rem;padding:1rem;background: #FFF;border:2px solid #FFF;}
.am-form-detail .am-form-group .radio-btn .am-btn.am-active {background: #FFF;border:2px solid #0e90d2;}
.am-form-detail .am-form-group .radio-btn .am-btn img{width:120px;height:60px;}
@media only screen
and (max-width : 640px){
	.am-form-detail .am-form-group .radio-btn .am-btn{margin-right:0px;}
}
</style>
<script type="text/javascript">
var order_product_infos=<?php echo isset($order_product_infos)?json_encode($order_product_infos):'{}';?>;
if(order_product_infos.length>0){
	var server_host="<?php echo $server_host; ?>";
	var order_product_detail=order_product_infos[0];
	var wechat_shareTitle=order_product_detail['OrderProduct']['product_name'];
	var wechat_imgUrl=order_product_detail['Product']['img_thumb'];
	var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
	var objExp=new RegExp(Expression);
	if(!objExp.test(wechat_imgUrl)){
		wechat_imgUrl=server_host+web_base+wechat_imgUrl;
	}
	var wechat_lineLink=server_host+web_base+"/products/view/"+order_product_detail['OrderProduct']['product_id'];
}
var is_wechat="<?php echo $is_wechat; ?>";
function topay(){
	var $radios = $('[name="payment_id"]');
	var pay=$radios.filter(':checked').val();
	
	if(typeof(pay) == 'undefined'){
		alert("<?php echo $ld['fill_payment_method']?>");
		return false;
	}else{
		var payment_code=$radios.filter(':checked').is(".pay_weixinpay");
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

function check_form(fm){
	var a=document.getElementById("card_num").value;
	if(a == ''){
		alert('card num can not be null');
		return false;
	}
	else{
		return true;
	}
}
</script>