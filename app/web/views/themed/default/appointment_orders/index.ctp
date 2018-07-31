<div class="am-g am-reservation">
	<form class='am-form am-form-horizontal' method='POST' action="<?php echo $html->url('/appointment_orders/checkout_order'); ?>" onsubmit="return appointment_order(this);">
		<div class='am-form-group' data-am-modal="{target: '#product_service_type', closeViaDimmer: 0}">
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>项目</label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'><input type='hidden' name='service_type' value='' /><span id='show_service_type'></span>&nbsp;(点击添加项目)</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'><?php echo $ld['address']; ?></label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'>
				<?php if(isset($user_address_info)&&!empty($user_address_info)){ ?>
				<input type='hidden' name='address_id' value="<?php echo $user_address_info['UserAddress']['id']; ?>" />
				<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
					<div><?php if(isset($region_list)){echo $region_list[$user_address_info['UserAddress']['country']].$region_list[$user_address_info['UserAddress']['province']].$region_list[$user_address_info['UserAddress']['city']];};?><?php echo $user_address_info['UserAddress']['address']; ?></div>
					<div><?php echo $user_address_info['UserAddress']['consignee']; ?></div>
					<div><?php echo $user_address_info['UserAddress']['mobile']; ?></div>
				</div>
				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'>
					<a href="<?php echo $html->url('/appointment_orders/select_address?select_address_id='.$user_address_info['UserAddress']['id']); ?>">&gt;</a>
				</div>
				<?php }else{ ?>
				<a href="<?php echo $html->url('/appointment_orders/address_view/0'); ?>">添加地址</a>
				<?php } ?>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>预约时间</label>
			<div class='am-u-lg-3 am-u-md-4 am-u-sm-3'>
				<input type='text' name="appointment_date[]" id='appointment_date' readonly />
			</div>
			<div class='am-u-lg-3 am-u-md-4 am-u-sm-3'>
				<select name="appointment_date[]">
					<option value=''><?php echo $ld['please_select']; ?></option>
					<?php if(isset($informationresource_infos['best_time'])&&sizeof($informationresource_infos['best_time'])>0){foreach($informationresource_infos['best_time'] as $k=>$v){ ?>
					<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group'>
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4'>取衣方式</label>
			<div class='am-u-lg-8 am-u-md-8 am-u-sm-8'><input type='hidden' name="shipping_id" value="<?php echo isset($shipping_info['Shipping'])?$shipping_info['Shipping']['id']:'0'; ?>" /><?php echo isset($shipping_info['ShippingI18n'])?$shipping_info['ShippingI18n']['name']:''; ?></div>
			<div class='am-cf'></div>
		</div>
		<div class='am-form-group am-text-center'>
			<button class='am-btn am-btn-warning am-btn-block' type='submit' <?php echo !isset($shipping_info['Shipping'])?'disabled':''; ?>>马上预约</button>
			<div class='am-cf'></div>
		</div>
	</form>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="product_service_type">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		<div class='am-form-group'>
    			<?php if(isset($system_resources['order_product_service_type'])&&sizeof($system_resources['order_product_service_type'])>0){foreach($system_resources['order_product_service_type'] as $k=>$v){ ?>
    			<div class='am-g'>
	    			<div class='am-u-sm-4'>&nbsp;</div>
	    			<div class='am-u-sm-4'>
	    				<label class='am-checkbox am-warning'>
		    				<input type="checkbox" value="<?php echo $v; ?>" data-am-ucheck> <?php echo $v; ?>
		    			</label>
	    			</div>
	    			<div class='am-u-sm-4'>&nbsp;</div>
	    			<div class='am-cf'></div>
	    		</div>
    			<?php }} ?>
    		</div>
    		<div class="am-form-group">
    			<button class='am-btn am-btn-warning am-btn-block' type='button' onclick="set_server_type()">完成</button>
    		</div>
    </div>
  </div>
</div>
<style type='text/css'>
.am-reservation{max-width:1200px;margin:0 auto;}
.am-reservation .am-form-group [class*=am-u-]{padding:0px 0.1rem;}
.am-reservation .am-btn.am-btn-block{width:100%;}
#product_service_type label.am-checkbox{text-align:center;}
</style>
<script type='text/javascript'>
var nowTemp = new Date();
var nowDay = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0).valueOf();
var nowMoth = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 1, 0, 0, 0, 0).valueOf();
var nowYear = new Date(nowTemp.getFullYear(), 0, 1, 0, 0, 0, 0).valueOf();
var $appointment_date = $('#appointment_date');
var checkin = $appointment_date.datepicker({
  theme:'success',
  onRender: function(date, viewMode) {
	    // 默认 days 视图，与当前日期比较
	    var viewDate = nowDay;
	    switch (viewMode) {
		      // moths 视图，与当前月份比较
		      case 1:
		        viewDate = nowMoth;
		        break;
		      // years 视图，与当前年份比较
		      case 2:
		        viewDate = nowYear;
		        break;
	    }
	    return date.valueOf() < viewDate ? 'am-disabled' : '';
  }
}).data('amui.datepicker');

function set_server_type(){
	var server_type=[];
	$("#product_service_type input[type='checkbox']:checked").each(function(){
		server_type.push($(this).val());
	});
	$("#show_service_type").parent().find("input[type='hidden']").val(server_type.join(','));
	$("#show_service_type").html(server_type.join(','));
	$('#product_service_type').modal('close');
}

function appointment_order(post_form){
	var address_id=$("input[name='address_id']").val();
	if(typeof(address_id)=='undefined'){
		alert("<?php echo $ld['fill_address']; ?>");return false;
	}
	var appointment_date=$("input[name='appointment_date[]']").val().trim();
	var appointment_time=$("select[name='appointment_date[]']").val().trim();
	if(appointment_date==''||appointment_time==''){
		alert("请选择预约时间");return false;
	}
	return true;
}
</script>