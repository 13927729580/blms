<style>
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 
.am-radio input[type="radio"]{margin-left:0px;}	
.img_select{max-width:150px;max-height:120px;}

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
<div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
		<?php echo $form->create('Link',array('action'=>'view/'.(isset($link['Link']['id'])?$link['Link']['id']:""),'onsubmit'=>'return link_name_check()'));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
				<button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<input type="hidden" name="data[Link][id]" value="<?php echo isset($link['Link']['id'])?$link['Link']['id']:'';?>" />
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<input name="data[LinkI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
			<?php }}?>
			<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['link_name']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input id="link_name_<?php echo $v['Language']['locale'];?>" type="text" name="data[LinkI18n][<?php echo $k?>][name]" value="<?php echo isset($link['LinkI18n'][$k]['name'])?$link['LinkI18n'][$k]['name']:'';?>" />
			    				</div>
			    					<?php if(sizeof($backend_locales)>1){?>
			    						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label" style="padding-left: 0;"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
			    					<?php }?>
			    				<?php }}?>	
			    			</div>
			    		</div>				
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['link_type']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<select id='data[Link][type]' name='data[Link][type]' data-am-selected>
                                          			<option value="0"><?php echo $ld['please_select'];?></option>
										<?php foreach($link_type as $lk => $l){?>
									<option value="<?php echo $lk;?>" <?php if(isset($link['Link']['type'])&&$lk==$link['Link']['type']){?>selected<?php }?> ><?php echo $l;?></option>
										<?php }?>
									</select>
			    				</div>
			    			</div>
			    		</div>	
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['link_logo']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				 <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
			    					<input name="data[LinkI18n][<?php echo $k;?>][img01]" id="linkI18n_logo_<?php echo $v['Language']['locale'];?>" type="text" value="<?php echo isset($link['LinkI18n'][$v['Language']['locale']])?$link['LinkI18n'][$v['Language']['locale']]['img01']:'';?>">
			    				</div>
			    						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label" style="padding-left: 0;">
			    					       <?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
			    					       </label>
			    					      <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
			    					     <input type="button" class="am-btn am-btn-xs am-btn-success am-radius am-u-lg-2 am-u-md-2 am-u-sm-2" onclick="select_img('linkI18n_logo_<?php echo $v['Language']['locale'];?>')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px; margin-bottom:5px;" /> &nbsp;<span style="margin-bottom:5px;" >(PS:100px*30px)</span>
			    					       </div>
									<div class=" am-u-lg-12 am-u-md-12 am-u-sm-12 ">
									 	 <div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($link['LinkI18n'][$v['Language']['locale']])&&$link['LinkI18n'][$v['Language']['locale']]['img01']!="")?$link['LinkI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_linkI18n_logo_'.$v['Language']['locale']))?>
									</div>
									</div>	 
			    				 	 
			    			  <?php }}?>	
			    			</div>
			    		</div>					
			    		 	<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['link_description']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"   >
			    					<textarea name="data[LinkI18n][<?php echo $k?>][description]"><?php echo isset($link['LinkI18n'][$k]['description'])?$link['LinkI18n'][$k]['description']:'';?></textarea>
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label" style="padding-left: 0;"><?php echo $ld[$v['Language']['locale']]?></label>
			    				<?php }?>
			    				<?php }}?>
			    			</div>				
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['link_address']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
			    					<input type="text" name="data[LinkI18n][<?php echo $k?>][url]" value="<?php echo isset($link['LinkI18n'][$k]['url'])?$link['LinkI18n'][$k]['url']:'';?>" />
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label" style="padding-left: 0;"><?php echo $ld[$v['Language']['locale']]?></label>
			    				<?php }?>
			    			<?php }}?>

			    				<div style="margin-left: 15px;"><?php echo $ld['page_url_desc']; ?></div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['contacter']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Link][contact_name]" value="<?php echo isset($link['Link']['contact_name'])?$link['Link']['contact_name']:'';?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['phone']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Link][contact_tele]" value="<?php echo isset($link['Link']['contact_tele'])?$link['Link']['contact_tele']:'';?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">Email</label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input id="contact_email" type="text" name="data[Link][contact_email]" value="<?php echo isset($link['Link']['contact_email'])?$link['Link']['contact_email']:'';?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['display_order']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" class="input_sort" name="data[Link][orderby]" value="<?php echo isset($link['Link']['orderby'])?$link['Link']['orderby']:'';?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['display']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success">
			    						<input type="radio" name="data[Link][status]" data-am-ucheck value="1" <?php echo !isset($link['Link']['status'])||(isset($link['Link']['status'])&&$link['Link']['status']==1)?"checked":""; ?> />
			    						<?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[Link][status]" data-am-ucheck  value="0" <?php echo isset($link['Link']['status'])&&$link['Link']['status']==0?"checked":""; ?> />
										<?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['new_window']?></label>
			    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success">
			    						<input type="radio" name="data[Link][target]" data-am-ucheck  value="_blank" <?php if(!empty($link['Link']['target'])&&$link['Link']['target']=='_blank')echo "checked";?>/>
			    						<?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[Link][target]" data-am-ucheck  value="_self" <?php if(empty($link['Link']['target'])||$link['Link']['target']=='_self')echo "checked";?>/>
										<?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
			    		</div>
					</div>
			 
			    			
				</div>
			</div>
			
		<?php echo $form->end();?>
	</div>
</div>

<script type="text/javascript">
function link_name_check(){
	var link_name_obj = document.getElementById("link_name_"+backend_locale);
	
	if(link_name_obj.value==""){
		alert("<?php echo $ld['enter_link_name']?>");
		return false;
	}
	/*验证邮箱*/
	var email=document.getElementById("contact_email");
	if(email.value!=""){
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		if(!reg.test(email.value))
	    {
	        alert("<?php echo $ld['enter_valid_email']?>");
	        return false;
	    }
	}
	return true;
	
}					
</script>
