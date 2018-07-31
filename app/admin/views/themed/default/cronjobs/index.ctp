<style>
	.am-panel-title{font-weight:bold;}
	.am-form-label{font-weight:bold;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
</style>
<div class="am-g" style="margin-top:10px;margin-left:0;margin-right:0;">
	<div class="listsearch">
		<?php echo $form->create('Cronjob',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();',"class"=>"am-form am-form-horizontal"));?>
		<input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
				<li class="am-margin-top-xs">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['system'] ?></label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
					<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="system_code" id='system_code_select'>
						<option value=""><?php echo $ld['please_select']; ?></option>
						<?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
						<option value="<?php echo $v; ?>" <?php echo isset($system_code)&&$system_code==$v?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
					</div>
				</li>
				<li class="am-margin-top-xs">
					<label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text"><?php echo $ld['module'] ?></label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
						<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
					</div>
				</li>
				<li style="margin-top: 9px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo '应用';?></label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
						<select class="all" name="cronjob_app" id="cronjob_app" data-am-selected="{noSelectedText:''}">
							<option value=""><?php echo $ld['select_app_code']?></option>
							<?php if(isset($appcode_tree) && sizeof($appcode_tree)>0){?><?php foreach($appcode_tree as $k=>$v){?>
					  		<option value="<?php echo $v['Application']['code']?>" <?php if($cronjob_app == $v['Application']['code'] && $cronjob_app!=""){?>selected<?php }?>><?php echo $v['Application']['code']?></option>
							<?php }}?>
						</select>
					</div>
				</li>
						
				<li style="margin-top: 9px">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo $ld['keyword'];?></label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-7" style="">
						<input type="text" class="am-form-field am-radius" name="cronjob_keywords" id="cronjob_keywords" value="<?php echo $cronjob_keywords?>"  placeholder="<?php echo $ld['task_name']?>"/>
					</div>
					
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-margin-left-0 am-hide-lg-only">&nbsp;</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-7" style="padding-left:25px;">
					<button style="margin-top:10px;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  >
						<?php echo $ld['search'];?>
					</button>
				</div>
				</li>
			</ul>
			<div class="action-span add am-text-right" style="margin:0px 0px 10px 0px;">
				<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('view'); ?>">
					<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
				</a>
			</div>
		<?php echo $form->end();?>
	</div>	
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['task_name']?></div>
					<div class="am-u-lg-1  am-hide"><?php echo $ld['system'] ?></div>
					<div class="am-u-lg-1  am-hide"><?php echo $ld['module'] ?></div>
					<div class="am-u-lg-1 am-hide"><?php echo $ld['task_code']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['last_time']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['next_time']?></div>
					<div class="am-u-lg-1 am-hide"><?php echo $ld['interval_time']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4" style="padding-left:0;padding-right:0;"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($cronjobs) && sizeof($cronjobs)>0){foreach($cronjobs as $k=>$v){;?>	
		<div>
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['Cronjob']['task_name'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-hide"><span ondblclick="system_modified(this,'<?php echo $v['Cronjob']['id']; ?>')"><?php echo trim($v['Cronjob']['system_code'])==''?'-':$v['Cronjob']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-hide"><span ondblclick="module_modified(this,'<?php echo $v['Cronjob']['id']; ?>')"><?php echo trim($v['Cronjob']['module_code'])==''?'-':$v['Cronjob']['module_code']; ?></span>&nbsp;</div>
					<div class="am-u-lg-1 am-hide"><?php echo $v['Cronjob']['task_code'] ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Cronjob']['last_time'] ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Cronjob']['next_time'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-hide"><?php echo $v['Cronjob']['interval_time'] ?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
						<?php if( $v['Cronjob']['status']==1 ){?>
							<?php if($svshow->operator_privilege('cronjob_edit')){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'cronjobs/toggle_on_status',<?php echo $v['Cronjob']['id'];?>)"></span>
							<?php }elseif($opertor_type=="D"){?>
								<span class="am-icon-check am-yes" ></span>
							<?php }else{?>
								<span class="am-icon-check am-yes" ></span>
							<?php }?>
						<?php }elseif($v['Cronjob']['status'] == 0){?>
							<?php if($svshow->operator_privilege('cronjob_edit')){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'cronjobs/toggle_on_status',<?php echo $v['Cronjob']['id'];?>)"></span>
									
							<?php }elseif($opertor_type=="D"){?>
								<span class="am-icon-close am-no"></span>
							<?php }else{?>
								<span class="am-icon-close am-no"></span>
							<?php }?>
						<?php }?>
					</div>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4" style="padding-left:0;padding-right:0;">
						<?php echo 	$html->link($ld['run'],"javascript:;",array("onclick"=>"addexec('{$v['Cronjob']['task_name']}','{$shop_name}')","class"=>"am-btn am-radius am-btn-default  am-btn-sm ","style"=>"margin-bottom:0.5rem;")).'&nbsp'; ?>
						<?php
							if($svshow->operator_privilege("cronjob_edit")){echo $html->link($ld['edit'],"/cronjobs/view/{$v['Cronjob']['id']}",array("class"=>"am-btn am-radius am-btn-default  am-btn-sm ","style"=>"margin-bottom:0.5rem;")).'&nbsp;';}
							if($svshow->operator_privilege("cronjob_remove")){echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-radius am-btn-default am-text-danger am-btn-sm ","style"=>"margin-bottom:0.5rem;","onclick"=>"list_delete_submit('{$admin_webroot}cronjobs/remove/{$v['Cronjob']['id']}');"));}?> 
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php }}else{?>
			<div style="margin:50px;">
				<div style="text-align:center;"><label><?php echo $ld['no_Cronjob']?></label></div>
			</div>
		<?php }?>			
	</div>
	<?php if(isset($cronjobs) && sizeof($cronjobs)>0){ ?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-4 am-u-md-3 am-hide-sm-only">&nbsp;</div>
			<div class="am-u-lg-8 am-u-md-9 am-u-sm-12"> 
				<?php echo $this->element('pagers')?>
			</div>
		</div>
	<?php }?>
</div>	
<script>
	
	function addexec(taskname,shopname){
		window.location.href = encodeURI(admin_webroot+"cronjobs/execute?taskname="+taskname+"&shopname="+shopname);
	}	
	function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		
		}	
	});
}

function system_modified(obj,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "select"){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
  	var SELECT = document.createElement("SELECT");
  	var SystemCodeOptions = document.getElementById("system_code_select").options;
  	for(var i=0;i<=SystemCodeOptions.length-1;i++){
  		var select_option=SystemCodeOptions[i];
  		SELECT.options.add(new Option(select_option.textContent,select_option.value,true,val==select_option.value?true:false));
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
			url:admin_webroot+'cronjobs/system_modified',
			data:{'id':id,'val':Utils.trim(val)},
			async: false,
			success: function(data) {
				try{
					var result= JSON.parse(data);
					if(result.flag == 1){
						var result_content = result.content==''?'-':result.content;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}else{
						alert(result.content);
						obj.innerHTML = org;
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = org;
				}
			}
		}); 
	};
}

function module_modified(obj,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
  	var txt = document.createElement("INPUT");
	txt.type = "text" ;
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.className = "input_text" ;
	txt.style.width = (obj.offsetWidth + 12) + "px" ;
	txt.style.minWidth = "20px" ;
  	
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
	
	txt.onblur=function(){
		$.ajax({
			cache: true,
			type: "POST",
			url:admin_webroot+'cronjobs/module_modified',
			data:{'id':id,'val':Utils.trim(txt.value)},
			async: false,
			success: function(data) {
				try{
					var result= JSON.parse(data);
					if(result.flag == 1){
						var result_content = Utils.trim(result.content)==''?'-':result.content;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}else{
						alert(result.content);
						obj.innerHTML = org;
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = org;
				}
			}
		}); 
	};
}
</script>				
			