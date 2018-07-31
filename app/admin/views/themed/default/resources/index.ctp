<?php 
/*****************************************************************************
 * Seevia 资源库管理
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
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>	
<div class="content">
<!--Main Start-->
	<div class="listsearch ">
		<?php echo $form->create('Resources',array('class'=>"am-form am-form-horizontal",'action'=>'/','name'=>"SeearchForm","type"=>"get"));?>
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
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
						<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
					</div>
				</li>
				<li class="am-margin-top-xs" >
					<label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
						<input type="text" class="am-input-sm" name="keywords"  id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" style="font-weight:normal ;"/>
						</div>
				</li>
				<li class="am-margin-top-xs" >
					<div class="am-u-md-3  am-u-sm-4 ">&nbsp;</div>
					<div class="am-u-sm-3">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm"><?php echo $ld['search'];?></button>
					</div>
				</li>
			</ul>
			
			
		<?php echo $form->end()?>
	</div>
	<?php if($svshow->operator_privilege("resources_add")){?>
		<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3 am-padding-right-0" style="text-align:right;margin-bottom:10px;">
			<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
			 <a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/resources/resource_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
			<?php } ?>
			<a class="am-btn am-btn-warning am-btn-xs am-radius" href="<?php echo $html->url('/resources/view/0'); ?>" >
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
		</div>
			<div style="clear:both;"></div>
	<?php }?>
</div>
	<?php echo $form->create('Resources',array('action'=>'','name'=>"ResourceForm","type"=>"get","onsubmit"=>"return false"));?>
		
	<div class="am-g">
		<div class="am-panel-group am-panel-tree" id="accordion">
			<div class="am-panel am-panel-default am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
							<label class="am-checkbox am-success" style="display: inline;">
						            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
									value="checkbox" data-am-ucheck>
						          
								<?php echo $ld['resource_name'];?>
							</label>
						</div>
						<div class="am-u-lg-1  am-show-lg-only"><?php echo $ld['system'] ?></div>
						<div class="am-u-lg-1  am-show-lg-only"><?php echo $ld['module'] ?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['resource_code'];?></div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['z_resource_value'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['version'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['status'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort'];?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-center"><?php echo $ld['operate'];?></div>
						<div style="clear:both;"></div>
				  	</div>
				</div>
			</div>
		
				<?php if(isset($resource) && sizeof($resource)>0){?><?php foreach($resource as $k => $v){ ?>
			<div>
			<div class="am-panel am-panel-default am-panel-body">
				<div class="am-panel-bd">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
						<label class="am-checkbox am-success">
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Resource']['id']?>" />
						
						<span data-am-collapse="{parent: '#accordion', target: '#Resource_<?php echo $v['Resource']['id']?>'}" class="<?php echo (isset($v['SubMenu']) && !empty($v['SubMenu']))?"am-icon-plus":"am-icon-minus";?>">
							&nbsp;<?php echo $v['ResourceI18n']['name'];?>
						</span>&nbsp;
						</label>
					</div>
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $v['Resource']['id']; ?>')"><?php echo trim($v['Resource']['system_code'])==''?'-':$v['Resource']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $v['Resource']['id']; ?>')"><?php echo trim($v['Resource']['module_code'])==''?'-':$v['Resource']['module_code']; ?></span>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v['Resource']['code']?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $v['Resource']['resource_value']?>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Resource']['section']?>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><span class="<?php echo (!empty($v['Resource']['status'])&&$v['Resource']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Resource']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-right" style="margin-top:5px;"><a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/resources/view/'.$v['Resource']['id']); ?>" ><?php echo $ld['edit']; ?></a>&nbsp;<a class="am-btn am-btn-default am-text-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>resources/remove/<?php echo $v['Resource']['id']; ?>')"><?php echo $ld['delete']; ?></a></div>
					<div style="clear:both;"></div>
				</div>
				
			<?php if(isset($v['SubMenu']) && !empty($v['SubMenu'])){?>
		    <div class="am-panel-collapse am-collapse am-panel-child" id="Resource_<?php echo $v['Resource']['id']?>">
		    	<?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
				<div class="am-panel-bd am-panel-childbd">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">&nbsp;&nbsp;&nbsp;<?php echo $html->link($vv['ResourceI18n']['name'],"view/{$vv['Resource']['id']}",array("style"=>"margin-left:20px;"),false,false);?>
					</div>	
					<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="system_modified(this,'<?php echo $vv['Resource']['id']; ?>')"><?php echo trim($vv['Resource']['system_code'])==''?'-':$vv['Resource']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-show-lg-only"><span ondblclick="module_modified(this,'<?php echo $vv['Resource']['id']; ?>')"><?php echo trim($vv['Resource']['module_code'])==''?'-':$vv['Resource']['module_code']; ?></span>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $vv['Resource']['code']?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $vv['Resource']['resource_value']?>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Resource']['section']?>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><span class="<?php echo(!empty($vv['Resource']['status'])&&$vv['Resource']['status'])?'am-icon-check am-yes':'am-icon-close am-no';?>"></span>&nbsp;</div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $vv['Resource']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-right">
						<a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/resources/view/'.$vv['Resource']['id']); ?>">
							<?php echo $ld['edit']; ?>
						</a>&nbsp;<a class="am-btn am-btn-default am-text-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>resources/remove/<?php echo $vv['Resource']['id']; ?>')">
							<?php echo $ld['delete']; ?>
						</a>
					</div>
					<div style="clear:both;"></div>
				</div>
			<?php }?>
			</div>
			<?php }?>
			</div>
			</div>
			   <?php }}else{?>
				<div>
					<div style="text-align:center;margin:20px 0px;"><?php echo $ld['no_page_data']?></div>
				</div>
				<?php }?>
				<div id="btnouterlist" class="btnouterlist">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchs_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
							<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					    		 <?php } ?>
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
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $this->element('pagers')?></div>
				</div>
		</div>
	</div>
<?php echo $form->end();?>

<!--Main Start End-->

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
			window.location.href=admin_webroot+"/resources/all_export_csv";
		
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
			url:admin_webroot+"resources/batch_operations/",
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
	window.location.href=admin_webroot+"resources/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchs_opration_select_onchange(obj){
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
})

function list_delete_submit1(sUrl){
	YUI().use("io",function(Y) {
		var request = Y.io(sUrl, {method: "POST"});//开始请求
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
			}catch (e){
				alert(j_object_transform_failed);
				alert(o.responseText);
			}
			if(result.flag==1){
				window.location.reload();
			}
			if(result.flag==2){
				alert("删除失败，该资源还有子资源");
			}
		}
		var handleFailure = function(ioId, o){

		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
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
			url:admin_webroot+'resources/system_modified',
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
			url:admin_webroot+'resources/module_modified',
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