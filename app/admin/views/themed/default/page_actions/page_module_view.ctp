<?php
	echo $html->css('/skins/default/css/docs');
	echo $html->css('/skins/default/css/codemirror');
	echo $javascript->link('/skins/default/js/codemirror');
	echo $javascript->link('/skins/default/js/css');
?>
<style type="text/css">
    .am-radio, .am-checkbox{display:inline;}
    em{color:red;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}

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

    .am-selected.am-dropdown {width: 50%;}
    .am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{width: 100%;}
    .CodeMirror{margin:0;width: 80%;}
</style>
<?php echo $form->create('PageAction',array('action'=>'page_module_view','onsubmit'=>'return modules_check();'));?>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:100%;">
    <!-- 导航 -->
    <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
        <ul>
            <li><a href="#basic_info"><?php echo $ld['basic_information'];?></a></li>
        </ul>
    </div>
    <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
        <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">
            <?php echo $ld['d_submit'];?>
        </button>
        <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" >
            <?php echo $ld['d_reset']?>
        </button>
    </div>
    <!-- 导航结束 -->
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <input type="hidden" id="id" name="data[PageModule][id]" value="<?php if(isset($modules_info['PageModule']) && $modules_info['PageModule']['id'] !=0){echo $modules_info['PageModule']['id'];}?>"/>
                <?php if(isset($backend_locales) && sizeof($backend_locales)>0){
                    foreach ($backend_locales as $k => $v){?>
                        <input id="PageModuleI18n<?php echo $k;?>Locale" name="data[PageModuleI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
                        <input id="PageModuleI18n<?php echo $k;?>Id" name="data[PageModuleI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){echo $modules_info['PageModuleI18n'][$v['Language']['locale']]['id'];}?>">
                        <input id="PageModuleI18n<?php echo $k;?>PageModuleI18nId" name="data[PageModuleI18n][<?php echo $k;?>][module_id]" type="hidden" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['id'];}?>">
                <?php }}?>
                <table class="am-table" id="hotel_img_ul">
                    <tr>
                        <th style="padding-top:15px;width: 20%;"><?php echo $ld['module_code']?></th>
                        <td><input type="text" id="code" style="width:50%;float:left;" name="data[PageModule][code]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['code'];} ?>" onblur="operator_change()" /><em>*</em></td>
                    </tr>
                    <!--名称-->
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['module_name']?></th>
                    </tr>
                    <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input type="text" style="width:50%;float:left;" id="name_<?php echo $v['Language']['locale']?>" name="data[PageModuleI18n][<?php echo $k;?>][name]"  <?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){?>value="<?php echo  $modules_info['PageModuleI18n'][$v['Language']['locale']]['name'];?>"<?php }else{?>value=""<?php }?> /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                    <?php }} ?>
                    <!--模块标题-->
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['module_title']?></th>
                    </tr>
                    <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input type="text" style="width:50%;float:left;" id="url<?php echo $v['Language']['locale']?>" name="data[PageModuleI18n][<?php echo $k;?>][title]"  <?php if(isset($modules_info['PageModuleI18n'][$v['Language']['locale']])){?>value="<?php echo  $modules_info['PageModuleI18n'][$v['Language']['locale']]['title'];?>"<?php }else{?>value=""<?php }?> /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                        </tr>
                    <?php }} ?>
                    <tr>
                        <th ><?php echo $ld['page']?></th>
                        <td>
                            <input type="hidden" name="data[PageModule][page_action_id]" value="<?php echo isset($page_action_id)?$page_action_id:''; ?>" />
                            <?php
                            if(isset($page_action_list)&&sizeof($page_action_list)>0){
                                foreach($page_action_list as $k=>$v){
                                    if($page_action_id==$v['PageAction']['id']){
                                        echo $v['PageAction']['name'];
                                        break;
                                    }
                                }
                            }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:12px;"><?php echo $ld['module_parent']?></th>
                        <td><select id="PageModuleParentId" name="data[PageModule][parent_id]" data-am-selected>
                                <option value="0"><?php echo $ld['root']?></option>
                                <?php if(isset($modules_tree) && sizeof($modules_tree)){foreach($modules_tree as $k=>$v){//第一层 ?>
                                    <option value="<?php echo $v['PageModule']['id'];?>" <?php echo isset($this->data['PageModule']['parent_id'])&&$v['PageModule']['id']==$this->data['PageModule']['parent_id']?"selected":"";?> ><?php echo $v['PageModuleI18n']['name'];?></option>
                                    <?php if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){foreach($v['SubPageModule'] as $kk=>$vv){//第二层?>
                                        <option value="<?php echo $vv['PageModule']['id'];?>" <?php echo isset($this->data['PageModule']['parent_id'])&&$vv['PageModule']['id']==$this->data['PageModule']['parent_id']?"selected":"";?> >|-- <?php echo $vv['PageModuleI18n']['name'];?></option>
                                        <?php if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){foreach($v['SubPageModule'] as $kkk=>$vvv){//第二层 ?>
                                        <?php }}}}}}?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_type']?></th>
                        <td>
                            <select data-am-selected="{maxHeight:300}" id='type' name="data[PageModule][type]" onchange="set_ModelorFunction(this)">
					<?php if(isset($module_types)&&sizeof($module_types)>0){foreach($module_types as $k=>$v ){ ?>
					<option value="<?php echo $v['Resource']['resource_value'];?>" <?php echo isset($modules_info['PageModule']['type'])&&$modules_info['PageModule']['type']==$v['Resource']['resource_value']?"selected":""; ?>><?php echo $v['ResourceI18n']['name'];?></option>
					<?php }} ?>
					<option value='custom' <?php echo isset($modules_info['PageModule']['type'])&&!isset($module_types[$modules_info['PageModule']['type']])?'selected':''; ?>><?php echo $ld['custom']; ?></option>
                            </select>
                        	<input type='text' name="data[PageModule][type]"  style="width:40%;display: inline;" id='custom_module_type' value="<?php echo isset($modules_info['PageModule']['type'])?$modules_info['PageModule']['type']:''; ?>" <?php echo isset($modules_info['PageModule']['type'])&&!isset($module_types[$modules_info['PageModule']['type']])?" class='am-hide' disabled":''; ?> />
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo 'Model';?></th>
                        <td><input type="text" id="model" style="width:50%;float:left;" name="data[PageModule][model]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['model'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['function'];?></th>
                        <td><input type="text" id="function" style="width:50%;float:left;" name="data[PageModule][function]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['function'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;">ID<?php //echo $ld['function'];?></th>
                        <td><input type="text" id="parameters" style="width:50%;float:left;" name="data[PageModule][parameters]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['parameters'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_orderby_type']?></th>
                        <td><input type="text" id="orderby_type" style="width:50%;float:left;" name="data[PageModule][orderby_type]" value="<?php echo isset($modules_info['PageModule']['orderby_type'])?$modules_info['PageModule']['orderby_type']:'';?>"/><em>*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['folder_name'];?></th>
                        <td><input type="text" id="file_name" style="width:50%;float:left;" name="data[PageModule][file_name]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['file_name'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_location']?></th>
                        <td><select name="data[PageModule][position]" data-am-selected>
                                <?php foreach( $module_position as $kk=>$vv ){ ?>
                                    <option value="<?php echo $kk;?>" <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['position']==$kk){echo "selected";}?> ><?php echo $vv;?></option>
                                <?php }?>
                            </select></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_limit_number']?></th>
                        <td><input type="text" style="width:50%;float:left;" name="data[PageModule][limit]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['limit'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_width']?></th>
                        <td><input type="text" id="width" style="width:50%;float:left;" name="data[PageModule][width]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['width'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['module_height']?></th>
                        <td><input type="text" style="width:50%;float:left;" name="data[PageModule][height]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['height'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding-top:12px;"><?php echo $ld['sort']?></th>
                        <td><input type="text" style="width:50%;float:left;" name="data[PageModule][orderby]" value="<?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['orderby'];} ?>" /></td>
                    </tr>
                    <tr>
                        <th ><?php echo $ld['module_float']?></th>
                        <td><label class="am-radio am-success" style="top: 0px;"><input type="radio" value="0" name="data[PageModule][float]" checked data-am-ucheck /><?php echo $ld['module_float_in_entire_row']?></label>
                            <label style="margin-left:10px;top: 0px;" class="am-radio am-success"><input type="radio" name="data[PageModule][float]" value="1" data-am-ucheck <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['float'] == 1){ echo "checked"; } ?>/><?php echo $ld['module_left_floating']?></label>
                            <label style="margin-left:10px;top: 0px;" class="am-radio am-success"><input type="radio" name="data[PageModule][float]" value="2" data-am-ucheck <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['float'] == 2){ echo "checked"; } ?>/><?php echo $ld['module_right_floating']?></label>
                        </td>
                    <tr>
                        <th ><?php echo $ld['valid']?></th>
                        <td><label class="am-radio am-success" style="top: 0px;"><input type="radio" value="1" name="data[PageModule][status]" checked data-am-ucheck /><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;top: 0px;" class="am-radio am-success"><input type="radio" name="data[PageModule][status]" value="0" data-am-ucheck <?php if(isset($modules_info['PageModule'])&&$modules_info['PageModule']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                    <tr>
                        <th ><?php echo $ld['module_css']?></th>
                        <td style="padding:4px;"><textarea id="PageModule_css" name="data[PageModule][css]" style="resize: none;"><?php if(isset($modules_info['PageModule'])){echo $modules_info['PageModule']['css'];} ?></textarea></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    var editor;
    function setCodeMirror(){
        if($("#PageModule_css").parent().find(".CodeMirror").length>0){
            var _value1=editor.getValue();
            document.getElementById("PageModule_css").value=_value1;
            $("#PageModule_css").parent().find(".CodeMirror").remove();
        }
        editor = CodeMirror.fromTextArea(document.getElementById("PageModule_css"), {
            lineNumbers: true
        });
    }

    $(document).ready(function(){
        setCodeMirror();
    })

    function add_to_seokeyword(obj,keyword_id){
        var keyword_str = GetId(keyword_id).value;
        var keyword_str_arr = keyword_str.split(",");
        for( var i=0;i<keyword_str_arr.length;i++ ){
            if(keyword_str_arr[i]==obj.value){
                return false;
            }
        }
        if(keyword_str!=""){
            GetId(keyword_id).value+= ","+obj.value;
        }else{
            GetId(keyword_id).value+= obj.value;
        }
    }
    function modules_check(){
        if(document.getElementById("orderby_type").value==''){
            alert("排序方式不能为空！");
            return false;
        }
        if(document.getElementById("name_"+backend_locale).value==''){
            alert("<?php echo $ld['module_name_can_not_empty']?>");
            return false;
        }
        if(document.getElementById('code').value==''){
            alert("<?php echo $ld['module_code_can_not_empty']?>");
            return false;
        }
        return true;
    }

    function operator_change(){
        var code = document.getElementById("code").value;
        if(code!=""){
            var code=document.getElementById('code');
            var id=document.getElementById('id').value;
            if(id==''){
                var id=0;
            }
            var sUrl = admin_webroot+"page_actions/check_code/"+id;
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {code:code.value},
                success: function (result) {
                    var form_submit=document.createAttribute("onsubmit");
                    if(result.code==1){
                        document.getElementById("code").style.color="black";
                        form_submit.nodeValue="modules_check()";
                    }else{
                        document.getElementById("code").style.color="red";
                        alert("<?php echo $ld['module_code_already_exists']?>");
                        form_submit.nodeValue="return false;";
                    }
                    document.getElementById("PageActionPageModuleViewForm").setAttributeNode(form_submit);
                }
            });
        }
    }

    function set_ModelorFunction(obj){
    	 if(obj.value=='custom'){
    	 	$(obj).parent().find("#custom_module_type").attr('disabled',false).removeClass('am-hide');
    	 }else{
    	 	 $(obj).parent().find("#custom_module_type").attr('disabled',true).addClass('am-hide');
	        var sUrl = admin_webroot+"page_actions/set_modelorfunction/";
	        $.ajax({
	            type: "POST",
	            url: sUrl,
	            dataType: 'json',
	            data: {res_val:obj.value},
	            success: function (result) {
	                if(result.status=='1'){
				document.getElementById("function").value=result.function;
				document.getElementById("model").value=result.model;
	                }else{
				var old_function=document.getElementById("function").value;
				var old_model=document.getElementById("model").value;
				document.getElementById("function").value=old_function!=''?old_function:'';
				document.getElementById("model").value=old_model!=''?old_model:'';
	                }
	            }
	        });
         }
    }
</script>
