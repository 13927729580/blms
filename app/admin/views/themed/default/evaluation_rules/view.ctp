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
    <?php echo $form->create('/evaluation_rules',array('action'=>'view/'.$evaluation_rule_info["EvaluationRule"]["id"],'id'=>'evaluation_rule_edit_form','name'=>'evaluation_rule_edit','type'=>'POST','onsubmit'=>""));?>
    <input type="hidden" name="data[EvaluationRule][id]" id="_id" value="<?php echo $evaluation_rule_info['EvaluationRule']['id'];?>" />
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
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">所选题库</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select id="child_evaluation_code" name="data[EvaluationRule][child_evaluation_code]">
                                <?php if(empty($evaluation_rule_info['EvaluationRule']['child_evaluation_code'])&&sizeof($evaluation_list)>1){?>
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                <?php }?>
                                <?php foreach ($evaluation_list as $tid=>$t){ ?>
                                    <option value="<?php echo $t['Evaluation']['code'];?>"><?php echo $t['Evaluation']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationRule][question_type]" <?php if($evaluation_rule_info['EvaluationRule']['question_type'] == 1){?>checked="checked"<?php }?> value="1"/>多选</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationRule][question_type]" <?php if($evaluation_rule_info['EvaluationRule']['question_type'] == 0){?>checked="checked"<?php }?> value="0"/>单选</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">题目数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="proportion" name="data[EvaluationRule][proportion]" value="<?php echo @$evaluation_rule_info['EvaluationRule']['proportion'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">每题分值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="score" name="data[EvaluationRule][score]" value="<?php echo @$evaluation_rule_info['EvaluationRule']['score'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
</div>
<style type="text/css">
    .am-g.admin-content{margin:0 auto;}
    .am-form-label{text-align:right;}
    .am-form .am-form-group:last-child{margin-bottom:0;}
    #rank_operator select{width:50%;}
    #rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
    #rank_operator input[type="button"]{margin-right:1.2rem;}
</style>