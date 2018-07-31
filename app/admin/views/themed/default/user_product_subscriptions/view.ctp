<style>
	.scrollspy-nav {
    top: 0;
    z-index: 500;
    background: #5eb95e;
    width: 100%;
    padding: 0 10px;
  }

  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
    color: #eee;
    padding: 10px 20px;
    display: inline-block;
  }

  .scrollspy-nav a.am-active {
    color: #fff;
    font-weight: bold;
  }
  
  .crumbs{
  	padding-left:0;
  	margin-bottom:22px;
  }
</style>

<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
	<ul>
	 	<li><a href="#notify_information"><?php echo $ld['basic_information'] ?></a></li>
	</ul>
</div>

<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-panel-group" style="padding:0;">
<form class="am-form" method="post" onsubmit="return notify_form()" id="subscript_form" action="<?php echo $html->url('/notify_templates/view/'.isset($subscription_data['UserProductSubscription']['id'])?$subscription_data['UserProductSubscription']['id']:0 ) ?>">
	<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
<button type="button" onclick="checksave()" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['save']; ?></button>
<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['reset']; ?></button>
</div>
	<div class="am-panel am-panel-default" id="notify_information" style="min-height:620px;">
		<div class="am-panel-hd am_hd_background" style="border-bottom:1px solid #ddd;font-weight:600">
			<?php echo $ld['basic_information'] ?>
		</div>
		<div class="am-panel-bd am-cf">
			<input type="hidden" value="<?php echo isset($subscription_data['UserProductSubscription']['id'])?$subscription_data['UserProductSubscription']['id']:0 ?>" name="data[UserProductSubscription][id]">
			<div class="am-form-group am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['user_name'] ?></label>
		  	<div class="am-u-sm-3 am-u-lg-2 am-u-md-3">
		  		<select name="data[UserProductSubscription][user_id]" id="subscription_select_name" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
		  			<option value=""><?php echo $ld['please_select'] ?></option>
		  			<?php if(isset($subscription_data['UserProductSubscription']['user_id'])&&!empty($subscription_data['UserProductSubscription']['user_id'])){ ?>
							<option value="<?php echo $subscription_data['User']['id']; ?>" selected><?php echo $subscription_data['User']['name']; ?></option>
					<?php } ?>
		  		</select>
		  	</div>
		  	<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
		  		<input type="text" name="" id="subscriptions_input" value="" style="height:32px;">
		  	</div>
		  	<div class="am-u-sm-3 am-u-md-3 am-u-lg-2">
		  		<a href="javascript:void(0)" onclick="subscriptions_search(this,<?php echo isset($subscription_data['UserProductSubscription']['id'])?$subscription_data['UserProductSubscription']['id']:0; ?>)" class="am-btn am-btn-success am-btn-sm am-radius subscriptions_search"><?php echo $ld['search'] ?></a>
		  	</div>
			</div>
			<?php if(isset($subscription_data['User'])){ ?>
			<div class="am-form-group am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['real_name'] ?></label>
		  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6" style="line-height:37px;">
		  			<?php echo $subscription_data['User']['first_name']; ?>
		  		</div>
			</div>
			<?php } ?>
			<div class="am-form-group am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['subscription_name'] ?></label>
		  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  			<input type="text" name="data[UserProductSubscription][name]" id="" value="<?php echo $subscription_data['UserProductSubscription']['name'] ?>">
		  		</div>
			</div>

			<div class="am-form-group am-g" style="margin-top:5px;">
		  	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['brand'] ?></label>
		  	<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  	<select id="subscriptions_brand"  name="data[UserProductSubscription][brand][]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($brand_data)){foreach ($brand_data as $k => $v) { ?>
  			<option <?php echo isset($subscription_data['UserProductSubscription']['brand'])&&in_array($v['Brand']['id'],$subscription_data['UserProductSubscription']['brand'])?'selected':''; ?> value="<?php echo $v['Brand']['id'] ?>" ><?php echo $v['BrandI18n']['name'] ?></option>
			<?php }} ?>
		  	</select>
		  	</div>
			</div>
			
			<div class="am-form-group am-g" style="margin-top:5px;">
		  	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['classification'] ?></label>
		  	<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  	<select id="subscriptions_category"  name="data[UserProductSubscription][category][]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($category_data)){foreach ($category_data as $k => $v) { ?>
  			<option <?php echo isset($subscription_data['UserProductSubscription']['category'])&&in_array($v['CategoryProduct']['id'], $subscription_data['UserProductSubscription']['category'])?'selected':'' ?> value="<?php echo $v['CategoryProduct']['id'] ?>"><?php echo $v['CategoryProductI18n']['name'] ?></option>
  			<?php if (isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0) {foreach ($v['SubCategory'] as $kk => $vv) { ?>
			<option <?php echo isset($subscription_data['UserProductSubscription']['category'])&&in_array($vv['CategoryProduct']['id'], $subscription_data['UserProductSubscription']['category'])?'selected':'' ?> value="<?php echo $vv['CategoryProduct']['id'] ?>">--<?php echo $vv['CategoryProductI18n']['name'] ?></option>
  			<?php }} ?>
			<?php }} ?>
		  	</select>
		  	</div>
			</div>
			
			<div class="am-form-group am-g" style="margin-top:5px;">
		  	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['product_type'] ?></label>
		  	<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  	<select placeholder="<?php echo $ld['please_select'] ?>" name="data[UserProductSubscription][product_type][]" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}" id="attribute_group" onchange="attribute()">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($product_type_data)){foreach ($product_type_data as $k => $v) { ?>
  			<option <?php echo isset($subscription_data['UserProductSubscription']['product_type'])&&in_array($v['ProductType']['id'],$subscription_data['UserProductSubscription']['product_type'])?'selected':''; ?> value="<?php echo $v['ProductType']['id'] ?>" ><?php echo $v['ProductTypeI18n']['name'] ?></option>
			<?php }} ?>
		  	</select>
		  	</div>
			</div>
			
			<div class="am-form-group am-g" style="margin-top:5px;">
		  	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['attribute'] ?></label>
		  	<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  	<select multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}"  name="data[UserProductSubscription][attribute_value][]" id="attribute_optgroup">
		  	<option value=""><?php echo $ld['please_select'] ?></option>
		  	</select>
		  	</div>
			</div>
			
			<div class="am-form-group am-g" style="margin-top:5px;">
		  <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['sending_time_period'] ?></label>
		  <div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
		  <select name="data[UserProductSubscription][send_time]" id="send_time" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
  			<option value=""><?php echo $ld['please_select'] ?></option>
			<?php if(isset($informationresource_infos)){foreach ($informationresource_infos['product_subscription'] as $k => $v) { ?>
  			<option <?php if ($k == $subscription_data['UserProductSubscription']['send_time']) {
  				echo "selected";
  			} ?> value="<?php echo $k ?>" ><?php echo $v ?></option>
			<?php }} ?>
		  </select>
		  </div>
			</div>

			<div class="am-form-group am-g" style="margin-top:5px;">
		  	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['status'] ?></label>
		  	<div class="am-u-lg-5 am-u-md-6 am-u-sm-6" style="">
		  		<label class="am-radio-inline">
		  		<input type="radio" name="data[UserProductSubscription][status]" <?php if (isset($subscription_data['UserProductSubscription']['status'])&&$subscription_data['UserProductSubscription']['status'] == 1) {echo "checked";}elseif (!isset($subscription_data['UserProductSubscription']['status'])) {
		 	 	echo "checked";
		 	 	} ?> value="1"><?php echo $ld['yes'] ?>
		  		</label>
		  		<label class="am-radio-inline">
		  		<input type="radio" name="data[UserProductSubscription][status]" <?php if (isset($subscription_data['UserProductSubscription']['status'])&&$subscription_data['UserProductSubscription']['status'] == 0) {echo "checked";} ?> value="0"><?php echo $ld['no'] ?>
		  		</label>
		  	</div>
		 	</div>
			
		</div>
	</div>
</form>
</div>

<script>
var subscription_attribute_value = <?php echo isset($subscription_data['UserProductSubscription']['attribute_value'])?json_encode($subscription_data['UserProductSubscription']['attribute_value']):'""'; ?>;

	function attribute () {
	  $("#attribute_optgroup optgroup").remove();	
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
	  	url:admin_webroot+"user_product_subscriptions/ajax_product_attribute",
	  	dataType:"json",
	  	type:"POST",
	  	data:{"product_type_id":post_data},
	  	success:function (data) {
	  		if (data.code == 1) {
	  			$.each(data.data,function (index,content) {
	  				var optgroup_option = ""
	  				$.each(content.AttributeOption,function (ind,con) {
	  					var attr_check=false;
	  					if (subscription_attribute_value!="") {
	  						$.each(subscription_attribute_value,function (ind2,con2) {
	  							var attr_text_arr = con2.split(":");
	  							if (attr_text_arr[0]==con.attribute_id && attr_text_arr[1] == con.option_value) {
	  								attr_check = true;
	  							};
	  						})
	  					};
	  						optgroup_option+='<option '+(attr_check?"selected":"")+' value="'+con.attribute_id+":"+con.option_value+'">'+con.option_name+'</option>';
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

	function  subscriptions_search(obj,user_id) {
		$("#subscription_select_name option:gt(0)").remove();
		var subscription_data = $("#subscriptions_input").val().trim()
		$.ajax({
			url:admin_webroot+"users/order_search_user_information",
			type:"POST",
			dataType:"json",
			data:{"keywords":subscription_data},
			success:function (data) {
				var name_html = "";
				$.each(data.message,function (index,content) {
					name_html+="<option value='"+content['User']['id']+"'>"+content['User']['name']+"</option>";
				});
				$("#subscription_select_name").append(name_html);
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