<?php
	//pr($navigation_list);//导航
	//pr($backend_locales);//友情链接
?>
<style type='text/css'>
div.am-form-group xmp{word-wrap: break-word;margin-top:0px;margin-bottom:0px;word-break:break-all;width:100%;overflow: auto;}
.img_select{max-width:120px;max-height:120px;text-align:center;border:1px solid #ddd;height:120px;margin-right:5px;margin-bottom:5px;margin-top: 2px;}
ul.link_list li,ul.navigation_list li{margin-bottom:5px;}
ul.link_list li>div,ul.navigation_list li>div{width:98%;margin:0 auto;padding:5px;border:1px solid #ccc;}
ul.link_list li:last-child,ul.navigation_list li:last-child{padding:5px 10px;text-align:left;}
#page_module_list th,#page_module_list td{text-align:center;}
#page_module_list td a.am-btn{margin-right:5px;}
</style>
<div id="gonggong"  class="am-panel am-panel-default">
		<div class="am-panel-hd" style="border-bottom: 1px solid #ddd;">
		<h4 class="am-panel-title">
			<div class='am-u-sm-6'><?php echo $ld['template'] ?></div>
			<div class='am-u-sm-6 am-text-right'><?php if($svshow->operator_privilege("themes_view")){echo $html->link($ld['manager_templates'],"/themes/index",array('class'=>'am-btn am-btn-radius am-btn-default am-btn-xs'));} ?></div>
			<div class='am-cf'></div>
	        </h4>
	    </div>
		<div class="am-panel-bd am-form-detail am-form am-form-horizontal"> 
			<div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:6px;"><?php echo $ld['choose_language']; ?></label>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-right" style="margin-top: 2px;">
					<select id='page_locale' data-am-selected>
						<?php if(isset($backend_locales)){foreach($backend_locales as $k=>$v){ ?>
						<option value="<?php echo $v['Language']['locale'];?>" <?php echo isset($page_locale)&&$page_locale==$v['Language']['locale']?'selected':''; ?>><?php echo $v['Language']['name'];?></option>
						<?php }} ?>
					</select>
				</div>
			</div>
			<div class="am-form-group am-hide">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:6px;"><?php echo $ld['template']; ?></label>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-right" style="margin-top: 2px;">
					<select id='page_template' data-am-selected>
						<?php if(isset($template_list)){foreach($template_list as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo $template_info['Template']['name']==$k?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</div>
			<hr>
			<?php
					//网站icon
					$shop_icon_data=isset($web_config_list['shop_icon'])?$web_config_list['shop_icon']:array();
					if(!empty($shop_icon_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $shop_icon_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){ if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($shop_icon_data['ConfigI18n'][$v['Language']['locale']]['id'])?$shop_icon_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $shop_icon_data['Config']['id']; ?>" />
							<input type='text' id="<?php echo 'web_config_'.$shop_icon_data['Config']['id'].'_'.$v['Language']['locale']; ?>" name='data[ConfigI18n][value]' value="<?php echo isset($shop_icon_data['ConfigI18n'][$v['Language']['locale']]['value'])?$shop_icon_data['ConfigI18n'][$v['Language']['locale']]['value']:''; ?>" readonly />
							<div class="img_select">
								<?php echo $html->image(isset($shop_icon_data['ConfigI18n'][$v['Language']['locale']]['value'])?$shop_icon_data['ConfigI18n'][$v['Language']['locale']]['value']:'',array('onclick'=>"SelectConfigImg('web_config_".$shop_icon_data['Config']['id'].'_'.$v['Language']['locale']."')","id"=>"show_web_config_".$shop_icon_data['Config']['id'].'_'.$v['Language']['locale'])); ?>
							</div>
						</div>
					</form>
					<!--
					<?php if(sizeof($backend_locales)>1){ ?>
					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left"><?php echo $ld[$v['Language']['locale']];?></label>		
					<?php }?>
					-->
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//关键字
					$seo_key_data=isset($web_config_list['seo-key'])?$web_config_list['seo-key']:array();
					if(!empty($seo_key_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $seo_key_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($seo_key_data['ConfigI18n'][$v['Language']['locale']]['id'])?$seo_key_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $seo_key_data['Config']['id']; ?>" />
							<span onclick="javascript:ConfigEdit(this)"><?php echo isset($seo_key_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($seo_key_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$seo_key_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></span>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//网站描述
					$shop_description_data=isset($web_config_list['shop_description'])?$web_config_list['shop_description']:array();
					if(!empty($shop_description_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $shop_description_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($shop_description_data['ConfigI18n'][$v['Language']['locale']]['id'])?$shop_description_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $shop_description_data['Config']['id']; ?>" />
							<xmp onclick="javascript:ConfigEdit(this,2)"><?php echo isset($shop_description_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($shop_description_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$shop_description_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></xmp>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//网站头部meta代码
					$head_content_data=isset($web_config_list['head_content'])?$web_config_list['head_content']:array();
					if(!empty($head_content_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $head_content_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($head_content_data['ConfigI18n'][$v['Language']['locale']]['id'])?$head_content_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $head_content_data['Config']['id']; ?>" />
							<xmp onclick="javascript:ConfigEdit(this,2)"><?php echo isset($head_content_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($head_content_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$head_content_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></xmp>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//网站Logo
					$shop_logo_data=isset($web_config_list['shop_logo'])?$web_config_list['shop_logo']:array();
					if(!empty($shop_logo_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $shop_logo_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($shop_logo_data['ConfigI18n'][$v['Language']['locale']]['id'])?$shop_logo_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $shop_logo_data['Config']['id']; ?>" />
							<input type='text' id="<?php echo 'web_config_'.$shop_logo_data['Config']['id'].'_'.$v['Language']['locale']; ?>" name='data[ConfigI18n][value]' value="<?php echo isset($shop_logo_data['ConfigI18n'][$v['Language']['locale']]['value'])?$shop_logo_data['ConfigI18n'][$v['Language']['locale']]['value']:''; ?>" readonly />
							<div class="img_select"  style="width: 50px;height: 50px;">
							<?php echo $html->image(isset($shop_logo_data['ConfigI18n'][$v['Language']['locale']]['value'])?$shop_logo_data['ConfigI18n'][$v['Language']['locale']]['value']:'',array('onclick'=>"SelectConfigImg('web_config_".$shop_logo_data['Config']['id'].'_'.$v['Language']['locale']."')","id"=>"show_web_config_".$shop_logo_data['Config']['id'].'_'.$v['Language']['locale'])); ?>
							</div>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php
					//导航
					if(isset($navigation_type_info)&&sizeof($navigation_type_info)>0){foreach($navigation_type_info as $k=>$v){if($k!='T'&&$k!='B')continue; ?>
			<div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:6px;"><?php echo isset($ld[$v])?$ld[$v]:$v; ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-7" style="margin-top: 2px;">
					<ul class='am-avg-lg-6 am-avg-md-4 am-avg-sm-2 navigation_list'>
						<?php if(isset($navigation_list[$k])&&sizeof($navigation_list[$k])>0){foreach ($navigation_list[$k] as $v){ ?>
						<li>
							<div class='am-g'>
								<form class='navigation_form' method='post'>
									<input type='hidden' name='data[Navigation][id]' value="<?php echo $v['Navigation']['id']; ?>" />
									<input type='hidden' name='data[Navigation][type]' value="<?php echo $v['Navigation']['type']; ?>" />
									<div class='am-fl'><span onclick="editNav(this)"><?php echo $v['NavigationI18n']['name']; ?></span></div>
									<a class="am-icon-close am-no am-fr" onclick="removeNav(this)"></a>
									<div class='am-cf'></div>
								</form>
							</div>
						</li>
						<?php }}?>
						<li><a class="am-icon-plus-circle" onclick="addNav(this,'<?php echo $k; ?>')"></a></li>
					</ul>
				</div>
			</div>
			<?php }} ?>
			<!--中间的东西-->
			<div id="tablelist" style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;" class="am-panel-bd am-form-detail am-form am-form-horizontal">
				<?php if($svshow->operator_privilege("page_types_add")){?>
				<div class="am-g">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label"><?php echo $ld['page']; ?></label>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
						<select data-am-selected="{maxHeight:200}" id="template_page">
							<option value='0'><?php echo $ld['please_select'] ?></option>
							<?php if(isset($template_page)&&sizeof($template_page)>0){foreach($template_page as $v){if(!isset($v['page_action'])||sizeof($v['page_action'])==0)continue; ?>
							<optgroup label="<?php echo $v['name']; ?>">
								<?php foreach($v['page_action'] as $vv){ ?>
								<option value="<?php echo $vv['id']; ?>" <?php echo isset($page_action_id)&&$page_action_id==$vv['id']?'selected':''; ?>><?php echo $vv['name']; ?></option>
								<?php } ?>
							</optgroup>
							<?php }} ?>
						</select>
					</div>
					<div class="am-u-lg-6 am-u-md-5 am-u-sm-4 am-text-right">
						<?php if($svshow->operator_privilege("page_types_add")){?>
						<a href="javascript:void(0);" class="am-btn am-btn-radius am-btn-warning am-btn-xs" onclick="add_page_module()"><span class='am-icon-plus'></span> <?php echo $ld['add'].$ld['module']; ?></a>
						<?php } ?>
					</div>
					<div class='am-cf'></div>
				</div>
				<?php }?>
                        <table class="am-table" id="page_module_list">
					<thead>
						<tr>
							<th><?php echo $ld['module_name']?></th>
							<th style="width:15%;padding-left:83px;"><?php echo $ld['module_title']?></th>
							<th style="width:10%;"><?php echo $ld['module_code']?></th>
							<th><?php echo $ld['module_location']?></th>
							<th><?php echo $ld['module_width']?></th>
							<th><?php echo $ld['module_height']?></th>
							<th><?php echo $ld['module_float']?></th>
							<th class="thicon"><?php echo $ld['status']?></th>
							<th style="width:12%;"><?php echo $ld['operate']?></th>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($pagemodule_list)&&sizeof($pagemodule_list)>0){foreach($pagemodule_list as $v){ ?>
						<tr class="tr0">
							<td><span class="<?php echo (isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $v['PageModule']['id']?>"></span><?php echo $v['PageModuleI18n']['name']; ?></td>
							<td style="padding-left:83px;"><?php echo $v['PageModuleI18n']['title']; ?></td>
							<td><?php echo $v['PageModule']['code']; ?></td>
							<td><?php echo $v['PageModule']['position']; ?></td>
							<td><?php echo $v['PageModule']['width']; ?></td>
							<td><?php echo $v['PageModule']['height']; ?></td>
							<td><?php if($v['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($v['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($v['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
							</td>
							<td><?php
									if($v['PageModule']['status']==1){
										echo $html->image('/admin/skins/default/img/yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')'));
									}elseif($v['PageModule']['status'] == 0){
										echo $html->image('/admin/skins/default/img/no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')'));
									}
								?>
							</td>
							<td><?php
								if($svshow->operator_privilege("page_types_edit")){
									echo $html->link($ld['edit'],"/page_actions/page_module_view/{$v['PageModule']['id']}",array('class'=>'am-btn am-btn-xs am-btn-radius am-btn-default am-icon-pencil-square-o'));
								}
								if($svshow->operator_privilege("page_types_reomve")){
									echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$v['PageModule']['id']}/{$page_action_id}';}",'class'=>'am-btn am-btn-xs am-btn-radius am-btn-default am-icon-trash-o','style'=>'color:#dd514c;'));
								}
								?>
							</td>
						</tr>
						
						<?php	if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){
								foreach($v['SubPageModule'] as $kk=>$vv){
						?>
						<tr class="tr1">
							<td><span class="<?php echo (isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vv['PageModule']['id']?>"></span><?php echo $vv['PageModuleI18n']['name']; ?></td>
							<td style="padding-left:83px;"><?php echo $vv['PageModuleI18n']['title']; ?></td>
							<td><?php echo $vv['PageModule']['code']; ?></td>
							<td><?php echo $vv['PageModule']['position']; ?></td>
							<td><?php echo $vv['PageModule']['width']; ?></td>
							<td><?php echo $vv['PageModule']['height']; ?></td>
							<td><?php if($vv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
							</td>
							<td><?php
								if($vv['PageModule']['status']==1){
										echo $html->image('/admin/skins/default/img/yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')'));
								}elseif($vv['PageModule']['status'] == 0){
										echo $html->image('/admin/skins/default/img/no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')'));
								}	?>
							</td>
							<td><?php
								if($svshow->operator_privilege("page_types_edit")){
									echo $html->link($ld['edit'],"/page_actions/page_module_view/{$vv['PageModule']['id']}",array('class'=>'am-btn am-btn-xs am-btn-radius am-btn-default am-icon-pencil-square-o'));
								}
								if($svshow->operator_privilege("page_types_remove")){
									echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$vv['PageModule']['id']}/{$page_action_id}';}",'class'=>'am-btn am-btn-xs am-btn-radius am-btn-default am-icon-trash-o','style'=>'color:#dd514c;'));
								}
							?>
							</td>
						</tr>
						
						<?php
								if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){
									foreach($vv['SubPageModule'] as $kkk=>$vvv){
						?>
						<tr class="tr2">
							<td><span class="<?php echo (isset($vvv['SubPageModule']) && sizeof($vvv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vvv['PageModule']['id']?>"></span><?php echo $vvv['PageModuleI18n']['name']; ?></td>
							<td><?php echo $vvv['PageModuleI18n']['title']; ?></td>
							<td><?php echo $vvv['PageModule']['code']; ?></td>
							<td><?php echo $vvv['PageModule']['position']; ?></td>
							<td><?php echo $vvv['PageModule']['width']; ?></td>
							<td><?php echo $vvv['PageModule']['height']; ?></td>
							<td><?php if($vvv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vvv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vvv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
							</td>
							<td><?php
									if($vvv['PageModule']['status']==1){
										echo $html->image('/admin/skins/default/img/yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')'));
									}elseif($vvv['PageModule']['status'] == 0){
										echo $html->image('/admin/skins/default/img/no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')'));
									}	?>
							</td>
							<td><?php
								if($svshow->operator_privilege("page_types_edit")){
									echo $html->link($ld['edit'],"/page_actions/page_module_view/{$vvv['PageModule']['id']}",array('class'=>'am-btn am-btn-xs am-btn-radius am-btn-default'));
								}
								if($svshow->operator_privilege("page_types_remove")){
									echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$vvv['PageModule']['id']}/{$page_action_id}';}",'class'=>'am-btn am-btn-xs am-btn-radius am-btn-danger'));
								}
							?>
							</td>
						</tr>
							<?php }} ?>
						
						<?php }} ?>
						
					<?php }} ?>
					</tbody>
                        </table>
                    </div>
			<div class="am-form-group" style="margin-top:10px;">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:6px;"><?php echo $ld['links']?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-7" style="margin-top: 2px;">
					<ul class='am-avg-lg-6 am-avg-md-4 am-avg-sm-2 link_list'>
						<?php if(isset($link_list)&&sizeof($link_list)>0){foreach ($link_list as $v){ ?>
						<li>
							<div class='am-g'>
								<form class='link_form' method='post'>
									<input type='hidden' name='data[Link][id]' value="<?php echo $v['Link']['id']; ?>" />
									<div class='am-fl'><span onclick='editLink(this)'><?php echo trim($v['LinkI18n']['name'])==''?'-':$v['LinkI18n']['name']; ?></span></div>
									<a class="am-icon-close am-no am-fr" onclick="removeLink(this)"></a>
									<div class='am-cf'></div>
								</form>
							</div>
						</li>
						<?php }}?>
						<li><a class="am-icon-plus-circle" onclick="addLink(this)"></a></li>
					</ul>
				</div>
			</div>
			
			<?php
					//统计JS代码
					$google_js_data=isset($web_config_list['google-js'])?$web_config_list['google-js']:array();
					if(!empty($google_js_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $google_js_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($google_js_data['ConfigI18n'][$v['Language']['locale']]['id'])?$google_js_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $google_js_data['Config']['id']; ?>" />
							<xmp onclick="javascript:ConfigEdit(this,2)"><?php echo isset($google_js_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($google_js_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$google_js_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></xmp>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//网站前台分享JS代码
					$share_js_data=isset($web_config_list['share_js'])?$web_config_list['share_js']:array();
					if(!empty($share_js_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $share_js_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($share_js_data['ConfigI18n'][$v['Language']['locale']]['id'])?$share_js_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $share_js_data['Config']['id']; ?>" />
							<xmp onclick="javascript:ConfigEdit(this, 2)"><?php echo isset($share_js_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($share_js_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$share_js_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></xmp>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//客服JS代码
					$customer_js_data=isset($web_config_list['customer-js'])?$web_config_list['customer-js']:array();
					if(!empty($customer_js_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $customer_js_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($customer_js_data['ConfigI18n'][$v['Language']['locale']]['id'])?$customer_js_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $customer_js_data['Config']['id']; ?>" />
							<xmp onclick="javascript:ConfigEdit(this, 2)"><?php echo isset($customer_js_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($customer_js_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$customer_js_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></xmp>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//版权信息
					$copyright_display_data=isset($web_config_list['copyright-display'])?$web_config_list['copyright-display']:array();
					if(!empty($copyright_display_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $copyright_display_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($copyright_display_data['ConfigI18n'][$v['Language']['locale']]['id'])?$copyright_display_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $copyright_display_data['Config']['id']; ?>" />
							<span onclick="javascript:ConfigEdit(this,0)"><?php echo isset($copyright_display_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($copyright_display_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$copyright_display_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></span>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					$technicalsupport_data=isset($web_config_list['technicalsupport'])?$web_config_list['technicalsupport']:array();
				     if(!empty($technicalsupport_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $technicalsupport_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($technicalsupport_data['ConfigI18n'][$v['Language']['locale']]['id'])?$technicalsupport_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $technicalsupport_data['Config']['id']; ?>" />
							<span onclick="javascript:ConfigEdit(this,0)"><?php echo isset($technicalsupport_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($technicalsupport_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$technicalsupport_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></span>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					//ICP备案证书号
					$icp_number_data=isset($web_config_list['icp_number'])?$web_config_list['icp_number']:array();
					if(!empty($icp_number_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $icp_number_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($icp_number_data['ConfigI18n'][$v['Language']['locale']]['id'])?$icp_number_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $icp_number_data['Config']['id']; ?>" />
							<span onclick="javascript:ConfigEdit(this)"><?php echo isset($icp_number_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($icp_number_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$icp_number_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></span>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php
					$beiangov_data=isset($web_config_list['beiangov'])?$web_config_list['beiangov']:array();
				     if(!empty($beiangov_data)){
			?>
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-radio-label" style="margin-top:6px;"><?php echo $beiangov_data['ConfigI18n'][$backend_locale]['name']; ?></label>
				<div class='am-u-lg-8 am-u-md-6 am-u-sm-6'>
					<?php foreach($backend_locales as $k=>$v){if(isset($page_locale)&&$page_locale!=$v['Language']['locale'])continue; ?>
					<form class='config_setting_form' method='POST'>
						<div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>
							<input type='hidden' name='data[ConfigI18n][id]' value="<?php echo isset($beiangov_data['ConfigI18n'][$v['Language']['locale']]['id'])?$beiangov_data['ConfigI18n'][$v['Language']['locale']]['id']:0; ?>" />
							<input type='hidden' name='data[ConfigI18n][locale]' value="<?php echo $v['Language']['locale']; ?>" />
							<input type='hidden' name='data[ConfigI18n][config_id]' value="<?php echo $beiangov_data['Config']['id']; ?>" />
							<span onclick="javascript:ConfigEdit(this,0)"><?php echo isset($beiangov_data['ConfigI18n'][$v['Language']['locale']]['value'])&&trim($beiangov_data['ConfigI18n'][$v['Language']['locale']]['value'])!=''?$beiangov_data['ConfigI18n'][$v['Language']['locale']]['value']:'-'; ?></span>
						</div>
					</form>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
				
			</div>
			
			<div style="clear:both;"></div>
		</div>
</div>
<script type='text/javascript'>
//网站设置编辑
function ConfigEdit(obj,attr_input_type,attr_options){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && (tag.toLowerCase() == "input"||tag.toLowerCase() == "select"||tag.toLowerCase() == "textarea")){
   		return;
  	}
  	var config_setting_form=$(obj).parents("form.config_setting_form");
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	if(typeof(attr_input_type)== "undefined")attr_input_type=0;
  	if(attr_input_type==1){
  		if(typeof(attr_options) == "undefined")attr_options='';
  		var attr_option_info=attr_options.split("\t");
  		var SELECT = document.createElement("SELECT");
  		SELECT.name = "data[ConfigI18n][value]";
  		SELECT.options.add(new Option(j_please_select,''));
  		for(var i=0;i<attr_option_info.length;i++){
  			var attr_option_txt=attr_option_info[i];
  			var attr_option_arr=attr_option_txt.split("||");
  			SELECT.options.add(new Option(attr_option_arr[1],attr_option_arr[0],true,attr_option_arr[0]==val));
  		}
  		obj.innerHTML = "";
		obj.appendChild(SELECT);
		SELECT.focus();
		
		SELECT.onchange=function(){
			var sel_index=SELECT.selectedIndex;
			var val = SELECT.options[sel_index].value;
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+'configvalues/ajax_modified_config',
				data:config_setting_form.serialize(),
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							config_setting_form.find("input[name='data[ConfigI18n][id]']").val(result.last_config_id);
						}else{
							alert(result.content);
							obj.innerHTML = org;
						}
						var result_content = (result.flag == 1) ? (result.content==''?'-':result.content) : org;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
				}
			}); 
		};
  	}else if(attr_input_type==2){
	  	/* 创建一个输入框 */
		var txt = document.createElement("textarea");
		txt.name = "data[ConfigI18n][value]";
		txt.value = (val == 'N/A')|| (val == '-')? '' : val;
		txt.className = "input_text" ;
		txt.style.width ='100%';
		txt.style.minWidth = "20px" ;
	  	
	  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
		obj.innerHTML = "";
		obj.appendChild(txt);
		txt.focus();
		
		/* 编辑区输入事件处理函数 */
		txt.onkeypress = function(e){
			var evt = Utils.fixEvent(e);
			var obj = Utils.srcElement(e);
			if(evt.keyCode == 13){
				obj.blur();
				return false;
			}
			if(evt.keyCode == 27){
				obj.parentNode.innerHTML = org;
			}
		 }
		
		/* 编辑区失去焦点的处理函数 */
		txt.onblur = function(e){
			if(Utils.trim(txt.value).length > 0 || true){
				$.ajax({
					cache: true,
					type: "POST",
					url:admin_webroot+'configvalues/ajax_modified_config',
					data:config_setting_form.serialize(),
					async: false,
					success: function(data) {
						try{
							var result= JSON.parse(data);
							if(result.flag == 1){
								config_setting_form.find("input[name='data[ConfigI18n][id]']").val(result.last_config_id);
							}else{
								alert(result.content);
								obj.innerHTML = org;
							}
							var result_content = (result.flag == 1) ? (result.content==''?'-':result.content) : org;
							if(Browser.isIE){
								obj.innerText=Utils.trim(result_content);
							}else{
								obj.innerHTML=Utils.trim(result_content);
							}
						}catch(e){
							alert(j_object_transform_failed);
							obj.innerHTML = org;
						}
					}
				});
			}else{
		  		alert(j_empty_content);
		    		obj.innerHTML = org;
		    	}
		}
	}else{
	  	/* 创建一个输入框 */
		var txt = document.createElement("INPUT");
		txt.name = "data[ConfigI18n][value]";
		txt.type = "text" ;
		txt.value = (val == 'N/A')|| (val == '-')? '' : val;
		txt.className = "input_text" ;
		txt.style.width =  "100%" ;
		txt.style.minWidth = "20px" ;
	  	
	  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
		obj.innerHTML = "";
		obj.appendChild(txt);
		txt.focus();
		
		/* 编辑区输入事件处理函数 */
		txt.onkeypress = function(e){
			var evt = Utils.fixEvent(e);
			var obj = Utils.srcElement(e);
			if(evt.keyCode == 13){
				obj.blur();
				return false;
			}
			if(evt.keyCode == 27){
				obj.parentNode.innerHTML = org;
			}
		 }
		
		/* 编辑区失去焦点的处理函数 */
		txt.onblur = function(e){
			if(Utils.trim(txt.value).length > 0 || true){
				$.ajax({
					cache: true,
					type: "POST",
					url:admin_webroot+'configvalues/ajax_modified_config',
					data:config_setting_form.serialize(),
					async: false,
					success: function(data) {
						try{
							var result= JSON.parse(data);
							if(result.flag == 1){
								config_setting_form.find("input[name='data[ConfigI18n][id]']").val(result.last_config_id);
							}else{
								alert(result.content);
								obj.innerHTML = org;
							}
							var result_content = (result.flag == 1) ? (result.content==''?'-':result.content) : org;
							if(Browser.isIE){
								obj.innerText=Utils.trim(result_content);
							}else{
								obj.innerHTML=Utils.trim(result_content);
							}
						}catch(e){
							alert(j_object_transform_failed);
							obj.innerHTML = org;
						}
					}
				});
			}else{
		  		alert(j_empty_content);
		    		obj.innerHTML = org;
		    	}
		}
	}
}

var ConfigImgTime=null;
var ConfigImgWindow=null;
function SelectConfigImg(id_str,type){
	if(typeof(type)=="undefined"){type="";}
	if(ConfigImgTime!=null)return false;
	ConfigImgWindow=window.open(admin_webroot+'image_spaces/select_image/'+id_str+"/?type="+type, 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
	ConfigImgTime=window.setInterval("ConfigImgChange('"+id_str+"')",1000);
}

function ConfigImgChange(id_str){
	if(typeof(ConfigImgWindow.location.href)=='undefined'){
		window.clearInterval(ConfigImgTime);
		ConfigImgTime=null;
		var input_obj=document.getElementById(id_str);
		ConfigImgEdit(input_obj);
	}
}

function ConfigImgEdit(obj){
	var config_setting_form=$(obj).parents("form.config_setting_form"); 
	$.ajax({
		cache: true,
		type: "POST",
		url:admin_webroot+'configvalues/ajax_modified_config',
		data:config_setting_form.serialize(),
		async: false,
		success: function(data) {
			try{
				var result= JSON.parse(data);
				if(result.flag != 1){
					alert(result.content);
				}
			}catch(e){
				alert(j_object_transform_failed);
				obj.innerHTML = org;
			}
		}
	});
}

//添加外部链接块
function addLink(obj){
	var liobj=$(obj).parents('li');
	var lihtml="<li><div class='am-g'><form class='link_form' method='post'><input type='hidden' name='data[Link][id]' value='0' /><div class='am-fl'><span onclick='editLink(this)'>-</span></div><a class='am-icon-close am-no am-fr' onclick='removeLink(this)'></a><div class='am-cf'></div></form></div></li>";
	liobj.before(lihtml);
}

//删除导航块
function removeLink(obj){
	if(confirm(j_confirm_delete)){
		var liobj=$(obj).parents('li');
		var link_id=liobj.find("input[name='data[Link][id]']").val();
		if(link_id=='0'||link_id==''){
			liobj.remove();
		}else{
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+'links/remove/'+link_id,
				data:{},
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							liobj.remove();
						}else{
							alert(result.content);
						}
					}catch(e){
						alert(j_object_transform_failed);
					}
				}
			});
		}
	}
}

//编辑导航
function editLink(obj){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && (tag.toLowerCase() == "input")){
   		return;
  	}
  	var link_form=$(obj).parents("form.link_form");
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
	var txt = document.createElement("INPUT");
	txt.name = "data[LinkI18n][name]";
	txt.type = "text" ;
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.style.minWidth = "20px" ;
  	
  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();
	
	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
		var evt = Utils.fixEvent(e);
		var obj = Utils.srcElement(e);
		if(evt.keyCode == 13){
			obj.blur();
			return false;
		}
		if(evt.keyCode == 27){
			obj.parentNode.innerHTML = org;
		}
	 }
	
	/* 编辑区失去焦点的处理函数 */
	txt.onblur = function(e){
		if(Utils.trim(txt.value).length > 0){
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+'links/ajax_modified_link',
				data:link_form.serialize(),
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							link_form.find("input[name='data[Link][id]']").val(result.last_id);
							window.open(admin_webroot+"links/view/"+result.last_id);
						}else{
							alert(result.content);
							obj.innerHTML = org;
						}
						var result_content = (result.flag == 1) ? (result.content==''?'-':result.content) : org;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
				}
			});
		}else{
	  		alert(j_empty_content);
	    		obj.innerHTML = org;
	    	}
	}
}

//添加导航块
function addNav(obj,navigation_type){
	var liobj=$(obj).parents('li');
	var lihtml="<li><div class='am-g'><form class='navigation_form' method='post'><input type='hidden' name='data[Navigation][id]' value='0' /><input type='hidden' name='data[Navigation][type]' value='"+navigation_type+"' /><div class='am-fl'><span onclick='editNav(this)'>-</span></div><a class='am-icon-close am-no am-fr' onclick='removeLink(this)'></a><div class='am-cf'></div></form></div></li>";
	liobj.before(lihtml);
}

//删除导航块
function removeNav(obj){
	if(confirm(j_confirm_delete)){
		var liobj=$(obj).parents('li');
		var navigation_id=liobj.find("input[name='data[Navigation][id]']").val();
		if(navigation_id=='0'||navigation_id==''){
			liobj.remove();
		}else{
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+'navigations/remove/'+navigation_id,
				data:{},
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							liobj.remove();
						}else{
							alert(result.content);
						}
					}catch(e){
						alert(j_object_transform_failed);
					}
				}
			});
		}
	}
	
}

//编辑导航
function editNav(obj){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && (tag.toLowerCase() == "input")){
   		return;
  	}
  	var navigation_form=$(obj).parents("form.navigation_form");
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
	var txt = document.createElement("INPUT");
	txt.name = "data[NavigationI18n][name]";
	txt.type = "text" ;
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.style.minWidth = "20px" ;
  	
  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();
	
	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
		var evt = Utils.fixEvent(e);
		var obj = Utils.srcElement(e);
		if(evt.keyCode == 13){
			obj.blur();
			return false;
		}
		if(evt.keyCode == 27){
			obj.parentNode.innerHTML = org;
		}
	 }
	
	/* 编辑区失去焦点的处理函数 */
	txt.onblur = function(e){
		if(Utils.trim(txt.value).length > 0){
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+'navigations/ajax_modified_navigation',
				data:navigation_form.serialize(),
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							navigation_form.find("input[name='data[Navigation][id]']").val(result.last_id);
							window.open(admin_webroot+"navigations/view/"+result.last_id);
						}else{
							alert(result.content);
							obj.innerHTML = org;
						}
						var result_content = (result.flag == 1) ? (result.content==''?'-':result.content) : org;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
				}
			});
		}else{
	  		alert(j_empty_content);
	    		obj.innerHTML = org;
	    	}
	}
}

$(function(){
	var default_page_locale=$('#page_locale').val();
	$('#page_locale').on('change',function(){
		var page_locale=$(this).val();
		if(page_locale==default_page_locale)return false;
		default_page_locale=null;
		window.location.href=window.location.pathname+'?backend_locale='+page_locale;
	});
	
	var default_template_page=$('#template_page').val();
	$('#template_page').on('change',function(){
		var page_action_id=$(this).val();
		if(default_template_page==page_action_id||page_action_id==0)return false;
		default_template_page=null;
		window.location.href=window.location.pathname+'?page_action_id='+page_action_id;
	});
	
	var default_template=$("#page_template").val();
	$('#page_template').on('change',function(){
		var template_name=$(this).val();
		if(default_template==template_name)return false;
		default_template=null;
		window.location.href=admin_webroot+'themes/template/'+template_name;
	});
});

function add_page_module(){
	var page_action_id=$('#template_page').val();
	if(page_action_id==0)return false;
	window.location.href=admin_webroot+'page_actions/page_module_view?action_id='+page_action_id;
}
</script>