<style>
.btnouter{margin:50px;}
label{font-weight:normal;}
.am-form-label{font-weight:bold;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
input{
	margin-bottom:15px;
}
.am-u-lg-2.am-u-md-3.am-u-sm-3.am-form-label{text-align: left;}
.am-u-lg-1.am-u-md-1.am-u-sm-1.am-form-label.am-text-left{padding-left: 0;margin-left: 0;}

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
	<div class="am-panel-group admin-content  am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
		<?php echo $form->create('Language',array('action'=>'view','onsubmit'=>'return languages_check();','onsubmit'=>'return lan_input_checks()'));?> 
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#edit_languages"><?php echo $ld['edit_languages']?></a></li>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<input id="LanguageId" name="data[Language][id]" type="hidden" value="<?php echo $this->data['Language']['id'];?>"/>
			<div id="edit_languages"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['edit_languages']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						
						<div class="am-form-group" style="margin-bottom:10px">
			          	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['system'] ?></label>
			          	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
						<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Language][system_code]">
							<option value=""><?php echo $ld['please_select']; ?></option>
							<?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($this->data['Language']['system_code'])&&$this->data['Language']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
					  </div>
			        </div>
			        					
			  	<div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['module'] ?></label>
			          <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			        		<input type='text' name="data[Language][module_code]" value="<?php echo isset($this->data['Language']['module_code'])?$this->data['Language']['module_code']:''; ?>" />
					  </div>
			        </div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['language_name']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="language_name" name="data[Language][name]" value="<?php echo $this->data['Language']['name'];?>" />
							</div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left"><em style="color:red">*</em></label>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['language_code']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="language_locale" name="data[Language][locale]" value="<?php echo $this->data['Language']['locale'];?>"/>
							</div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" ><em style="color:red">*</em></label>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['character_set']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="language_charset" name="data[Language][charset]" value="<?php echo $this->data['Language']['charset'];?>"/>
							</div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left"><em style="color:red">*</em></label>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['browser_character_set']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="language_map" name="data[Language][map]" value="<?php echo $this->data['Language']['map'];?>"/>
							</div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left"><em style="color:red">*</em></label>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['google_character_set']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" id="language_google_translate_code" name="data[Language][google_translate_code]" value="<?php echo $this->data['Language']['google_translate_code'];?>"/>
							</div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left"><em style="color:red">*</em></label>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['icon']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="text" name="data[Language][img01]" id="upload_img_text_1" value="<?php echo $this->data['Language']['img01'];?>"/> <?php echo @$html->image("{$this->data['Language']['img01']}",array('id'=>'logo_thumb_img_1','height'=>'50','style'=>!empty($this->data['Language']['img01'])?"display:block;margin:0 0 5px 3px;":"display:none"))?>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px"><?php echo $ld['partition_selection']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id="select4" name="data[Language][img02]" data-am-selected>
									<option value="0"><?php echo $ld['please_select']?></option>
									<?php for($i=1;$i<6;$i++){
										$select='';
										if($this->data['Language']['img02']==$i)
										$select='selected';
										echo "<option value='{$i}' {$select}>{$i}</option>";
										}?>
									<option <?php if($this->data['Language']['img02']==10) echo 'selected';?> value="10">others</option>
								</select>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['set_to_the_default_language']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success" style="padding-top:1px;">
									<input type="radio" name="data[Language][is_default]" data-am-ucheck value="1" <?php if( $this->data['Language']['is_default'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;
								<?php if($this->data['Language']['is_default']!='1'){ ?>
								<label class="am-radio am-success" style="padding-top:1px;">
									<input type="radio" name="data[Language][is_default]"  data-am-ucheck value="0" <?php if( $this->data['Language']['is_default'] == 0 ){ echo "checked"; } ?> /><?php echo $ld['no']?>
								</label>
								<?php } ?>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['fornt_valid']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success" style="padding-top:1px;"> 
									<input type="radio" name="data[Language][front]"  data-am-ucheck value="1" <?php if( $this->data['Language']['front'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;
								<?php if(isset($this->data['Language']['is_default'])&&$this->data['Language']['is_default']!='1'&&isset($default_language_list['front'])){ ?>
								<label class="am-radio am-success" style="padding-top:1px;">
									<input type="radio" name="data[Language][front]"  data-am-ucheck value="0" <?php if( $this->data['Language']['front'] == 0 ){ echo "checked"; } ?> /><?php echo $ld['no']?>
								</label>
								<?php } ?>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:12px"><?php echo $ld['backend_valid']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success" style="padding-top:1px;">
									<input type="radio" name="data[Language][backend]"  data-am-ucheck value="1" <?php if( $this->data['Language']['backend'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;
								<?php if(isset($this->data['Language']['is_default'])&&$this->data['Language']['is_default']!='1'&&isset($default_language_list['backend'])){ ?>
								<label class="am-radio am-success" style="padding-top:1px;">
									<input type="radio" name="data[Language][backend]"  data-am-ucheck value="0" <?php if( $this->data['Language']['backend'] == 0 ){ echo "checked"; } ?> /><?php echo $ld['no']?>
								</label>
								<?php } ?>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		<?php $form->end();?>
	</div>
</div>

<script type="text/javascript">
function lan_input_checks(){
	var lan_name_obj = document.getElementById("language_name");
	var lanlocale_name_obj = document.getElementById("language_locale");
	var lancharset_name_obj = document.getElementById("language_charset");
	var lanmap_name_obj = document.getElementById("language_map");
	var langoogle_name_obj = document.getElementById("language_google_translate_code");
	
	if(lan_name_obj.value==""){
		alert("<?php echo $ld['enter_language_name']?>");
		return false;
	}
	if(lanlocale_name_obj.value==""){
		alert("<?php echo $ld['enter_language_code']?>");
		return false;
	}
	if(lancharset_name_obj.value==""){
		alert("<?php echo $ld['enter_character_set']?>");
		return false;
	}
	if(lanmap_name_obj.value==""){
		alert("<?php echo $ld['enter_browser_character_set']?>");
		return false;
	}
	if(langoogle_name_obj.value==""){
		alert("<?php echo $ld['enter_character_set']?>");
		return false;
	}
	var chkObjs = document.getElementsByName("data[Language][front]");
	//var chk=0;
	for(var i=0;i<chkObjs.length;i++){
		if(chkObjs[i].checked){
			front = chkObjs[i].value;
			break;
		}
	}	
	var chkObjs = document.getElementsByName("data[Language][backend]");
	//var chk=0;
	for(var i=0;i<chkObjs.length;i++){
		if(chkObjs[i].checked){
			back = chkObjs[i].value;
			break;
		}
	}
	
	return true;
}
</script>
