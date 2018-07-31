<div class='am-g'>
	<div class='am-text-center am-text-xl'><?php echo isset($virtual_data['name'])?$virtual_data['name']:''; ?><hr class='am-margin-top-sm'></div>
	<div class='am-text-center am-padding-top-xs'>
		<?php if(isset($configs['use_point'])&&$configs['use_point']=='1'){ ?>
		<form method='POST' class="am-form">
			<div class='am-form-group am-margin-bottom-0'>
				<label class='am-form-label am-u-lg-5 am-text-left am-padding-right-0'><?php echo  $ld['your_points'];?><span class='am-text-danger'><?php echo isset($user_data['User'])?$user_data['User']['point']:0;?></span>&nbsp;&nbsp;<br ><?php echo $ld['available_points']; ?>:<span id="available_points" class='am-text-danger'><?php
								$can_use_point = round($order_need_pay * $configs['point-equal']);
								echo isset($user_data['User'])&&$user_data['User']['point']>$can_use_point?$can_use_point:(isset($user_data['User'])?$user_data['User']['point']:0);
		?></span>&nbsp;<span style='color:#aaa;'>(<?php echo sprintf($ld['points_gifts_money'],$configs['point-equal'],$svshow->price_format("1",$configs['price_format']));?>)</span></label>
				<div class='am-u-lg-4'>
					<div class="am-input-group">
						<input type='hidden' id='point_equal' value="<?php echo isset($configs['point-equal'])?$configs['point-equal']:0; ?>" />
						<input type="text" class="am-form-field" value='0'>
						<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="order_point_pay(this,<?php echo isset($order_info['Order'])?$order_info['Order']['id']:0; ?>)"><?php echo $ld['use'] ?></button></span>
					</div>
				</div>
				<div class='am-u-lg-3 am-text-left am-padding-top-xs'>
					<a target="_blank" href="<?php echo $html->url('/points'); ?>" style='text-decoration: underline;'><?php echo $ld['account_reward_points']; ?></a>
				</div>
				<div class='am-cf'></div>
				<div class='am-text-center am-text-danger am-padding-top-sm' id="order_point_message"></div>
			</div>
			<hr class='am-margin-top-0'>
		</form>
		<?php } ?>
		<?php if(isset($configs['enable_balance'])&&$configs['enable_balance']==1){ ?>
		<form method='POST' class="am-form">
			<div class='am-form-group am-margin-bottom-0'>
				<label class='am-form-label am-u-lg-5 am-text-left am-padding-top-xs am-padding-right-0'>当前余额:<span class='am-text-danger'><?php echo $svshow->price_format(isset($user_data['User']['balance'])?$user_data['User']['balance']:0,$configs['price_format']); ?></span></label>
				<div class='am-u-lg-4'>
					<div class="am-input-group">
						<input type="text" class="am-form-field" value='0'>
						<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="order_balance_pay(this,<?php echo isset($order_info['Order'])?$order_info['Order']['id']:0; ?>)"><?php echo $ld['use'] ?></button></span>
					</div>
				</div>
				<div class='am-u-lg-3 am-text-left am-padding-top-xs'>
					<a target="_blank" href="<?php echo $html->url('/users/deposit') ?>" style='text-decoration: underline;'><?php echo $ld['i_want_to_recharge']; ?></a>
				</div>
				<div class='am-cf'></div>
				<div class='am-text-center am-text-danger am-padding-top-sm' id="order_balance_message"></div>
			</div>
			<hr class='am-margin-0'>
		</form>
		<?php } ?>
	</div>
	<div class='am-text-center am-padding-top-xs'>
		<?php if($order_need_pay>0&&isset($order_info['Order']['id']))echo $html->link($ld['pay_now'],'javascript:void(0);',array('class'=>'am-btn am-btn-danger am-btn-block am-hide','id'=>'purchase_order_pay_btn','onclick'=>"purchase_order_pay(this,'".$order_info['Order']['id']."')")); ?>
		<?php 
			echo $form->create('virtual_purchases',array('action'=>'/ajax_purchase_order','type'=>'POST','id'=>'virtual_purchase_form'));
			if(isset($virtual_data)&&!empty($virtual_data)){
				foreach($virtual_data as $k=>$v)echo "<input type='hidden' name='data[Virtual][{$k}]' value='{$v}' />";
			}
		?>
		<input type='hidden' name="data[Order][user_balance]" value="0" />
		<input type='hidden' name="data[Order][point_use]" value="0" />
		<input type='hidden' name="data[Order][total]" value="<?php echo $order_need_pay; ?>" />
		<input type='hidden' name="data[Order][discount]" value="<?php echo isset($virtual_data['discount'])?$virtual_data['discount']:0; ?>" />
		<div class="am-g am-form am-form-horizontal">
			<div class="am-form-group am-margin-bottom-sm">
				<div class="am-u-sm-12 am-text-center am-text-danger am-text-xl am-order-total"><?php echo $svshow->price_format($order_need_pay,$configs['price_format']);?></div>
				<div class='am-cf'></div>
				<hr class='am-margin-top-xs am-margin-bottom-0'>
			</div>
			<div class="am-form-group am-margin-bottom-sm" style="display:none;">
				<button type='button' class='am-btn am-btn-primary am-btn-block' id="fast_submit_order" onclick="ajax_purchase_order(this)"><?php echo $ld['submit_order'] ?></button>
			</div>
			<div class="am-form-group am-margin-bottom-sm">
				<div class="am-form-label am-u-sm-12 am-text-left"><?php echo $ld['fill_payment_method']; ?>:</div>
				<div class='am-cf'></div>
			</div>
			<?php if(isset($sub_paylist)&&sizeof($sub_paylist)>0){ ?>
			<div class="am-form-group">
				<div class="am-u-sm-12 am-btn-group radio-btn payment_btn am-text-center" data-am-button>
				<?php foreach($sub_paylist as $k=>$v){ ?>
					<label class="am-btn am-btn-default am-u-lg-5 am-u-md-5 am-u-sm-12" onclick="ajax_purchase_order(this)">
						<input type="radio" class="payments  pay_<?php echo $v['Payment']['code']; ?>" name="data[Order][sub_pay]" value="<?php echo $v['Payment']['id']; ?>"/><img alt="<?php echo $v['PaymentI18n']['name']; ?>" src="<?php echo $v['Payment']['logo'] ?>">
					</label>
				<?php } ?>
                		</div>
                		<div class='am-cf'></div>
			</div>
			<?php } ?>
		</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type='text/javascript'>
$(function(){
	if(document.getElementById('purchase_order_pay_btn')){
		$("#purchase_order_pay_btn").click();
	}
	if(document.getElementById('virtual_purchase_form')){
		var order_total=$("#virtual_purchase_form input[name='data[Order][total]']").val().trim();
		order_total=order_total==''?0:parseInt(order_total);
		if(order_total==0){
			$("#virtual_purchase_form #fast_submit_order").parent().show();
			$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").hide();
		}
	}
});

function ajax_purchase_order(btn){
	var PostForm=$(btn).parents('form');
	$(btn).find("input[type='radio']").attr('checked',true);
	$.ajax({
		url:web_base+'/virtual_purchases/ajax_purchase_order',
		type:"POST",
		data:PostForm.serialize(),
		dataType:"json",
		success:function(result){
			if(result.code=='1'){
				if(result.order_need_pay>0){
					purchase_order_pay($('#virtual_purchase_form'),result.order_id,result.payment_method);
				}else{
					window.location.reload();
				}
			}else{
				alert(result.message);
			}
		}
	});
}

function order_point_pay(btn,order_id){
	var use_point_input=$(btn).parents('div.am-input-group').find("input[type='text']");
	var use_point=$(use_point_input).val().trim();
	use_point=use_point==''?0:parseInt(use_point);
	var max_use_point=$("#available_points").html().trim();
	max_use_point=max_use_point==''?0:parseInt(max_use_point);
	$("#order_point_message").html('');
	var point_equal=$(btn).parents('div.am-input-group').find("input[type='hidden']").val().trim();
	point_equal=point_equal==''?0:parseInt(point_equal);
	if(use_point>0&&use_point>max_use_point){
		$("#order_point_message").html('最大使用积分:'+max_use_point);
	}else if(use_point>0&&use_point<=max_use_point){
		if(document.getElementById('virtual_purchase_form')){
			$("#virtual_purchase_form input[name='data[Order][point_use]']").val(use_point);
			var order_total=$("#virtual_purchase_form input[name='data[Order][total]']").val().trim();
			var use_point_fee=((use_point/point_equal)+'').indexOf('.') >= 0?(use_point/point_equal).toFixed(2):(use_point/point_equal);
			var user_balance=$("#virtual_purchase_form input[name='data[Order][user_balance]']").val().trim();
			var order_need_pay=order_total-use_point_fee-user_balance;
			if(!order_need_pay>0){
				$("#virtual_purchase_form #fast_submit_order").parent().show();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").hide();
			}else{
				$("#virtual_purchase_form #fast_submit_order").parent().hide();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").show();
			}
			order_need_pay=sprintf(js_config_price_format,parseFloat(order_need_pay).toFixed(2));
			$("div.am-order-total").html(order_need_pay);
		}else{
			$(btn).button('loading');
			$.ajax({
				url:web_base+'/orders/order_point_pay',
				type:"POST",
				data:{'order_id':order_id,'use_point':use_point},
				dataType:"json",
				success:function(result){
					if(result.code=='1'){
						$("#available_points").html(max_use_point-use_point);
						$(use_point_input).val(0);
						if(result.need_pay<=0){
							$('#payform').parent().html('');
							$("#order_point_message").html(result.message);
							setTimeout("window.location.reload();",3000);
						}else{
							purchase_order_pay($('#payform'),order_id);
						}
					}else{
						$("#order_point_message").html(result.message);
					}
				},
				complete:function(result){
					$(btn).button('reset');
				}
			});
		}
	}else if(use_point==0){
		if(document.getElementById('virtual_purchase_form')){
			$("#virtual_purchase_form input[name='data[Order][point_use]']").val(use_point);
			var order_total=$("#virtual_purchase_form input[name='data[Order][total]']").val().trim();
			var use_point_fee=((use_point/point_equal)+'').indexOf('.') >= 0?(use_point/point_equal).toFixed(2):(use_point/point_equal);
			var user_balance=$("#virtual_purchase_form input[name='data[Order][user_balance]']").val().trim();
			var order_need_pay=order_total-use_point_fee-user_balance;
			if(!order_need_pay>0){
				$("#virtual_purchase_form #fast_submit_order").parent().show();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").hide();
			}else{
				$("#virtual_purchase_form #fast_submit_order").parent().hide();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").show();
			}
			order_need_pay=sprintf(js_config_price_format,parseFloat(order_need_pay).toFixed(2));
			$("div.am-order-total").html(order_need_pay);
		}
	}
}

function order_balance_pay(btn,order_id){
	var use_balance_input=$(btn).parents('div.am-input-group').find("input[type='text']");
	var use_balance=$(use_balance_input).val().trim();
	use_balance=use_balance==''?0:parseFloat(use_balance);
	$("#order_balance_message").html('');
	if(use_balance>0){
		if(document.getElementById('virtual_purchase_form')){
			$("#virtual_purchase_form input[name='data[Order][user_balance]']").val(use_balance);
			var order_total=$("#virtual_purchase_form input[name='data[Order][total]']").val().trim();
			var use_point=$("#virtual_purchase_form input[name='data[Order][point_use]']").val().trim();
			if(document.getElementById('point_equal')){
				var point_equal=document.getElementById('point_equal').value;
			}else{
				var point_equal=0;
			}
			var use_point_fee=((use_point/point_equal)+'').indexOf('.') >= 0?(use_point/point_equal).toFixed(2):(use_point/point_equal);
			var user_balance=$("#virtual_purchase_form input[name='data[Order][user_balance]']").val().trim();
			var order_need_pay=order_total-use_point_fee-user_balance;
			if(!order_need_pay>0){
				$("#virtual_purchase_form #fast_submit_order").parent().show();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").hide();
			}else{
				$("#virtual_purchase_form #fast_submit_order").parent().hide();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").show();
			}
			order_need_pay=sprintf(js_config_price_format,parseFloat(order_need_pay).toFixed(2));
			$("div.am-order-total").html(order_need_pay);
		}else{
			$(btn).button('loading');
			$.ajax({
				url:web_base+'/orders/order_balance_pay',
				type:"POST",
				data:{'order_id':order_id,'use_balance':use_balance},
				dataType:"json",
				success:function(result){
					if(result.code=='1'){
						$(use_balance_input).val(0);
						if(result.need_pay<=0){
							$('#payform').parent().html('');
							$("#order_balance_message").html(result.message);
							setTimeout("window.location.reload();",3000);
						}else{
							purchase_order_pay($('#payform'),order_id);
						}
					}else{
						$("#order_balance_message").html(result.message);
					}
				},
				complete:function(result){
					$(btn).button('reset');
				}
			});
		}
	}else if(use_balance==0){
		if(document.getElementById('virtual_purchase_form')){
			$("#virtual_purchase_form input[name='data[Order][user_balance]']").val(use_balance);
			var order_total=$("#virtual_purchase_form input[name='data[Order][total]']").val().trim();
			var use_point=$("#virtual_purchase_form input[name='data[Order][point_use]']").val().trim();
			if(document.getElementById('point_equal')){
				var point_equal=document.getElementById('point_equal').value;
			}else{
				var point_equal=0;
			}
			var use_point_fee=((use_point/point_equal)+'').indexOf('.') >= 0?(use_point/point_equal).toFixed(2):(use_point/point_equal);
			var user_balance=$("#virtual_purchase_form input[name='data[Order][user_balance]']").val().trim();
			var order_need_pay=order_total-use_point_fee-user_balance;
			if(!order_need_pay>0){
				$("#virtual_purchase_form #fast_submit_order").parent().show();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").hide();
			}else{
				$("#virtual_purchase_form #fast_submit_order").parent().hide();
				$("#virtual_purchase_form div.am-form div.am-form-group:nth-child(3),#virtual_purchase_form div.am-form div.am-form-group:nth-child(4)").show();
			}
			order_need_pay=sprintf(js_config_price_format,parseFloat(order_need_pay).toFixed(2));
			$("div.am-order-total").html(order_need_pay);
		}
	}
}

function purchase_order_pay(btn,order_id,sub_pay){
	sub_pay=typeof(sub_pay)=='undefined'?null:sub_pay;
	$.ajax({ 
		url:web_base+"/orders/orderpay/"+order_id,
		dataType:"html",
		type:"POST",
		success: function(data){
			if(data.length>0){
				$(btn).parent().html(data);
				//$('#order_pay').modal({closeViaDimmer:false});
				$('#order_pay').modal('open');
				if(sub_pay!=null){
					var pay_btn=$("input[name='payment_id'][value='"+sub_pay+"']").parents('label');
					pay_btn.click();
				}
			}
	    }
	});
}
</script>