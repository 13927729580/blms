<div class="am-g am-reservation-address">
	<div class='am-text-center am-reservation-address-add'>
		<a class='am-btn am-btn-warning am-btn-block' href="<?php echo $html->url('/appointment_orders/address_view/0'); ?>">新增收货地址</a>
	</div>
	<?php
		if(isset($user_address_list)&&sizeof($user_address_list)>0){
			foreach($user_address_list as $v){
	?>
	<div class='am-g'>
		<label class='am-u-lg-2 am-u-md-2 am-u-sm-3'>地址</label>
		<div class='am-u-lg-10 am-u-md-10 am-u-sm-9'><?php if(isset($region_list)){echo $region_list[$v['UserAddress']['country']].$region_list[$v['UserAddress']['province']].$region_list[$v['UserAddress']['city']];};?><?php echo $v['UserAddress']['address']; ?></div>
		<div class='am-cf'></div>
		<label class='am-u-lg-2 am-u-md-2 am-u-sm-3'>姓名</label>
		<div class='am-u-lg-10 am-u-md-10 am-u-sm-9'><?php echo $v['UserAddress']['consignee']; ?></div>
		<div class='am-cf'></div>
		<label class='am-u-lg-2 am-u-md-2 am-u-sm-3'>电话</label>
		<div class='am-u-lg-10 am-u-md-10 am-u-sm-9'><?php echo $v['UserAddress']['mobile']; ?></div>
		<div class='am-cf'></div>
		<div class='am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-left'>
			<?php if($v['UserAddress']['id']==$v['User']['address_id']){echo "当前默认";}else{ ?>
			<label class='am-radio am-warning'>
				<input type="radio" value="<?php echo $v['UserAddress']['id']; ?>" onclick="ajax_default_address(this)" name='default_address' data-am-ucheck> 设为默认
			</label>
			<?php } ?>
		</div>
		<div class='am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-right'>
			<a href="<?php echo $html->url('/appointment_orders/address_view/'.$v['UserAddress']['id']); ?>"><?php echo $ld['edit']; ?></a>
			<a href="javascript:void(0);" onclick="delete_address(<?php echo $v['UserAddress']['id']; ?>)"><?php echo $ld['delete']; ?></a>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php
			}
		}
	?>
	<div class="am-cf">
		<a class='am-btn am-btn-warning am-btn-block' href="javascript:void(0);" onclick="window.history.go(-1);">返回</a>
	</div>
</div>
<style type='text/css'>
.am-reservation-address{max-width:1200px;margin:0 auto;}
.am-reservation-address>div.am-g{border-bottom:1px solid #dedede;margin-bottom:5px;}
.am-reservation-address-add{width:100%;margin: 10px auto;margin-top:30px;text-align:right!important;}
@media only screen and (max-width:641px) {
	.am-reservation-address-add{position: fixed;bottom:0px;margin-top:10px;}
	.am-reservation-address-add a.am-btn.am-btn-block{width:100%;}
}
</style>
<script type='text/javascript'>
function ajax_default_address(radio_input){
	var address_id=$(radio_input).val();
	$.ajax({
  		url: web_base+"/appointment_orders/ajax_default_address",
		type:"POST",
		data:{'address_id':address_id},
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				window.location.reload();
			}else{
				alert(result.message);
			}
  		}
  	});
}

function delete_address(address_id){
	if(confirm(confirm_delete)){
		$.ajax({
	  		url: web_base+"/addresses/user_deladdress/"+address_id,
			type:"POST",
			data:{},
			dataType:"json",
			success: function(result){
				window.location.reload();
	  		}
	  	});
	}
}
</script>