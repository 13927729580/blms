<!--档案--><style>.am-form-label{font-weight:bold;  margin-top:-3px;margin-left:15px;}

  .max_w{min-width:88px;}
	</style>

	<?php echo $form->create('Profile',array('action'=>'index',"type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal')); ?>
<div class="listsearch">
    <?php echo $form->create('Profile',array('action'=>'/','name'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
	<ul class="am-avg-lg-3 am-avg-md-3  am-avg-sm-1 am-text-left">
		<li class="am-margin-top-xs " style="margin-top: 10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['system'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="system_code" id='system_code_select'>
						<option value=""><?php echo $ld['please_select']; ?></option>
						<?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
						<option value="<?php echo $v; ?>" <?php echo isset($system_code)&&$system_code==$v?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<li class="am-margin-top-xs" style="margin-top: 10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['module'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
				</div>
			</li>
			<?php if(!empty($group_tree)){?>
          <li style="margin:0 0 10px 0;margin-top: 10px;">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label  am-text-left"><?php echo $ld['classification'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
            <select class="all" name="profile_group" data-am-selected="{maxHeight:300}" id="profile_group" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                <option value=""><?php echo $ld['all_data'];?></option>
                <?php if(isset($group_tree) && sizeof($group_tree)>0){?>
                    <?php foreach($group_tree as $k=>$v){?>
                        <option value="<?php echo $v['Profile']['group']?>" <?php if($select_group == $v['Profile']['group'] && $select_group!=""){?>selected<?php }?>><?php echo $v['Profile']['group'];?></option>
                        <!-- <?php echo $v['ProfileI18n']['name']?> -->
                    <?php }}?>
            </select>
          </li>
        <?php }?>
          <li style="margin:0 0 10px 0;margin-top: 10px;">
            
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                <input style="" placeholder="" type="text" name="profiles_keywords" id="profiles_keywords" value="<?php if(isset($profiles_keywords)){echo $profiles_keywords;}?>" />
            </div>
        </li>
          <li style="margin:0 0 10px 0;margin-top: 10px;">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left"></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding:0 1.5rem;">
                  <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>"  />
            </div>
			</li>	
		</ul>
    <?php echo $form->end();?>
</div>
<?php echo $form->end();?>
<p class="am-u-md-12 am-btn-group-xs am-text-right">
    <?php if($svshow->operator_privilege("profiles_add")){?> 
        
        <?php echo $html->link($ld['bulk_upload'],"/profiles/uploadprofiles",array("class"=>"am-btn am-radius am-btn-sm am-btn-default   "));?>
        		<a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/profiles/view'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
          </a>
    <?php }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
        <tr>
            <th class="max_w"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['classification'];?></b></label></th>
           
            <th class="am-hide-md-down "><?php echo $ld['code'];?></th>
			<th  class="am-hide-md-down "><?php echo $ld['system'] ?></th>
			<th  class="am-hide-md-down "><?php echo $ld['module'] ?></th>
            <th><?php echo $ld['name'];?></th>
            <th class="am-hide-md-down"><?php echo $ld['description'];?></th>
            <th><?php echo $ld['status'];?></th>
            <th class="am-hide-md-down"><?php echo $ld['sort'];?></th>
            <th><?php echo $ld['operate'];?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($profiles) && sizeof($profiles)>0){foreach($profiles as $t){?>
            <tr>
                <td  ><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $t['Profile']['id']?>" /> </span><?php echo $t['Profile']['group'];?></td>
                  <td class="am-hide-md-down"><?php echo $t['Profile']['code'];?></td>
				  
				  <td class="am-hide-md-down"><span  ondblclick="system_modified(this,'<?php echo $t['Profile']['id']; ?>')"><?php echo trim($t['Profile']['system_code'])==''?'-':$t['Profile']['system_code'];?></span>&nbsp;</td>
				<td class="am-hide-md-down"><span ondblclick="module_modified(this,'<?php echo $t['Profile']['id']; ?>')"><?php echo trim($t['Profile']['module_code'])==''?'-':$t['Profile']['module_code']; ?></span>&nbsp;</td>
				
                <td><?php echo $t['ProfileI18n']['name'];?></td>
                <td class="am-hide-md-down"><?php echo $t['ProfileI18n']['description'];?></td>
                <td><?php
                    if($t['Profile']['status']=="0"){
                        echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                    }else if($t['Profile']['status']=="1"){
                        echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                    }?></td>
                <td class="am-hide-md-down"><?php echo $t['Profile']['orderby'];?></td>
                <td class="am-action">
                    <?php
                    if($svshow->operator_privilege("profiles_edit")){?> 
                         <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/profiles/view/'.$t['Profile']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                   <?php  }
                    if($svshow->operator_privilege("profiles_remove")){?>
                    	<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){window.location.href=(admin_webroot+'/profiles/remove/<?php echo $t['Profile']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>
                   <?php  }?>
                </td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td colspan="8" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($profiles) && sizeof($profiles)){?>
        <div id="btnouterlist" class="btnouterlist" style="height:45px;">
            <div class="am-u-lg-6 am-u-md-5 am-u-sm-12  am-hide-sm-only ">
                <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                <span><select id="barch_opration_select" onchange="barch_opration_select_onchange(this)" data-am-selected>
                    <option value="0"><?php echo $ld['batch_operate']?></option>
                    <?php if($svshow->operator_privilege("profiles_remove")){?>
                        <option value="batch_delete"><?php echo $ld['batch_delete']?></option>
                    <?php }?>
                    <option value="export"><?php echo $ld['batch_export']?></option>
                </select></span>
                <span style="display:none;"><select id="export_select" data-am-selected>
                    <option value="all_export"><?php echo  $ld['all_export']?></option>
                    <option value="choice_export"><?php echo $ld['choice_export']?></option>
                    <option value="category_export"><?php echo $ld['category_export']?></option>
                    <option value="search_result"><?php echo $ld['search_export']?></option>
                </select></span>
                <input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['submit']?>" onclick="batch_operations()" />
            </div>
            <div class="am-u-lg-6 am-u-md-6 am-u-sm-12"><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    

    function barch_opration_select_onchange(obj){
        var barch_opration=obj.value;
        if(barch_opration=='export'){
            $("#export_select").parent().show();
        }else{
            $("#export_select").parent().hide();
        }
    }

    function batch_operations(){
        var barch_opration=document.getElementById("barch_opration_select").value;
        if(barch_opration=="batch_delete"){
            batch_delete();
        }else if(barch_opration=="export"){
            var export_action=document.getElementById("export_select").value;
            var linkurl=admin_webroot+"profiles/profile_export/"+export_action;
            if(export_action=="search_result"){
                var profiles_keywords=document.getElementById('profiles_keywords').value;
                var profile_group=document.getElementById('profile_group').value;
                linkurl+= "?profiles_keywords="+profiles_keywords+"&profile_group="+profile_group;
                window.location.href=encodeURI(linkurl);
            }else if(export_action=="choice_export"){
                var postData = "";
                var bratch_operat_check = document.getElementsByName("checkboxes[]");
                for(var i=0;i<bratch_operat_check.length;i++){
                    if(bratch_operat_check[i].checked){
                        postData+="&checkboxes[]="+bratch_operat_check[i].value;
                    }
                }
                if( postData=="" ){
				alert("<?php echo $ld['please_select'] ?>");
				return;
		
                	}else{
                    linkurl+= "?"+postData;
                    window.location.href=encodeURI(linkurl);
                }
            }else{
                window.location.href=encodeURI(linkurl);
            }
            //window.location.href=encodeURI(linkurl);
        }
    }

    function batch_delete(){//批量删除 
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var postData=Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                postData.push(bratch_operat_check[i].value);
            }
        }
          if( postData=="" ){
            alert("<?php echo $ld['select_related_data']; ?>");
            return;
        }
	        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
	        var sUrl = admin_webroot+"profiles/removeall/";//访问的URL地址
	        	 $.ajax({
	                 type:'POST',
	                 	 url:sUrl,
	                 	 dataType:'json',
	                 	 data:{postData:postData},
	                 	 success:function(result)
	                 	 {
	                 	 	 //跳转地址
	                 	 	 window.location.href = window.location.href;
	                 	 }
	        
	        
	              });
	        	
	        }
        
     
        }
   




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
			url:admin_webroot+'profiles/system_modified',
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
			url:admin_webroot+'profiles/module_modified',
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