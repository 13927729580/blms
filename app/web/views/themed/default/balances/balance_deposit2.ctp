<input type='hidden' value="<?php echo isset($order_code)?$order_code:''; ?>" id='order_code' />
<style type='text/css'>
.order_pay_message{width:100%;margin:10px auto;text-align:center;}
.order_pay_message a.order_pay_note,.order_pay_message a.order_pay_note:hover{color:#333;}
.order_pay_message a.order_pay_note:hover{text-decoration: underline;}
</style>
<script type='text/javascript'>
function load(URL){
	var host=window.location.host;
	window.location = "http://"+host+URL;
}
</script>
<?php
    if(isset($wechatpay_type)&&isset($url2)){
        if($wechatpay_type){
?>
<div style="text-align: center;" id="wrapper">
	<div style="margin-left: 10px;font-size:30px;font-weight: bolder;">扫码支付</div><br/>
	<div style="width:150px;height:150px;margin:0 auto;" id='order_pay_QRCode'></div><!--
	<img alt="扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>-->
	<div class='order_pay_message'>请打开微信,使用扫一扫扫描二维码支付</div>
</div>
<script type='text/javascript'>
	var QRCode = $.AMUI.qrcode;
	$('#order_pay_QRCode').html(new QRCode({text:'<?php echo $url2; ?>',width: 150,height: 150}));
	
	function check_order(){
		var order_code=document.getElementById('order_code').value;
		$.ajax({
	        	url: web_base+"/balances/check_order",
	        	type: 'POST',
	        	data: {'order_id': order_code},
	        	dataType: 'json',
	        	success: function (result) {
	        		if(result.code=='1'){
	        			if(typeof(wechat_pay_time)!="undefined"){
						window.clearInterval(wechat_pay_time);
					}
					var order_item_detail=result.data.order_item_detail;
					var order_id=result.data.id;
					var service_type=result.data.service_type;
					var item_type=result.data.item_type;
					if(service_type=='virtual'){
						var back_url = web_base+'/virtual_purchases/api_pay_callback/'+order_id;
					}else{
						var back_url = web_base+'/orders/view/'+order_id;
					}
					var msg = '<i class="am-icon-lg am-icon-check-circle am-text-xxl am-text-success"></i><div class="am-text-default am-margin-lg am-margin-bottom-sm">'+order_item_detail+"&nbsp;购买成功</div>";
					if(item_type=="course"){
						msg+="<div class='am-text-center am-margin-bottom-xs'>您现在可以开始学习了</div>";
					}
					msg+="<div class='am-text-center'><a href='"+back_url+"' class='order_pay_note'>如果页面没有跳转，请点击刷新</a></div>";
					$("#wrapper img").hide();
					$(".order_pay_message").html(msg);
	        			window.setTimeout('load("' + back_url + '")',5000);
	        		}
	            }
	    	});
	}
	
	var wechat_pay_time=window.setInterval("check_order()",3000);
</script>
<?php }else{ ?>

<div class="am-g am-text-center"  id="wrapper">
    <i class="am-icon-spinner am-icon-pulse am-icon-lg"></i>
</div>
<script type="text/javascript">
callpay();
function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo $url2; ?>,
		function(res){
	            if(res.err_msg == "get_brand_wcpay_request:ok") {
			    	check_order();
	            }else{
	                	window.location.href=web_base+'/orders/';
	            }
		}
	);
}

function callpay(){
	if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        	document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        	document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        	document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
	}else{
	    	jsApiCall();
	}
}

function check_order(){
	var order_code=document.getElementById('order_code').value;
	$.ajax({
	    	url: web_base+"/balances/check_order",
	    	type: 'POST',
	    	data: {'order_id': order_code},
	    	dataType: 'json',
	    	success: function (result) {
	    		if(result.code=='1'){
					var msg = '你的订单:'+order_code+'&nbsp;支付成功';
					var order_id=result.data.id;
					var service_type=result.data.service_type;
					if(service_type=='virtual'){
						var back_url = web_base+'/virtual_purchases/api_pay_callback/'+order_id;
					}else{
						var back_url = web_base+'/orders/view/'+order_id;
					}
					var html='<div id="sidebarbox"><div class="error" style="height:200px;"><ul><li>&nbsp;&nbsp;<a href="'+back_url+'" class="ojb">'+msg+'</a></li></ul></div></div>';
	    				$("#wrapper").html(html);
	        			window.setTimeout('load("' + back_url + '")',2000);
	        	}
	        }
	});
}
</script>
<?php  }?>
<?php }else if(isset($pay_form_txt)&&$pay_form_txt!=""){ ?>
<div id="payform_show">
	<?php echo $pay_form_txt; ?>
</div>
<script type="text/javascript">
var pay_url=$("#payform_show form").prop('action');
var pay_data=$("#payform_show form").serialize();
var pay_link=pay_url+"&"+pay_data;
var tmpForm = $("<form action='/pages/redirect_link' method='get'><input type='hidden' value='"+pay_link+"' name='redirect_link_url'/></form>");
$("#payform_show").append(tmpForm);
tmpForm.submit();
</script>
<?php }else{ ?>
<script type="text/javascript">
window.location.href=web_base+"/";
</script>
<?php } ?>