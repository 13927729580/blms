<?php 
/*****************************************************************************
 * SV-Cart 字典管理
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
<!--Search-->
<div class="search_box">
	<div class="am-form-group">
		<?php echo $form->create('dictionaries',array('action'=>'/','name'=>'type_form','id'=>'type_form','type'=>'GET','class'=>'am-form am-form-horizontal'));?>
		<ul class="am-avg-lg-3 am-avg-md-3  am-avg-sm-1 am-text-left">
			<li class="am-margin-top-xs ">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['system'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="system_code" id='system_code_select'>
						<option value=""><?php echo $ld['please_select']; ?></option>
						<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
						<option value="<?php echo $v; ?>" <?php echo isset($system_code)&&$system_code==$v?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<li class="am-margin-top-xs">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['module'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
				</div>
			</li>
			<li class="am-margin-top-xs ">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['language']; ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select  data-am-selected name="dictionary_locale">
						<option value="-1"><?php echo $ld['choose_language'];?></option>
						<?php if(isset($language) && sizeof($language)>0){foreach($language as $key=>$value){ ?>
						<option value="<?php echo $value['Language']['locale'];?>" <?php echo isset($dictionary_locale)&&$dictionary_locale==$value['Language']['locale']?'selected':''; ?>><?php echo $value['Language']['name'];?></option>
						<?php }}?>
					</select>
				</div>
			</li>
			<li class="am-margin-top-xs ">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['location']; ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select name="language_location" data-am-selected>
						<option value="all_location"><?php echo $ld['all_position'];?></option>
						<option value="front" <?php echo isset($language_location)&&$language_location=='front'?'selected':''; ?>><?php echo $ld['frontend'];?></option>
						<option value="backend" <?php echo isset($language_location)&&$language_location=='backend'?'selected':''; ?>><?php echo $ld['backend'];?></option>
					</select>
				</div>
			</li>
			<li class="am-margin-top-xs ">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['type']; ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select name="language_type"  data-am-selected>
						<option value="all_type"><?php echo $ld['all_type'];?></option>
						<option value="label" <?php echo isset($language_type)&&$language_type=='label'?'selected':''; ?>>label</option>
						<option value="js" <?php echo isset($language_type)&&$language_type=='js'?'selected':''; ?>>js</option>
					</select>
				</div>
			</li>
			<li class="am-margin-top-xs">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text" style="margin-top: 5px;"><?php echo $ld['name'].'/'.$ld['content'];?>:</label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<input class="am-input-sm"  type="text" name="dictionary_keywords" value="<?php echo isset($dictionary_keywords)?$dictionary_keywords:''; ?>" />
				</div>
			</li>
			<li class="am-margin-top-xs">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;</div>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="margin-top: 10px;">
				<button  style="margin:5px 0 0 5px;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm"><?php echo $ld['s_select'];?></button>
				</div>
			</li>
		</ul>
		<?php echo $form->end();?>
	</div>
	<p class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-right am-btn-group-xs" style="margin-right:27px;">
			<button type="button"class="am-btn am-btn-warning am-radius am-btn-xs" data-am-modal="{target: '#dictionarie_form', closeViaDimmer: 0, width: 500, height:450}"><?php echo $ld['add']; ?></button>
			<a class='am-btn am-btn-default am-radius am-btn-xs'  href="<?php echo $html->url('/dictionaries/upload');?>"><?php echo $ld['bulk_upload']; ?></a>
	</p>
</div>
<!-- end -->
<!--Search End-->
<!--Main Start-->
<div class="home_main" style="padding:0px 0 20px 0;clear:both;">
  
  <div style="clear:both;"></div>
		<div class="am-panel-group am-panel-tree">
			<div class="am-panel am-panel-default am-panel-header"  style="margin-top:20px;">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-1 am-show-lg-only">
							<label class="am-checkbox am-success" style="display: inline;">
						            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
									value="checkbox" data-am-ucheck>
								
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-4"><?php echo $ld['z_name'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['system'] ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['module'] ?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['z_position'];?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['z_type'];?></div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['z_content'];?></div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['z_operation'];?></div>
					</div>
				</div>
			</div>
			<?php if(isset($language_dictionaries) && count($language_dictionaries)>0){?>
			<?php foreach($language_dictionaries as $k=>$v){?>
				<div class="am-panel-hd">
			<div  <?php if((abs($k)+2)%2!=1){ echo 'class="am-g tr_bgcolor"'; }else{ echo 'class="am-g"'; }?> >
				<div class="am-u-lg-1 am-show-lg-only" >
						<label class="am-checkbox am-success">
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Dictionary']['id']?>" />
						</label>
				</div>
				<div class="am-panel-title am-u-lg-2 am-u-md-4 am-u-sm-4" >
					<div>
						<div id="lang_name <?php echo $v['Dictionary']['id']?>">
								<span onclick="javascript:listTable.edit(this, 'dictionaries/update_dictionaries_name/', <?php echo 	$v['Dictionary']['id']?>)"><?php echo $v['Dictionary']['name']?></span>
						</div>
					</div>
				</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $v['Dictionary']['id']; ?>')"><?php echo trim($v['Dictionary']['system_code'])==''?'-':$v['Dictionary']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $v['Dictionary']['id']; ?>')"><?php echo trim($v['Dictionary']['module_code'])==''?'-':$v['Dictionary']['module_code']; ?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Dictionary']['location'];?>&nbsp;</div>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" id="type<?php echo $v['Dictionary']['id']?>">
					<div id="lang_type<?php echo $v['Dictionary']['id']?>">
					<?php if(isset($language_type_assoc[$v['Dictionary']['type']])) echo $language_type_assoc[$v['Dictionary']['type']]; else echo $v['Dictionary']['type'];?>
					</div>
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<div id="lang_value<?php echo $v['Dictionary']['id']?>">
							<span onclick="javascript:listTable.edit(this, 'dictionaries/update_dictionaries_value/', <?php echo $v['Dictionary']['id']?>)"><?php echo $v['Dictionary']['value']?></span>
						</div>
				</div>
				<div class="am-u-lg-1 am-u-md-3 am-u-sm-3" style="margin:2px 0px;">
						<button type="button" class="am-btn am-btn-default am-text-danger am-radius am-btn-sm" onclick="if(confirm('<?php echo $ld['confirm_delete']; ?>')){window.location.href='<?php echo $admin_webroot; ?>/dictionaries/remove/<?php echo $v['Dictionary']['id']; ?>';}"><?php echo $ld['remove'];?></button>
				</div>
			</div>
			</div>		
		<?php }?>
            <div class="btnouterlist" style="position:relative">
            	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchdics_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					            </select>
			            	</div> 
						<div class="am-fl" style="display:none;margin-left:3px;">
			                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" >
			                        <option value=""><?php echo $ld['click_select']?></option>
			                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
			                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
			                    </select>&nbsp;
			              	</div>
						<div class="am-fl" style="margin-left:3px;">
			               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
			              	</div>
				</div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $this->element('pagers');?> </div>
			</div>
		<?php }else{?>
			<div class="am-g">
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="margin-left:40%;margin-top:2%;">
					<span style='font-size:16px;font-weight:bold;'>
					<?php if(isset($is_select_locale)){?>
					<?php echo$ld['z_prompt1'];?>
					<?php }else{?>
					<?php echo$ld['z_prompt4'];?>
					<?php }?>
					</span>
				</div>
			</div>
		<?php }?>
		</div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="dictionarie_form">
  <div class="am-modal-dialog" style="height:420px;"> 
    <div class="am-modal-hd" style="border-bottom: 1px solid #ddd;"><?php echo $ld['add']; ?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		<?php echo $form->create('dictionaries',array('action'=>'add','class'=>'am-form am-form-horizontal'));?>
		<div class="am-form-group" style="margin-top:13px;margin-bottom:6px">
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php echo $ld['system'] ?></div>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Dictionary][system_code]">
					<option value=""><?php echo $ld['please_select']; ?></option>
					<?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
					<option value="<?php echo $v; ?>" <?php echo isset($system_code)&&$system_code==$v?'selected':''; ?>><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
	  	</div>
	  <div class="am-form-group"  style="margin-bottom:6px">
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php echo $ld['module'] ?></div>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left">
				<input type='text' class="am-input-sm am-form-field" name="data[Dictionary][module_code]" value="<?php echo isset($module_code)?$module_code:''; ?>" />
			</div>
	  </div>
	  <div class="am-form-group">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php echo$ld['z_name'];?></div>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left" style="padding-bottom:5px;">
			<input type="text" name="data[Dictionary][name]" value="" class="am-input-sm am-form-field">
		</div>
	  </div>
	  <div class="am-form-group">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php echo$ld['z_position'];?></div>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left " style="padding-bottom:5px;">
		  <select data-am-selected name="data[Dictionary][location]" >
			<option value="front"><?php echo $ld['frontend'];?></option>
			<option value="backend"><?php echo $ld['backend'];?></option>
		  </select>
		</div>
	  </div>
	  <div class="am-form-group">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php echo$ld['z_type'];?></div>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left" style="padding-bottom:5px;">
		  <select data-am-selected name="data[Dictionary][type]">
			<option value="label">label</option>
			<option value="js">js</option>
		  </select>
		</div>
	  </div>
	  <div class="am-form-group">
		<?php if(isset($language) && sizeof($language)>0){foreach($language as $key=>$value){ ?>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left" style="margin-bottom:5px">
		  <?php echo $value['Language']['name'];?>
		</div>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="margin-bottom:5px">
		  <input type="text" class="am-input-sm am-form-field am-input-sm am-form-field" name="data[Dictionary][value][<?php echo $value['Language']['locale']; ?>]" value="">
		</div>
		<?php }}?>
	  </div>
	  <div class="am-form-group" style="margin-top: 2px;">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;</div>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left">
			<button  style="margin:5px 0 0 0px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm am-text-left" onclick="dictionary_add(this)"><?php echo $ld['save'];?></button>
		  </div>
	  </div>
	  <?php echo $form->end();?>
    </div>
    <div class="am-cf"></div>
  </div>
</div>

<!--Main Start End-->
<style>
@media only screen  and (min-width : 1025px) 
{
	/*.lg_width input[type='text']{max-width:200px;}*/
	.am-topbar-nav{width:100%;}
}
@media only screen and (min-width:641px) and (max-width : 1024px) 
{
	.am-nav-pills > li {
	    float: none;
		min-height:40px;
	    padding: 10px 0;
	}
	.am-topbar-nav{float:none;}
}
@media only screen and (max-width:640px)
{
	.am-nav-pills > li {
	    float: none;
		min-height:40px;
	    padding: 10px 0;
	}
}
.am-form select{padding:0.3em;}
</style>
<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_operations();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/dictionaries/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}

//批量删除
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		$.ajax({ 
			url:admin_webroot+"dictionaries/batch_operations/",
			type:"GET",
			dataType:"json",
			data: postData,
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}	

//选择导出
function choice_upload(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}else{
	window.location.href=admin_webroot+"dictionaries/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchdics_opration_select_onchange(obj){
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

function export_act(){ 
	document.getElementById('export_type').value = document.getElementById('language_type').value;
	document.getElementById('export_location').value = document.getElementById('language_location').value;
	document.getElementById('export_keyword').value = document.getElementById('keywords').value;
	document.forms['Export'].submit(); 
}		
function import_act(){
	if(document.getElementById('import_span').style.display == "none"){
	    document.getElementById('import_span').style.display = "";
	}else{
	    document.getElementById('import_span').style.display = "none";
	}
}

function dictionary_add(btn){
	var post_form=$(btn).parents('form');
	var post_data=post_form.serializeArray();
	var form_flag=true;
	var dictionary_value_flag=true;
	$.each(post_data,function(index,item){
		var field_name=item.name;
		var field_value=item.value.trim();
		if(field_name=='data[Dictionary][name]'&&field_value==""){
			alert(j_enter_name);
			form_flag=false;
			return false;
		}else if(field_name.indexOf('data[Dictionary][value]')>=0&&field_value!=""){
			dictionary_value_flag=false;
		}
	});
	if(form_flag){
		if(dictionary_value_flag){
			alert(j_empty_content);
			return false;
		}
		post_data=post_form.serialize();
		$.ajax({
		        cache: true,
		        type: "POST",
		        url:admin_webroot+"dictionaries/add",
		        data:post_data,
		        dataType:'JSON',
		        success: function(data) {
		          		alert(data.msg);
		          		if(data.code=='1'){
		          			window.location.reload();
		          		}
		        }
		});
		
		
	}
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
			url:admin_webroot+'dictionaries/system_modified',
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
			url:admin_webroot+'dictionaries/module_modified',
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