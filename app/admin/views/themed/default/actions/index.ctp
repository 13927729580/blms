<?php 
/*****************************************************************************
 * SV-Cart 权限管理
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
<style type="text/css">
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
.fuji label.am-checkbox,.am-panel-childbd label.am-checkbox{display:inline;padding-left:10px;}
</style>
<?php echo $form->create('',array('action'=>'/',"type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
<ul class="am-avg-lg-3 am-avg-md-2  am-avg-sm-1">
	<li class="am-margin-top-xs">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['system'] ?></label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="system_code" id='system_code_select'>
				<option value=""><?php echo $ld['please_select']; ?></option>
				<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
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
	<li class="am-margin-top-xs" >
		<div class=" am-u-lg-3  am-u-md-3 am-u-sm-4 ">&nbsp;</div>
		<div class="am-u-sm-3 am-u-end" style="margin-left:1rem;">
			
				<button class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['search']; ?></button>
			
			
		</div>
	</li>
</ul>
<?php echo $form->end();?>

<div class="am-g am-container am-other_action">
	<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-6 am-padding-right-0" style="text-align:right;margin-bottom:10px;">
			<a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/actions/operator_action_upload'); ?>"><?php echo $ld['bulk_upload']?></a>

		<a class="am-btn am-btn-warning am-btn-xs am-radius" href="<?php echo $html->url('/actions/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
	</div>
</div>
<div class="">
	<div class="am-panel-group am-panel-tree" id="accordion">
	<!--标题栏-->
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		      <div class="am-panel-title">
				 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
					<label class="am-checkbox am-success" style="display: inline;">
				            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
							value="checkbox" data-am-ucheck>
						<?php echo $ld['z_action_name'];?>
					</label>
				</div>
				<div class="am-u-lg-1  am-show-lg-only"><?php echo $ld['system'] ?></div>
				<div class="am-u-lg-1  am-show-lg-only"><?php echo $ld['module'] ?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code'];?></div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['versions'];?></div>
				 <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['status'];?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['orderby'];?></div>
				 <div class="am-u-lg-2 am-u-md-4 am-u-sm-4 "><?php echo $ld['operate'];?></div>
				 <div style="clear:both;"></div>
		      </div>
		    </div>
		</div>
	<!--一级 菜单-->
		<?php if(isset($action_tree) && sizeof($action_tree)>0){foreach($action_tree as $k => $v){//pr($v);?>
		<div>
		<div class="am-panel am-panel-default am-panel-body" >
		    <div class="am-panel-bd fuji">
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
					<label class="am-checkbox am-success">
						<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Action']['id']?>" />
					</label>
					<label class="am-checkbox am-success">
						<span data-am-collapse="{parent: '#accordion', target: '#action_<?php echo $v['Action']['id']?>'}" class="<?php echo (isset($v['SubAction'])&&!empty($v['SubAction']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
						<?php echo $html->link($v['ActionI18n']['name'],"view/{$v['Action']['id']}",array(),false,false);?>
					</label>
				</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $v['Action']['id']; ?>')"><?php echo trim($v['Action']['system_code'])==''?'-':$v['Action']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $v['Action']['id']; ?>')"><?php echo trim($v['Action']['module_code'])==''?'-':$v['Action']['module_code']; ?></span>&nbsp;</div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Action']['code']?>&nbsp;</div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo isset($v['Action']['section'])?$v['Action']['section']:'-';?>&nbsp;</div>
				 <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<span class="<?php echo (!empty($v['Action']['status'])&&$v['Action']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Action']['orderby']?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
				    <a class="am-btn am-btn-default  am-btn-sm am-radius" href="<?php echo $html->url('/actions/view/'.$v['Action']['id']); ?>" style="margin-bottom:0.5rem;">
				    <?php echo $ld['edit']; ?>
				    </a>&nbsp;
				    <a class="am-btn am-btn-default am-text-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>operator_actions/remove/<?php echo $v['Action']['id']; ?>')" style="margin-bottom:0.5rem;">
				    	<?php echo $ld['delete']; ?>
				    </a>
				</div>
				<div style="clear:both;"></div>
		    </div>
		<!--二级 菜单-->
			<?php if(isset($v['SubAction']) && sizeof($v['SubAction'])>0){?>
		    <div class="am-panel-collapse am-collapse am-panel-child" id="action_<?php echo $v['Action']['id']?>">
				<?php foreach($v['SubAction'] as $kk=>$vv){?>
				<div class="am-panel-bd am-panel-childbd action_<?php echo $vv['Action']['id']?>">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
						<label class="am-checkbox am-success" style='margin-left:20px;'>
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $vv['Action']['id']?>" />
						</label>
						<label class="am-checkbox am-success">
							<span data-am-collapse="{parent: '#accordion', target: '#action_<?php echo $vv['Action']['id']?>'}" class="<?php echo (isset($vv['SubAction'])&&!empty($vv['SubAction']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
							<?php echo $html->link($vv['ActionI18n']['name'],"view/{$vv['Action']['id']}",array(),false,false);?>
						</label>
					</div>
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $vv['Action']['id']; ?>')"><?php echo trim($vv['Action']['system_code'])==''?'-':$vv['Action']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $vv['Action']['id']; ?>')"><?php echo trim($vv['Action']['module_code'])==''?'-':$vv['Action']['module_code']; ?></span>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['Action']['code']?>&nbsp;</div>
		   			<div class="am-u-lg-2 am-show-lg-only"><?php echo isset($vv['Action']['section'])?$vv['Action']['section']:'-';?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<span class="<?php echo (!empty($vv['Action']['status'])&&$vv['Action']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;&nbsp;
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $vv['Action']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
						<a class="am-btn am-btn-default  am-btn-sm am-radius am-text-left" href="<?php echo $html->url('/actions/view/'.$vv['Action']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;
						<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-sm am-radius am-text-left","onclick"=>"list_delete_submit('{$admin_webroot}operator_actions/remove/{$vv['Action']['id']}')"));?>
					</div>
					<div style="clear:both;"></div>
		    	</div>
		<!--三级 菜单-->			
				<?php if(isset($vv['SubAction']) && sizeof($vv['SubAction'])>0){?>
				<div class="am-panel-collapse am-collapse am-panel-subchild" id="action_<?php echo $vv['Action']['id']?>">
				<?php foreach($vv['SubAction'] as $lk=>$lv){?>
				<div class="am-panel-bd am-panel-childbd">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
						<label class="am-checkbox am-success" style='margin-left:40px;'>
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $lv['Action']['id']?>" />&nbsp;
							<?php echo $html->link($lv['ActionI18n']['name'],"view/{$lv['Action']['id']}",array(),false,false);?>
						</label>
					</div>
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $lv['Action']['id']; ?>')"><?php echo trim($lv['Action']['system_code'])==''?'-':$lv['Action']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $lv['Action']['id']; ?>')"><?php echo trim($lv['Action']['module_code'])==''?'-':$lv['Action']['module_code']; ?></span>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $lv['Action']['code']?>&nbsp;</div>
		   			<div class="am-u-lg-2 am-show-lg-only"><?php echo isset($lv['Action']['section'])?$lv['Action']['section']:'-';?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<span class="<?php echo (!empty($lv['Action']['status'])&&$lv['Action']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;&nbsp;
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $lv['Action']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
						<a class="am-btn am-btn-default am-btn-sm am-radius am-text-left" href="<?php echo $html->url('/actions/view/'.$lv['Action']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;
						<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-sm am-radius am-text-left","onclick"=>"list_delete_submit('{$admin_webroot}operator_actions/remove/{$lv['Action']['id']}');"));?>
					</div>
					<div style="clear:both;"></div>
		    	</div>
				<?php }?>
				</div>
				<?php }?>
				<?php }?>
			</div>	
		   <?php }?>		
		</div>
		</div>
		<?php }}?> 		
	</div>
		<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchactions_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					            </select>
			            	</div> 
						<div class="am-fl" style="display:none;margin-left:3px;">
			                    <select id="export_csv" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="barch_opration_select_onchange" >
			                        <option value=""><?php echo $ld['please_select']?></option>
			                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
			                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
			                    </select>&nbsp;
			              	</div>
						<div class="am-fl" style="margin-left:3px;">
			               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
			              	</div>
				</div>
				
		</div>
</div>	
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
			window.location.href=admin_webroot+"/actions/all_export_csv";
		
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
			url:admin_webroot+"operator_actions/batch_operations/",
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
	window.location.href=admin_webroot+"operator_actions/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchactions_opration_select_onchange(obj){
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
	var $subchild =  $('.am-panel-subchild');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent().find(".fuji");
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus");
	});
	
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent().find(".fuji");
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
	
	$subchild.on('opened.collapse.amui', function() {
		var am_panel_child_className=$(this).attr('id');
		var parentbody2=$(this).parents(".am-panel-child").find("."+am_panel_child_className);
		var collapseoobj2=parentbody2.find(".am-icon-plus");
		collapseoobj2.removeClass("am-icon-plus");
		collapseoobj2.addClass("am-icon-minus")
	});
	$subchild.on('closed.collapse.amui', function() {
		var am_panel_child_className=$(this).attr('id');
		var parentbody2=$(this).parents(".am-panel-child").find("."+am_panel_child_className);
		var collapseoobj2=parentbody2.find(".am-icon-minus");
		collapseoobj2.removeClass("am-icon-minus");
		collapseoobj2.addClass("am-icon-plus")
	});
	
})





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
			url:admin_webroot+'actions/system_modified',
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
			url:admin_webroot+'actions/module_modified',
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