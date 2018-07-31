<style>

</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('Combined',array('action'=>'view' ,"onsubmit"=>"return add_order_basicinfo();"));?>
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in" >
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<input type="hidden" name="data[Order][code]" id="affixx" value="<?php echo empty($codes)?"":$codes;?>">
						<div class="am-form-group" style="margin-bottom:5px;">
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['please_enter_the_merge_Order_No.']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="opener_select_user_id">
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<input type="button" class="am-btn am-radius am-btn-sm am-btn-success"  onclick="combined_order();" value="<?php echo $ld['add']?>">
							</div>
						</div>
						<div class="am-panel-group am-panel-tree"  >
							<div class="am-panel am-panel-default am-panel-header">
								<div class="am-panel-hd">
									<div class="am-panel-title">
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['order_code']?></div>
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_goods']?></div>
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['consignee']?></div>
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['consignee_address']?></div>
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
										<div style="clear:both;"></div>
									</div>
								</div>
							</div>
							<div id="order_infobody">
							<?php if(!empty($all_codes)){foreach ($all_codes as $k =>$v) {?>
								<div>
									<div class="am-panel am-panel-default am-panel-body" >
										<div class="am-panel-bd">	
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $v['Order']['order_code'];?></div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<?php if(!empty($v['OrderProduct'])){foreach ($v['OrderProduct'] as $kk => $vv){echo $vv['product_code'].'--'.$vv['product_name'].'--'.$vv['product_quntity']."<br>";}}?>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<?php echo empty($v['Order']['consignee'])?'':$v['Order']['consignee'];?>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<?php echo $v['Order']['country'].' '.$v['Order']['province'].' '.$v['Order']['city'].' '.$v['Order']['district'];?>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<a href="#" onclick="delete_order(this,'<?php echo $v['Order']['order_code']?>')"><?php echo $ld['delete']?></a>
											</div>
											<div style="clear:both;"></div>
										</div>
									</div>
								</div>							
							<?php }}?>
							</div>
						</div>
						<div class="btnouter">
							<input type="submit" class="am-btn am-radius am-btn-sm am-btn-success" value="<?php echo $ld['merge']?>" onclick="" />
							<input type="reset" class="am-btn am-radius am-btn-sm am-btn-default" value="<?php echo $ld['reset']?>" onclick="table_reset()" />
						</div>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>

<script type="text/javascript">
function table_reset(){
	$("#order_infobody>tr").remove();
	document.getElementById('affixx').value="";
}
function add_order_basicinfo(){

	var opener_select_user_id=document.getElementById('affixx');//订单号
	if( opener_select_user_id.value == '' ){
		alert('合并订单最少需要2个订单号');
		return false;
	}else{
		if(opener_select_user_id.value.split("|").length<=2){
			alert('合并订单最少需要2个订单号');
			return false;
		}
		document.getElementById('affixx')='';
		return true;
	}
}
<?php if(!empty($error)){?>
	alert("<?php echo $error.'订单不能合并';?>");
<?php }?>

//是否有效订单（订单合并）
function combined_order(){

	var opener_select_user_id=document.getElementById('opener_select_user_id');//订单号
	var affixx=document.getElementById('affixx');
	if( opener_select_user_id.value == "" ){
		alert('请填写订单号');
		return false;
	}
	$.ajax({
		url:admin_webroot+"combineds/combined_order_unique/"+opener_select_user_id.value,
		type:"POST",
		dataType:"json",
		success:function(data){
			if(data.flag==1){
					if(affixx.value.indexOf(opener_select_user_id.value)<0){
						append_row(data.content);
						document.getElementById('affixx').value += data.content['Order']['order_code']+"|";
					}else{
						alert("不能添加重复的订单号!");
					}
				}
				if(data.flag==2){
					alert(data.content);
				}
		}
	});
	
	
	
	
	
/*	YUI().use("io",function(Y){
		var sUrl = admin_webroot+"combineds/combined_order_unique/"+opener_select_user_id.value;
		var cfg = {
			method: 'POST'
		};
		var request = Y.io(sUrl,cfg);
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
				if(result.flag==1){
					if(affixx.value.indexOf(opener_select_user_id.value)<0){
						append_row(result.content);
						document.getElementById('affixx').value += result.content['Order']['order_code']+"|";
					}else{
						alert("不能添加重复的订单号!");
					}
				}
				if(result.flag==2){
					alert(result.content);
				}
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}
	function append_row(v){
//		var tbody = document.getElementById('order_infobody');
//		var newTR = tbody.insertRow(-1);
//		var newTD0 = newTR.insertCell(-1);
//			newTD0.innerHTML=v['Order']['order_code'];
//		var newTD1 = newTR.insertCell(-1);
//			newTD1.innerHTML='';
		var product='';
			for (var i=0; i < v['OrderProduct'].length; i++) {
				product += v['OrderProduct'][i]['product_code']+'--'+v['OrderProduct'][i]['product_name']+'--'+v['OrderProduct'][i]['product_quntity']+"\r\n";
			};
//		var newTD4 = newTR.insertCell(-1);
//			newTD4.innerHTML=v['Order']['consignee'];
//		var newTD2 = newTR.insertCell(-1);
//			newTD2.innerHTML=v['Order']['country']+' '+v['Order']['province']+' '+v['Order']['city']+' '+v['Order']['district'];
//		var newTD3 = newTR.insertCell(-1);
//			newTD3.innerHTML='<a href="#" onclick="delete_order(this,'+v["Order"]["order_code"]+')">删除</a>';
		var div="<div><div class='am-panel am-panel-default am-panel-body'><div class='am-panel-bd'><div class='am-u-lg-4 am-u-md-4 am-u-sm-4'>"+v['Order']['order_code']+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>"+product+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>"+v['Order']['consignee']+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>"+ v['Order']['country']+" "+v['Order']['province']+""+v['Order']['city']+""+v['Order']['district']+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><a href='javascript:void(0);' onclick='delete_order(this,"+v['Order']['order_code']+")'><?php echo $ld['delete']?></a></div><div style='clear:both;'>&nbsp;</div></div></div></div>";
		$("#order_infobody").append(div);



	}

	function delete_order(obj,order_code){
		var val = document.getElementById('affixx').value;
		//alert(val);
		document.getElementById('affixx').value = val.replace(order_code+'|','');
	//	alert(document.getElementById('affixx').value)
		$(obj).parent().parent().parent().parent().remove();
	//	alert(document.getElementById('affixx').value);
	}
</script>