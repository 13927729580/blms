<?php 
	//pr($data);
 ?>
 <?php
	
	//pr($brand_data);
	 //pr($category_data);
	 //pr($product_type_data);
	//pr($public_attribute_data);
	//pr($informationresource_infos);
	
	/*
		数据提交:
			/user_product_subscriptions/index
				name:订阅名称
				brand_id:品牌(多选)
				category_id:分类(多选)
				product_type:商品属性组(多选)
				product_attribute_value:属性(多选)
				
		属性联动:
			/user_product_subscriptions/ajax_product_attribute
				product_type_id:属性组id(多个采用','分割)
		
	// */?>
<style>
	.subscriptions_view_title{
		color:#0e90d2;
		padding-left: 10px;
	}
</style>

<div class="subscriptions-view">
		<div class="am-cf" style="border-bottom:1px solid #ddd;padding:30px 0px 10px 0px;">
		<h3 class="subscriptions_view_title"><?php echo $ld['subscription_editor'] ?></h3>
		</div>
<form class="am-form" id="subscript_form" method="post" action="<?php echo $html->url('/user_product_subscriptions/view/'.isset($data['UserProductSubscription']['id'])?$data['UserProductSubscription']['id']:0); ?>">
	<input type="hidden" value="<?php echo $data['UserProductSubscription']['id'] ?>" name="data[UserProductSubscription][id]">
		<div class="am-form-group am-g" style="margin-top:18px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['subscription_name'] ?></label>
		  <div class="am-u-lg-7 am-u-md-8 am-u-sm-8">
		  <input type="text" name="data[UserProductSubscription][name]" id="" value="<?php echo $data['UserProductSubscription']['name'] ?>">
		  </div>
		</div>
		<!-- 品牌 -->
		<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['brand_mfg'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
		  <select placeholder="<?php echo $ld['please_select'] ?>" id="subscriptions_brand"  name="data[UserProductSubscription][brand][]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($brand_data)){foreach ($brand_data as $k => $v) { ?>
  			<option <?php echo isset($data['UserProductSubscription']['brand'])&&in_array($v['Brand']['id'],$data['UserProductSubscription']['brand'])?'selected':''; ?> value="<?php echo $v['Brand']['id'] ?>" ><?php echo $v['BrandI18n']['name'] ?></option>
			<?php }} ?>
		  </select>
		  </div>
		</div>
		<!-- 分类 -->
		<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['classification'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
		  <select placeholder="<?php echo $ld['please_select'] ?>" id="subscriptions_category"  name="data[UserProductSubscription][category][]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($category_data)){foreach ($category_data as $k => $v) { ?>
  			<option <?php echo isset($data['UserProductSubscription']['category'])&&in_array($v['CategoryProduct']['id'], $data['UserProductSubscription']['category'])?'selected':'' ?> value="<?php echo $v['CategoryProduct']['id'] ?>"><?php echo $v['CategoryProductI18n']['name'] ?></option>
  			<?php if (isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0) {foreach ($v['SubCategory'] as $kk => $vv) { ?>
			<option <?php echo isset($data['UserProductSubscription']['category'])&&in_array($vv['CategoryProduct']['id'], $data['UserProductSubscription']['category'])?'selected':'' ?> value="<?php echo $vv['CategoryProduct']['id'] ?>">--<?php echo $vv['CategoryProductI18n']['name'] ?></option>
  			<?php }} ?>
			<?php }} ?>
		  </select>
		  </div>
		</div>

		<!-- 属性组 -->
		<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['attribute_group'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
		  <select placeholder="<?php echo $ld['please_select'] ?>" multiple  name="data[UserProductSubscription][product_type][]" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}" id="attribute_group" onchange="attribute()">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($product_type_data)){foreach ($product_type_data as $k => $v) { ?>
  			<option <?php echo isset($data['UserProductSubscription']['product_type'])&&in_array($v['ProductType']['id'],$data['UserProductSubscription']['product_type'])?'selected':''; ?> value="<?php echo $v['ProductType']['id'] ?>" ><?php echo $v['ProductTypeI18n']['name'] ?></option>
			<?php }} ?>
		  </select>
		  </div>
		</div>
		
		<!-- 动态属性 -->

		<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['attribute'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
		  <input type="hidden" id="optgroup_value">
		  <select multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}"  name="data[UserProductSubscription][attribute_value][]" id="attribute_optgroup">
		  </select>
		  </div>
		</div>

		<!-- 发送时间段 -->

		<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="line-height:37px;"><?php echo $ld['sending_time_period'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
		  <select name="data[UserProductSubscription][send_time]" id="send_time" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($informationresource_infos)){foreach ($informationresource_infos['product_subscription'] as $k => $v) { ?>
  			<option <?php if ($k == $data['UserProductSubscription']['send_time']) {
  				echo "selected";
  			} ?> value="<?php echo $k ?>" ><?php echo $v ?></option>
			<?php }} ?>
		  </select>
		  </div>
		</div>


		<!-- 状态 -->
		<div class="am-form-group am-g">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
		  <div class="am-u-lg-9 am-u-md-8 am-u-sm-8" style="">
		  	<label class="am-radio-inline">
		  <input type="radio" name="data[UserProductSubscription][status]" <?php if (isset($data['UserProductSubscription']['status'])&&$data['UserProductSubscription']['status'] == 1) {echo "checked";}elseif (!isset($data['UserProductSubscription']['status'])) {
		  echo "checked";
		  } ?> value="1"><?php echo $ld['yes'] ?>
		  	</label>
		  	<label class="am-radio-inline">
		  <input type="radio" name="data[UserProductSubscription][status]" <?php if (isset($data['UserProductSubscription']['status'])&&$data['UserProductSubscription']['status'] == 0) {echo "checked";} ?> value="2"><?php echo $ld['no'] ?>
		  	</label>
		  </div>
		 </div>

	<div class="am-text-center">
			<button type="button" onclick="checksave()" class="am-btn am-btn-secondary am-btn-sm am-radius" value=""><?php echo $ld['save']; ?></button>

			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['reset']; ?></button> 
	</div>
</form>
</div>
<script type="text/javascript">
	var subscription_attribute_value = <?php echo isset($data['UserProductSubscription']['attribute_value'])?json_encode($data['UserProductSubscription']['attribute_value']):'""'; ?>;
	function attribute () {
	  $("#attribute_optgroup").html('');	
	  var attribute_option = $("#attribute_group option:gt(0)");
	  var array_id = [];
	  for(var i = 0;i<attribute_option.length;i++){
	  	var attribute_option_attr = attribute_option[i].selected
	  	if (attribute_option_attr) {
	  		array_id.push(attribute_option[i].value);
	  	};
	  }
	  var post_data = array_id.toString();
	  $.ajax({
	  	url:web_base+"/user_product_subscriptions/ajax_product_attribute",
	  	dataType:"json",
	  	type:"POST",
	  	data:{"product_type_id":post_data},
	  	success:function (data) {
	  		if (data.code == 1) {
	  			$.each(data.data,function (index,content) {
	  				var optgroup_option = ""
	  				$.each(content.AttributeOption,function (ind,con) {
	  						var attr_check=false;
	  						if(subscription_attribute_value!=""){
	  							$.each(subscription_attribute_value,function (ind,attr_text) {
	  								var attr_text_arr=attr_text.split(':');
	  								if(attr_text_arr[0]==con.attribute_id&&attr_text_arr[1]==con.option_value){
	  									attr_check=true;
	  								}
		  						})
	  						}
	  						optgroup_option+='<option value="'+con.attribute_id+":"+con.option_value+'" '+(attr_check?"selected":"")+'>'+con.option_name+'</option>';
	  				})
	  				if (optgroup_option != '') {
	  					var optgroup_title = '<optgroup label="'+content.AttributeI18n.name+'">'+optgroup_option+'</optgroup>';
	  					$("#attribute_optgroup").append(optgroup_title);
	  				}
	  			})
	  		}
	  	}
	  })
	}

	function checksave () {
		if ($("input[name='data[UserProductSubscription][name]']").val() == '') {
			alert(fill_in_the_name);
			return false;
		}
		if ($("#subscriptions_brand").val() == null&& $("#attribute_group").val() == null&& $("#subscriptions_category").val() == null&& $("#attribute_optgroup").val() == null) {
			alert(select_at_least_one_item);
			return false;
		}
		if ($("#send_time").val() == '') {
			alert(select_time_period);
			return false;
		}
		document.getElementById('subscript_form').submit();
	}
</script>