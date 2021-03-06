<style type="text/css">
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-form-horizontal .am-radio {
    display: inline;
    margin-top: 0.5rem;
    padding-top: 0;
    position: relative;
    top: 5px;
}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('ContactConfigs',array('action'=>'view/'.(isset($contact_config_data['ContactConfig'])?$contact_config_data['ContactConfig']['id']:'0'),'name'=>'ContactConfigForm','onsubmit'=>'return contact_config_checks();'));?>
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<input name="data[ContactConfig][id]" type="hidden" value="<?php echo isset($contact_config_data['ContactConfig']['id'])?$contact_config_data['ContactConfig']['id']:'';?>">
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<input name="data[ContactConfigI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
						<?php }}?>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:18px;"><?php echo $ld['type'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select id="contactconfig_type" name="data[ContactConfig][type]" data-am-selected>
										<option value=""><?php echo $ld['please_select'];?></option>
										<?php if(isset($Resource_info['contact_us_type'])&&!empty($Resource_info['contact_us_type'])>0){foreach($Resource_info['contact_us_type'] as $k=>$v){ ?>
										<option value="<?php echo $k; ?>" <?php if(isset($contact_config_data['ContactConfig']['type']) && $contact_config_data['ContactConfig']['type'] == $k) echo 'selected'?>><?php echo $v; ?></option>
										<?php }} ?>
									</select>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:21px;"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld["code"]?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="contact_config_code" onchange="contact_config_code_check('<?php echo isset($contact_config_data['ContactConfig']['id'])?$contact_config_data['ContactConfig']['id']:0; ?>')" name="data[ContactConfig][code]" value="<?php echo isset($contact_config_data['ContactConfig']['code'])?$contact_config_data['ContactConfig']['code']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:20px;"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>
			    					
						<div class="am-form-group" >
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld['name']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<input type="hidden" name="data[ContactConfigI18n][<?php echo $k;?>][id]" value="<?php echo isset($contact_config_data['ContactConfigI18n'][$v['Language']['locale']])?$contact_config_data['ContactConfigI18n'][$v['Language']['locale']]['id']:'0';?>" />
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input id="contact_config_name_<?php echo $v['Language']['locale'];?>" name="data[ContactConfigI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($contact_config_data['ContactConfigI18n'][$v['Language']['locale']])?$contact_config_data['ContactConfigI18n'][$v['Language']['locale']]['name']:'';?>">
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label am-text-left" style="font-weight:normal;padding-top:22px;">
			    						<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
				    		<?php }}?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:18px;"><?php echo $ld['value_type'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select  name="data[ContactConfig][value_type]" data-am-selected>
										<option value=""><?php echo $ld['please_select']?></option>
										<option value="text" <?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type'] =="text"){echo "selected";}?>>text</option>
                                        <option value="numbertext" <?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type'] =="numbertext"){echo "selected";}?>>number text</option>
										<option value="radio"<?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type'] 
										== "radio"){echo "selected";}?>>radio</option>
										<option value="select"<?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type']  == "select"){echo "selected";}?>>select</option>
										<option value="checkbox"<?php if(isset($contact_config_data['ContactConfig']['value_type']) &&  $contact_config_data['ContactConfig']['value_type']  == "checkbox"){echo "selected";}?>>checkbox</option>
										<option value="textarea"<?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type']  == "textarea"){echo "selected";}?>>textarea</option>
                                        <option value="file"<?php if(isset($contact_config_data['ContactConfig']['value_type']) && $contact_config_data['ContactConfig']['value_type']  == "file"){echo "selected";}?>>file</option>
									</select>&nbsp;&nbsp;
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">
			    					</label>
				    			<?php }?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['description'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<textarea name="data[ContactConfigI18n][<?php echo $k;?>][description]"><?php echo isset($contact_config_data['ContactConfigI18n'][$v['Language']['locale']])?$contact_config_data['ContactConfigI18n'][$v['Language']['locale']]['description']:"";?></textarea>&nbsp;
				    				</div>
					    			<?php if(sizeof($backend_locales)>1){?>
				    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:30px;">
				    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
				    					</label>
					    			<?php }?>
				    			<?php }}?>
			    			</div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['user_config_value'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
				    					<textarea name="data[ContactConfigI18n][<?php echo $k;?>][contact_config_values]"><?php echo isset($contact_config_data['ContactConfigI18n'][$v['Language']['locale']])?$contact_config_data['ContactConfigI18n'][$v['Language']['locale']]['contact_config_values']:"";?></textarea>
				    				</div>
					    			<?php if(sizeof($backend_locales)>1){?>
				    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:30px;">
				    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
				    					</label>
					    			<?php }?>
				    			<?php }}?>
			    			</div>
			    		</div>
						<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld['status']?></label>
    		    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    			    				<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[ContactConfig][status]" data-am-ucheck  value="1" <?php echo (isset($contact_config_data['ContactConfig']['status'])&&$contact_config_data['ContactConfig']['status']=='1')||!isset($contact_config_data['ContactConfig'])?'checked':''; ?>/><?php echo $ld['valid']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[ContactConfig][status]" data-am-ucheck  value="0" <?php echo isset($contact_config_data['ContactConfig']['status'])&&$contact_config_data['ContactConfig']['status']=='0'?'checked':''; ?>/><?php echo $ld['invalid']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>
                        <div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld['required']?></label>
    		    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    			    				<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[ContactConfig][is_required]" data-am-ucheck  value="1" <?php echo (isset($contact_config_data['ContactConfig']['is_required'])&&$contact_config_data['ContactConfig']['is_required']=='1')?'checked':''; ?>/><?php echo $ld['yes']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[ContactConfig][is_required]" data-am-ucheck  value="0" <?php echo (isset($contact_config_data['ContactConfig']['is_required'])&&$contact_config_data['ContactConfig']['is_required']=='0')||(!isset($contact_config_data['ContactConfig']['is_required']))?'checked':''; ?>/><?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:20px;"><?php echo $ld['sort']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<input type="text" name="data[ContactConfig][orderby]" value="<?php echo isset($contact_config_data['ContactConfig']['orderby'])?$contact_config_data['ContactConfig']['orderby']:50 ?>" onkeyup="check_input_num(this)"/>
				    					<?php echo $ld['sort_info']?>
				    				</div>
			    			</div>
			    		</div>
                    </div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" onclick="contact_config_code_check('<?php echo isset($contact_config_data['ContactConfig']['id'])?$contact_config_data['ContactConfig']['id']:0; ?>')"><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">
var config_code_flag=true;
var config_submit_flag=false;
function contact_config_checks(){
	var contactconfig_type = document.getElementById("contactconfig_type").value;
	var contactconfig_name = document.getElementById("contact_config_name_"+backend_locale).value;
	var code = document.getElementById("contact_config_code").value;
	if(contactconfig_type.trim()==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['type']); ?>");
		return false;
	}
	if(contactconfig_name.trim()==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['name']); ?>");
		return false;
	}
	if(code.trim()==""){
		config_code_flag=false;
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['code']); ?>");
		return false;
	}
	config_submit_flag=true;
	if(!config_code_flag){
		return false;
	}
	return true;
}

function contact_config_code_check(contact_config_id){
	var contact_config_code=document.getElementById("contact_config_code").value;
	if(contact_config_code.trim()!=""){
		config_code_flag=false;
		$.ajax({
			url:admin_webroot+"contact_configs/ajax_contact_code_check/",
			type:"POST",
			data: {'contact_config_id':contact_config_id,'contact_config_code':contact_config_code},
			dataType:"json",
			success:function(data){
				if(data.code == '1'){
					config_code_flag=true;
					if(config_submit_flag){
						document.ContactConfigForm.submit();
					}
				}else{
					alert("<?php echo $ld['code_already_exists']; ?>");
				}
			}	
		});
	}
}
</script>