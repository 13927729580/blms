<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
    .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
	    background-color: transparent;
	    display: inline-table;
	    left: 0;
	    margin: 0;
	    position: absolute;
	    top: 5px;
	    transition: color 0.25s linear 0s;
	}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
        </ul>
    </div>
    <?php echo $form->create('/evaluation_options',array('action'=>'add/'.$code,'id'=>'evaluation_option_add_form','name'=>'evaluation_option_add','type'=>'POST','onsubmit'=>""));?>
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
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;top:0px;"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /><?php echo $ld['name']?></label></th>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['description'];?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['orderby']?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr >
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="0" data-am-ucheck /><input type="text" name="data[0][EvaluationOption][name]" id="name0" value="A"></label></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><input type="text" name="data[0][EvaluationOption][description]" id="description0" value=""></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-radio-inline"><input type="radio" name="data[0][EvaluationOption][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[0][EvaluationOption][status]" value="0"/>无效</label></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[0][EvaluationOption][orderby]" id="orderby0" value="0"></td>
                            </tr>
                			<tr >
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="1" data-am-ucheck /><input type="text" name="data[1][EvaluationOption][name]" id="name1" value="B"></label></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><input type="text" name="data[1][EvaluationOption][description]" id="description1" value=""></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-radio-inline"><input type="radio" name="data[1][EvaluationOption][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[1][EvaluationOption][status]" value="0"/>无效</label></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[1][EvaluationOption][orderby]" id="orderby1" value="0"></td>
                            </tr>
                			<tr >
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="2" data-am-ucheck /><input type="text" name="data[2][EvaluationOption][name]" id="name2" value="C"></label></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><input type="text" name="data[2][EvaluationOption][description]" id="description2" value=""></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-radio-inline"><input type="radio" name="data[2][EvaluationOption][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[2][EvaluationOption][status]" value="0"/>无效</label></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[2][EvaluationOption][orderby]" id="orderby2" value="0"></td>
                            </tr>
                			<tr >
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="3" data-am-ucheck /><input type="text" name="data[3][EvaluationOption][name]" id="name3" value="D"></label></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><input type="text" name="data[3][EvaluationOption][description]" id="description3" value=""></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-radio-inline"><input type="radio" name="data[3][EvaluationOption][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[3][EvaluationOption][status]" value="0"/>无效</label></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[3][EvaluationOption][orderby]" id="orderby3" value="0"></td>
                            </tr>
                			<tr >
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="4" data-am-ucheck /><input type="text" name="data[4][EvaluationOption][name]" id="name4" value="E"></label></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><input type="text" name="data[4][EvaluationOption][description]" id="description4" value=""></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-radio-inline"><input type="radio" name="data[4][EvaluationOption][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[4][EvaluationOption][status]" value="0"/>无效</label></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[4][EvaluationOption][orderby]" id="orderby4" value="0"></td>
                            </tr>
                        </tbody>
                    </table>
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