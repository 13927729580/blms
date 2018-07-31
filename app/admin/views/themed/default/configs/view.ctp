<?php 
/*****************************************************************************
 * SV-Cart 编辑实商店设置
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style>
	.am-radio input[type="radio"]{margin-left:0px;}
	.am-radio, .am-checkbox{display:inline-block;}
	.am-u-lg-2.am-u-md-3.am-u-sm-3.am-form-label{text-align: left;margin-top: 10px;}
	.am-u-lg-1.am-u-md-1.am-u-sm-1{padding-left: 0;padding-top: 5px;}
	em{color:red;}

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
	.btnouter{margin:0;}
</style>
<div class="am-g" style="width: 98%;margin-left: 1%;">
	<?php echo $form->create('Config',array('action'=>'/view/','onsubmit'=>'return userconfigs_check();'));?>
		<!-- 导航 -->
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#shop_configs"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['edit'].' '.$ld['shop_configs']:$ld['edit'].$ld['shop_configs'];?></a></li>
			</ul>
		</div>
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">
				<?php echo $ld['d_submit'];?>
			</button>
			<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" >
				<?php echo $ld['d_reset']?>
			</button>
		</div>
		<!-- 导航结束 -->
		<div class="am-panel-group admin-content" id="accordion">
			<div class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#shop_configs'}">
						<label><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['edit'].' '.$ld['shop_configs']:$ld['edit'].$ld['shop_configs'];?></label>
					</h4>
		    	</div>
		    	<div id="shop_configs" class="am-panel-collapse am-collapse am-in">
	  				<input type="hidden" name="data[Config][id]" value="<?php echo isset($configs_info['Config']['id'])?$configs_info['Config']['id']:'0';?>" />
	  				<input type="hidden" name="data[Config][store_id]" value="<?php echo isset($configs_info['Config']['store_id'])?$configs_info['Config']['store_id']:'0';?>" />
	  				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
	  					<input type="hidden" name="data[ConfigI18n][<?php echo $k;?>][id]" value="<?php echo isset($configs_info['ConfigI18n'][$v['Language']['locale']])?$configs_info['ConfigI18n'][$v['Language']['locale']]['id']:'0';?>" />
	  					<input type="hidden" name="data[ConfigI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>" />
	  				<?php }} ?>
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
					
					
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['system'] ?></label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Config][system_code]">
							<option value=" "><?php echo $ld['please_select']; ?></option>
							<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($this->data['Config']['system_code'])&&$this->data['Config']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
								</div>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['module'] ?></label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<input type='text' name="data[Config][module_code]" value="<?php echo isset($this->data['Config']['module_code'])?$this->data['Config']['module_code']:''; ?>" />
								</div>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['group'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<select name="data[Config][group_code]" data-am-selected onchange="subgroup_code_get(this,'<?php echo isset($configs_info['Config']['subgroup_code'])?$configs_info['Config']['subgroup_code']:''; ?>')">
										<?php if(isset($config_group_code) && sizeof($config_group_code)>0){?>
											<?php foreach( $config_group_code as $k=>$v ){?>
											<option value="<?php echo $k; ?>" <?php if($k == @$configs_info['Config']['group_code']){echo "selected";}?>><?php echo $v; ?></option>
										<?php }}?>
									</select>
								</div>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['subparameter'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<select name="data[Config][subgroup_code]" id="config_subgroup_code">
									</select>
								</div>	
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></div>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="config_code" name="data[Config][code]"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['code'];}?>"/>
								</div>	
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></div>	
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">
								<?php echo isset($backend_locale)&&$backend_locale=='eng'?'HTML '.$ld['type']:'HTML'.$ld['type'];?>:
							</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<select name="data[Config][type]" data-am-selected  onchange="selectClicked(this.value)" id="ConfigType">
										<option value="text" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="text"){echo "selected";} ?> >text</option>
										<option value="radio" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="radio"){echo "selected";} ?> >radio</option>
										<option value="select" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="select"){echo "selected";} ?> >select</option>
										<option value="multiselect" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="multiselect"){echo "selected";} ?>>multiselect</option>
										<option value="checkbox" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="checkbox"){echo "selected";} ?> >checkbox</option>
										<option value="textarea" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="textarea"){echo "selected";} ?> >textarea</option>
										<option value="image" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="image"){echo "selected";} ?> >image</option>
										<option value="hidden" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="hidden"){echo "selected";} ?> >hidden</option>
										<option value="map" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="map"){echo "selected";} ?> >map</option>
										<option value="send_email_test" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="send_email_test"){echo "selected";} ?> >send email test</option>
									</select>	
								</div>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['versions']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="config_section" name="data[Config][section]"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['section'];}?>" />
								</div>		
							</div>
						</div>								
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
										<input id="configs_name_<?php echo $v['Language']['locale'];?>" name="data[ConfigI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($configs_info['ConfigI18n'][$v['Language']['locale']])?$configs_info['ConfigI18n'][$v['Language']['locale']]['name']:'';?>">
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><?php echo $ld[$v['Language']['locale']]?>&nbsp;<em>*</em></div>
									<?php }?>
								<?php }}?>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['default_value']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
										<textarea id="configs_value_<?php echo $v['Language']['locale'];?>" name="data[ConfigI18n][<?php echo $k;?>][default_value]" ><?php if(isset($configs_info['ConfigI18n'][$k]['default_value'])){ echo $configs_info['ConfigI18n'][$k]['default_value']; }?></textarea>
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld[$v['Language']['locale']]?></div>
									<?php }?>
								<?php }}?>		
							</div>
						</div>	
						<div class="am-form-group option_textarea" style="<?php if(isset($configs_info['Config'])&&($configs_info['Config']['type'] == 'radio' || $configs_info['Config']['type'] == 'select'|| $configs_info['Config']['type'] == 'checkbox')){echo '';}else{echo 'display:none;';} ?>">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['option_list']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">	
										<textarea  name="data[ConfigI18n][<?php echo $k;?>][options]" id="ConfigI18n<?php echo $k;?>Options"><?php if(isset($configs_info['ConfigI18n'][$v['Language']['locale']]['options'])){ echo $configs_info['ConfigI18n'][$k]['options']; }?></textarea>
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld[$v['Language']['locale']]?></div>
									<?php }?>		
								<?php }}?>	
							</div>
						</div>
									
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">	
										<input type="text" name="data[ConfigI18n][<?php echo $k;?>][description]"  value="<?php if(isset($configs_info['ConfigI18n'][$k])&&!empty($configs_info['ConfigI18n'][$k])){echo $configs_info['ConfigI18n'][$k]['description'];}?>" />
									</div>
								<?php }}?>
							</div>	
						</div>
							
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0;"><?php echo $ld['readonly']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<label class="am-radio am-success" style="padding-top:0px;"><input type="radio" class="radio" name="data[Config][readonly]" data-am-ucheck value="1" <?php if( !empty($configs_info) && $configs_info['Config']['readonly'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?></label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:0px;"><input type="radio" class="radio" name="data[Config][readonly]" data-am-ucheck value="0" <?php if( !empty($configs_info) && $configs_info['Config']['readonly'] == 0 ){ echo "checked"; } ?> /><?php echo $ld['no']?></label>	
								</div>	
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style=""><?php echo $ld['valid']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<label class="am-radio am-success" style="padding-top: 0;">
										<input type="radio" name="data[Config][status]" data-am-ucheck value="1" <?php if(isset($configs_info['Config']['status']) && $configs_info['Config']['status'] == 1){ echo "checked";} ?> /><?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:0px;">
										<input type="radio" name="data[Config][status]" data-am-ucheck value="0" <?php if(isset($configs_info['Config']['status']) && $configs_info['Config']['status'] != 1){ echo "checked";} ?> /><?php echo $ld['no']?>
									</label>
								</div>	
							</div>
						</div>				
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['sort']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<input type="text" id="config_section" name="data[Config][orderby]" onkeyup="check_input_num(this)"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['orderby'];}?>" /><?php echo $ld['role_sort_default_num']?>
								</div>	
							</div>
						</div>		
					</div>
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>	
<script type="text/javascript">

function selectClicked(htmlType){
    send_style=document.getElementById('option_textarea');
    if(htmlType == 'text'|| htmlType == 'textarea' || htmlType == 'checkbox'|| htmlType == 'image'){
		$(".option_textarea").hide();
    }
    else if(htmlType == 'radio'|| htmlType == 'select' || htmlType == 'multiselect'){
		$(".option_textarea").show();
    }
}

function subgroup_code_get(obj,subgroup_code){
	var group_code=obj.value;
	$.ajax({
		url:admin_webroot+"configs/ajax_subgroup_code_get/"+group_code,
		type:"POST",
		data: {},
		dataType:"json",
		success:function(data){
			$("#config_subgroup_code").find("option").remove();
			$("<option></option>").val(' ').text(j_please_select).appendTo($("#config_subgroup_code"));
			$(data).each(function(i,item){
				if(subgroup_code==item['SystemResource']["code"]){
					$("<option selected></option>").val(item['SystemResource']["code"]).text(item['SystemResourceI18n']["name"]).appendTo($("#config_subgroup_code"));
				}else{
					$("<option></option>").val(item['SystemResource']["code"]).text(item['SystemResourceI18n']["name"]).appendTo($("#config_subgroup_code"));
				}
			});
			$("#config_subgroup_code").selected();
		}
	});
}
</script>