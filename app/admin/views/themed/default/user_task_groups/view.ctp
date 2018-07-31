<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_task_groups',array('action'=>'view/'.$task_group["UserTaskGroup"]["id"],'id'=>'user_task_edit_form','name'=>'user_task_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return check_all();"));?>
        <input type="hidden" name="data[UserTaskGroup][id]" id="_id" value="<?php echo $task_group['UserTaskGroup']['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#condition">前置条件</a></li>
                    <li><a href="#task">任务列表</a></li>
                </ul>
            </div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
				<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />
				<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
			</div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" name="data[UserTaskGroup][name]" id="name" value="<?php echo $task_group['UserTaskGroup']['name'];?>"></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['start_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserTaskGroup][start_time]" value="<?php echo $task_group['UserTaskGroup']['start_time'];?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['end_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserTaskGroup][end_time]" value="<?php echo $task_group['UserTaskGroup']['end_time'];?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio-inline"><input type="radio" name="data[UserTaskGroup][status]" <?php if($task_group['UserTaskGroup']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[UserTaskGroup][status]" <?php if($task_group['UserTaskGroup']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Condition_pancel'}">前置条件&nbsp;</h4>
            </div>
            <div id="Condition_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="condition" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <?php if(count($condition_code)<3){?>
                        <p style="text-align:right;">
                            <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_condtion();">
                                <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
                            </a>
                        </p>
                    <?php }?>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th>条件类型</th>
                            <th>条件值</th>
                            <th><?php echo $ld['operate']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($task_condition) && sizeof($task_condition)>0){foreach($task_condition as $k=>$v){ ?>
                            <tr >
                                <td><?php echo $condition_resource[$v['UserTaskCondition']['params']]; ?></td>
                                <td><?php echo $v['UserTaskCondition']['value'];?></td>
                                <td>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_task_conditions/view/'.$v['UserTaskCondition']['id']); ?>">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_task_conditions/remove/<?php echo $v['UserTaskCondition']['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Task_pancel'}">任务列表&nbsp;</h4>
            </div>
            <div id="Task_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="task" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <p style="text-align:right;">
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="show()">
                            <span class="am-icon-plus"></span>关联项目
                        </a>
                    </p>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo $ld['name'];?></th>
                            <th><?php echo $ld['type'];?></th>
                            <th><?php echo $ld['operate'];?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($type_list) && sizeof($type_list)>0){foreach($type_list as $k=>$v){ ?>
                            <tr >
                                <td><?php echo $v['UserGroupRelation']['id'];?></td>
                                <td><?php echo $v['name']?></td>
                                <td><?php echo $v['UserGroupRelation']['type']?></td>
                                <td>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_task_groups/rel_remove/<?php echo $v['UserGroupRelation']['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>

<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="select_evaluation" style="width:640px;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            关联项目
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal" action="/admin/user_task_groups/set_group/<?php echo $task_group['UserTaskGroup']['id'];?>" onsubmit="return groups_check()">
                <div class="am-form-group">
                    <table id="addr-tables" class="listtable" style="border-collapse:separate; border-spacing:5px;">
                        <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th style="padding-left:60px;">类型</th>
                            <th style="padding-left:60px;">类型编号</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <a href="javascript:;" onclick="addaddr(this)">[+]</a></td>
                            <td>
                                <select name="type[]" onchange="changeType(this)">
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                    <?php if(isset($task_resource) && sizeof($task_resource)>0){
                                        foreach ($task_resource as $tid=>$t){?>
                                            <option value="<?php echo $tid;?>"><?php echo $t;?></option>
                                        <?php }}?>
                                </select>
                            </td>
                            <td><input style="width:80%" type="text" name="type_id[]" value=""/></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div><button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button></div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_condtion">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">添加前置条件</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[UserTaskCondition][task_group_id]" value="<?php echo $task_group['UserTaskGroup']['id'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">条件类型</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                            <select id="params" name="data[UserTaskCondition][params]" onchange="changeType(this)">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($condition_resource as $tid=>$t){ if(!in_array($tid,$condition_code)){?>
                                    <option value="<?php echo $tid;?>"><?php echo $t;?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group" id="default_condition_value" style="display: none;">
                    	<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                    	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                    		<input type='text' name="data[UserTaskCondition][value]" value="" />
                    	</div>
                    </div>
                    <div class="am-form-group" id="ability_level_table" style="display: none;">
                        <table class="am-table">
                			<tr>
	                        <?php foreach($level_list as $lv_k=>$lv_v){?>
	                            <td><?php echo $lv_k."<br/>";
	                            	foreach($lv_v as $lv_kk=>$lv_vv){?>
	                            	<label class="am-radio-inline" style="margin-left:0px;"><input type="radio" name="data[UserTaskCondition][ability_level][<?php echo $lv_k;?>]" value="<?php echo $lv_vv['AbilityLevel']['id'];?>"/><?php echo $lv_vv["Ability"]["name"].$lv_vv["AbilityLevel"]["name"];?></label>
	                                <?php }?>
	                            </td>
	                        <?php }?>
                        	</tr>
                        </table>
                    </div>
                    <div class="am-form-group">
                    	<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                    	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left">
                        		<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                        	</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function show(){
        $("#select_evaluation").modal("open");
    }

    function add_condtion(){
        $("#add_condtion").modal('open');
    }

    function addaddr(obj){
        var src = obj.parentNode.parentNode;
        var idx = rowindex(src);
        var tbl = document.getElementById('addr-tables');
        var row = tbl.insertRow(idx + 1);
        var cell = row.insertCell(-1);
        var img_str = src.cells[0].innerHTML.replace(/(.*)(addaddr)(.*)(\[)(\+)/i, "$1removeaddr$3$4-");
        cell.innerHTML = img_str;
        for(var i=1;i<5;i++){
            row.insertCell(-1).innerHTML=src.cells[i].innerHTML;
        }
    }

    function removeaddr(obj){
        var row = rowindex(obj.parentNode.parentNode);
        var tbl = document.getElementById('addr-tables');
        tbl.deleteRow(row);
    }

    function set_group(){
        var type = document.getElementsByName("type[]");
        var type_id = document.getElementsByName("type_id[]");
        var type_list=new Array();
        var type_id_list=new Array();
        for(var i=0;i<type.length;i++){
            if(type[i].value!="-1" && type_id[i].value!="-1"){
                type_list.push(type[i].value);
                type_id_list.push(type_id[i].value);
            }
        }
        if(type_id_list.length != 0 && type_list.length != 0){
            $.ajax({
                url:admin_webroot+"user_task_groups/set_group/",
                type:"POST",
                data:{type:type_list,type_id:type_id_list},
                dataType:"json",
                success:function(data){
                    try{
                        alert(data.msg);
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                    window.location.href = window.location.href;
                }
            });
        }
    }

    function ajax_modify_submit(btn){
        var params_obj = document.getElementById("params");
        if(params_obj.value==""){
            alert("条件不能为空");
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"user_task_conditions/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    alert(data.message);
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function changeType(obj){
	        if(obj.value=="ability_level"){
	        	$("#default_condition_value").hide();
	            	$("#ability_level_table").show();
	        }else{
	            	$("#ability_level_table").hide();
	            	$("#default_condition_value").show();
	        }
    }

    function groups_check(){
        if($('select[name="type[]"]').val()==''){
            alert('请选择类型！');
            return false;
        }
        if($('input[name="type_id[]"]').val()==''){
            alert('类型编号不能为空！');
            return false;
        }
    }

</script>