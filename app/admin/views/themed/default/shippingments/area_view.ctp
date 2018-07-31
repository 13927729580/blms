<?php echo $javascript->link('/skins/default/js/shipping');?>
<style>
.btnouter{}
label{font-weight:normal;}
 
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-form-horizontal .am-checkbox{padding-top: 0px;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.am-ucheck-icons .am-icon-unchecked{margin-top: 1px;}
.am-ucheck-icons .am-icon-checked{margin-top: 1px;}

.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
</style>
<div>
	<div class="am-panel-group admin-content" id="accordion" style="width:98%;float:right;margin-right: 1%;">
		<?php echo $form->create('Shippingment',array('action'=>'area_view/'.(empty($shippingarea['ShippingArea']['id'])?"0":$shippingarea['ShippingArea']['id'])."/".$shipping_id));?> 	
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
					<li><a href="#available_regions"><?php echo $ld['available_regions']?></a></li>
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				<?php echo $html->link($ld['shipping_method_list'],"/shippingments/",array('class'=>'am-btn am-btn-warning am-btn-sm am-radius'),'',false,false);?>
			</div>
			<!-- 导航结束 -->	
			<input type="hidden" name="data[ShippingArea][shipping_id]" value="<?php echo $shipping_id?>">
			<input type="hidden" name="data[ShippingArea][id]" value="<?php echo empty($shippingarea['ShippingArea']['id'])?'':$shippingarea['ShippingArea']['id'];?>">
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<input name="data[ShippingAreaI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
			<?php }}?>
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:18px"><?php echo $ld['region_name']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" name="data[ShippingAreaI18n][<?php echo $k?>][name]" value="<?php echo @$shippingarea['ShippingAreaI18n'][$v['Language']['locale']]['name']?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:10px;"><?php echo $ld['region_description']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<textarea name="data[ShippingAreaI18n][<?php echo $k?>][description]"><?php echo @$shippingarea['ShippingAreaI18n'][$v['Language']['locale']]['description']?></textarea>
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:10px;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['less_than_gram']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="money[][value]" value="<?php echo empty($money[0])?'':$money[0]['value']; ?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['between_weight_cost']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="money[][value]" value="<?php echo empty($money[1])?'':$money[1]['value'];?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['more_than_cost']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="money[][value]" value="<?php echo empty($money[2])?'':$money[2]['value'];?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['free_order_amount']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[ShippingArea][free_subtotal]" value="<?php echo empty($shippingarea['ShippingArea']['free_subtotal'])?'':$shippingarea['ShippingArea']['free_subtotal'];?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:2px;"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success" style="padding-top:2px;">
			    						<input type="radio" name="data[ShippingArea][status]" data-am-ucheck value="1" <?php if( $shippingarea['ShippingArea']['status'] = 1 ){ echo "checked";} ?> /> <?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:2px;"> 
										<input type="radio" name="data[ShippingArea][status]" data-am-ucheck value="0" <?php if( $shippingarea['ShippingArea']['status'] = 0 ){ echo "checked";} ?> /> <?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['sort']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[ShippingArea][orderby]" value="<?php echo empty($shippingarea['ShippingArea']['orderby'])?'50':$shippingarea['ShippingArea']['orderby'];?>" />
			    				</div>
			    			</div>
			    		</div>
					</div>			
				</div>
			</div>
			<div id="available_regions"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['available_regions']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div >
							<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1" style="margin-bottom:10px;">
								<li style="margin-bottom:10px; "  class="am-form-group">
									<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label " style="padding-top:3px;"><?php echo $ld['top_level_region']?></label>
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
  									  <div>
										<select data-am-selected="{dropUp: 1,maxHeight: 150}" id="country_id" onchange="region_country('country')">
											<?php if(isset($region_country) && sizeof($region_country)>0){?>
											<?php foreach( $region_country as $kz=>$v ){?>
											<option value="<?php echo $v['Region']['id']?>" <?php if($kz==0){echo "selected";}?>><?php echo $v['RegionI18n']['name']?></option>
											<?php }}?>
										</select>
										</div>
									</div>
								</li>
								 <!-- data-am-selected="{dropUp: 1,maxHeight: 150}" -->
								<li style="margin-bottom:10px;" class="am-form-group">
									<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['two_level_regions']?></label>
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
										<select data-am-selected="{dropUp: 1,maxHeight: 150}" id="province_id" onchange="regions('province')">
											<option value="" selected><?php echo $ld['please_select']?></option>
										</select>
									</div></div>
								</li>
								<li style="margin-bottom:10px;" class="am-form-group">
									<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['three_level_regions']?></label>
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
										<select data-am-selected="{dropUp: 1,maxHeight: 150}" id="citys"  onchange="region_city('city');">
											<option value="" selected><?php echo $ld['please_select']?></option>
										</select>
									</div></div>
								</li>
								<li style="margin-bottom:10px;" class="am-form-group">
									<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['four_level_regions']?></label>
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
										<select data-am-selected="{dropUp: 1,maxHeight: 150}" id="area_id">
											<option value="" selected><?php echo $ld['please_select']?></option>
										</select></div>
									</div>
									<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
										<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="addItems()" value="<?php echo $ld['add']?>" style="margin-top:15px;" />
									</div>
								</li>
							</ul>			
						</div>
						<div class="am-form-group">					
							<ul id="item_id" class="areadata am-avg-lg-4 am-avg-md-4 am-avg-sm-2" >
								<?php if(isset($region_edit) && sizeof($region_edit)>0)foreach( $region_edit as $k=>$v ){foreach($v as $kks=>$vvs){?>
								<li>
									<label class="am-checkbox am-success"><input type="checkbox" value="<?php echo $kks?>" data-am-ucheck name="items[]" checked > <?php echo $vvs['Region']['name']?></label>
								</li>
								<?php } } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>


<script type="text/javascript">
region_country('country');

var province1 = $('#province_id').val();
console.log(province1);
$('#province_id').change(function(){
	var province2 = $('#province_id').val();
	console.log(province2)
	if(province2 != province1){
		console.log('1111');
	}
})

function addItems(){
	var item = document.getElementById('item_id');
	var country = document.getElementById('country_id');
	var province = document.getElementById('province_id');
	var city = document.getElementById('citys');
	var area = document.getElementById('area_id');

	if( cTrim(area.value,0)!=""){
		for (i=0;i<area.length;i++){
 		if(area.options[i].selected){
 			var items = document.getElementsByName("items[]");
 			for( var j=0;j<=items.length-1;j++ ){
	 			if( items[j].value == area.options[i].value ){
	 				alert('<?php echo $ld['this_option_already_exists']?>');
	 				return;
	 			}
 			}
 			var item_data=document.createElement("div");
            var item_data_html="<li><label><input type=checkbox name=items[] value="+area.options[i].value+" checked>"+area.options[i].text+"</label></li>";
            $(item_data).html(item_data_html);
            $(item_data).find("li").addClass("am-checkbox am-success");
            $(item_data).find("input[type='checkbox']").uCheck();
            item.innerHTML= item.innerHTML+item_data.innerHTML;
 		}
 	}return;


	}

	if( cTrim(city.value,0)!="" ){
		for (i=0;i<city.length;i++){
 		if(city.options[i].selected){
 			var items = document.getElementsByName("items[]");
 			for( var j=0;j<=items.length-1;j++ ){
	 			if( items[j].value == city.options[i].value ){
	 				alert('<?php echo $ld['this_option_already_exists']?>');
	 				return;
	 			}
 			}
 			var item_data=document.createElement("div");
            var item_data_html="<li><label><input type=checkbox name=items[] value="+city.options[i].value+" checked>"+city.options[i].text+"</label></li>";
            $(item_data).html(item_data_html);
            $(item_data).find("li").addClass("am-checkbox am-success");
            $(item_data).find("input[type='checkbox']").uCheck();
            item.innerHTML= item.innerHTML+item_data.innerHTML;
 		}
 	}return;


	}

	if( cTrim(province.value,0)!="" ){
	//	alert("zz");
		for (i=0;i<province.length;i++){
 		if(province.options[i].selected){
 			var items = document.getElementsByName("items[]");
 			for( var j=0;j<=items.length-1;j++ ){
	 			if( items[j].value == province.options[i].value ){
	 				alert('<?php echo $ld['this_option_already_exists']?>');
	 				return;
	 			}
 			}
 			var item_data=document.createElement("div");
            var item_data_html="<li><label><input type=checkbox name=items[] value="+province.options[i].value+" checked>"+province.options[i].text+"</label></li>";
            $(item_data).html(item_data_html);
            $(item_data).find("li").addClass("am-checkbox am-success");
            $(item_data).find("input[type='checkbox']").uCheck();
            item.innerHTML= item.innerHTML+item_data.innerHTML;
 		}
 	}return;


	}
	if( cTrim(country.value,0)!="" ){
		for (i=0;i<country.length;i++){
 		if(country.options[i].selected){
 			var items = document.getElementsByName("items[]");
 			for( var j=0;j<=items.length-1;j++ ){
	 			if( items[j].value == country.options[i].value ){
	 				alert('<?php echo $ld['this_option_already_exists']?>');
	 				return;
	 			}
 			}
            
            var item_data=document.createElement("div");
            var item_data_html="<li><label><input type=checkbox name=items[] value="+country.options[i].value+" checked>"+country.options[i].text+"</label></li>";
            $(item_data).html(item_data_html);
            $(item_data).find("li").addClass("am-checkbox am-success");
            $(item_data).find("input[type='checkbox']").uCheck();
            item.innerHTML= item.innerHTML+item_data.innerHTML;
 		}
 	}
 	return;
 }
}
function cTrim(sInputString,iType){
	var sTmpStr = ' '
	var i = -1
	if(iType == 0 || iType == 1){
		while(sTmpStr == ' '){
			++i
			sTmpStr = sInputString.substr(i,1)
		}
		sInputString = sInputString.substring(i)
	}

	if(iType == 0 || iType == 2){
		sTmpStr = ' '
		i = sInputString.length
		while(sTmpStr == ' '){
			--i
			sTmpStr = sInputString.substr(i,1)
		}
		sInputString = sInputString.substring(0,i+1)
	}
	return sInputString
}


</script>
