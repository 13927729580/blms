<style>
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-form-label{font-weight:bold;}
.btnouter{}
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
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div>	
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
		<?php echo $form->create('shippingments',array('action'=>'edit/'.$Shipping_info['Shipping']['id'],'onsubmit'=>'return shipping_input_checks()'));?> 
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		   			<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				<?php echo $html->link($ld['set_region'],'/shippingments/area/'.$Shipping_info['Shipping']['id'],array('class'=>'am-btn am-btn-warning am-btn-sm am-radius'),'',false,false);?>
			</div>
			<!-- 导航结束 -->
			<input name="data[Shipping][id]" type="hidden" value="<?php echo isset($Shipping_info['Shipping']['id'])?$Shipping_info['Shipping']['id']:'';?>">
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<input name="data[ShippingI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>"> <input type="hidden" name="data[ShippingI18n][<?php echo $k?>][id]" value="<?php echo @$Shipping_info['ShippingI18n'][$v['Language']['locale']]['id']?>" />
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
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:19px;"><?php echo $ld['code']?></label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    				    <input type="text" id="Shipping_Code" name="data[Shipping][code]" value="<?php echo @$Shipping_info['Shipping']['code']; ?>" />
                                </div>
                                <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left" style="font-weight:normal;padding-left: 0;"><em style="color:red;">*</em></label>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:19px;"><?php echo $ld['delivery_name']?></label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
				    					<input id="shipping_name_<?php echo $v['Language']['locale'];?>" type="text" name="data[ShippingI18n][<?php echo $k?>][name]" value="<?php echo @$Shipping_info['ShippingI18n'][$v['Language']['locale']]['name']?>" />
				    				</div>
					    			<?php if(sizeof($backend_locales)>1){?>
				    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-form-group-label am-text-left" style="font-weight:normal;padding-left: 0;">
				    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
				    					</label>
					    			<?php }?>
				    			<?php }} ?>							
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:19px;">手续费</label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    				    <input type="text" id="Shipping_Code" name="data[Shipping][insure_fee]" value="<?php echo @$Shipping_info['Shipping']['insure_fee']; ?>" />
                                </div>
			    			</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['cod']?></label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success" style="padding-top:2px;">
			    						<input type="radio" name="data[Shipping][support_cod]" data-am-ucheck value="1" <?php if(@$Shipping_info['Shipping']['support_cod']==1){ echo "checked";}?> /><?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:2px;">
										<input type="radio" name="data[Shipping][support_cod]" data-am-ucheck value="0" <?php if(@$Shipping_info['Shipping']['support_cod']==0){ echo "checked";}?> /><?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
						</div>
						<?php if(isset($php_code) && sizeof($php_code)>0){foreach($php_code as $k=>$v){?>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" ><?php echo $v['name']?></label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
									<input type="text" name="data[php_code][<?php echo $k?>][value]" value="<?php echo $v['value']?>" /> <input type="hidden" name="data[php_code][<?php echo $k?>][name]" value="<?php echo $v['name']?>" />
								</div>
							</div>
						<?php }}?>
                        <div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['valid']?></label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success" style="padding-top:2px;">
			    						<input type="radio" name="data[Shipping][status]" data-am-ucheck value="1" <?php echo !isset($Shipping_info['Attribute']['status'])||(isset($Shipping_info['Shipping']['status'])&&$Shipping_info['Attribute']['status']==1)?"checked":""; ?> /><?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:2px;">
										<input type="radio" name="data[Shipping][status]" data-am-ucheck value="0" <?php echo isset($Shipping_info['Attribute']['status'])&&$Shipping_info['Attribute']['status']==0?"checked":"";?> /><?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
						</div>
					</div>
										
					<div>
						 
						 
				     </div>  
						
				</div>
			</div>
										
			<div id="detail_description" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['content']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      		    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"> </label>
						 <div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<?php if($configs["show_edit_type"]){?>
							<div class="am-form-group">
			    				<div ><span class="ckeditorlanguage  " style="right: -40px;"><?php echo $v['Language']['name'];?></span></div>
			    				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"> </label>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[ShippingI18n][<?php echo $k;?>][description]" rows="10" style="width:auto;height:300px;"><?php echo isset($Shipping_info['ShippingI18n'][$v['Language']['locale']]['description'])?$Shipping_info['ShippingI18n'][$v['Language']['locale']]['description']:"";?></textarea>
								<script>
								var editor;
								KindEditor.ready(function(K) {
								editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {
						width:'80%',
		                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
								});
								</script>						
							</div>
						<?php }else{?>
								<div class="am-form-group">
				    				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $v['Language']['name'];?></label>
					    				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
											<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[ShippingI18n][<?php echo $k;?>][description]" rows="10"><?php echo isset($Shipping_info['ShippingI18n'][$v['Language']['locale']]['description'])?$Shipping_info['ShippingI18n'][$v['Language']['locale']]['description']:"";?></textarea>
											<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
										</div>
									</div>
							<?php }?>
							<?php }}?>
								 </div>
							 <div class="am-cf"></div>
					</div>
			    </div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>

<script type="text/javascript">
function shipping_input_checks(){
    var Shipping_Code=document.getElementById("Shipping_Code").value;
	var shipping_name_obj = document.getElementById("shipping_name_"+backend_locale);
    if(Shipping_Code==""){
        alert(j_enter_code);
		return false;
    }
	if(shipping_name_obj.value==""){
		alert("<?php echo $ld['enter_shipping_name']?>");
		return false;
	}
	return true;

}
</script>
