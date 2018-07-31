<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
        </ul>
    </div>
    <?php echo $form->create('/user_task_conditions',array('action'=>'view/'.$task_group["UserTaskCondition"]["id"],'id'=>'task_condition_edit_form','name'=>'task_group_edit','type'=>'POST','onsubmit'=>""));?>
    <input type="hidden" name="data[UserTaskCondition][id]" id="_id" value="<?php echo $task_group['UserTaskCondition']['id'];?>" />
    <div class="am-panel-group admin-content" id="accordion">
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
            <button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 编辑按钮区域 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-padding-top-xs"><input type="hidden" name="data[UserTaskCondition][params]" value="<?php echo $task_group['UserTaskCondition']['params'];?>"><?php echo @$condition_resource[$task_group['UserTaskCondition']['params']];?></label>
                	   <div class="am-u-lg-8 am-u-lg-8 am-u-lg-8">
                		<table class="am-table">
                			<tr>
	                        <?php if($task_group['UserTaskCondition']['params']=="ability_level"){
	                            foreach($level_list as $lv_k=>$lv_v){?>
	                            <td><?php echo $lv_k."<br/>";
	                            	foreach($lv_v as $lv_kk=>$lv_vv){?>
	                            	<label class="am-radio-inline" style="margin-left:0px;"><input type="radio" name="data[UserTaskCondition][value][<?php echo $lv_k;?>]" <?php if(in_array($lv_vv["AbilityLevel"]["id"],$condition_array)) echo 'checked';?> value="<?php echo $lv_vv['AbilityLevel']['id'];?>"/><?php echo $lv_vv["Ability"]["name"].$lv_vv["AbilityLevel"]["name"];?></label>
	                                <?php }?>
	                            </td>
	                            <?php }}else{ ?>
	                            <td><input type='text' name="data[UserTaskCondition][value]" value="<?php echo $task_group['UserTaskCondition']['value'];?>" /></td>
	                            <?php } ?>
                        	</tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
</div>