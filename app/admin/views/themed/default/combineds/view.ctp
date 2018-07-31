
<script type="text/javascript">
	
var user_address_obj = <?php echo $user_addresses_json;?>;
var regions_info=<?php echo $regions_info;?>
	
//地址簿的选择
function select_user_address_change(select_value){
	if(select_value==""){
		document.getElementById("address_select_span").className='';
		return;
	}
	document.getElementById("order_telephone").value = user_address_obj[select_value].UserAddress.telephone;
	document.getElementById("order_consignee").value = user_address_obj[select_value].UserAddress.consignee;
	document.getElementById("order_mobile").value = user_address_obj[select_value].UserAddress.mobile;
    
    var country="";
	var country_id="";
	if(regions_info[user_address_obj[select_value].UserAddress.country]==undefined){
		country ='';
	}else{
		country = regions_info[user_address_obj[select_value].UserAddress.country];
		country_id=user_address_obj[select_value].UserAddress.country;
	}
	var province="";
	var province_id="";
	if(regions_info[user_address_obj[select_value].UserAddress.province]==undefined){
		province ='';
	}else{
		province = regions_info[user_address_obj[select_value].UserAddress.province];
		province_id=user_address_obj[select_value].UserAddress.province;
	}
	var city="";
	if(regions_info[user_address_obj[select_value].UserAddress.city]==undefined){
		city ='';
	}else{
		city = regions_info[user_address_obj[select_value].UserAddress.city];
	}
	getRegions(0,'',country);
	getRegions(country_id,'country',province);
	getRegions(province_id,'province',city);
	
	var RegionsInfo=country+(province!=""?" - "+province:"")+(city!=""?" - "+city:city);
	document.getElementById("order_country2").value =country;
	document.getElementById("order_province2").value = province;
	document.getElementById("order_city2").value = city;
	document.getElementById("order_region_txt").value = RegionsInfo;
	//document.getElementById("order_district").value = user_address_obj[select_value].UserAddress.district;
	document.getElementById("order_sign_building").value = user_address_obj[select_value].UserAddress.sign_building;
	document.getElementById("order_address").value = user_address_obj[select_value].UserAddress.address;
	document.getElementById("order_best_time").value = user_address_obj[select_value].UserAddress.best_time;
	document.getElementById("order_zipcode").value = user_address_obj[select_value].UserAddress.zipcode;
	document.getElementById("order_email").value = user_address_obj[select_value].UserAddress.email;
	document.getElementById("order_zipcode").value = user_address_obj[select_value].UserAddress.zipcode;
}

//获取地址
function getRegions(id,region,sel_value){
		if(sel_value=="undefined"){
			sel_value="";
		}
		if(region=="country"&&id==""){
			$('#province_select').addClass('order_status');
			$('#city_select').addClass('order_status');
			return;
		}
		if(region=="province"&&id==""){
			$('#city_select').addClass('order_status');
			return;
		}
		$.ajax({
			url:admin_webroot+"orders/getRegions/",
			type:"POST",
			data:{id:id},
			dataType:"json",
			success:function(data){
				if(data.region.length==0){
				return;
				}
				$("#address_select_span").show();
				$('#country_select').show();
				$('#province_select').show();
				if(region=="country"&&id==1){
					$('#city_select').show();
				}
				if(region=="country"&&id!=1){
					$('#city_select').hide();
				}
					
				if(region=="country"){
					$('#province_select').removeClass('order_status');
				
					var sel = document.getElementById('province_select');
				}else if(region=="province"){
					
					$('#city_select').removeClass('order_status');
					var sel = document.getElementById('city_select');
				}else if(region==""){
					 if(document.getElementById('country_select')){
					 var sel = document.getElementById('country_select');
					}else{
					 return;
					}
				}else{
					return ;
				}
				sel.options.length = 0;
				var opt = document.createElement("OPTION");
				opt.value = "";
				opt.text = j_please_select;
				sel.options.add(opt);
				for (i = 0; i < data.region.length; i++ ){
			    	var opt = document.createElement("OPTION");
			        opt.value = data.region[i]['Region'].id;
			        opt.text  = data.region[i]['RegionI18n'].name;
			        if(data.region[i]['RegionI18n'].name==sel_value){
			        	opt.selected=true;
			        }
					sel.options.add(opt);
			    }
			}
		});
}

function add_sub_pay(obj){
	pv = obj.value;
//	var postData = "id="+pv;
	$.ajax({
		url:admin_webroot+"orders/get_sub_pay/",
		type:"POST",
		data:{id:pv},
		dataType:"json",
		success:function(data){
			if(data.cd == 0 && data.ps.length > 0){
				var sel = document.getElementById('sub_pay');
				sel.innerHTML = "";
				var opt = document.createElement("OPTION");
				opt.value = "";
				opt.text = j_please_select;
				sel.options.add(opt);
				for (i = 0; i < data.ps.length; i++ ){
			    	var opt = document.createElement("OPTION");
			        opt.value = data.ps[i].id;
			        opt.text  = data.ps[i].value;
					sel.options.add(opt);
				}
				document.getElementById('sub_pay').style.display="inline";
			}else if(data.cd == 2 || data.ps.length <= 0){
				var sel = document.getElementById('sub_pay');
				sel.innerHTML = "";
				document.getElementById('sub_pay').style.display="none";
			}
			else{
				alert(j_failed_order_update);
			}
		}
	});
}
</script>
<style type="text/css">
	#search_user_infos { *width: 99%; }
	#order_region_txt{display:none;}
	.order_status{ display:inline-block; }
	.selecthide { display:none; }
	.am-form-horizontal .am-form-label{padding-top:0;}
	.btnouter{margin:50px;}
	.aax{text-decoration:line-through;color:#778899;}
</style>
<?php echo $javascript->link('/skins/default/js/product');
	echo $javascript->link('/skins/default/js/regions');
?>
		
<div>		
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#consignee_information"><?php echo $ld['consignee_information']?></a></li>
			<li><a href="#product_list"><?php echo $ld['product_list']?></a></li>
			<li><a href="#other_information"><?php echo $ld['other_information']?></a></li>
		</ul>
	</div>	
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">	
		<?php echo $form->create('Combined',array('action'=>'/view',"method"=>"post",'name'=>"CombinedForm","onsubmit"=>"return order_data_save();"));?>
			<input name="data[Order][user_id]"  type="hidden" value="<?php echo $order_info['User']['id']?>" />
			<input name="data[Order][shipping_status]"  type="hidden" value="<?php echo $order_info['Order']['shipping_status']?>" />
  			<input name="data[Order][payment_status]"  type="hidden" value="<?php echo $order_info['Order']['payment_status']?>" />
  			<input name="data[Order][logistics_company_id]"  type="hidden" value="<?php echo $order_info['Order']['logistics_company_id']?>" />
  			<input type="hidden" name="data[Order][order_locale]" value='<?php echo $backend_locale;?>'>
  			<input name="data[Order][order_currency]" type="hidden" value="<?php echo empty($order_info['Order']['order_currency'])?'RMB':$order_info['Order']['order_currency'];?>" />
			<input name="data[Order][order_domain]" id="data_order_id" type="hidden" value='<?php echo $_SERVER["HTTP_HOST"];?>' />
				
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<ul class="am-avg-lg-3 am-avg-md-1 am-avg-sm-1">
		      				<li>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_code']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<input name="data[Order][order_code]"  type="hidden" value="0" />
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orders_time']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo date("Y-m-d H:i:s");?>
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_user']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<?php if(isset($order_info["User"]['name'])){ echo $order_info["User"]['name'];}else{?>
										<input type="text" name="data[Order][user_name]" id="opener_select_user_name"> 
										<input type="hidden" name="data[Order][user_id]" id="opener_select_user_id"> 
										<?php //echo $html->link($ld['find_user'],"javascript:;",array("onclick"=>"search_user();"),false,false);?>
										<input type="button" id="search_user_button" onclick="search_user();" value="<?php echo $ld['find_user']; ?>" />
										<p><select id="search_user_infos" class="selecthide" onchange="select_user(this.value)"></select></p>
										<?php }?>
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['shipping']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<select id="order_shipping_id" name="data[Order][shipping_id]">
											<?php if(isset($shipping_effective_list) && sizeof($shipping_effective_list)>0){foreach($shipping_effective_list as $k=>$v){?>
											<option value="<?php echo $v['Shipping']['id']?>" <?php if($order_info['Order']['shipping_id']==$v['Shipping']['id']){echo "selected";}?> ><?php echo $v['ShippingI18n']['name']?></option>
											<?php }}?>
										</select>
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_status']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<?php echo $Resource_info["order_status"][$order_info['Order']['status']];?>,<?php echo $Resource_info["payment_status"][$order_info['Order']['payment_status']];?>,<?php echo $Resource_info["shipping_status"][$order_info['Order']['shipping_status']];?>
										<?php if($order_info['Order']['shipping_status']==1){?>
										<?php foreach($logistics_company_list as $k=>$v){if($v['LogisticsCompany']['id']==$order_info['Order']['logistics_company_id']){?>
										<?php echo $v['LogisticsCompany']['name'];?>
										<?php }}?>
										<?php echo $order_info['Order']['shipping_time'];?>
										<?php }?>
				    				</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['message_to_customer']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<textarea id="order_postscript"><?php echo $order_info['Order']['to_buyer']?></textarea>
				    				</div>
					    		</div>	
							</li>
											
							<li>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['product_total_amount']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<?php echo $order_info['Order']['subtotal'];?><input type="hidden" name="data[Order][subtotal]" value="<?php echo $order_info['Order']['subtotal']?>">
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['shipping_fee']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<input type="text" id="order_shipping_fee" name="data[Order][shipping_fee]" value="<?php echo $order_info['Order']['shipping_fee'];?>"/>
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['insured_costs']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<input type="text" id="order_insure_fee" name="data[Order][insure_fee]" value="<?php echo $order_info['Order']['insure_fee'];?>" />
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['invoice_tax']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    					<input type="text" id="order_tax" name="data[Order][tax]" value="<?php echo $order_info['Order']['tax']?>"/>
					    			</div>
					    		</div>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['discount']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<input type="text" id="order_discount" value="<?php echo $order_info['Order']['discount']?>" />
					    			</div>
					    		</div>	
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['paymengts']?></label>
					    			<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
					    				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php $sub_paymentlist=array(); ?>
					    					<select id="payment_id" name="data[Order][payment_id]" onchange="add_sub_pay(this)">
					    						<option value="0"><?php echo $ld["please_select"];?></option>
					                            <?php if(isset($payment_effective_list) && sizeof($payment_effective_list)>0){foreach($payment_effective_list as $k=>$v){ 			if(!isset($v['SubMenu'])||empty($v['SubMenu'])){continue;}?>
													<option value="<?php echo $v['Payment']['id']?>"><?php echo $v['PaymentI18n']['name'];?></option>
												<?php }}?>
					                        </select>
					                    </div>
					                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					                        <select id="sub_pay" name="data[Order][sub_pay]">
				                                <option value="0"><?php echo $ld["please_select"];?></option>
											</select>
										</div>
					    			</div>
					    		</div>	
							</li>
							
							<li>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_total_amount']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo $order_info['Order']['total'];?><input type="hidden" name="data[Order][total]" value="<?php echo $order_info['Order']['total']?>">
					    			</div>
					    		</div>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['amount_paid']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo sprintf("%01.2f",$order_info['Order']['money_paid']);?>
					    			</div>
					    		</div>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['use_balance']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo sprintf("%01.2f",$order_user_balance_log_info);?>
					    			</div>
					    		</div>
					    		<?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['points_exchange']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo $order_info['Order']['point_fee'];?><input type="hidden" name="data[Order][point_fee]" value="<?php echo $order_info['Order']['point_fee']?>">
					    			</div>
					    		</div>
					    		<?php }?>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['amount_payable']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php echo $order_info['Order']['money_paid'];?><input type="hidden" name="data[Order][money_paid]" value="<?php echo $order_info['Order']['money_paid']?>">
					    			</div>
					    		</div>
								<div class="am-form-group">
					    			<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['invoice_number']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php if(!empty($order_info['Order']['invoice_no'])){echo $order_info['Order']['invoice_no'];}?><input type="hidden" name="data[Order][invoice_no]" value="<?php echo $order_info['Order']['invoice_no']?>">
					    			</div>
					    		</div>
							</li>
						</ul>
					</div>
					<div class="btnouter">
						<input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
						<input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['d_submit'];?>" />
						<input type="button"  class="am-btn am-btn-default am-btn-sm am-radius" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
					</div>
				</div>
			</div>
										
			<div id="consignee_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['consignee_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['select_from_delivery_address']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select onchange="select_user_address_change(this.value);">
										<option value=""><?php echo $ld['please_select']?>...</option>
										<?php foreach( $user_addresses_array as $k=>$v){?>
										<option value='<?php echo $k;?>' ><?php echo $v["UserAddress"]["consignee"];?>,<?php echo isset($regions_info3[$v["UserAddress"]["country"]])?$regions_info3[$v["UserAddress"]["country"]]:'2';?>,<?php echo isset($regions_info3[$v["UserAddress"]["province"]])?$regions_info3[$v["UserAddress"]["province"]]:'3';?>,<?php echo isset($regions_info3[$v["UserAddress"]["city"]])?$regions_info3[$v["UserAddress"]["city"]]:'4';?>,<?php echo $v["UserAddress"]["district"];?>,<?php echo $v["UserAddress"]["address"];?>,<?php echo $v["UserAddress"]["zipcode"];?></option>
										<?php }?>
									</select>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['consignee']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_consignee" name="data[Order][consignee]" value="<?php echo $order_info['Order']['consignee'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
								<?php echo $ld['region'];?>
								<input type="hidden" id="order_country2" name="data[Order][country]" value="<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?>">
								<input type="hidden" id="order_province2" name="data[Order][province]" value="<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?>">
								<input type="hidden" id="order_city2" name="data[Order][city]" value="<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>">
							</label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<span class="address_span" id="order_region_txt">
										<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:''; ?>
										<?php echo isset($order_info['Order']['province'])?"- ".$order_info['Order']['province']:''; ?>
										<?php echo isset($order_info['Order']['city'])?"- ".$order_info['Order']['city']:''; ?>
									</span>
									<div id="address_select_span" class="address">
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
											<select   gtbfieldid="1" name="country_select" id="country_select" onchange="getRegions(this.value,'country')">
											</select>
										</div>
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
											<select class="order_status" gtbfieldid="1" name="province_select" id="province_select" onchange="getRegions(this.value,'province')">
											</select>
										</div>
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
											<select  class="order_status" gtbfieldid="1"  name="city_select" id="city_select" onchange="getRegions(this.value,'city')">
											</select>
										</div>
									</div>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_address" name="data[Order][address]" value="<?php echo $order_info['Order']['address'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['zip_code']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_zipcode" name="data[Order][zipcode]" value="<?php echo $order_info['Order']['zipcode'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_email" name="data[Order][email]" value="<?php echo $order_info['Order']['email'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['phone']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_telephone" name="data[Order][telephone]" value="<?php echo $order_info['Order']['telephone'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_mobile" name="data[Order][mobile]" value="<?php echo $order_info['Order']['mobile'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address_to']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_sign_building" name="data[Order][sign_building]" value="<?php echo $order_info['Order']['sign_building'];?>" />
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['best_delivery_time']?></label>
			    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    					<input type="text" id="order_best_time" name="data[Order][best_delivery_time]" value="<?php echo $order_info['Order']['best_time'];?>" />
			    				</div>
			    				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
									<select onchange="document.getElementById('order_best_time').value=this.value">
										<option value=""><?php echo $ld['please_select']?>...</option>
										<?php foreach( $information_resources_info["best_time"] as $k=>$v){?>
										<option value="<?php echo $v?>"><?php echo $v?></option>
										<?php }?>
									</select>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['delivery_remark']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<textarea id="order_note" name="data[Order][note]"><?php echo $order_info['Order']['note'];?></textarea>
			    				</div>
			    			</div>
						</div>
					</div>
					<div class="btnouter">
						<input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
						<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['d_submit'];?>"/>
						<input type="button" class="am-btn am-btn-default am-btn-sm am-radius"  value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
					</div>
				</div>
			</div>
			<!--商品列表-->
			<div id="product_list"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['product_list']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-panel-group am-panel-tree">
							<div class="am-panel am-panel-default am-panel-header">
								<div class="am-panel-hd">
									<div class="am-panel-title">
										<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">&nbsp;</div>
										<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['name']?></div>
										<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['product_attribute']?></div>
										<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['price']?></div>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['order_quantity']?></div>
										<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['subtotal']?></div>
										<div style="clear:both;"></div>
									</div>
								</div>		
							</div>
							<?php $sum = 0;if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){foreach($order_info['OrderProduct'] as $k=>$v){?>
								<div>
									<div class="am-panel am-panel-default am-panel-body">
										<div class="am-panel-bd">
											<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
												<?php if(!empty($product_img_new[$v['product_code']]["Product"]["img_thumb"])){$product_img_new1=substr($product_img_new[$v['product_code']]["Product"]["img_thumb"],0,4);
												if($product_img_new1=="http"){echo $html->image($product_img_new[$v['product_code']]["Product"]["img_thumb"],array('width'=>'100','height'=>'100'));}
												else{echo $html->image($product_img_new[$v['product_code']]["Product"]["img_thumb"],array('width'=>'100','height'=>'100'));}}?>&nbsp;
											</div>
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
												<?php echo $html->link($v['product_name'],$webroot."products/{$v['product_id']}",array('target'=>'_blank'));?><br />
												<?php echo $html->link($v['product_code'],$webroot."products/{$v['product_id']}",array('target'=>'_blank','style'=>'color:#778899;'));?>
												<?php if(!empty($v['delivery_note'])){?>
												注：<?php echo $v['delivery_note'];}?>&nbsp;
											</div>
											<div class="am-u-lg-3 am-show-lg-only"><?php echo $v['product_attrbute']?>&nbsp;</div>
											<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
												<?php if($product_img_new[$v['product_code']]["Product"]["market_price"]== $v['product_price']){?>
													<?php echo sprintf($configs['price_format'],sprintf("%01.2f",$v['product_price']));?>
												<?php }else{?>
													<span class="aax">
														<?php echo sprintf($configs['price_format'],sprintf("%01.2f",$product_img_new[$v['product_code']]["Product"]["market_price"]));?>
													</span><br />
													<?php echo sprintf($configs['price_format'],sprintf("%01.2f",$v['product_price']));?>
												<?php }?>&nbsp;
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['product_quntity']?>&nbsp;</div>
											<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
												<?php echo sprintf($configs['price_format'],sprintf("%01.2f",$v['total']));?>&nbsp;
											</div>
											<div style="clear:both;"></div>									
										</div>
									</div>
								</div>
							<?php }?>
						</div>
							
							<div class="am-text-right">
								<?php if(!empty($order_info['Order']['note'])){echo '<span style="float:left;">'.$ld['note2'].": ".$order_info['Order']['note'].'</span>';}?>
								<span style="display:block"><?php
									if($order_info['Order']['subtotal']>0){ echo "&emsp;".$ld['product_total_amount'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['subtotal']));}?>
								</span>
								<span style="display:block">
									<?php
									if($order_info['Order']['tax']>0){ echo "&emsp;".$ld['order_abbr_tax'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['tax']));}
									if($order_info['Order']['insure_fee']>0){ echo "&emsp;".$ld['order_abbr_insuredFee'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['insure_fee']));}
									if($order_info['Order']['pack_fee']>0){ echo "&emsp;".$ld['packaging_costs'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['pack_fee']));}
									if($order_info['Order']['card_fee']>0){ echo "&emsp;".$ld['card_fees'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['card_fee']));}
									if($order_info['Order']['payment_fee']>0){ echo "&emsp;".$ld['order_abbr_handling'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['payment_fee']));}
									if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){if($order_info['Order']['point_fee']>0){
									echo "&emsp;".$ld['points_exchange'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info["Order"]["point_fee"]));
									}}
									if($order_info['Order']['money_paid']>0){ echo "&emsp;".$ld['amount_paid'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info["Order"]["money_paid"]));}
									if($order_info['Order']['coupon_fees']>0){ echo "&emsp;".$ld['use_coupons'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info["Order"]["coupon_fees"]));}
									?>
								</span>
								<span style="display:block">
									<?php
									if($order_info['Order']['shipping_fee']>0){ echo "&emsp;".$ld['order_abbr_shippingFee'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['shipping_fee']));}
									if($order_info['Order']['discount']>0){ echo "&emsp;".$ld['discount'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['discount']));}
									if($order_info['Order']['total']>0){ echo "&emsp;".$ld['order_abbr_total'].": ";
										printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']));}
									?>
								</span>
								<span style="display:block">
									<?php echo "&emsp;".$ld['order_abbr_totalToPay'].": ";											printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']-$order_info["Order"]["point_fee"]-$order_info['Order']['discount']-$order_info['Order']['coupon_fees']));?>
								</span>
							</div>
							<?php }?>
						<?php if(isset($order_packaging_list) && sizeof($order_packaging_list)>0){?>	
							<div class="am-panel-group am-panel-tree">
								<div class="am-panel am-panel-default am-panel-header">
									<div class="am-panel-hd">
										<div class="am-panel-title">
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['package_name']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['package_price']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['package_number']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['note2']?></div>
											<div style="clear:both;"></div>
										</div>
									</div>
								</div>
								<?php foreach($order_packaging_list as $k=>$v){?>
									<div>
										<div class="am-panel am-panel-default am-panel-body">
											<div class="am-panel-bd">
												<input name="OrderPackaging_id[]" type="hidden" value="<?php echo $v['OrderPackaging']['id']?>" />
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<?php echo $v['OrderPackaging']['packaging_name'];?>
												</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<?php echo $v['OrderPackaging']['packaging_fee']?>
												</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<?php echo $v['OrderPackaging']['packaging_quntity']?>
												</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<?php echo $v['OrderPackaging']['note']?>
												</div>
												<div style="clear:both;"></div>
											</div>
										</div>
									</div>
								<?php }?>
							</div>
						<?php }?>
						
						<?php if(isset($order_card_list) && sizeof($order_card_list)>0){?>
							<div class="am-panel-group am-panel-tree">
								<div class="am-panel am-panel-default am-panel-header">
									<div class="am-panel-hd">
										<div class="am-panel-title">
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['card_name']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['card_price']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['card_number']?></div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['note2']?></div>
											<div style="clear:both;"></div>
										</div>
									</div>		
								</div>
								<?php 	foreach($order_card_list as $k=>$v){?>
									<div>
										<div class="am-panel am-panel-default am-panel-body">
											<div class="am-panel-bd">
												<input name="OrderCsrd_id[]" type="hidden" value="<?php echo $v['OrderCard']['id']?>" />
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OrderCard']['card_name'];?></div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OrderCard']['card_fee']?></div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OrderCard']['card_quntity']?></div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OrderCard']['note']?></div>
												<div style="clear:both;"></div>
											</div>
										</div>
									</div>
								<?php }?>			
							</div>				
						<?php }?>					
					</div>
				</div>
			</div>
					
			<!--其它信息-->		
			<div id="other_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['other_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['message_to_customer']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<textarea id="order_to_buyer" name="data[Order][to_buyer]"><?php echo $order_info['Order']['to_buyer'];?></textarea>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_reffer']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php echo $order_info['Order']['referer'];?><input type="hidden" name="data[Order][referer]" value="<?php echo $order_info['Order']['referer']?>">
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_language']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php if(isset($lname)&&$lname!=""&&isset($order_info['Order']['order_locale'])) echo isset($lname[$order_info['Order']['order_locale']])?$lname[$order_info['Order']['order_locale']]:"";?>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_currency']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php echo $order_info['Order']['order_currency'];?>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">订单类型</label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php echo $order_info['Order']['type']?>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['invoice_title']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="order_invoice_payee" name="data[Order][invoice_payee]" value="<?php echo $order_info['Order']['invoice_payee'];?>"/>
			    				</div>
			    			</div>
						</div>
						
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['invoice_type']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select id="order_invoice_type" name="data[Order][invoice_type]">
										<option value=''><?php echo $ld['please_select'];?></option>
										<?php if(isset($invoice_type_list) && sizeof($invoice_type_list)>0){foreach( $invoice_type_list as $k=>$v ){?>
										<option value='<?php echo $v["InvoiceType"]["id"];?>' <?php if($order_info['Order']['invoice_type']==$v["InvoiceType"]["id"]){echo "selected";}?>><?php echo $v["InvoiceTypeI18n"]["name"];?></option>
										<?php }}?>
									</select>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['invoice_content']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<textarea id="order_invoice_content" nmae="data[Order][invoice_content]"><?php echo $order_info['Order']['invoice_content'];?></textarea>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['stock_handling']?></label>
			    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    					<input type="text" id="order_how_oos" name="data[Order][how_oos]" value="<?php echo $order_info['Order']['how_oos'];?>"/>
			    				</div>
			    				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
									<select onchange="document.getElementById('order_how_oos').value=this.value">
										<option value=""><?php echo $ld['please_select']?>...</option>
										<?php foreach( $information_resources_info["how_oos"] as $k=>$v){?>
										<option value="<?php echo $v?>"><?php echo $v?></option>
										<?php }?>
									</select>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['domain_from']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php echo $order_info['Order']['order_domain']?>
			    				</div>
			    			</div>
						</div>
		      		</div>
		      		<div class="btnouter">
						<input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
						<input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['d_submit'];?>"/>
						<input type="button"  class="am-btn am-btn-default am-btn-sm am-radius" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
					</div>
		      	</div>
			</div>
					
		<?php echo $form->end();?>
	</div>
</div>	
<script type="text/javascript">
getRegions(0,'',"<?php echo isset($order_info['Order'])?$order_info['Order']['country']:'' ?>");
<?php if(isset($order_info['Order'])&&isset($regions_infovalues[$order_info['Order']['country']])){ ?>
	getRegions(<?php echo $regions_infovalues[$order_info['Order']['country']]; ?>,'country',"<?php echo isset($order_info['Order'])?$order_info['Order']['province']:'' ?>");
<?php } ?>
<?php if(isset($order_info['Order'])&&isset($regions_infovalues[$order_info['Order']['province']])){ ?>
	getRegions(<?php echo $regions_infovalues[$order_info['Order']['province']]; ?>,'province',"<?php echo isset($order_info['Order'])?$order_info['Order']['city']:'' ?>");
<?php } ?> 
        

<?php if(!empty($tishi)){ ?>alert('确认订单信息后点击【确定】提交订单')<?php }?>
	
function search_user(){
	var obj = document.getElementById('search_user_button');
	obj.className += " disablebtn";
	//var postData = "keywords="+keywords;
	var keywords=document.getElementById("opener_select_user_name").value;
	$.ajax({
		url:admin_webroot+"users/order_search_user_information/",
		type:"POST",
		data:{keywords:keywords},
		dataType:"json",
		success:function(data){
			var sel = document.getElementById('search_user_infos');
				 sel.innerHTML = "";
				 
				 if (data.message){
 	 				 	if(data.message.length==0){
 	 				 	document.getElementById("search_user_infos").className = "selecthide";
 	 				 	document.getElementById('user_info').innerHTML='匿名用户';
 	 				 	document.getElementById('opener_select_user_id').value="";
						alert("没有找到相关数据！");
		         		obj.className="";
		         		return;
					}
				 	if(data.message.length==1){
				 		var m=data.message[0]['User'].id+'-'+data.message[0]['User'].first_name+'-'+data.message[0]['User'].mobile+'-'+data.message[0]['User'].email;
						select_user(m);
						return;
					}
					var opt = document.createElement("OPTION");
					opt.value = "";
					opt.text = j_please_select;
					sel.options.add(opt);
		            for (i = 0; i < data.message.length; i++ ){
		            	if(data.message[i]['User'].mobile=="null"){
		             		data.message[i]['User'].mobile="";
		             	}
		                var opt = document.createElement("OPTION");
		                if(data.message[i]['User'].consignee!=""){
		           			opt.value = data.message[i]['User'].id+'-'+data.message[i]['User'].first_name+'+'+data.message[i]['User'].consignee+'-'+data.message[i]['User'].mobile;
		                }else{
		              		opt.value = data.message[i]['User'].id+'-'+data.message[i]['User'].first_name+'-'+data.message[i]['User'].mobile;
		                }
		                if(data.message[i]['User'].first_name!=""&&data.message[i]['User'].first_name!=null){
		                	
		                	opt.text  = data.message[i]['User'].name+'+'+data.message[i]['User'].first_name;
		                }else{
		                	opt.text  = data.message[i]['User'].name;
		                }
		                if(data.message[i]['User'].mobile!=""){
		                	opt.text = opt.text +"-"+data.message[i]['User'].mobile;
		                }
		                if(data.message[i]['User'].email!=""){
		                	opt.text = opt.text +"-"+data.message[i]['User'].email;
		            	}
		            	
		                sel.options.add(opt);
		            }
		            document.getElementById("search_user_infos").className = "";
		         }
			     if (webBrowser.IE6) {
			     	 obj.className=" input_button";
			     }else{
			     	 obj.className="";
			     };
		}
	});
/*	YUI().use("io",function(Y) {
		var cfg = {
			method: "POST",
			data: postData
		};
		var sUrl = admin_webroot+"users/order_search_user_information/";//访问的URL地址
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId,o){
			if(o.responseText !== undefined){
				 try{
					eval('var result='+o.responseText);
				 }catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				 }
				 var sel = document.getElementById('search_user_infos');
				 sel.innerHTML = "";
				 
				 if (result.message){
 	 				 	if(result.message.length==0){
 	 				 	document.getElementById("search_user_infos").className = "selecthide";
 	 				 	document.getElementById('user_info').innerHTML='匿名用户';
 	 				 	document.getElementById('opener_select_user_id').value="";
						alert("没有找到相关数据！");
		         		obj.className="";
		         		return;
					}
				 	if(result.message.length==1){
				 		var m=result.message[0]['User'].id+'-'+result.message[0]['User'].first_name+'-'+result.message[0]['User'].mobile+'-'+result.message[0]['User'].email;
						select_user(m);
						return;
					}
					var opt = document.createElement("OPTION");
					opt.value = "";
					opt.text = j_please_select;
					sel.options.add(opt);
		            for (i = 0; i < result.message.length; i++ ){
		            	if(result.message[i]['User'].mobile=="null"){
		             		result.message[i]['User'].mobile="";
		             	}
		                var opt = document.createElement("OPTION");
		                if(result.message[i]['User'].consignee!=""){
		           			opt.value = result.message[i]['User'].id+'-'+result.message[i]['User'].first_name+'+'+result.message[i]['User'].consignee+'-'+result.message[i]['User'].mobile;
		                }else{
		              		opt.value = result.message[i]['User'].id+'-'+result.message[i]['User'].first_name+'-'+result.message[i]['User'].mobile;
		                }
		                if(result.message[i]['User'].first_name!=""&&result.message[i]['User'].first_name!=null){
		                  	//  opt.value = result.message[i]['User'].id+'-'+result.message[i]['User'].name+'+'+result.message[i]['User'].first_name+'-'+result.message[i]['User'].mobile;;
		                	opt.text  = result.message[i]['User'].name+'+'+result.message[i]['User'].first_name;
		                }else{
		                	opt.text  = result.message[i]['User'].name;
		                }
		                if(result.message[i]['User'].mobile!=""){
		                	opt.text = opt.text +"-"+result.message[i]['User'].mobile;
		                }
		                if(result.message[i]['User'].email!=""){
		                	opt.text = opt.text +"-"+result.message[i]['User'].email;
		            	}
		            	
		                sel.options.add(opt);
		            }
		            document.getElementById("search_user_infos").className = "";
		         }
			     if (webBrowser.IE6) {
			     	 obj.className=" input_button";
			     }else{
			     	 obj.className="";
			     };

			//document.getElementById('result').className=""; 
			}
		}
		var handleFailure = function(ioId,o){}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}
function select_user(value){
	if(value==""){
		document.getElementById('opener_select_user_id').value="";
		document.getElementById('opener_select_user_name').value="";
		document.getElementById('order_consignee').innerHTML="";
		document.getElementById('order_mobile').value="";
		document.getElementById('order_email').value="";
		return;
	}
	var userInfo=value.split("-");
	document.getElementById('opener_select_user_id').value=userInfo[0];
	document.getElementById('opener_select_user_name').value=userInfo[1];
	var userNames=userInfo[1].split("+");
//	document.getElementById('order_consignee').innerHTML=userNames[0];
	document.getElementById('order_consignee').value= (userNames[1])?userNames[1]:userNames[0];
	document.getElementById('order_mobile').value=userInfo[2];
	document.getElementById('order_email').value=(userInfo[3])?userInfo[3]:"";
}

function order_data_save(){
    var order_payment_id=document.getElementById('payment_id').value;
    var order_sub_pay=document.getElementById('sub_pay').value;
    if(order_payment_id=='0'||order_sub_pay=='0'){
        alert('请选择支付方式!');
        return false;
    }
    return true;
}
</script>
