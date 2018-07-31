<div class="am-g am-reservation-order">
	<div class='am-form am-form-horizontal'>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-3'>项目</label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'><?php echo isset($_POST['service_type'])?$_POST['service_type']:''; ?></div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'><?php echo $ld['address']; ?></label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'>
				<?php if(isset($user_address_info)&&!empty($user_address_info)){ ?>
					<div><?php echo $user_address_info['UserAddress']['address']; ?></div>
					<div><?php echo $user_address_info['UserAddress']['consignee']; ?></div>
					<div><?php echo $user_address_info['UserAddress']['mobile']; ?></div>
				<?php } ?>
				&nbsp;
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>预约时间</label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'>
				<?php echo trim(implode(' ',isset($_POST['appointment_date'])?$_POST['appointment_date']:array())) ?>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>取衣方式</label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'><?php echo isset($shipping_info['ShippingI18n'])?$shipping_info['ShippingI18n']['name']:''; ?></div>
			<div class='am-cf'></div>
		</div>
		<?php if(isset($shipping_info['Shipping'])&&$shipping_info['Shipping']['insure_fee']>0){ ?>
		<?php if(isset($configs['use_point'])&&$configs['use_point']=='1'){ ?>
		<div class='am-form-group'>
			<label class='am-u-sm-12'><?php echo $ld['your_points']; ?><span class='am-text-danger'><?php echo isset($user_info['User']['point'])?$user_info['User']['point']:0; ?></span>&nbsp;<?php echo $ld['available_points']; ?>:<span class='am-text-danger'><?php echo isset($user_info['User']['point'])&&isset($configs['point-equal'])&&is_numeric($configs['point-equal'])&&($configs['point-equal']*$shipping_info['Shipping']['insure_fee'])<$user_info['User']['point']?($configs['point-equal']*$shipping_info['Shipping']['insure_fee']):(isset($user_info['User']['point'])?$user_info['User']['point']:0); ?></span></label>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'><?php echo $ld['use_points']?></label>
			<div class='am-u-lg-3 am-u-md-3 am-u-sm-7'><input type="text" id="use_point" value="0" max="<?php echo isset($user_info['User']['point'])&&isset($configs['point-equal'])&&is_numeric($configs['point-equal'])&&($configs['point-equal']*$shipping_info['Shipping']['insure_fee'])<$user_info['User']['point']?($configs['point-equal']*$shipping_info['Shipping']['insure_fee']):(isset($user_info['User']['point'])?$user_info['User']['point']:0); ?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" /></div>
			<div class='am-show-sm-only am-u-sm-4'>&nbsp;</div>
			<div class='am-u-lg-4 am-u-md-2 am-u-sm-7 am-text-left'><?php echo isset($configs['point-equal'])?sprintf($ld['points_gifts_money'],$configs['point-equal'],$svshow->price_format("1",$configs['price_format'])):'';?></div>
			<div class='am-cf'></div>
		</div>
		<?php } ?>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>支付项目</label>
			<div class='am-u-lg-6 am-u-md-6 am-u-sm-5'><?php echo $shipping_info['ShippingI18n']['name']; ?></div>
			<div class='am-u-lg-4 am-u-md-2 am-u-sm-3 am-text-left'><?php echo $shipping_info['Shipping']['insure_fee']; ?></div>
			<div class='am-cf'></div>
		</div>
		<?php } ?>
		<?php if(isset($order_message)&&$order_message!=''){ ?>
		<div class='am-form-group am-text-center order_message'><?php echo $order_message; ?></div>
		<?php }else{ ?>
		<div class='am-form-group am-text-center am-reservation-order-submit'>
			<button class='am-btn am-btn-warning am-btn-block' type='button' onclick='submit_appointment_order(this)'>提交订单</button>
			<div class='am-cf'></div>
		</div>
		<?php } ?>
	</div>
</div>
<style type='text/css'>
.am-reservation-order{max-width:1200px;margin:0 auto;}
.am-reservation-order .am-btn.am-btn-block{width:100%;}
.am-reservation-order-submit{width:100%;margin: 10px auto;margin-top:50px;}
@media only screen and (max-width:641px) {
	.am-reservation-order-submit{position: fixed;bottom:0px;margin-top:10px;}
}
div.order_message{color:red;}
</style>
<script type='text/javascript'>
var post_data=<?php echo isset($_POST)?json_encode($_POST):''; ?>;
post_data['is_submit']='1';
function submit_appointment_order(btn){
	if(document.getElementById('use_point')){
		var max_use_point=$('#use_point').attr('max');
		max_use_point=parseInt(max_use_point);
		var use_point=document.getElementById('use_point').value;
		use_point=parseInt(use_point);
		if(use_point>max_use_point){
			alert('最大可使用积分:'+max_use_point);
			return;
		}
		post_data['use_point']=use_point;
	}
	$.ajax({
  		url: web_base+"/appointment_orders/checkout_order",
		type:"POST",
		data:post_data,
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				var order_detail=result.order_detail;
				var order_message="预约成功\r\n订单号:"+order_detail.order_code;
				alert(order_message);
				window.location.href=web_base+"/orders/view/"+order_detail.id;
			}else{
				alert(result.message);
			}
  		}
  	});
}
</script>