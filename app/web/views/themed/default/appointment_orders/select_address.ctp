<div class="am-g am-reservation-address-select">
	<?php
		if(isset($user_address_list)&&sizeof($user_address_list)>0){
			foreach($user_address_list as $v){
	?>
	<div class='am-g'>
		<div class='am-u-lg-9 am-u-md-8 am-u-sm-9'>
			<label class='am-u-lg-2 am-u-md-2 am-u-sm-4'>地址</label>
			<div class='am-u-lg-10 am-u-md-10 am-u-sm-8'><?php if(isset($region_list)){echo $region_list[$v['UserAddress']['country']].$region_list[$v['UserAddress']['province']].$region_list[$v['UserAddress']['city']];};?><?php echo $v['UserAddress']['address']; ?></div>
			<div class='am-cf'></div>
			<label class='am-u-lg-2 am-u-md-2 am-u-sm-4'>姓名</label>
			<div class='am-u-lg-10 am-u-md-10 am-u-sm-8'><?php echo $v['UserAddress']['consignee']; ?></div>
			<div class='am-cf'></div>
			<label class='am-u-lg-2 am-u-md-2 am-u-sm-4'>电话</label>
			<div class='am-u-lg-10 am-u-md-10 am-u-sm-8'><?php echo $v['UserAddress']['mobile']; ?></div>
			<div class='am-cf'></div>
		</div>
		<div class='am-u-lg-3 am-u-md-4 am-u-sm-3'>
			<div class="am-text-right select_address" address_id="<?php echo $v['UserAddress']['id']; ?>"><?php if(isset($_REQUEST['select_address_id'])&&$_REQUEST['select_address_id']==$v['UserAddress']['id']){ ?><span class='am-icon-check'></span><?php } ?>&nbsp;</div>
			<div class="am-text-center default_address"><?php echo $v['UserAddress']['id']==$v['User']['address_id']?"[当前默认]":""; ?></div>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php
			}
		}
	?>
	<div class='am-text-center am-reservation-address-manager'>
		<a class='am-btn am-btn-warning am-btn-block' href="<?php echo $html->url('/appointment_orders/address_list'); ?>">管理收货地址</a>
	</div>
</div>
<style type='text/css'>
.am-reservation-address-select{max-width:1200px;margin:0 auto;}
.am-reservation-address-select>div.am-g{border-bottom:1px solid #dedede;margin-bottom:5px;}
.am-reservation-address-manager{width:100%;margin: 10px auto;margin-top:30px;}
@media only screen and (max-width:641px) {
	.am-reservation-address-manager{position: fixed;bottom:0px;margin-top:10px;}
}
.am-reservation-address-manager a.am-btn.am-btn-block{width:100%;}
.select_address{margin-top:20px;}
.select_address span{color:#5eb95e;}
.default_address{color:#f37b1d;}
</style>
<script type='text/javascript'>
$(function(){
	$('.select_address').parent().parent().click(function(){
		var child_span=$(this).find('span').length;
		if(child_span==0){
			$('.select_address span').remove();
			$(this).find('.select_address').append("<span class='am-icon-check'></span>");
		}
		var address_id=$(this).find('.select_address').attr('address_id');
		window.location.href=web_base+"/appointment_orders/index?select_address_id="+address_id;
	});
});
</script>