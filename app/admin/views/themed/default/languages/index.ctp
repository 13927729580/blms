<!--系统-->
<style>
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
.language_logo{max-width:52px;}
</style>
<div>
	<?php echo $form->create('',array('action'=>'/',"type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
<ul class="am-avg-lg-3 am-avg-md-2  am-avg-sm-1">
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
		<div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
			<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
		</div>
	</li>
	<li class="am-margin-top-xs" >
		<div class="am-u-sm-3 ">&nbsp;</div>
		<div class="am-u-sm-3 ">
			<button class="am-btn am-btn-success am-btn-sm am-radius">搜索</button>
		</div>
	</li>
</ul>
<?php echo $form->end();?>
	<div class="am-panel-group am-panel-tree" style="margin-top: 20px;">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-1 am-show-lg-only"></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['language_name']?></div>
					
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['system'] ?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['module'] ?></div>
					
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['language_icon']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['language_code']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['default_option']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['front_using']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['background_using']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($languages) && sizeof($languages)>0){foreach($languages as $k=>$language){?>
			<?php if(!isset($apps['Applications'][strtoupper('app-lang-'.$language['Language']['locale'])]) ||  $apps['Applications'][strtoupper('app-lang-'.$language['Language']['locale'])]['status']==0) continue;?>
			<div>
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">	
					<div class="am-u-lg-1 am-show-lg-only"></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $language['Language']['name']?></div>
					
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $language['Language']['id']; ?>')"><?php echo trim($language['Language']['system_code'])==''?'-':$language['Language']['system_code'];?></span>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $language['Language']['id']; ?>')"><?php echo trim($language['Language']['module_code'])==''?'-':$language['Language']['module_code']; ?></span>&nbsp;</div>
					
					<div class="am-u-lg-1 am-show-lg-only"><?php if($language['Language']['img01'])echo $html->image($language['Language']['img01'],array('class'=>'language_logo'))?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php if($language['Language']['map'])echo $language['Language']['locale'];?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['is_default']){?><span class="am-icon-check am-yes"></span><?php }else{?><span class="am-icon-close am-no"></span>
					<?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['front']){?><span class="am-icon-check am-yes"></span><?php } else{?><span class="am-icon-close am-no"></span><?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<?php if($language['Language']['backend']){?><span class="am-icon-check am-yes"></span><?php } else{?><span class="am-icon-close am-no"></span><?php }?>
					</div>
					<div style="width:80px" class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-btn-group-xs am-action">
						<?php if($svshow->operator_privilege("languages_edit")){?>
		<!--echo $html->link($ld['edit'],"/languages/view/{$language['Language']['id']}",array("class"=>"am-btn am-btn-success am-radius am-btn-sm"));-->
		
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/languages/view/'.$language['Language']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
						<?php 	}?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/dictionaries?locale=chi'); ?>">
                         <?php echo '字典管理' ?>
						</a>
						
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			</div>
		<?php }}?>
		<?php if(!empty($lost) && 1==2){foreach($lost as $k=>$v){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">	
						<div class="am-u-lg-1 am-show-lg-only">--</div>
						<div class="am-u-lg-1 am-u-md-4 am-u-sm-4"><?php echo $v["name"];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php if($v['img01'])echo $html->image($v['img01'])?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php if($v['map'])echo $v['locale'];?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('/admin/skins/default/img/no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('/admin/skins/default/img/no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->image('/admin/skins/default/img/no.gif');?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $html->link($ld['install'],"/languages/install/{$v['locale']}");?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}?>			
	</div>
</div>
<script>
	//触发子下拉
function barchmenus_opration_select_onchange(obj){
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();		
	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"){
			$("#export_csv").parent().show();
		}else{
			$("#export_csv").parent().show();
		}
	}

}
								
								
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
});

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
			url:admin_webroot+'languages/system_modified',
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
			url:admin_webroot+'languages/module_modified',
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