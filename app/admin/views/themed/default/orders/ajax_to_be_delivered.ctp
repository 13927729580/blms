<style type='text/css'>
.am-u-lg-4.am-u-md-4.am-u-sm-9{padding:0;}
.am-selected {width: 33%;float: left;}
</style>
<table class='am-table' style="border-bottom: 1px solid #ccc;">
	<?php if(isset($order_product_list)&&sizeof($order_product_list)>0){ ?>
	<tr>
		<th>&nbsp;</th>
		<th><?php echo $ld['product_name']; ?></th>
		<th><?php echo $ld['product_code']; ?></th>
		<th><?php echo '商品条码'; ?></th>
		<th><?php echo $ld['order_quantity']; ?></th>
		<th><?php echo $ld['product_attribute']; ?></th>
	</tr>
	<?php foreach($order_product_list as $v){ ?>
	<tr>
		<td><label class='am-checkbox am-success'><input type='checkbox' name='order_product_id[]' value="<?php echo $v['OrderProduct']['id']; ?>" checked /></label></td>
		<td><?php echo $v['OrderProduct']['product_name']; ?></td>
		<td><?php echo $v['OrderProduct']['product_code']; ?></td>
		<td><?php echo $v['OrderProduct']['product_number']; ?></td>
		<td><?php echo $v['OrderProduct']['product_quntity']; ?></td>
		<td><?php echo $v['OrderProduct']['product_attrbute']; ?></td>
	</tr>
	<?php } ?>
</table>
	
	<div>
		<!-- 收货人和电话 -->
	    <div class="am-form-group">
            <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['consignee']?></div>
            <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" id="order_h" style="margin-top:1rem;">
  		        <input type="text" id="Shipment_order_consignee" name="data[OrderShipment][consignee]" value="<?php echo $order_info['Order']['consignee'];?>"/>
		    </div>
 	    </div>
 	    <div class="am-form-group">
	        <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['phone']?></div>
	        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
		    <input type="text" id="Shipment_order_telephone" name="data[OrderShipment][telephone]" value="<?php echo $order_info['Order']['telephone'];?>" />
		    </div>
 	    </div>
 	    <!-- 手机 --> 
		<div class="am-form-group" class="am-u-lg-12">
	        <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['mobile']?></div>
	        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
			    <input type="text" id="Shipment_order_mobile" name="data[OrderShipment][mobile]" value="<?php echo $order_info['Order']['mobile'];?>" />
			</div>
		</div>
		<div class="order_user_address_edit am-form-group">
			<div class="am-cf">
				<div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['region']?>
				  <input type="hidden" id="order_country2" value="<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?>">
				  <input type="hidden" id="order_province2" value="<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?>">
				  <input type="hidden" id="order_city2" value="<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>">
				</div>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
				    <div id="address_select_span" style="margin-top:0px;">
					    <select style="width: 33.33%;float: left;" name="data[OrderShipment][country]" id="Shipment_country_select" onchange="Change_Shipment_getRegions(this.value,'country')">
					    	<option value="">请选择</option>
					    </select>
					    <select style="width: 33.33%;float: left;" name="data[OrderShipment][province]" id="Shipment_province_select" onchange="Change_Shipment_getRegions(this.value,'province')">
					    	<option value="">请选择</option>
					    </select>
					    <select style="width: 33.33%;float: left;" name="data[OrderShipment][city]" id="Shipment_city_select" onchange="Change_Shipment_getRegions(this.value,'city')">
					    	<option value="">请选择</option>
					    </select>
				  	</div>			
			 	</div>
			</div>
	 	</div>
		
		<!-- 地址和email -->
		<div class="am-form-group" class="am-u-lg-12">
	        <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['address']?></div>
	          <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
			    <input type="text" id="Shipment_order_address" name="data[OrderShipment][address]" value="<?php echo $order_info['Order']['address'];?>" />
			  </div>
		</div>
		<div class="am-form-group" class="am-u-lg-12">
	        <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['email']?></div>
	          <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
		  		<input type="text" id="Shipment_order_email" name="data[OrderShipment][email]" value="<?php echo $order_info['Order']['email'];?>" />		
			  </div>
		</div>
		<!-- 邮编和发货备注 -->
		<div class="order_user_address_edit am-form-group">  
	          <div class="am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4" style="margin-top:1rem;font-weight:700;"><?php echo $ld['zip_code']?></div>
	          <div class="am-u-lg-9 am-u-md-9 am-u-sm-8" style="margin-top:1rem;">
			    <input type="text" id="Shipment_order_zipcode" name="data[OrderShipment][zipcode]" value="<?php echo $order_info['Order']['zipcode'];?>" />
			  </div>
		</div>
	</div>

	<div class="am-g am-cf" style="border-top:1px solid #ddd;padding-bottom:1rem;">
		<div class="am-cf" style="margin-top:1rem;">
		<div class='am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4' style="font-weight:700;"><?php echo $ld['order_logistics_company'] ?></div>
		<div class="am-u-lg-9 am-u-md-9 am-u-sm-8"><select name="logistics_company_id">
			<option value='0'><?php echo $ld['order_logistics']?></option>
			<?php if(isset($logistics_company_list)&&sizeof($logistics_company_list)>0){foreach($logistics_company_list as $v){ ?>
			<option value="<?php echo $v['LogisticsCompany']['id']; ?>"><?php echo $v['LogisticsCompany']['name']; ?></option>
			<?php }} ?>
		</select></div>
	</div>
	<div  class="am-cf" style="margin-top:1rem;">
		<div class='am-text-right am-u-lg-3 am-u-md-3 am-u-sm-4' style="font-weight:700;"><?php echo $ld['invoice_number'] ?></div>
		<div class="am-u-lg-9 am-u-md-9 am-u-sm-8"><input type='text' name='invoice_no' value="" /></div>
</div>
	</div>
	<table class='am-table'>
	<tr>
		<td colspan='4' class='am-text-center'>
			<?php if( isset($order_action['pay']) && $order_action['pay'] && !isset($order_action['unship']) && $svshow->operator_privilege('order_shippings_view')){?><button type='button' class='am-btn am-btn-success am-radius am-btn-sm' onclick="ajax_order_delivered(this,'order_payment_delivery')"><?php echo $ld['payment_and_shipping']?></button><?php } ?>
			<?php if(((isset($order_action['pay']) && $order_action['pay'])||(isset($order_action['ship']) && $order_action['ship']))&&!isset($order_action['unship'])&& $svshow->operator_privilege('order_shippings_view')){?>
				  <?php if($order_info['Order']['to_type_id']==$admin['type_id']){?>
				  	<button type='button' class='am-btn am-btn-success am-radius am-btn-sm' onclick="ajax_order_delivered(this,'order_delivery')"><?php echo $ld['delivery']?></button>
				  <?php } ?>
			<?php } ?>
		</td>
	</tr>
	<?php }else{ ?>
	<tr>
		<td class='am-text-center'  colspan='6'><?php echo $ld['no_record']; ?></td>
	</tr>
	<?php } ?>
</table>
<?php
	$region_arr=array();
	if(isset($order_shipment_info['OrderShipment'])){
		$region_arr=explode(" ",$order_shipment_info['OrderShipment']['regions']);
	}else{
		$region_arr=explode(" ",$order_info['Order']['regions']);
	}
?>
<script type='text/javascript'>
<?php if(isset($region_arr[0])){ ?>
Load_Shipment_getRegions('0','country','<?php echo $region_arr[0]; ?>');
<?php } ?>
<?php if(isset($region_arr[0])){ ?>
Load_Shipment_getRegions('<?php echo $region_arr[0]; ?>','province','<?php echo isset($region_arr[1])?$region_arr[1]:0; ?>');
<?php } ?>
<?php if(isset($region_arr[1])){ ?>
Load_Shipment_getRegions('<?php echo $region_arr[1]; ?>','city','<?php echo isset($region_arr[2])?$region_arr[2]:0; ?>');
<?php } ?>
function Load_Shipment_getRegions(id,region,sel_value){
	if(typeof(sel_value)=="undefined"){
		sel_value="";
	}
	if(region!="country"&&id=="0"){
		return;
	}
	var sUrl = admin_webroot+"orders/getRegions/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: { 'id': id},
		success: function(data){
			if(data.region.length==0){
				return;
			}
			var select_obj=null;
			if(region=="country"){
				select_obj=document.getElementById('Shipment_country_select');
			}else if(region=='province'){
				select_obj=document.getElementById('Shipment_province_select');
			}else{
				select_obj=document.getElementById('Shipment_city_select');
			}
			if(select_obj==null)return;
			select_obj.options.length = 0;
			var opt = document.createElement("OPTION");
			opt.value = "0";
			opt.text = j_please_select;
			select_obj.options.add(opt);
			for (i = 0; i < data.region.length; i++ ){
		    		var opt = document.createElement("OPTION");
		        	opt.value = data.region[i]['Region'].id;
		        	opt.text  = data.region[i]['RegionI18n'].name;
			    	if(opt.value==sel_value){
			        	opt.selected=true;
			        }
				select_obj.options.add(opt);
		    	}
		}
	});
}
function Change_Shipment_getRegions(id,region){
	var country_select=document.getElementById('Shipment_country_select');
	var province_select=document.getElementById('Shipment_province_select');
	var city_select=document.getElementById('Shipment_city_select');
	if(region == 'country'){
		$("#Shipment_province_select").find("option").remove(); 
		$("#Shipment_city_select").find("option").remove(); 
		$("#Shipment_province_select").append("<option value=''>"+j_please_select+"</option>");
		$("#Shipment_city_select").append("<option value=''>"+j_please_select+"</option>");
	}
	if(region == 'province'){
		$("#Shipment_city_select").find("option").remove(); 
	}
	if(region!="country"&&id=="0"){
		return;
	}
	var sUrl = admin_webroot+"orders/getRegions/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: { 'id': id},
		success: function(data){
			if(data.region.length==0){
				return;
			}
			var select_obj=null;
			if(region=="country"){
				select_obj=document.getElementById('Shipment_province_select');
			}else if(region=='province'){
				select_obj=document.getElementById('Shipment_city_select');
			}
			if(select_obj==null)return;
			select_obj.options.length = 0;
			var opt = document.createElement("OPTION");
			opt.value = "0";
			opt.text = j_please_select;
			select_obj.options.add(opt);
			for (i = 0; i < data.region.length; i++ ){
		    		var opt = document.createElement("OPTION");
		        	opt.value = data.region[i]['Region'].id;
		        	opt.text  = data.region[i]['RegionI18n'].name;
				select_obj.options.add(opt);
		    	}
		}
	});
}
</script>