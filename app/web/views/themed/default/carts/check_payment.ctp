<div class="am-cf am-container am-check-payment am-check-payment-td">
		<!-- 收货人 -->
		<div class="am-panel am-panel-default ">
		<div class="am-panel-hd" ><strong class="am-text-primary am-text-default"><?php echo $ld['contact_information']?></strong>
			<?php echo $html->link($ld['modify'],"/carts/check_shipping/".$_SESSION['checkout']['address']['id'],array('class'=>'am-btn am-btn-secondary am-btn-sm am-fr','style'=>'margin:0;position:relative;top:-2px;padding:5px 10px'));?>
		</div>
		
		<div class="am-panel-bd">
			<table >
				<tr>
					<td>
					<?php echo $ld['consignee']?>:
					</td>
					<td>
					<?php if(isset($_SESSION['checkout']['address']['consignee']))echo $_SESSION['checkout']['address']['consignee'];?>
					<?php if(isset($_SESSION['checkout']['address']['mobile']) && $_SESSION['checkout']['address']['mobile']!=""){ ?>
					&nbsp;&nbsp;
					<span ><?php echo $_SESSION['checkout']['address']['mobile'];?></span>
					<?php } ?>
					</td>
	  			</tr>

                <?php if(!isset($_SESSION['checkout']['shipping'])||(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']!='cac')){ ?>
				<tr>
					<td><?php echo $ld['delivery_address']?>:&nbsp;</td>
					<td><?php if(isset($_SESSION['checkout']['address']['regionI18n']))echo $_SESSION['checkout']['address']['regionI18n'];?><?php if(isset($_SESSION['checkout']['address']['address']))echo $_SESSION['checkout']['address']['address'];?></td>
	  			</tr>
                <?php } ?>
	  			
	  		

	  			<tr>
 				<td><?php echo $ld['shipping_method']?>:&nbsp;</td>
 				<td><?php echo $_SESSION['checkout']['shipping']['shipping_name'];?></td>
	  			</tr>	
	  		
				<tr>
				<?php if(isset($_SESSION['checkout']['remark'])&&$_SESSION['checkout']['remark']!=""){?>
				<tr>
				<td><?php echo $ld['remarks']?>:&nbsp;</td>
				<td><?php echo $_SESSION['checkout']['remark'];?></td>
				
			</tr>
				<?php }?>

			</table>
		</div>
	</div>
	<?php echo $form->create('carts',array('action'=>'/checkout','class'=>'am-form am-form-horizontal','name'=>'check_order','type'=>'POST','onsubmit'=>'return payment_check();'));?>
	<div class="am-panel am-panel-default payment_method" style="font-size:1.4rem">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-lg"><?php echo $ld['payment_method']?></strong></div>
		<div class="am-panel-bd">
			<?php if(!empty($payments)&&sizeof($payments)>0){ ?>
			<div class="am-form-detail">
	    		<div class="am-form-group">
	    			<?php
					$order_type='';//交易类型
					if(isset($_SESSION['checkout']['cart_info']['sum_quantity'])&&$_SESSION['checkout']['cart_info']['sum_quantity']!='0'){//购物
						$order_type='P';
					}else if(isset($_SESSION['checkout']['cart_info']['lease_quantity'])&&$_SESSION['checkout']['cart_info']['lease_quantity']!='0'){//租赁
						$order_type='L';
					}
	    				foreach($payments as $k=>$v){
	    					if($order_type=="L"&&$v['Payment']['code']=='cod'){continue;}
	    					if(!isset($v['SubMenu'])||sizeof($v['SubMenu'])==0){continue;}
		    				$radio_btn_checkedstr="";
	    				if(isset($_SESSION['checkout']['payment']['payment_id'])&&$_SESSION['checkout']['payment']['payment_id']==$v['Payment']['id']){ $radio_btn_checkedstr="checked='checked'";}
	    			?>
		    			<div class="radio-btn"><label><input type="radio" name="payment" onclick="show_sub_payments('<?php echo $v['Payment']['code'];?>')" id="payment_<?php echo $v['Payment']['code'];?>" value="<?php echo $v['Payment']['id']; ?>" <?php echo $radio_btn_checkedstr; ?> /><span><?php echo $v['PaymentI18n']['name']; ?></span></label></div>
		    		<?php } ?>
	    		</div>
	    		<div class="am-form-group">
	    			<a class="buyaddsub am-btn am-btn-primary am-btn-sm" href="javascript:payment_check();void(0);"><span><?php echo $ld['submit_payment_method'];?></span></a>
	    		</div>
			</div>
			<?php }else{ ?>
				<p><em>*</em><?php echo $ld['no_payment']?></p>
			<?php } ?>
		</div>
	</div>
	<?php echo $form->end();?>
</div>
<style type="text/css">
.am-check-payment-td tr td:first-child{padding:0;}
.am-check-payment .am-panel .am-panel-bd{padding:10px 0px 10px 15px;}
</style>
<script type="text/javascript">
function show_sub_payments(payment_code){
	$(".sub_payments").addClass("sub_payments_hid");
	$(".sub_payments input[type=radio]").attr("checked",false);
	$(".payment_"+payment_code).removeClass("sub_payments_hid");
}

function check_subpay(){
	var payments=document.getElementsByName('payment');
	for(var i=0;i<payments.length;i++){
		var payment_input=document.getElementsByName('payment')[i];
		if(payment_input.checked){
			var payment_id=payment_input.id;
			$("."+payment_id).removeClass("sub_payments_hid");
			break;
		}
	}
}
window.load=check_subpay();

function aim_go(){
	var cname=document.getElementById('card_name').value;
	var cnum=document.getElementById('card_num').value;
	var cavv=document.getElementById('card_cavv').value;
	var cdate=document.getElementById('month').value+"_"+document.getElementById('year').value;

	var sUrl = web_base + '/carts/aim_check/'+1+"/"+cnum+"/"+cname+"/"+cavv+"/"+cdate;
	var postData ={
		is_ajax:1
	};
	$.post(sUrl, postData, aim_Success,'json');

}


function payment_check(){
	var pay=0;
	var payments=document.getElementsByName('payment');
	for(var i=0;i<payments.length;i++){
		var payment_input=document.getElementsByName('payment')[i];
		if(payment_input.checked){
			pay=payment_input.value;
			break;
		}
	}
	if(pay == 0){
		alert("<?php echo $ld['fill_payment_method']?>");
		return false;
	}
	document.forms['check_order'].submit();
}


var aim_Success = function(data){
	if(data.message==0){
		alert('<?php $ld["cart_001"]; ?>');
		return;
	}
	if(data.message==1){
		document.forms['check_order'].submit();
		return;
	}
	if(data.message==2){
		alert('<?php $ld["cart_002"]; ?>');
		return;
	}
	if(data.message==3){
		alert('<?php $ld["cart_003"]; ?>');
		return;
	}
}

function isShow(){
	var pay=i=0;
	while(true){
		if(document.getElementById('payment_'+i)==null){
			break;
		}
		if(document.getElementById('payment_'+i).checked){
			var xx=document.getElementById('payment_'+i);
			pay = 1;
		}
		i++;
	}
	if(xx.className=='cc'){
		document.getElementById('cc_div').style.display="block";
		y=document.getElementById('bank_div');
		if(y !=null){
			t=y.parentNode.parentNode;
			t.style.display='';
		}
		z=document.getElementById('pos_div');
		if(z !=null)
			z.style.display="none";
	}else if(xx.className=='bank_cc'){
		document.getElementById('bank_div').parentNode.parentNode.style.display="";
		x=document.getElementById('cc_div');
		if(x !=null)
			x.style.display="none";
		z=document.getElementById('pos_div');
		if(z !=null)
			z.style.display="none";
	}else if(xx.className=='pos_cc'){
		document.getElementById('pos_div').style.display="block";
		x=document.getElementById('cc_div');
		if(x !=null)
			x.style.display="none";
		y=document.getElementById('bank_div');
		if(y !=null){
			t=y.parentNode.parentNode;
			t.style.display='none';
		}
	}else{
		x=document.getElementById('cc_div');
		if(x !=null)
			x.style.display="none";
		y=document.getElementById('bank_div');
		if(y !=null){
			t=y.parentNode.parentNode;
			t.style.display='none';
		}
		z=document.getElementById('pos_div');
		if(z !=null)
			z.style.display="none";
	}
}
</script>