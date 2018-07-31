<style>
    #add_option form{max-height:300px;overflow-y:scroll;}
    .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
        background-color: transparent;
        display: inline-table;
        left: 0;
        margin: 0;
        position: absolute;
        top: 5px;
        transition: color 0.25s linear 0s;
    }
    .am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
    td.am-u-lg-3.am-u-md-2.am-u-sm-2{margin:0px;padding:10px!important;}
    td.am-u-lg-3.am-u-md-2.am-u-sm-2 label{padding-right:0;padding-top:0;}
    td.am-u-lg-2.am-u-md-2.am-u-sm-2{margin:0px;padding:10px!important;}
    .am-u-lg-2.am-u-md-3.am-u-sm-12{margin-bottom: 5px;text-align: left;}
    .am-u-lg-6.am-u-md-2.am-u-sm-2{margin:0;padding:10px!important;}
    td{border-top: 0px!important;}
    th{border-top: 0px!important;}
    .am-checkbox.am-success{padding-top: 0px;}
    <?php if($organizations_id!=''){ ?>
    .am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
    .am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
    .am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
    .am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
    <?php } ?>
</style>
<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-g am-g-fixed">
	<?php if($organizations_id!=''){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>

	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-product <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" style="font-size: 1.25rem;">
	    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	        <?php if($organizations_id!=''){$aa = '?organizations_id='.$organizations_id;}else{$aa = '';} ?>
	        <?php echo $form->create('/evaluations',array('action'=>'evaluation_questions_view/'.$evaluation_question_info["EvaluationQuestion"]["id"].$aa,'id'=>'evaluation_question_edit_form','class'=>'am-form am-form-horizontal','name'=>'evaluation_question_edit','type'=>'POST','onsubmit'=>"return check_all();"));?>
	        <input type="hidden" name="data[EvaluationQuestion][id]" id="_id" value="<?php echo $evaluation_question_info['EvaluationQuestion']['id'];?>" />
	        <div class="am-g">
	            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:50,animation:'slide-top'}">
	                <div class="am-text-right save_action">
	                    <button type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius">确认</button>
	                    <button type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius">重置</button>
	                </div>
	            </div>
	        </div>
	        <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	          <span style="float:left;"><?php echo $evaluation_question_info['EvaluationQuestion']['name'];?></span>
	          <div class="am-cf"></div>
	        </div>
	        <div class="am-panel am-panel-default">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}">基本信息&nbsp;</h4>
	            </div>
	            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in" style="margin-top: 20px;">
	                <div id="basic_information" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label">编码</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" onchange="check_code(this)" name="data[EvaluationQuestion][code]" id="code" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>"></div>
	                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label">题目</label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
	                            <textarea cols="30" id="elm1" name="data[EvaluationQuestion][name]" rows="10" style="width:auto;height:300px;"><?php echo $evaluation_question_info['EvaluationQuestion']['name'];?></textarea>
	                            <script>
	                                var editor;
	                                KindEditor.ready(function(K) {
	                                    editor = K.create('#elm1', {width:'100%',items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
	                                        langType : 'zh-cn',cssPath : '/css/index.css',filterMode : false});
	                                });
	                            </script>
	                        </div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label"><?php echo $ld['type'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <!-- <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" <?php if($evaluation_question_info['EvaluationQuestion']['question_type'] == 1){?>checked="checked"<?php }?> value="1"/>多选</label>
	                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" <?php if($evaluation_question_info['EvaluationQuestion']['question_type'] == 0){?>checked="checked"<?php }?> value="0"/>单选</label> -->
	                            <label class="am-radio-inline" style="padding-top: 4px;">
	                                <input type="radio" name="data[EvaluationQuestion][question_type]" value="1" data-am-ucheck <?php if($evaluation_question_info['EvaluationQuestion']['question_type'] == 1){?>checked="checked"<?php }?>>
	                                多选
	                            </label>
	                            <label class="am-radio-inline" style="padding-top: 4px;">
	                                <input type="radio" name="data[EvaluationQuestion][question_type]" value="0" data-am-ucheck checked <?php if($evaluation_question_info['EvaluationQuestion']['question_type'] == 0){?>checked="checked"<?php }?>>
	                                单选
	                            </label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label"><?php echo $ld['status'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <!-- <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" <?php if($evaluation_question_info['EvaluationQuestion']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
	                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" <?php if($evaluation_question_info['EvaluationQuestion']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label> -->

	                            <label class="am-radio-inline" style="padding-top: 4px;">
	                                <input type="radio" name="data[EvaluationQuestion][status]" value="1" data-am-ucheck <?php if($evaluation_question_info['EvaluationQuestion']['status'] == 0){?>checked="checked"<?php }?>>
	                                有效
	                            </label>
	                            <label class="am-radio-inline" style="padding-top: 4px;">
	                                <input type="radio" name="data[EvaluationQuestion][status]" value="0" data-am-ucheck checked <?php if($evaluation_question_info['EvaluationQuestion']['status'] == 0){?>checked="checked"<?php }?>>
	                                无效
	                            </label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label">正确答案</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" id="right_answer" name="data[EvaluationQuestion][right_answer]" value="<?php echo $evaluation_question_info['EvaluationQuestion']['right_answer'];?>"/></div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-form-label">题目解析</label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
	                            <textarea cols="30" id="elm2" name="data[EvaluationQuestion][analyze]" rows="10" style="width:auto;height:300px;"><?php echo $evaluation_question_info['EvaluationQuestion']['analyze'];?></textarea>
	                            <script>
	                                var editor;
	                                KindEditor.ready(function(K) {
	                                    editor = K.create('#elm2', {width:'100%',items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
	                                        langType : 'zh-cn',cssPath : '/css/index.css',filterMode : false});
	                                });
	                            </script>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="am-panel am-panel-default">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Option_pancel'}" style="position: relative;line-height: 28px;">
	                选项列表
	                <div style="position:absolute;right: 0;top: 0;">
	                    <a style="font-size: 12px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_option()">
	                        <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	                    </a>
	                </div>
	                </h4>
	            </div>
	            <div id="Option_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="option" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    
	                    <table class="am-table  table-main">
	                        <thead>
	                        <tr>
	                            <th class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;">名称</th>
	                            <th class="am-u-lg-7 am-u-md-5 am-u-sm-4" style="margin:0px;padding:10px;">描述</th>
	                            <th class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;">顺序</th>
	                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin:0px;padding:10px;">状态</th>
	                            <th class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="margin:0px;padding:10px;">操作</th>
	                            <div class="am-cf"></div>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if(isset($evaluation_option_info) && sizeof($evaluation_option_info)>0){foreach($evaluation_option_info as $k=>$v){ ?>
	                            <tr>
	                            	<input type="hidden" name="data[EvaluationOption][<?php echo $k;?>][id]" value="<?php echo $v['EvaluationOption']['id']; ?>">
	                                <td class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;"><input type="text" name="data[EvaluationOption][<?php echo $k;?>][name]" value="<?php echo $v['EvaluationOption']['name']; ?>"></td>
	                                <td class="am-u-lg-7 am-u-md-5 am-u-sm-4" style="margin:0px;padding:10px;"><input type="text" name="data[EvaluationOption][<?php echo $k;?>][description]" value="<?php echo htmlspecialchars($v['EvaluationOption']['description']); ?>"></td>
	                                <td class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;"><input type="text" name="data[EvaluationOption][<?php echo $k;?>][orderby]" value="<?php echo $v['EvaluationOption']['orderby']; ?>"></td>
	                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin:0px;padding:10px;height: 57px;">
	                                    <!-- <label class="am-radio-inline"><input type="radio" name="data[EvaluationOption][<?php echo $k;?>][status]" <?php if($v['EvaluationOption']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
	                                    <label class="am-radio-inline"><input type="radio" name="data[EvaluationOption][<?php echo $k;?>][status]" <?php if($v['EvaluationOption']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label> -->
	                                    <label class="am-checkbox" style="padding-top: 4px;">
	                                        <input type="checkbox" name="data[EvaluationOption][<?php echo $k;?>][status]" value="" data-am-ucheck  <?php if($v['EvaluationOption']['status'] == 1){?>checked="checked"<?php }?>>
	                                        有效
	                                    </label>
	                                </td>
	                                <td class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="margin:0px;padding:10px;">
	                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/evaluations/evaluation_option_remove/<?php echo $v['EvaluationOption']['id'] ?>');">
	                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                    </a>
	                                </td>
	                            </tr>
	                        <?php }}else{?>
	                            <tr><td colspan="6" align="center" style="text-align: center;width: 100%;">暂无选项</td></tr>
	                        <?php }?>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	        <?php echo $form->end(); ?>
	    </div>
	</div>
</div>
<div class="am-modal am-modal-no-btn" id="add_option">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">添加选项</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[0][EvaluationOption][evaluation_question_code]" id="question_code0" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>">
                <input type="hidden" name="data[1][EvaluationOption][evaluation_question_code]" id="question_code1" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>">
                <input type="hidden" name="data[2][EvaluationOption][evaluation_question_code]" id="question_code2" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>">
                <input type="hidden" name="data[3][EvaluationOption][evaluation_question_code]" id="question_code3" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>">
                <input type="hidden" name="data[4][EvaluationOption][evaluation_question_code]" id="question_code4" value="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>">
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr >
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;height: 43px;"><label class="am-checkbox am-success" style="padding-top:3px;"><input onclick='listTable(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />名称</label></th>
                            <th class="am-u-lg-6 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;">描述</th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;">状态</th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin:0px;padding:10px;">顺序</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style="padding:10px;clear: both;">
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="0" data-am-ucheck /><input type="text" name="data[0][EvaluationOption][name]" id="name0" value="A"></label></td>
                            <td class="am-u-lg-6 am-u-md-2 am-u-sm-2"><input type="text" name="data[0][EvaluationOption][description]" id="description0" value=""></td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <!-- <label class="am-radio-inline"><input type="radio" name="data[0][EvaluationOption][status]" value="1"/>有效</label>
                                <label class="am-radio-inline" style="margin-left: 0;"><input type="radio" name="data[0][EvaluationOption][status]" value="0"/>无效</label> -->
                                <label class="am-checkbox" style="padding-top: 4px;">
                                    <input type="checkbox" name="data[0][EvaluationOption][status]" value="1" data-am-ucheck checked="checked">
                                    有效
                                </label>
                            </td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[0][EvaluationOption][orderby]" id="orderby0" value="0"></td>
                        </tr>
                        <tr >
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="1" data-am-ucheck /><input type="text" name="data[1][EvaluationOption][name]" id="name1" value="B"></label></td>
                            <td class="am-u-lg-6 am-u-md-2 am-u-sm-2"><input type="text" name="data[1][EvaluationOption][description]" id="description1" value=""></td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <!-- <label class="am-radio-inline"><input type="radio" name="data[1][EvaluationOption][status]" value="1"/>有效</label>
                                <label class="am-radio-inline" style="margin-left: 0;"><input type="radio" name="data[1][EvaluationOption][status]" value="0"/>无效</label> -->
                                <label class="am-checkbox" style="padding-top: 4px;">
                                    <input type="checkbox" name="data[1][EvaluationOption][status]" value="1" data-am-ucheck checked="checked">
                                    有效
                                </label>
                            </td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[1][EvaluationOption][orderby]" id="orderby1" value="0"></td>
                        </tr>
                        <tr >
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="2" data-am-ucheck /><input type="text" name="data[2][EvaluationOption][name]" id="name2" value="C"></label></td>
                            <td class="am-u-lg-6 am-u-md-2 am-u-sm-2"><input type="text" name="data[2][EvaluationOption][description]" id="description2" value=""></td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <!-- <label class="am-radio-inline"><input type="radio" name="data[2][EvaluationOption][status]" value="1"/>有效</label>
                                <label class="am-radio-inline" style="margin-left: 0;"><input type="radio" name="data[2][EvaluationOption][status]" value="0"/>无效</label> -->
                                <label class="am-checkbox" style="padding-top: 4px;">
                                    <input type="checkbox" name="data[2][EvaluationOption][status]" value="1" data-am-ucheck checked="checked">
                                    有效
                                </label>
                            </td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[2][EvaluationOption][orderby]" id="orderby2" value="0"></td>
                        </tr>
                        <tr >
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="3" data-am-ucheck /><input type="text" name="data[3][EvaluationOption][name]" id="name3" value="D"></label></td>
                            <td class="am-u-lg-6 am-u-md-2 am-u-sm-2"><input type="text" name="data[3][EvaluationOption][description]" id="description3" value=""></td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <!-- <label class="am-radio-inline"><input type="radio" name="data[3][EvaluationOption][status]" value="1"/>有效</label>
                                <label class="am-radio-inline" style="margin-left: 0;"><input type="radio" name="data[3][EvaluationOption][status]" value="0"/>无效</label> -->
                                <label class="am-checkbox" style="padding-top: 4px;">
                                    <input type="checkbox" name="data[3][EvaluationOption][status]" value="1" data-am-ucheck checked="checked">
                                    有效
                                </label>
                            </td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[3][EvaluationOption][orderby]" id="orderby3" value="0"></td>
                        </tr>
                        <tr >
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="4" data-am-ucheck /><input type="text" name="data[4][EvaluationOption][name]" id="name4" value="E"></label></td>
                            <td class="am-u-lg-6 am-u-md-2 am-u-sm-2"><input type="text" name="data[4][EvaluationOption][description]" id="description4" value=""></td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <!-- <label class="am-radio-inline"><input type="radio" name="data[4][EvaluationOption][status]" value="1"/>有效</label>
                                <label class="am-radio-inline" style="margin-left: 0;"><input type="radio" name="data[4][EvaluationOption][status]" value="0"/>无效</label> -->
                                <label class="am-checkbox" style="padding-top: 4px;">
                                    <input type="checkbox" name="data[4][EvaluationOption][status]" value="1" data-am-ucheck checked="checked">
                                    有效
                                </label>
                            </td>
                            <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" name="data[4][EvaluationOption][orderby]" id="orderby4" value="0"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="option_check" class="am-text-left" style="color: red;"></div>
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function add_option(){
        $("#add_option").modal('open');
    }

    function ajax_modify_submit(btn){
		var bratch_operat_check = document.getElementsByName("checkboxes[]");
		var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        // if(checkboxes.length != 0){
        // 	alert("请选择选项")
        // 	return false;
        // }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: web_base+"/evaluations/ajax_modify_option",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                //alert('111');
                if(data.code=='1'){
                    // seevia_alert(data.message);
                    // window.location.reload();
                    $('#add_option').modal('close');
                    seevia_alert_func(jump_reload,data.message);
                }else{
                    //seevia_alert(data.message);
                    $('#option_check').text(data.message);
                }
            }
        });
    }

    var code_check=true;
    function check_code(obj){
        code_check=false;
        var code=obj.value;
        if(code==""){return false;}
        if(code!="<?php echo $evaluation_question_info['EvaluationQuestion']['code'];?>"){
            $.ajax({url: web_base+"/evaluations/check_code",
                type:"POST",
                data:{'code':code},
                dataType:"json",
                success: function(data){
                    try{
                        if(data.code==1){
                            code_check=true;
                        }else{
                            seevia_alert(data.msg);
                        }
                    }catch (e){
                        seevia_alert(j_object_transform_failed);
                    }
                }
            });
        }else{
            code_check=true;
        }
    }

    function check_all(){
        if(code_check==false){
            seevia_alert("code已存在");
            return false;
        }
        var name_obj = document.getElementById("elm1");
        var code_obj = document.getElementById("code");
        var answer_obj = document.getElementById("right_answer");
        if(code_obj.value==""){
            seevia_alert("编码不能为空");
            return false;
        }
        if(name_obj.value==""){
            seevia_alert("题目不能为空");
            return false;
        }
        if(answer_obj.value==""){
            seevia_alert("正确答案不能为空");
            return false;
        }
        return true;
    }

    function list_delete_submit(sUrl){
        var aa = function(){
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                success: function (result) {
                    if(result.flag==1){
                        //alert(result.message);
                        window.location.reload();
                    }
                    if(result.flag==2){
                        seevia_alert(result.message);
                    }
                }
            });
        }
        seevia_alert_func(aa,"确定删除？");
    }
    listTable= function(obj,chk){
        if(chk == null){
            chk = 'checkboxes';
        }
        var elems = document.getElementsByName(chk);
        for (var i=0; i < elems.length; i++){
            elems[i].checked = obj.checked;
        }
    }
</script>