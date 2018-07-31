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
    <?php echo $form->create('/user_task_conditions',array('action'=>'add/'.$code,'id'=>'task_condition_add_form','name'=>'task_condition_add','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
    <input type="hidden" name="data[UserTaskCondition][id]" id="_id" value="" />
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
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">条件类型</label>
                        <div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
                            <select id="params" name="data[UserTaskCondition][params]" onchange="changeType(this)">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($condition_resource as $tid=>$t){ if(!in_array($tid,$condition_code)){?>
                                    <option value="<?php echo $tid;?>"><?php echo $t;?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group" id="change2" style="display: none;">
                        <?php foreach($level_list as $lv_k=>$lv_v){?>
                            <label class="am-radio-inline"><input type="radio" name="data[UserTaskCondition][value]" value="<?php echo $lv_v['AbilityLevel']['id'];?>"/><?php echo $lv_v["Ability"]["name"].$lv_v["AbilityLevel"]["name"];?></label>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
</div>
<script>
    function chechk_form(){
        var params_obj = document.getElementById("params");
        if(params_obj.value==""){
            alert("条件不能为空");
            return false;
        }
        return true;
    }

    function changeType(obj){
        $("input[name='data[UserTaskCondition][value][]']").each(function(){
            $(this).attr("checked",false);
        });
        $("input[name='data[UserTaskCondition][value]']").val("0");
        if(obj.value=="ability_level"){
            $("#change2").show();
        }else{
            $("#change2").hide();
        }
    }
</script>