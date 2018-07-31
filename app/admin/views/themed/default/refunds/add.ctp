<style>
.am-radio, .am-checkbox{margin-top:0px;}
.am-checkbox input[type="checkbox"]{margin-left:10px;}
.am-form-group{margin-bottom:0;}
 
</style>
<div class="am-form-group" >
	<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label-text am-text-right">订单搜索:</label>
	<div class="am-u-lg-2 am-u-md-4 am-u-sm-4"><input type="text" class="am-form-field" id="Ordercode"/></div>
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
		<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="搜索" onclick="SearchOrder()" />
	</div>
</div>
<?php echo $form->create('Refund',array('action'=>'/saveOrder','name'=>"RefundOrderForm",'id'=>"RefundOrderForm","enctype"=>"multipart/form-data","class"=>"am-form am-form-horizontal"));?>
	<div class="am-form-group" style="margin-top:50px;">
		<ul class="am-avg-lg-6">
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">退款来源:</label>
				<span id="source_show" class="am-u-lg-6"></span>
			</li>
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">订单号:</label>
				<span id="order_code" class="am-u-lg-6"></span>
			</li>
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">运费:</label>
				<span id="shipping_fee" class="am-u-lg-6"></span>
			</li>
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">折扣:</label>
				<span id="discount" class="am-u-lg-6"></span>
			</li>
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">订单总额:</label>
				<span id="total" class="am-u-lg-6"></span>
			</li>
			<li style="">
				<label class="am-u-lg-5 am-text-right" style="padding-right:0;margin-left:10px;">已付款:</label>
				<span id="money_paid" class="am-u-lg-6"></span>
			</li>
		</ul>
	<div>
	<div class="am-panel-group am-panel-tree" id="priview_table">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:1%;">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck checked />
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-1 am-u-sm-1" style="width:13%;">退货商品-价格-数量</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:12%;">退货单号</div>
					<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">退货退款类型</div>
					<div class="am-u-lg-2 am-u-md-1 am-u-sm-1">退款状态</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">交易总金额</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">退货单价</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">退货数量</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">退货运费</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<div id="tbody1">
			
				
	
			</div>
		</div>
	</div>
	<div><input type="submit" class="am-btn am-radius am-btn-sm am-btn-success" id="Order_button" value="保存"/></div>
<?php echo $form->end();?>
<script>
	//ajax搜订单
	function SearchOrder(){
		var order_code = Trim(document.getElementById("Ordercode").value);//搜索关键字
		//var tb = document.getElementById('priview_table');
		//var index = tb.rows.length-1;
		var index=$("#priview_table").size()-1;
		//alert(index);
		if(order_code.replace(/([\u0391-\uFFE5])/ig,'11').length<4){
			alert('订单号必须大于6位');
			return false;
		}
		$.ajax({
			url:admin_webroot+"refunds/searchOrders/",
			type:"POST",
			data:{order_code:order_code,k:index},
			dataType:"json",
			success:function(data){
				if(data.flag=="1"){
					var tbody = document.getElementById('tbody1');
					tbody.innerHTML="";
					$.each(data.content,function(v,k){
						append_row(v,k);
					});
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
			}
		});
		
		
/*		YUI().use("io",function(Y) {
			var sUrl = admin_webroot+"refunds/searchOrders/";//访问的URL地址
			var tb = document.getElementById('priview_table');
			var index = tb.rows.length-1;
			var postData = "order_code="+order_code+"&k="+index;
			var cfg = {
				method: "POST",
				data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				if(o.responseText !== undefined){
					try{
						eval('result='+o.responseText);
					}catch(e){
						alert(j_object_transform_failed);
						alert(o.responseText);
					}
					if(result.flag=="1"){
						var tbody = document.getElementById('tbody1');
						tbody.innerHTML="";
						Y.each(result.content,function(v,k){
							append_row(v,k);
						});
						return;
					}
					if(result.flag=="2"){
						alert(result.content);
					}
				}
			}
			var handleFailure = function(ioId, o){
				//alert("异步请求失败!");
			}
			Y.on('io:success', handleSuccess);
			Y.on('io:failure', handleFailure);
		});*/
	}
	
	//动态增加一行
   	function append_row(v,k){
   	//	alert(v);
   	//	alert(k);
   		var tbody = document.getElementById("tbody1");
   		var show_source=document.getElementById("source_show");
   		var order_code=document.getElementById("order_code");
   		var shipping_fee=document.getElementById("shipping_fee");
   		var discount=document.getElementById("discount");
   		var total=document.getElementById("total");
   		var money_paid=document.getElementById("money_paid");
   		show_source.innerHTML=k.source_type_id;
   		order_code.innerHTML=k.order_code+'<input type="hidden" name="order_code"  value="'+k.order_code+'" />';
   		shipping_fee.innerHTML=k.shipping_fee;
   		discount.innerHTML=k.discount;
   		total.innerHTML=k.total;
   		money_paid.innerHTML=k.money_paid;
	var div="<div><div class='am-panel am-panel-default am-panel-body'>"+"<div class='am-panel-bd'>"+
				"<input type='hidden' name='source_type' value='"+k.source_type+"' />"+
				"<input type='hidden' name='source_type_id' value='"+k.source_type_id+"' />"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1' style='width:1%;'>"+
					"<label class='am-checkbox am-success'><input type='checkbox'  class='checkbox' value='"+v+"' name='checkboxes[]' checked></label>"+
				"</div>"+
				"<div class='am-u-lg-2 am-u-md-1 am-u-sm-1' style='width:13%;'>"+k.product_name+"<br/>货号："+k.product_code+"<br />"+k.product_price+" X "+k.product_quantity+
					"<input type='hidden' name='data["+v+"][Refund][product_id]' value="+k.product_id+" />"+
					"<input type='hidden' name='data["+v+"][Refund][product_name]' value='"+k.product_name+"' />"+
					"<input type='hidden' name='data["+v+"][Refund][product_code]' value='"+k.product_code+"' />"+
				"</div>"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1' style='width:12%;'>"+
					"<input type='text' class='text' name='data["+v+"][Refund][refund_id]' value='' />"+
				"</div>"+
				"<div class='am-u-lg-2 am-u-md-1 am-u-sm-1' style='padding-right:0;'>"+
					"<div class='am-form-group'>"+
						"<div class='am-u-lg-6 am-u-md-6 am-u-sm-6' style='margin-bottom:10px;padding:0;padding-rigth:2px;'>"+
							"<select name='data["+v+"][Refund][refund_type]'>"+
								"<option value='0'>退款</option>"+
								"<option value='1'>退货</option>"+
							"</select>"+
						"</div>"+
						"<div class='am-u-lg-6 am-u-md-6 am-u-sm-6' style='margin-bottom:10px;padding:0;padding-left:2px;'>"+
							"<input type='hidden' name='data["+v+"][Refund][receive_state]'  value='"+k.receive_state+"' />"+
							"<select name='data["+v+"][Refund][return_reason_type]'>"+
								"<option value='与卖家协商一致退款'>与卖家协商一致退款</option>"+
								"<option value='商品缺少所需样式'>商品缺少所需样式</option>"+
								"<option value='拒收'>拒收</option>"+
								"<option value='大库入'>大库入</option>"+
							"</select>"+
						"</div>"+
					"</div>"+
					"<div class='am-form-group'>"+
						"<label class='am-u-lg-12 am-u-md-6 am-u-sm-6 am-form-label am-text-left' style='margin-left:0;padding:0;'>退款原因：</label>"+
						"<div class='am-u-lg-12 am-u-md-6 am-u-sm-6' style='padding:0;'>"+
							"<input type='text' name='data["+v+"][Refund][return_reason]' value='' />"+
						"</div>"+
					"</div>"+
				"</div>"+
				"<div class='am-u-lg-2 am-u-md-1 am-u-sm-1'>"+
					"<select name='data["+v+"][Refund][status]'>"+
						"<option value='WAIT_SELLER_CONFIRM_GOODS'>买家已经退货，等待卖家确认收货</option>"+
						"<option value='WAIT_SELLER_AGREE'>买家已经申请退款，等待卖家同意</option>"+
						"<option value='WAIT_BUYER_RETURN_GOODS'>卖家已经同意退款，等待买家退货</option>"+
						"<option value='SELLER_REFUSE_BUYER'>卖家拒绝退款</option>"+
						"<option value='CLOSED'>退款关闭</option>"+
						"<option value='SUCCESS'>退款成功</option>"+
						"<option value='BUYER_NOT_ASK'>没有申请退款</option>"+
						"<option value='SELLER_REFUSE_RETURN'>卖家拒绝确认收货</option>"+
						"<option value='WAIT_SELLER_REFUND'>同意退款，待打款</option>"+
					"</select>"+
				"</div>"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 text'>"+
					"<input type='text'  name='data["+v+"][Refund][total_fee]' value='' />"+
				"</div>"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 text'>"+
					"<input type='text' name='data["+v+"][Refund][product_price]' value=''/>"+
				"</div>"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 text'>"+
					"<input type='text' name='data["+v+"][Refund][product_quantity]' value='' />"+
				"</div>"+
				"<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 text'>"+
					"<input type='text' name='data["+v+"][Refund][shipping_fee]' value='' />"+
				"</div>"+
				"<div style='clear:both;'></div>"+
			"</div>"+"</div></div>";
		$("#tbody1").append(div);
		$(".checkbox").uCheck();
	
   	}
</script>