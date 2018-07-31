<div class="am-g am-reservation-address-detail">
	<?php //pr($user_address_info); ?>
	<form class='am-form am-form-horizontal' method='POST'>
		<input type='hidden' id='shipping_area_region_ids' value="<?php echo isset($shipping_area_region_ids)?implode(',',$shipping_area_region_ids):''; ?>" />
		<input type='hidden' name='data[UserAddress][id]' value="<?php echo isset($user_address_info['UserAddress'])?$user_address_info['UserAddress']['id']:0; ?>" >
		<div class='am-form-group'>
			<label class='am-u-sm-3'>姓名</label>
			<div class='am-u-sm-9'>
				<input type='text' name='data[UserAddress][consignee]' value="<?php echo isset($user_address_info['UserAddress'])?$user_address_info['UserAddress']['consignee']:''; ?>" >
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-sm-3'>联系电话</label>
			<div class='am-u-sm-9'>
				<input type='text' name='data[UserAddress][mobile]' value="<?php echo isset($user_address_info['UserAddress'])?$user_address_info['UserAddress']['mobile']:''; ?>" >
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-sm-3'>市/区</label>
			<div class='am-u-sm-9'><?php $region_ids=isset($user_address_info['UserAddress'])?explode(' ',$user_address_info['UserAddress']['regions']):array(); ?>
				<select name='data[UserAddress][regions][]' onchange="shipping_area_region(this)" parent_region="0" default_region="<?php echo isset($region_ids[0])?$region_ids[0]:0; ?>">
					<option value='0'><?php echo $ld['please_select'] ?></option>
				</select>
				<select name='data[UserAddress][regions][]' onchange="shipping_area_region(this)" parent_region="<?php echo isset($region_ids[0])&&intval($region_ids[0])>0?$region_ids[0]:''; ?>"  default_region="<?php echo isset($region_ids[1])?$region_ids[1]:0; ?>">
					<option value='0'><?php echo $ld['please_select'] ?></option>
				</select>
				<select name='data[UserAddress][regions][]' parent_region="<?php echo isset($region_ids[1])&&intval($region_ids[1])>0?$region_ids[1]:''; ?>"  default_region="<?php echo isset($region_ids[2])?$region_ids[2]:0; ?>">
					<option value='0'><?php echo $ld['please_select'] ?></option>
				</select>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-sm-3'>详细地址</label>
			<div class='am-u-sm-9'>
				<input type='text' name='data[UserAddress][address]' value="<?php echo isset($user_address_info['UserAddress'])?$user_address_info['UserAddress']['address']:''; ?>" >
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<button class='am-btn am-btn-warning am-btn-block' type="button" onclick="address_save(this)"><?php echo $ld['save']; ?></button>
		</div>
	</form>
</div>
<style type='text/css'>
.am-reservation-address-detail{max-width:1200px;margin:0 auto;}
.am-reservation-address-detail button.am-btn.am-btn-block{width:100%;}
</style>
<script type='text/javascript'>
var shipping_area_region_id_txt=$("#shipping_area_region_ids").val();
var shipping_area_region_ids=shipping_area_region_id_txt.split(',');
$(function(){
	$("select[name='data[UserAddress][regions][]']").each(function(){
		var select_obj=$(this);
		var parent_region_id=$(this).attr('parent_region');
		var default_region_id=$(this).attr('default_region');
		if(parent_region_id=='')return;
		loadregion(select_obj,parent_region_id,default_region_id);
	});
});

function loadregion(select,parent_region,default_region){
	if(shipping_area_region_ids.length==0)return false;
	parent_region=typeof(parent_region)=='undefined'||parent_region==null?0:parent_region;
	default_region=typeof(default_region)=='undefined'||default_region==null?0:default_region;
	$.ajax({
  		url: web_base+"/regions/index",
		type:"POST",
		data:{'parent_id':parent_region},
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				var region_list=result.data;
				if(typeof(region_list[parent_region])!='undefined'){
					$.each(region_list[parent_region],function(index,item){
						if($.inArray(item['Region']['id'],shipping_area_region_ids)<=-1)return;
						if(item['Region']['id']==default_region){
							$("<option selected></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo(select);
						}else{
							$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo(select);
						}
					});
				}
			}
  		}
  	});
}

function shipping_area_region(select){
	var parent_region=select.value;
	$(select).nextAll('select').each(function(){
		$(this).html("<option value=''>"+j_please_select+"</option>").attr('disabled',false).show();
	});
	var NextRegionSelect=$(select).nextAll('select')[0];
	if(typeof(NextRegionSelect)=='undefined')return;
	$.ajax({
  		url: web_base+"/regions/index",
		type:"POST",
		data:{'parent_id':parent_region},
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				var region_list=result.data;
				if(typeof(region_list[parent_region])!='undefined'){
					$.each(region_list[parent_region],function(index,item){
						if($.inArray(item['Region']['id'],shipping_area_region_ids)<=-1)return;
						$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo(NextRegionSelect);
					});
				}
			}
  		}
  	});
}

function address_save(btn){
	var post_form=$(btn).parents('form');
	var post_data=post_form.serialize();
	var consignee=$("input[name='data[UserAddress][consignee]']").val().trim();
	var mobile=$("input[name='data[UserAddress][mobile]']").val().trim();
	var regions=$("select[name='data[UserAddress][regions][]']").length;
	var address=$("input[name='data[UserAddress][address]']").val().trim();
	if(consignee==""){
		alert("<?php echo $ld['no_empty_contactor']; ?>");return false;
	}else if(mobile==""){
		alert("<?php echo $ld['phone_can_not_be_empty']; ?>");return false;
	}else if(!/^1[3-9]\d{9}$/.test(mobile)){
		alert("<?php echo $ld['phone_incorrectly_completed']; ?>");return false;
	}else if(regions==0){
		
	}else if(address==''){
		alert("<?php echo $ld['fill_address']; ?>");return false;
	}
	$.ajax({
  		url: web_base+"/appointment_orders/address_view",
		type:"POST",
		data:post_data,
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				window.history.go(-1);
			}else{
				alert(result.message);
			}
  		}
  	});
}
</script>