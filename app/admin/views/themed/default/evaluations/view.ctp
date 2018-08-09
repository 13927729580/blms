<style type='text/css'>
.am-form-horizontal .am-form-label{text-align:left;}
#view_question,#add_condtion{top:50%;}
#view_question form,#add_condtion form{max-height:500px;overflow-y:scroll;}
[data-am-widget=tabs] .am-tabs-nav{width:20%}
.am-tabs-default .am-tabs-nav > .am-active a {background-color: #5eb95e;border-color: #5eb95e;color: #fff;}
.am-radio-inline{padding-top: 0!important;}
#Question_pancel table.am-table td pre{padding:0px;border:none;margin:0 auto;color:#333;background:none;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;font-family:inherit;}
#Question_pancel table.am-table td pre *{margin:0px;padding:0px;white-space: normal!important;}
#Question_pancel table.am-table td pre p ,#Question_pancel table.am-table td pre p>*{overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;white-space: normal!important;}
#Question_pancel table.am-table td pre p:nth-child(n+2){display:none;}
</style>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/evaluations',array('action'=>'view/'.$evaluation_info["Evaluation"]["id"],'id'=>'evaluation_edit_form','name'=>'evaluation_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return check_all();"));?>
        <input type="hidden" name="data[Evaluation][id]" id="_id" value="<?php echo $evaluation_info['Evaluation']['id'];?>" />
    	  <input type='hidden' id='evaluation_code' value="<?php echo $evaluation_info['Evaluation']['code'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#condition">前置条件</a></li>
                    <li><a href="#question">题目列表</a></li>
                    <?php if($svshow->operator_privilege("user_evaluation_result")){ ?>
                    <li><a href="#user_log">用户评测记录</a></li>
                    <?php } ?>
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">课程级别</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5" style="margin-top: 5px;">
                            <?php if(isset($evaluation_info['Evaluation']['user_id'])&&$evaluation_info['Evaluation']['user_id']==0){echo '系统级别';}else{echo '个人级别';} ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">评测类型</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <select id="evaluation_category_code" name="data[Evaluation][evaluation_category_code]">
                                <?php if(empty($evaluation_info['Evaluation']['evaluation_category_code'])&&sizeof($evaluation_category)>1){?>
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                <?php }?>
                                <?php foreach ($evaluation_category as $tid=>$t){ ?>
                                    <option value="<?php echo $t['EvaluationCategory']['code'];?>" <?php if($evaluation_info['Evaluation']['evaluation_category_code']==$t['EvaluationCategory']['code'])echo "selected"?>><?php echo $t['EvaluationCategory']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">可见性</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
				<label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="0" <?php if($evaluation_info['Evaluation']['visibility']==0)echo "checked"?>>公开</label>
				<label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="2" <?php if($evaluation_info['Evaluation']['visibility']==2)echo "checked"?>>限定</label>
				<label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="1" <?php if($evaluation_info['Evaluation']['visibility']==1)echo "checked"?>>仅自己</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
				<label class="am-radio-inline am-success"><input data-am-ucheck  type="radio" name="data[Evaluation][evaluation_type]" <?php if($evaluation_info['Evaluation']['evaluation_type'] == 0){?>checked="checked"<?php }?> value="0"/>练习</label>
				<label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][evaluation_type]" <?php if($evaluation_info['Evaluation']['evaluation_type'] == 1){?>checked="checked"<?php }?> value="1"/>考试</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" onchange="check_code(this)" name="data[Evaluation][code]" id="code" value="<?php echo $evaluation_info['Evaluation']['code'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[Evaluation][name]" id="name" value="<?php echo $evaluation_info['Evaluation']['name'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">图片</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input id="img" type="text" name="data[Evaluation][img]" value="<?php echo $evaluation_info['Evaluation']['img'];?>" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('img')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image($evaluation_info['Evaluation']['img'],array('id'=>'show_img'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">评测时间(分钟,0:无限制)</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="evaluation_time" name="data[Evaluation][evaluation_time]" value="<?php echo $evaluation_info['Evaluation']['evaluation_time'];?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">离开界面限制次数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="blur_time_limit" name="data[Evaluation][blur_time_limit]" value="<?php echo $evaluation_info['Evaluation']['blur_time_limit'];?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">及格分数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="pass_score" name="data[Evaluation][pass_score]" value="<?php echo $evaluation_info['Evaluation']['pass_score'];?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">点击数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Evaluation][clicked]" value="<?php echo $evaluation_info['Evaluation']['clicked'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline am-success"><input type="radio" data-am-ucheck name="data[Evaluation][status]" <?php if($evaluation_info['Evaluation']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline am-success"><input type="radio" data-am-ucheck name="data[Evaluation][status]" <?php if($evaluation_info['Evaluation']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">显示正确答案</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline am-success"><input type="radio" data-am-ucheck  name="data[Evaluation][show_right_answer]" <?php if($evaluation_info['Evaluation']['show_right_answer'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
                            <label class="am-radio-inline am-success"><input type="radio" data-am-ucheck  name="data[Evaluation][show_right_answer]" <?php if($evaluation_info['Evaluation']['show_right_answer'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        	<?php echo $this->element('editor',array('editorName'=>"data[Evaluation][description]",'editorId'=>'elm','editorValue'=>isset($evaluation_info['Evaluation']['description'])?$evaluation_info['Evaluation']['description']:'')); ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">价格</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="price" name="data[Evaluation][price]" value="<?php echo $evaluation_info['Evaluation']['price'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
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
                    <?php if(!isset($condition_resource)||empty($condition_resource)||(isset($condition_resource)&&sizeof($condition_resource)>count($condition_code))){?>
                        <p style="text-align:right;">
                            <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_precondtion(this,'<?php echo $evaluation_info['Evaluation']['code']; ?>');">
                                <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
                            </a>
                        </p>
                    <?php } ?>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th>条件类型</th>
                            <th>条件值</th>
                            <th><?php echo $ld['operate']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($evaluation_condition) && sizeof($evaluation_condition)>0){foreach($evaluation_condition as $k=>$v){ ?>
                            <tr >
                                <td><?php echo isset($condition_resource[$v['Precondition']['params']])?$condition_resource[$v['Precondition']['params']]:$v['Precondition']['params']; ?></td>
                                <td><?php if($v['Precondition']['params']=="parent_evaluation"){echo $parent_name;}else if($v['Precondition']['params']=="ability_level"){echo isset($ability_level_list)?implode(',',$ability_level_list):$v['Precondition']['value'];}else{echo $v['Precondition']['value'];}?></td>
                                <td>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_precondtion(<?php echo $v['Precondition']['id']?>);">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'preconditions/remove/<?php echo $v['Precondition']['id'] ?>');">
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
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Question_pancel'}">题目列表&nbsp;</h4>
            </div>
            <div id="Question_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="question" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <p style="text-align:right;">
                        <a class="mt am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" href="<?php echo $html->url('/evaluation_questions/download_csv_example/'.$evaluation_info['Evaluation']['code']); ?>">
                            导出题目
                        </a>
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="upload_question();">
                            <span class="am-icon-plus"></span>&nbsp;题库上传
                        </a>
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_rule();">
                            <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>规则
                        </a>
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="view_question(0);">
                            <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>题目
                        </a>
                    </p>
               	<?php if(isset($info_resource['question_type'])&&!empty($info_resource['question_type']))ksort($info_resource['question_type']); ?>
		            <div data-am-widget="tabs" class="am-tabs am-tabs-default">
						<ul class="am-tabs-nav am-cf">
							<?php if(isset($info_resource['question_type'])){foreach($info_resource['question_type'] as $info_k=>$info_v){?>
							<li class="<?php if($info_k==0){?>am-active<?php }?>"><a href="[data-tab-panel-<?php echo $info_k;?>]"><?php echo $info_v;?></a></li>
							<?php }} ?>
						</ul>
						<div class="am-tabs-bd">
					    <?php if(isset($info_resource['question_type'])){foreach($info_resource['question_type'] as $info_k=>$info_v){?>
					    	<div data-tab-panel-0 class="am-tab-panel <?php if($info_k==0){?>am-active<?php }?>">
					        	<table class="am-table  table-main">
		                            <thead>
			                            <tr>
								<th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;line-height: 1.6;padding-top: 0;display:inline-block;"><input onclick='listTable.selectAll(this,"checkboxes_<?php echo $info_k;?>[]")' data-am-ucheck  type="checkbox" /><?php echo $ld['code']?></label></th>
								<th class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $ld['name'];?></th>
								<th class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']; ?></th>
								<th class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['sort']; ?></th>
								<th class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></th>
			                            </tr>
		                            </thead>
		                            <tbody>
		                            <?php if(isset($evaluation_rule_info) && sizeof($evaluation_rule_info)>0){foreach($evaluation_rule_info as $k=>$v){
		                                if($v['EvaluationRule']['question_type']!=$info_k){continue;}?>
		                                <tr>
		                                    <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['EvaluationRule']['child_evaluation_code']; ?></td>
		                                    <td class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $v['Evaluation']; ?>（<?php echo $v['EvaluationRule']['proportion']; ?>题）</td>
		                                    <td class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;</td>
		                                    <td class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['EvaluationRule']['orderby']; ?></td>
		                                    <td class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left">
		                                        <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_rule(<?php echo $v['EvaluationRule']['id']; ?>);">
		                                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
		                                        </a>
		                                        <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'evaluation_rules/remove/<?php echo $v['EvaluationRule']['id'] ?>');">
		                                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
		                                        </a>
		                                    </td>
		                                </tr>
		                            <?php }}
		                            if(isset($evaluation_question_info) && sizeof($evaluation_question_info)>0){foreach($evaluation_question_info as $k=>$v){
		                                if($v['EvaluationQuestion']['question_type']!=$info_k){continue;}?>
		                                <tr>
		                                    <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;padding-top: 0;display:inline-block;"><input type="checkbox" name="checkboxes_<?php echo $info_k;?>[]" value="<?php echo $v['EvaluationQuestion']['code']?>"  data-am-ucheck /><?php echo $v['EvaluationQuestion']['code']; ?></label></td>
		                                    <td class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-text-left"><pre><?php echo strip_tags(preg_replace("/(<style.*?<\/style>)/is",'',$v['EvaluationQuestion']['name'])); ?></pre></td>
		                                    <td class="am-u-lg-1 am-u-md-1 am-u-sm-1">
		                                        <?php if ($v['EvaluationQuestion']['status'] == 1) {?>
		                                            <span class="am-icon-check am-yes"></span>
		                                        <?php }elseif($v['EvaluationQuestion']['status'] == 0){ ?>
		                                            <span class="am-icon-close am-no"></span>
		                                        <?php } ?>
		                                    </td>
		                                    <td class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['EvaluationQuestion']['orderby']; ?></td>
		                                    <td class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left"><?php if($v['EvaluationQuestion']['question_type']=='0'||$v['EvaluationQuestion']['question_type']=='1')echo $html->link('选项','javascript:void(0);',array('class'=>'am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit','onclick'=>"view_question_option('".$v['EvaluationQuestion']['code']."')")); ?>
		                                        <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href='javascript:void(0);' onclick="view_question(<?php echo $v['EvaluationQuestion']['id']; ?>)">
		                                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
		                                        </a>
		                                        <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'evaluation_questions/remove/<?php echo $v['EvaluationQuestion']['id'] ?>');">
		                                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
		                                        </a>
		                                    </td>
		                                </tr>
		                            <?php }}?>
		                                <tr>
		                                		<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;padding-top: 0;display:inline-block;"><input onclick="listTable.selectAll(this,&quot;checkboxes_<?php echo $info_k;?>[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label></td>
		                           		     	<td class='am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left'><input type="button" id="btn" value="批量删除" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_question(<?php echo $info_k;?>)" /></td>
		                                </tr>
		                            </tbody>
		                        </table>
					    	</div>
					    <?php }} ?>
						</div>
					</div>
                </div>
            </div>
        </div>
        <?php if($svshow->operator_privilege("user_evaluation_result")){ ?>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#User_log_pancel'}">用户评测记录&nbsp;</h4>
            </div>
            <div id="User_log_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="user_log" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th>评测者姓名</th>
                            <th>评测开始时间</th>
                            <th>评测结束进度</th>
                		  <th>得分</th>
                            <th>查看</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($user_evaluation) && sizeof($user_evaluation)>0){foreach($user_evaluation as $k=>$v){ ?>
                            <tr>
                                <td><?php echo isset($v['User']['name'])?$v['User']['name']:"-"; ?></td>
                                <td><?php echo $v['UserEvaluationLog']['start_time']; ?></td>
                                <td><?php echo $v['UserEvaluationLog']['end_time']; ?></td>
                                <td><?php echo $v['UserEvaluationLog']['score']; ?></td>
                                <td>
                                    <a class="am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/user_evaluation_logs/view/'.$v['UserEvaluationLog']['id']); ?>"><span class="am-icon-eye"></span>查看</a>
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
        <?php } ?>
        <?php echo $form->end(); ?>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="upload_question">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">批量上传题目</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('/evaluation_questions',array('action'=>'preview/'.$evaluation_info['Evaluation']['code'],'class'=>' am-form am-form-horizontal',"enctype"=>"multipart/form-data"));?>
            <div class="am-panel-bd">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:21px;"><?php echo $ld['csv_file_bulk_upload']?></label>
                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                        <p style="margin:10px 0px;"><input name="evaluation_question" id="evaluation_question" size="40" type="file" style="height:22px;" onchange="checkFile()"/></p>
                        <p style="padding:6px 0;"><?php echo $ld['articles_upload_file_encod']?></p>
                    </div>
                </div>
                <?php if(isset($profile_info['Profile'])){?>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                <?php echo $html->link($ld['download_example_batch_csv'],"/evaluation_questions/download_csv_example/",'',false,false);?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div class="am-text-left">
                    <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_rule">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="rule_title">添加规则</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[EvaluationRule][id]" id="rule_id" value="">
                <input type="hidden" name="data[EvaluationRule][evaluation_code]" value="<?php echo $evaluation_info['Evaluation']['code'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">所选题库</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <select id="child_evaluation_code" name="data[EvaluationRule][child_evaluation_code]">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($evaluation_list as $tid=>$t){ ?>
                                    <option value="<?php echo $t['Evaluation']['code'];?>"><?php echo $t['Evaluation']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-left am-padding-top-xs">
                        	<?php if(isset($info_resource['question_type'])){foreach($info_resource['question_type'] as $info_k=>$info_v){?>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationRule][question_type]" value="<?php echo $info_k; ?>"/><?php echo $info_v; ?></label>
                        	<?php }} ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">题目数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="proportion" name="data[EvaluationRule][proportion]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">每题分值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="score" name="data[EvaluationRule][score]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['sort'] ?></label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="rule_orderby" name="data[EvaluationRule][orderby]" value="50"/></div>
                    </div>
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_rule_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="view_precondtion">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
		<h4 class="am-popup-title">前置条件</h4>
		<span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            	
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="view_question">
	<div class="am-modal-dialog">
        <div class="am-modal-hd">
		<h4 class="am-popup-title">题目</h4>
		<span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
             
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="view_question_option">
	<div class="am-modal-dialog">
        <div class="am-modal-hd">
		<h4 class="am-popup-title">题目选项</h4>
		<span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
             
        </div>
    </div>
</div>
<script type="text/javascript">
    var code_check=true;
    function check_code(obj){
        code_check=false;
        var code=obj.value;
        if(code==""){return false;}
        if(code!="<?php echo $evaluation_info['Evaluation']['code'];?>"){
            $.ajax({url: admin_webroot+"evaluations/check_code",
                type:"POST",
                data:{'code':code},
                dataType:"json",
                success: function(data){
                    try{
                        if(data.code==1){
                            code_check=true;
                        }else{
                            alert(data.msg);
                        }
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                }
            });
        }else{
            code_check=true;
        }
    }

    function check_all(){
        if(code_check==false){
            alert("code已存在");
            return false;
        }
        var name_obj = document.getElementById("name");
        var code_obj = document.getElementById("code");
        if(code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        if(name_obj.value==""){
            alert("标题不能为空");
            return false;
        }
        return true;
    }

    //批量操作
    function batch_question(code){
        var bratch_operat_check = document.getElementsByName("checkboxes_"+code+"[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(checkboxes.length != 0){
            if(confirm("确定删除吗？")){
                $.ajax({
                    url:admin_webroot+"evaluation_questions/delete_all/",
                    type:"POST",
                    data:{ids:checkboxes},
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
    }

    function add_rule(){
        $("#rule_title").html("添加规则");
        $("#rule_id").val("");
        $("#proportion").val("0");
        $("#score").val("0");
        $("#rule_orderby").val("50");
        $("#add_rule select[name='data[EvaluationRule][child_evaluation_code]'] option:eq(0)" ).attr('selected',true);
        $("#add_rule .am-radio-inline input[type='radio']:checked").prop('checked',false);
        $("#add_rule .am-radio-inline input[type='radio'][value='0']").prop('checked',true);
        $("#add_rule").modal('open');
    }

    function edit_rule(id){
        $.ajax({
            url: admin_webroot+"evaluation_rules/ajax_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
			$("#add_rule .am-radio-inline input[type='radio']:checked").prop('checked',false);
			$("#add_rule .am-radio-inline input[type='radio'][value='"+data.data.EvaluationRule.question_type +"']").prop('checked',true);
			$("#add_rule select[name='data[EvaluationRule][child_evaluation_code]'] option[value='"+data.data.EvaluationRule.child_evaluation_code+"']" ).attr('selected',true);
			$("#rule_title").html("编辑规则");
			$("#rule_id").val(id);
			$("#proportion").val(data.data.EvaluationRule.proportion);
			$("#score").val(data.data.EvaluationRule.score);
			$("#rule_orderby").val(data.data.EvaluationRule.orderby);
			$("#add_rule").modal('open');
                }else{
                     alert(data.message);
                }
            }
        });
    }

    function view_question(question_id){
    		var evaluation_code=$("#evaluation_code").val();
    		var postData={'evaluation_code':evaluation_code};
    		if(question_id==0||question_id==''){
    			var question_type_href=$("#Question_pancel ul.am-tabs-nav li.am-active a").attr('href');
    			var type_href_reg = "\\[(.+?)\\]";
    			var type_href_reg_list=question_type_href.match(type_href_reg);
    			question_type_href=typeof(type_href_reg_list[1])!='undefined'?type_href_reg_list[1]:question_type_href;
    			var default_question_type_list=question_type_href.split('-');
    			var default_question_type='0';
    			if(default_question_type_list.length>0)default_question_type=default_question_type_list[default_question_type_list.length-1];
    			postData={'evaluation_code':evaluation_code,'question_type':default_question_type};
    		}
    		var evaluation_code=$("#evaluation_code").val();
    		$.ajax({
	            url: admin_webroot+"evaluation_questions/view/"+question_id,
	            type:"GET",
	            data:postData,
	            dataType:"html",
	            success: function(result){
	            	$('#view_question .am-modal-bd').html(result);
	            	$("#view_question").modal('open');
	            	$("#view_question .am-modal-bd select").selected();
	            }
	        });
    }
    
    function view_question_option(question_code){
    		var evaluation_code=$("#evaluation_code").val();
    		$.ajax({
	            url: admin_webroot+"evaluation_options/view",
	            type:"GET",
	            data:{'evaluation_code':evaluation_code,'question_code':question_code},
	            dataType:"html",
	            success: function(result){
	            	$('#view_question_option .am-modal-bd').html(result);
	            	$("#view_question_option").modal('open');
	            	$("#view_question_option .am-modal-bd input[type='checkbox']").uCheck();
	            }
	        });
    }

    function upload_question(){
        $("#upload_question").modal('open');
    }

    function ajax_modify_submit(btn){
        var params_obj = document.getElementById("params");
        if($("#condtion_title").html()=="添加前置条件"){
	        if(params_obj.value==""){
	            alert("条件类型不能为空");
	            return false;
	        }
	        if($("#value").val()=="" && params_obj.value=="cycle"){
	            alert("条件值不能为空");
	            return false;
	        }
        	if($("#evaluation_select").html()=="" && params_obj.value=="parent_evaluation"){
	            alert("前置评测不能为空");
	            return false;
	        }
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"evaluation_conditions/ajax_modify",
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

    function ajax_modify_rule_submit(btn){
    	var rule_code=$("#child_evaluation_code").val();
    	if(rule_code==""){
    		alert("请选择题库");
    		return false;
    	}
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"evaluation_rules/ajax_modify",
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
        $("input[name='data[EvaluationCondition][value][]']").each(function(){
            $(this).prop("checked",false);
        });
        $("input[name='data[EvaluationCondition][value]']").val("0");
        if(obj.value=="cycle"){
            $("#change2").hide();
            $("#change1").show();
            $("#change3").hide();
        }else if(obj.value=="ability_level"){
            $("#change1").hide();
            $("#change2").show();
            $("#change3").hide();
        }else if(obj.value=="parent_evaluation"){
            $("#change1").hide();
            $("#change2").hide();
            $("#change3").show();
        }else{
            $("#change1").hide();
            $("#change2").hide();
            $("#change3").hide();
        }
    }

    function searchevaluation(){
        var condition_id = document.getElementById("cond_id").value;
        var evaluation_keyword = document.getElementById("evaluation_keyword");//搜索关键字
        var sUrl = admin_webroot+"evaluation_conditions/searchEvaluation/";//访问的URL地址
        if(evaluation_keyword.value!=""){
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {evaluation_keyword:evaluation_keyword.value,condition_id:condition_id},
                success: function (result) {
                    if(result.flag=="1"){
                        var evaluation_select_sel = document.getElementById('evaluation_select');
                        evaluation_select_sel.innerHTML = "";
                        if(result.content){
                            var selhtml="";
                            for(i=0;i<result.content.length;i++){
                                selhtml+="<dl onclick=\"add_relation_evaluation('"+result.content[i]['Evaluation'].id+"')\"><span class='am-icon-plus'></span>"+result.content[i]['Evaluation'].name+"</dl>";
                            }
                            evaluation_select_sel.innerHTML = selhtml;
                        }
                        return;
                    }
                    if(result.flag=="2"){
                        alert(result.content);
                    }
                }
            });
        }
    }

    function add_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("cond_id").value;
        var code="<?php echo $evaluation_info['Evaluation']['code'];?>";
        var newhtml = "";
        var sUrl = admin_webroot+"evaluation_conditions/add_relation_evaluation/";//访问的URL地址
        $.ajax({
            type: "POST",
            url:sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id,code:code},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    $("#cond_id").val(result.condition_id);
                    return;
                }
                if(result.flag=="2"){
                    alert(result.content);
                }
            }
        });
    }

    function delete_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("cond_id").value;
        var sUrl = admin_webroot+"evaluation_conditions/delete_relation_evaluation/";//访问的URL地址
        var newhtml = "";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    return;
                }
                if(result.flag=="2"){
                    alert(j_failed_delete);
                }
            }
        });
    }

    function checkFile() {
        var obj = document.getElementById('file');
        var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
        if(suffix != 'csv'&&suffix != 'CSV'){
            alert("<?php echo $ld['file_format_csv']?>");
            obj.value="";
            return false;
        }
    }
    
    function add_precondtion(btn,evaluation_code){
    		$(btn).button('loading');
    		$.ajax({
			url: admin_webroot+"preconditions/add/evaluation/"+evaluation_code,
			type:"GET",
			dataType:"html",
			success:function(result){
				$("#view_precondtion .am-modal-bd").html(result);
				$("#view_precondtion .am-modal-bd select").selected({maxHeight: '100px',noSelectedText:j_please_select});
				$("#view_precondtion .am-modal-bd input[type='checkbox']").uCheck();
				$("#view_precondtion").modal('open');
			},complete:function(){
				$(btn).button('reset');
			}
    		});
    }
    
    function edit_precondtion(precondtion_id){
    		$.ajax({
			url: admin_webroot+"preconditions/view/"+precondtion_id,
			type:"GET",
			dataType:"html",
			success:function(result){
				$("#view_precondtion .am-modal-bd").html(result);
				$("#view_precondtion .am-modal-bd select").selected({maxHeight: '100px',noSelectedText:j_please_select});
				$("#view_precondtion .am-modal-bd input[type='checkbox']").uCheck();
				$("#view_precondtion").modal('open');
			}
    		});
    }
</script>