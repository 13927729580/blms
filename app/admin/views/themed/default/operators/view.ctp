<style type="text/css">
 .am-checkbox {margin-top:0px; margin-bottom:0px;}
 .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
 .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
 .am-radio, .am-checkbox{display:inline;}
 .am-form-label{font-weight:bold;}
 .btnouter{margin:50px;}
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
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
#operator_avatar{max-width:150px;max-height:150px;width:auto;height:auto;}

input[type='checkbox'].role_action+.am-ucheck-icons{cursor:not-allowed;}
input[type='checkbox'].role_action+.am-ucheck-icons .am-icon-checked,input[type='checkbox'].role_action:checked+.am-ucheck-icons .am-icon-checked{color:#d8d8d8;opacity:1;}
input[type='checkbox'].role_action+.am-ucheck-icons .am-icon-unchecked{opacity:0;}
</style>
<?php
	$google_translate_code="";
	foreach($backend_locales as $v)$google_translate_code=$v['Language']['locale']==$backend_locale?$v['Language']['google_translate_code']:$google_translate_code;
?>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
	  <ul>
	    <li><a href="#basic_information_prev"><?php echo $ld['basic_information']?></a></li>
		<li><a href="#password_content_prev"><?php echo $ld['alter_password']?></a></li>
		<?php if(isset($operator_id)&&$operator_id!=0){ ?><li><a href="#source_prev">渠道管理</a></li><?php } ?>
		<?php if(!(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all")){ ?>
		<li><a onclick="$('#roles').collapse('open');" href="#roles"><?php echo $ld['operator_roles']?></a></li>
		<li><a href="#operator_action_list"><?php echo $ld['action'];?></a></li>
		<?php } ?>
	  </ul>
	</div>
		  
	<div style="width:100%;" class="am-panel-group admin-content  am-detail-view" id="accordion">
	<!--基本信息-->
	<?php echo $form->create('Operators',array('action'=>'view/'.(isset($operator_data['Operator']['id'])?$operator_data['Operator']['id']:"0"),'onsubmit'=>'return check_all()','name'=>'userformedit','enctype'=>"multipart/form-data"));?>
		<div class="am-panel am-panel-default" id="basic_information_prev">
			<div class="am-panel-hd">
				<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}">
					<label><?php echo $ld['basic_information']; ?></label>
				</h4>
		    </div>
			<div id="basic_information" class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
				    <input type="hidden" name="data[Menu][id]" value="<?php echo isset($this->data['Menu']['id'])?$this->data['Menu']['id']:'0'; ?>"/>
				    <ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1 am-thumbnails">
					<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']=="0"){?>
	  		  		<?php if($_SESSION['type_id']=="0"&&!empty($type)&&!empty($view_type_id)){?>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 " style="text-align:right;">
							<?php echo $ld['operator_class']?>
						</label>
						<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
							<input name="data[Operator][type]" type="hidden"  value="<?php if(isset($type)){echo $type;}else{echo $_SESSION['type'];}?>"><?php if($type=="S"){echo $ld['system'];}elseif($type=="D"){echo $ld['dealer'];}if($_SESSION['type']=="S"&&$type==""){echo $ld['system'];}elseif($_SESSION['type']=="D"&&$type==""){echo $ld['dealer'];}?>
							<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php if(isset($view_type_id)){echo $view_type_id;}else{echo $_SESSION['type_id'];};?>" /><?php if($type=="D"){echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";}if($_SESSION['type']=="D"&&$type==""){echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";}?>
						</div>
					</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					
					<?php }elseif(!empty($operator_data['Operator']['id'])){?>
						<li>  
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right" style="padding-top:0px"><?php echo $ld['operator_class']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input name="data[Operator][type]" type="hidden"  value="<?php if($operator_data['Operator']['type_id']=="0"){echo "S";}else{echo "D";}?>"><?php if($operator_data['Operator']['type_id']=="0"){echo $ld['system'];}?>
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $operator_data['Operator']['type_id'];?>" />
							</div>
						</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					
					<?php  }else{?>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4"  ><?php echo $ld['operator_class']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input name="data[Operator][type]" type="hidden"  value="S"><?php echo $ld['system'];?>
							<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="0" />
						</div>
					</li>
					<li><div class="am-show-lg-only">&nbsp;</div></li>
					<?php }}else{?>
			  		<?php if(isset($_SESSION['type_id']) && $_SESSION['type_id']!="0" && !empty($type) && !empty($view_type_id) || isset($_SESSION['type_id'])&&$_SESSION['type_id']!="0" && $type=="D" && $view_type_id!=0){?>
					 	<li>  
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:right;">
								<?php echo $ld['operator_class']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							 	<input name="data[Operator][type]" type="hidden"  value="<?php echo $_SESSION['type'];?>"><?php echo $ld['dealer'];?>
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $view_type_id;?>" /><?php echo isset($dealer_name['Dealer']['name'])?$dealer_name['Dealer']['name']:"";?>
							</div>
						</li>
					<?php }elseif(!empty($operator_data['Operator']['id'])){?>
						
					 	<li>   
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
								<?php echo $ld['operator_class']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<input name="data[Operator][type]" type="hidden"  value="D">
								<input name="data[Operator][type_id]" id="type_id"  type="hidden"  value="<?php echo $operator_data['Operator']['type_id'];?>" />
							</div>
						</li>
					<?php } }?>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
								<input name="data[Operator][id]" type="hidden" id="id" value="<?php echo isset($operator_data['Operator']['id'])?$operator_data['Operator']['id']:'';?>">
								<?php echo $ld['operator']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input name="data[Operator][name]" id="name" onblur="operator_change()" type="text"  value="<?php echo empty($operator_data['Operator']['name'])?'':$operator_data['Operator']['name'];?>" />
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
						</li>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">Email</label>
							<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all"){?>
								<input name="data[Operator][actions]" id="" type="hidden"  value="all" />
							<?php 	}?>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input name="data[Operator][email]" id="user_email" type="text"  value="<?php echo empty($operator_data['Operator']['email'])?'':$operator_data['Operator']['email'];?>" />
							</div> 
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
						</li>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;"><?php echo $ld['mobile']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input name="data[Operator][mobile]" type="text"  value="<?php echo empty($operator_data['Operator']['mobile'])?'':$operator_data['Operator']['mobile'];?>" />
							</div>
						</li>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
								<?php echo $ld['operator_default_language']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select name="data[Operator][default_lang]" data-am-selected>
									<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>
									<option value="<?php echo $v['Language']['locale'];?>"<?php if(!empty($operator_data['Operator']['default_lang'])&&$v['Language']['locale']==$operator_data['Operator']['default_lang']){echo "selected";}?> ><?php echo $v['Language']['name']?></option>
									<?php }}?>
								</select>
							</div>
						</li>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
								<?php echo $ld['templates']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select name="data[Operator][template_code]" data-am-selected>
									<?php foreach($template_list as $v){ ?>
									<option value="<?php echo $v;?>"<?php if(!empty($operator_data['Operator']['template_code'])&&$v==$operator_data['Operator']['template_code']){echo "selected";}?> ><?php echo $v; ?></option>
									<?php } ?>
								</select>
							</div>
						</li>
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:11px;">
								<?php echo $ld['diary_record']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<label class="am-radio am-success">
									<input type="radio" name="data[Operator][log_flag]" style="margin-left:0px;" data-am-ucheck value="1" <?php if(!isset($operator_data['Operator']['log_flag'])||!empty($operator_data['Operator']['log_flag'])&&$operator_data['Operator']['log_flag']==1){echo "checked";}?> /><?php echo $ld['valid']?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio"  name="data[Operator][log_flag]" style="margin-left:0px;"  data-am-ucheck value="0" <?php if(isset($operator_data['Operator']['log_flag'])&&$operator_data['Operator']['log_flag']==0){echo "checked";}?>   /><?php echo $ld['invalid']?>
								</label>
							</div>
						</li>	
						<li>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:11px;text-align:right;">
								<?php echo $ld['status']?>
							</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<label class="am-radio am-success">
									<input type="radio"  name="data[Operator][status]"  style="margin-left:0px;"  data-am-ucheck value="1" <?php if(!isset($operator_data['Operator']['status'])||!empty($operator_data['Operator']['status'])&&$operator_data['Operator']['status']==1){echo "checked";}?> ><?php echo $ld['valid']?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio"  name="data[Operator][status]" data-am-ucheck value="0" <?php if(isset($operator_data['Operator']['status'])&&$operator_data['Operator']['status']==0){echo "checked";}?> /><?php echo $ld['invalid']?></label>
							</div>
						</li>
			 		</ul>
			 		<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1 am-thumbnails">
			 			<li>
			 				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label" style="text-align:right;padding-top:11px;"><?php echo $ld['avatar']; ?></label>
			 				<div class='am-u-lg-9 am-u-md-9 am-u-sm-9'>
			 					<img src="<?php echo isset($operator_data['Operator']['avatar'])?$operator_data['Operator']['avatar']:'/theme/default/images/default.png'; ?>" id="operator_avatar" />
			 					<input type='hidden' name="data[Operator][avatar]" value="<?php echo isset($operator_data['Operator']['avatar'])?$operator_data['Operator']['avatar']:''; ?>"/>
			 					<input type='file' name="OperatorAvatar" accept="image/*" onchange="OperatorAvator(this)"/>
			 				</div>
			 			</li>
			 			<li>
			 				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label" style="text-align:right;padding-top:11px;"><?php echo $ld['signature']; ?></label>
			 				<div class='am-u-lg-9 am-u-md-9 am-u-sm-9'>
			 					<textarea name="data[Operator][signature]" id="operactor_signature"><?php echo isset($operator_data['Operator']['signature'])?$operator_data['Operator']['signature']:''; ?></textarea>
								<script type='text/javascript'>
									KindEditor.ready(function(K) {
										editor = K.create('#operactor_signature', {width:'100%',
										langType : '<?php echo $google_translate_code; ?>',cssPath : '/css/index.css',filterMode : false});
									});
								</script>
			 				</div>
			 			</li>
			 		</ul>
					<?php if( empty($this->data['Operator']['id']) || $this->data['Operator']['id'] !=1 ){?>
						<div class="btnouter">
							<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
							<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
						</div>
					<?php }?>
					<div  class="am-cf"></div>
				</div>
			</div>
		</div>
		<div  class="am-panel am-panel-default" id="password_content_prev">
			<div class="am-panel-hd">
				<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#password_content'}">
					<?php echo $ld['alter_password']?>
				</h4>
			</div>
			<div id="password_content" class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<ul style="padding-top:0.7em" class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1 am-thumbnails">
			<li>
			 			<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
			 				<?php echo $ld['new_password']?>
			 			</label>
			 			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			 				<input name="newpassword" id="user_new_password" type="password" ></div>
			 			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
		 			</li>		
							
					<li> 
		 				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;padding-top:12px;">
		 					<?php echo $ld['confirm_password_again']?>
		 				</label>
			 			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			 				<input name="confirmpassword" id="user_new_password2" type="password" >
			 			</div>
			 			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></div>
		 			</li>
					
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
							<?php echo $ld['produce_password'];?>
						</label>
			 			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
			 				<button class="am-btn am-btn-warning am-radius am-btn-sm" type="button" value="" onclick="produce_password()"><?php echo $ld['produce']?></button>
			 			</div>
			 			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			 				<input type="text" name="produce" id="user_produce_password" value="">
			 			</div>
		 			</li>
					<li>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:right;">
							<?php echo $ld['save_and_sendmail'];?>
						</label>
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"style="padding-top: 0.7em;">
						<label class="am-checkbox am-success">
			 				<input type="checkbox"  data-am-ucheck name="data[Operator][send_password_mail]" value="1">
							</label>
			 			</div>
					</li>
				</ul>
				<?php if( empty($this->data['Operator']['id']) || $this->data['Operator']['id'] !=1 ){?>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
						<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php if(isset($operator_id)&&$operator_id!=0){ ?>
		<div  class="am-panel am-panel-default" id="source_prev">
			<div class="am-panel-hd">
				<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#source'}">
					<?php echo '渠道管理'?>
				</h4>
			</div>
			<div id="source" class="am-panel-collapse am-collapse am-in">
			<?php //pr($relation_info_check); ?>
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<?php if(isset($op_check)&&sizeof($op_check)>0){ ?>
						<?php foreach ($op_check as $k => $v) { ?>
							<div style="width:50%;margin-bottom:1rem;margin-left:10px;">
								<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-right" style="line-height:35px;font-weight:600;"><?php echo $v; ?></div>
								<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" value="<?php echo isset($relation_info_check[$k])?$relation_info_check[$k]['OperatorChannelRelation']['value']:''; ?>" name="channel_relation[<?php echo isset($k)?$k:0; ?>]"></div>
							</div>
						<?php } ?>
					<?php } ?>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
						<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
					</div>
				
				</div>
			</div>
		</div>
		<?php } ?>
		<!--Roles-->
	<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<div class='am-u-lg-2 am-u-md-3 am-u-sm-5'>
				<h2 class="am-panel-title">
					<label class="am-checkbox am-success" style='font-weight:bold;'><input type="checkbox"  class="checkboxall" data-am-ucheck onclick='checkall(this)' /><?php echo $ld['operator_roles'] ?></label>
				</h2>
			</div>
			<div class='am-u-sm-6'>
				<?php echo $html->link($ld['operator_roles'],"/roles",array('target'=>"_blank",'class'=>'taobtn '));?>
			</div>
			<div class='am-cf'></div>
		</div>
		<?php if(isset($view_type) && $view_type=="S" && isset($type) && $type!="D" || !isset($type) && $view_type=="S" || $view_type=="S" && isset($type) && $type=="D" && $view_type_id=="0"){?>
		<div class="am-g">
		<div id="roles" class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd">
			<div class="Action_list ">	
				<div class="am-form-group" style="margin-left:10%;margin-bottom:15px;margin-top:15px;">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:bold;">
						&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ld['operator_select_role']?>
					</label>
					<div class="am-u-lg-10">
						<ul class="am-avg-lg-5">
						<?php if(isset($operator_roles) && sizeof($operator_roles)>0){?>
								<?php foreach($operator_roles as $ov){?>
									<li>
									<label class="am-u-lg-12 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:normal;">
										<input type="checkbox"  name="operator_role[]" data-am-ucheck value="<?php echo $ov['Role']['id']?>" onclick="getActionByRole()" <?php if(in_array($ov['Role']['id'],$this->data['Operator']['role_arr'])) echo 'checked'; ?>   />
										&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ov['Role']['name']?>
									</label>
									</li>
								<?php }?>
						<?php }?>
						</ul>
						</div>
					</div>
				</div>
			<?php if( empty($this->data['Operator']['id']) || $this->data['Operator']['id'] !=1 ){?>
				<div class="btnouter">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
					<button type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
				</div>
			<?php } ?>
				<div  style="clear:both;"></div>
			</div>
		</div>
		</div>	
	</div>
	<?php 	}}?>
		
		
	<?php if(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']!="all"||!isset($operator_data['Operator']['actions'])){?>
	<div class="am-panel am-panel-default" id="operator_action_list">
		<?php if(isset($Actions) && sizeof($Actions)>0){
			if(isset($view_type) && ($view_type=="S"&&$type=="D"&&$view_type_id>0)||($view_type=="D")){foreach($Actions as $k=>$v){ 
				if (isset($dealer_actions[$v['Action']['code']])&&is_array($dealer_actions[$v['Action']['code']])) {?>
		<div class="am-panel-hd">
			<h2 class="am-panel-title">
				<label class="am-checkbox am-success "><input type="checkbox"  class="checkboxall" data-am-ucheck onclick='checkall(this)' />
				<?php echo $v['ActionI18n']['name']?></label>
			</h2>
		</div>
		<div id="<?php echo $v['ActionI18n']['name'];?>" class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd ">
			<?php if(isset($v['children']) && sizeof($v['children'])>0)foreach($v['children'] as $vv){if ((isset($dealer_actions[$v['Action']['code']][$vv['Action']['code']])&&is_array($dealer_actions[$v['Action']['code']][$vv['Action']['code']])||isset($dealer_actions[$v['Action']['code']]['所有']['所有'])&&$dealer_actions[$v['Action']['code']]['所有']['所有']===true)){?>
			    <div class="am-form-group">
					<label class="am-checkbox am-success" >
						<input class="operactor_actions"  id='operactor_action_id<?php echo $vv["Action"]["id"] ?>' type="checkbox"  data-am-ucheck name='<?php echo "ops_".$v["Action"]["id"];?>' onclick="checktr(this)" value="<?php echo $vv['Action']['id']?>" /><?php echo $vv['ActionI18n']['name']?>
					</label>
					<div>
						<?php if(isset($vv['children']) && sizeof($vv['children'])>0){foreach($vv['children'] as $vvv){if (isset($dealer_actions[$v['Action']['code']][$vv['Action']['code']][$vvv['Action']['code']])&&is_array($dealer_actions[$v['Action']['code']][$vv['Action']['code']][$vvv['Action']['code']])||isset($dealer_actions[$v['Action']['code']][$vv['Action']['code']]['所有'])&&$dealer_actions[$v['Action']['code']][$vv['Action']['code']]['所有']===true||isset($dealer_actions[$v['Action']['code']]['所有']['所有'])&&$dealer_actions[$v['Action']['code']]['所有']['所有']===true||isset($dealer_actions[$v['Action']['code']][$vv['Action']['code']][$vvv['Action']['code']])&&$dealer_actions[$v['Action']['code']][$vv['Action']['code']][$vvv['Action']['code']]===true) {
							?>
							<label class="am-checkbox am-success " ><input type="checkbox" class="operactor_actions" data-am-ucheck  id='operactor_action_id<?php echo $vvv["Action"]["id"] ?>' name="Action[]" value="<?php echo $vvv['Action']['id']?>"<?php if(in_array($vvv['Action']['id'],$operator_data['Operator']['action_arr'])) echo 'checked';?> /><?php echo $vvv['ActionI18n']['name']?></label>
						<?php }}}?>
					</div>
				</div>
			<?php }}?>
			</div>
		</div>
	</div>
	<?php	}}}elseif(isset($operator_data['Operator']['actions'])&&$operator_data['Operator']['actions']=="all"){}else{foreach($Actions as $k=>$v){?>
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h2 class="am-panel-title">
				<label class="am-checkbox am-success " style="font-weight:bold;">
					<input type="checkbox" class="checkboxall" data-am-ucheck onclick='checkall(this)' />
					<?php echo $v['ActionI18n']['name']?>
				</label>
			</h2>
		</div>
		<div class="Action_list ">
			<?php if(isset($v['children']) && sizeof($v['children'])>0)foreach($v['children'] as $vv){?>
			    <div class="am-form-group" style="margin-left:10%;margin-bottom:15px;margin-top:15px;">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:bold;">
							<input class="operactor_actions" id='operactor_action_id<?php echo $vv["Action"]["id"] ?>' type="checkbox" name='<?php echo "ops_".$v["Action"]["id"];?>' data-am-ucheck  onclick="checktr(this)" value="<?php echo $vv['Action']['id']?>" />&nbsp;&nbsp;
							<?php echo $vv['ActionI18n']['name']?>
					</label>
					<div class="am-u-lg-10">
						<?php if(isset($vv['children']) && sizeof($vv['children'])>0){?>
						<ul class="am-avg-lg-5">
						<?php foreach($vv['children'] as $vvv){?>
							<li>
							<label class="am-u-lg-12 am-u-md-3 am-u-sm-3 am-checkbox am-success " style="font-weight:normal;">	
								<input type="checkbox" class="operactor_actions"  id='operactor_action_id<?php echo $vvv["Action"]["id"] ?>' data-am-ucheck  name="Action[]" value="<?php echo $vvv['Action']['id']?>"<?php if(in_array($vvv['Action']['id'],$operator_data['Operator']['action_arr'])) echo 'checked';?> />
								&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $vvv['ActionI18n']['name']?>
							</label>
							</li>
						<?php }?>
						</ul>
						<?php }?>
					</div>
				</div>
				<div style="clear:both;"></div>
			<?php }?>
		</div>
		<div class="btnouter"  style="margin-top:20px;">
			<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
		</div>
			
	</div>

	<?php }}}} ?>	
						
			</div>
		
	<?php  echo $form->end();?>
		
	</div>			
</div>

<script type="text/javascript">
/**
 * 折叠菜单列表
 */

//操作员分批全选
function checktr(obj){
	var checktr = obj.parentNode.parentNode;
	var checkbox = checktr.getElementsByTagName("input");
	var checkStatus = obj.checked;
	for(i=1,len = checkbox.length; i<len;i++){
		if(!$(checkbox[i]).hasClass('role_action'))checkbox[i].checked = checkStatus;
	}
}
function on_hide(){
	document.getElementById("hide").style.display = (document.getElementById("type").options[1].selected ==true) ? "inline-block" : "none";
}

function checkall(obj){
	var checkTable = $(obj).parents('.am-panel-hd').parent();
	var checkbox = checkTable.find(".Action_list input[type=checkbox]");
	var checkStatus = obj.checked;
	for(var i=0;i<checkbox.length;i++){
		if(!$(checkbox[i]).hasClass('role_action'))checkbox[i].checked = checkStatus;
	}
}

function produce_password(){
	$user_new_password=document.getElementById("user_new_password");
	$user_new_password2=document.getElementById("user_new_password2");
	$user_produce_password=document.getElementById("user_produce_password");
	var postData = "";
	postData = "&password="+1;
	$.ajax({
		url:admin_webroot+"operators/produce_password/",
		type:"POST",
		data:postData,
		dataType:"json",
		success:function(data){
			if(data.code.length=="8"){
            	$user_produce_password.value=data.code;
     	  	 	$user_new_password.value=data.code;
     	  	 	$user_new_password2.value=data.code;
			}
		}
	});
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"operators/produce_password/";//访问的URL地址
		var cfg = {
			method: "POST",
			data: postData
		};
			var request = Y.io(sUrl, cfg);//开始请求
           var handleSuccess = function(ioId, o){
                try{
               	 eval('result='+o.responseText);
                }catch(e){
                     alert("对象转换失败");
                }
             	 if(result.code.length=="8"){
                	$user_produce_password.value=result.code;
         	  	 	$user_new_password.value=result.code;
         	  	 	$user_new_password2.value=result.code;
               	}
           }
           var handleFailure = function(ioId, o){
                alert("异步请求失败");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });*/
};

function checkall2(obj)
{
	var checkboxs = document.getElementsByName(obj);
	for(var i=checkboxs.length;i--;){
		checkboxs[i].click();
	}
}

//操作员复选框全部选取
function checkAll(frm, checkbox){
	for(i = 0; i < frm.elements.length; i++){
		if( frm.elements[i].type == "checkbox" ){
			frm.elements[i].checked = checkbox.checked;
		}
	}
}

function check_all(){
	if(document.getElementById('name').value==''){
		alert("<?php echo $ld['status']?><?php echo $ld['fill_in_user_name'];?>");
		return false;
	}
	if(document.getElementById('user_email').value==''){
		alert("<?php echo $ld['please_fill_user_email']?>");
		return false;
	}
	 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	 var email=document.getElementById('user_email').value;
	if(!myreg.test(email)){
 		alert("<?php echo $ld['enter_valid_email']?>");
 		return false;
 	}
	var newpass = document.getElementById('user_new_password').value;
	var newpasssec = document.getElementById('user_new_password2').value;
	var id = document.getElementById("id").value;
		if(id==''&& newpass == ''){
		alert("<?php echo $ld['please_fill_user_password']?>");
		return false;
	}
	if( newpass != '' || newpasssec != ''){
		if(newpass == '' || newpasssec == ''){
			alert("<?php echo $ld['please_fill_user_password']?>");
			return false;
		}else if( newpass != newpasssec ){
			alert("<?php echo $ld['password_different']?>");
			return false;
		}else if(id!=''&& newpass == newpasssec){
			if(document.getElementById("user_old_password").value==''){
				alert("<?php echo $ld['old_password_not_empty'];?>");
				return false;
			}
		}
	}
	return true;
}

function operator_change(){
	var name = document.getElementById("name").value;
	if(name!=""){
		var id=document.getElementById('id').value;
        if(id==''){
           	   var id=0;
           }
		$.ajax({
		url: admin_webroot+"operators/act_view/"+id,
		type:"POST",
		data: {"name":name},
		dataType:"json",
		success:function(data){
			try{
                     if(data.code==1){

                     }else{
                          alert("<?php echo $ld['user_exist']?>");
                     }
                }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                }
		}
		});
	
         
	}
 }
 
 function operator_passname(){
 	 var old_password = document.getElementById("user_old_password").value;
	 if(old_password!=""){
           YUI().use("io",function(Y) {
           var id=document.getElementById('id');
           var user_old_password=document.getElementById('user_old_password');
           var sUrl = admin_webroot+"operators/act_passview/"+id.value;
           var cfg = {
           method: "POST",
           data: "user_old_password="+user_old_password.value
           };
           var request = Y.io(sUrl, cfg);
           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     if(result.code==1){

                     }else{
                        alert("<?php echo $ld['old_password'];?><?php echo $ld['error']?>");
                     }
                }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                     alert(o.responseText);
                }
           }
           var handleFailure = function(ioId, o){
                alert("<?php echo $ld['asynchronous_request_failed']?>");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });
	 }
 }
 
 getActionByRole();
 
 function getActionByRole(){
 	var operator_role_ids="";
 	var operator_role=document.getElementsByName("operator_role[]");
 	for(i=0,len = operator_role.length; i<len;i++){
		if(operator_role[i].checked){
			operator_role_ids+=operator_role[i].value+";";
		}
	}
	if(operator_role_ids!=""){
		operator_role_ids=operator_role_ids.substring(0,operator_role_ids.length-1);
		$.ajax({ 
			url: admin_webroot+"roles/getActionByRole",
			type:"POST", 
			data: {operator_role_ids:operator_role_ids},
			dataType:"json",
			success: function(data){
				if(data.code==0){
					alert(data.msg);
				}else if(data.code==1){
					$(".operactor_actions").each(function(){
						$(this).attr("checked",false);
					});
				}else{
					var operator_action_ids=data.msg;
                    		var operator_action_ids_arr=operator_action_ids.split(";");
					$(".operactor_actions").each(function(){
						if($.inArray($(this).val(),operator_action_ids_arr)>=0){
							$(this).addClass('role_action');
						}else if($(this).hasClass('role_action')){
							$(this).removeClass('role_action');
						}
					});
				}
		  	}
		});
	}else{
        	$(".role_action").each(function(){
			$(this).removeClass('role_action');
		});
    	}
}

function OperatorAvator(fileBox){
	var uploadfile=fileBox.files[0];
	var previewImg=$(fileBox).parent().find('img');
	if(typeof(uploadfile)=='undefined'){
		$(previewImg).attr("src",'/theme/default/images/default.png');
		return;
	}
	var reader = new FileReader();
	reader.readAsText(uploadfile, 'UTF-8');
	reader.onload = function (e) {
		if(reader.readyState==2){//加载完成
			var fileSize=Math.round(e.total/1024/1024);
			if(fileSize>10){
                        	seevia_alert('最大文件限制为10M,当前为'+fileSize+'M');
                        	$(fileBox).val('');
                        	return false;
                    }
			var fileResult = reader.result;
			$(previewImg).attr("src", window.URL.createObjectURL(uploadfile));
		}
	}
}
</script>