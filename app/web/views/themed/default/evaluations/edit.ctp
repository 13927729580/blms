<style>
    #add_question form{max-height:300px;overflow-y:scroll;}
    #add_condtion form{max-height:300px;overflow-y:scroll;}
	[data-am-widget=tabs] .am-tabs-nav{width:20%;min-width: 100px;}
    .am-tabs-default .am-tabs-nav > .am-active a {
	    background-color: #5eb95e;
	    border-color: #5eb95e;
	    color: #fff;
	}
    .am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
    .am-topbar.am-container{display: none;}
    #accordion{font-size: 1.4rem;}
    .am-product label{font-weight: normal;}
    .am-selected.am-dropdown{width: 100%;}
    .am-selected-content.am-dropdown-content{width: 100%;}
    .am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{font-size: 1.4rem;}
    .am-product .scrollspy-nav ul {margin: 0;padding: 0;background: #5eb95e;}
    .am-product .scrollspy-nav li {display: inline-block;list-style: none;}
    .am-product .scrollspy-nav a.am-active {color: #fff;font-weight: 700;}
    .am-product .scrollspy-nav a {color: #fff;padding: 10px 20px;display: inline-block;}
    .listtable_div_top {border-top: 1px solid #ddd;}
    .am-modal.am-modal-no-btn.am-modal-active{margin-top: -150px!important;}
    .am-tabs-bd{border-top: 1px solid #ccc;}
    .am-form-field{padding: 0!important;}
    .am-radio-inline{padding-top: 0!important;}
    .am-u-lg-3.am-u-md-3.am-u-sm-12{margin-bottom: 5px;text-align: left;}
</style>
<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-g am-g-fixed">
	<?php
			if($organizations_id!=''){
				echo $this->element('org_menu');echo $this->element('organization_menu');
			}else{
				echo $this->element('users_menu');echo $this->element('users_offcanvas');
			}
	?>
	<div class="am-product am-u-lg-9" style="padding:0;">
	    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	        <?php if($organizations_id!=''){$aa = '?organizations_id='.$organizations_id;}else{$aa = '';} ?>
	        <?php echo $form->create('/evaluations',array('action'=>'edit/'.$evaluation_info["Evaluation"]["id"].$aa,'id'=>'evaluation_edit_form','name'=>'evaluation_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return check_all();"));?>
	        <input type="hidden" name="data[Evaluation][id]" id="_id" value="<?php echo $evaluation_info['Evaluation']['id'];?>" />
	        <div class="am-g">
				<div class="btnouter am-text-right" data-am-sticky="{top:'50px',animation:'slide-top'}" style="margin-bottom:0;">
	                <?php if(isset($this->params['url']['user_id'])==0){ ?>
					<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="确认" />
	                <?php } ?>
					<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="重置" />
				</div>
	        </div>
	        <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	          <span style="float:left;"><?php echo isset($evaluation_info['Evaluation']['name'])?$evaluation_info['Evaluation']['name']:''; ?></span>
	          <div class="am-cf"></div>
	        </div>
	        <div class="am-panel am-panel-default evaluation_detail">
	            <div class="am-panel-hd" style="font-size: 15px;">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}">基本信息&nbsp;</h4>
	            </div>
	            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="basic_information" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    <div class="am-form-group" style="margin-top: 10px;">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">评测类型</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8">
	                            <select data-am-selected="{maxHeight:100}" id="evaluation_category_code" name="data[Evaluation][evaluation_category_code]" onchange="evaluation_category_code_select(this.value)">
	                                <option value=''><?php echo $ld['please_select'];?></option>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($evaluation_category as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['EvaluationCategory']['code'];?>" <?php if($evaluation_info['Evaluation']['evaluation_category_code']==$t['EvaluationCategory']['code'])echo "selected"?>><?php echo $t['EvaluationCategory']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="evaluation_category_code_zidingyi" class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="display: none;">
	                            <input type="text" style="padding:5px;" name="evaluation_category_code_1">
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;">可见性</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="0" <?php if($evaluation_info['Evaluation']['visibility']==0)echo "checked"?>>公开</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="2" <?php if($evaluation_info['Evaluation']['visibility']==2)echo "checked"?>>限定</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="1" <?php if($evaluation_info['Evaluation']['visibility']==1)echo "checked"?>>仅自己</label>
	                        </div>
	                    </div>
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">编码</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" onchange="check_code(this)" name="data[Evaluation][code]" id="code" value="<?php echo $evaluation_info['Evaluation']['code'];?>"></div>
	                    </div> -->
	                    <input type="hidden" name="data[Evaluation][code]" id="code" value="<?php echo $evaluation_info['Evaluation']['code'];?>">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">名称</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" name="data[Evaluation][name]" id="name" value="<?php echo $evaluation_info['Evaluation']['name'];?>"></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">评测时间(分钟,0:无限制)</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><input type="text" id="evaluation_time" name="data[Evaluation][evaluation_time]" value="<?php echo $evaluation_info['Evaluation']['evaluation_time'];?>"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">离开界面限制次数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><input type="text" id="blur_time_limit" name="data[Evaluation][blur_time_limit]" value="<?php echo $evaluation_info['Evaluation']['blur_time_limit'];?>"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">及格分数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><input type="text" id="pass_score" name="data[Evaluation][pass_score]" value="<?php echo $evaluation_info['Evaluation']['pass_score'];?>"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">点击数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><input type="text" id="clicked" name="data[Evaluation][clicked]" value="<?php echo $evaluation_info['Evaluation']['clicked'];?>"/></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;"><?php echo $ld['status'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][status]" <?php if($evaluation_info['Evaluation']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][status]" <?php if($evaluation_info['Evaluation']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;">显示正确答案</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][recommend_flag]" <?php if($evaluation_info['Evaluation']['show_right_answer'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][recommend_flag]" <?php if($evaluation_info['Evaluation']['show_right_answer'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">图片</label>
	                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12 am-form-file">
	                            <div class="am-form-group am-form-file">
	                                <button type="button" class="am-btn am-btn-default am-btn-sm">
	                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
	                                <span class="" style="font-size:12px;">(推荐尺寸150*150)</span>
	                                <input type="file" multiple name="org_logo" onchange="ajax_upload_media(this,this.id)" id="org_logo">
	                                <input type="hidden" multiple name="data[Evaluation][img]" value="<?php echo $evaluation_info['Evaluation']['img']; ?>">
	                            </div>
	                            <?php if(isset($evaluation_info['Evaluation']['img'])&&$evaluation_info['Evaluation']['img']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$evaluation_info['Evaluation']['img'] ?>" data-rel="<?php echo $server_host.$evaluation_info['Evaluation']['img'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            <img src="" data-rel="" alt="" id="img_logo" style="display:none;max-width:100%;">
	                            <?php } ?>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">描述</label>
	                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12">
	                            <textarea cols="30" id="elm" name="data[Evaluation][description]" rows="10" style="width:auto;height:300px;"><?php echo @$evaluation_info['Evaluation']['description'];?></textarea>
	                            <script type='text/javascript'>
	                            var editor;
	                            KindEditor.ready(function(K) {
	                                editor = K.create('#elm', {width:'100%',
	                                    langType : 'zh-cn',filterMode : false,
	                                    items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent']
	                                });
	                            });
	                            </script>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">价格</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><input type="text" id="price" name="data[Evaluation][price]" value="<?php echo $evaluation_info['Evaluation']['price'];?>"/></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="am-panel am-panel-default evaluation_detail">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title am-active" style="position: relative;line-height: 28px;">
	                前置条件&nbsp;
	                <div style="position:absolute;right: 0;top: 0;">
	                    <a style="font-size: 12px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_condtion();">
	                        <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	                    </a>
	                </div>
	                </h4>
	            </div>
	            <div id="Condition_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="condition" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    <?php if(count($condition_code)<3){?>
	                        <p style="text-align:right;">
	                            
	                        </p>
	                    <?php }?>
	                    <table class="am-table  table-main">
	                        <thead>
	                        <tr>
	                            <th style="margin:0;width: 30%">条件类型</th>
	                            <th style="margin:0;width: 50%">条件值</th>
	                            <th style="margin:0;width: 20%">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if(isset($evaluation_condition) && sizeof($evaluation_condition)>0){foreach($evaluation_condition as $k=>$v){ ?>
	                            <tr >
	                                <td style="padding:10px 0;"><?php echo $condition_resource[$v['Precondition']['params']]; ?></td>
	                                <td style="padding:10px 0;"><?php if($v['Precondition']['params']=="parent_evaluation"){echo $parent_name;}else{echo $v['Precondition']['value'];}?></td>
	                                <td style="padding:10px 0;">
	                                    <a style="margin-top: 5px;" class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_condtion(<?php echo $v['Precondition']['id']?>);">
	                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                    </a>
	                                    <a style="margin-top: 5px;" class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/evaluations/remove_condition/<?php echo $v['Precondition']['id'] ?>');">
	                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                    </a>
	                                </td>
	                            </tr>
	                        <?php }}else{?>
	                            <tr><td colspan="6" align="center" style="width: 100%;text-align: center;">没有找到值</td></tr>
	                        <?php }?>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	        <div class="am-panel am-panel-default evaluation_detail">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" style="line-height: 28px;position: relative;">
	                    题目列表&nbsp;
	                    <div style="position:absolute;right: 0;top: 0;">
	                        <a style="font-size: 12px;" class="mt am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" href="<?php echo $html->url('/evaluation_questions/download_csv_example/'.$evaluation_info['Evaluation']['code']); ?>">
	                            导出
	                        </a>
	                        <a style="font-size: 12px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="upload_question();">
	                            <span class="am-icon-plus" style="margin-right: 5px;"></span>题库上传
	                        </a>
	                        <!-- <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_rule();">
	                            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>规则
	                        </a> -->
	                        <a style="font-size: 12px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_question();">
	                            <span class="am-icon-plus" style="margin-right: 5px;"></span><?php echo $ld['add'] ?>题目
	                        </a>
	                    </div>
	                </h4>
	            </div>
	            <div id="Question_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="question" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
			            <div data-am-widget="tabs" class="am-tabs am-tabs-default">
							<ul class="am-tabs-nav am-cf">
	                        <?php foreach($info_resource as $info_k=>$info_v){?>
	                            <li class="<?php if($info_k==0){?>am-active<?php }?>"><a href="[data-tab-panel-<?php echo $info_k;?>]" style="line-height: 35px;min-width: 50px;"><?php echo $info_v;?></a></li>
	                        <?php }?>
	                        </ul>
	                        <div class="am-tabs-bd">
	                        <?php foreach($info_resource as $info_k=>$info_v){?>
	                            <div data-tab-panel-0 class="am-tab-panel <?php if($info_k==0){?>am-active<?php }?>">
	                                <table class="am-table table-main">
	                                    <thead>
	                                        <tr>
	                                            <!-- <th style="margin:0;" class="am-u-lg-3 am-u-md-3 am-u-sm-3">编码</th> -->
	                                            <th style="margin:0;" class="am-u-lg-6 am-u-md-6 am-u-sm-4">名称</th>
	                                            <th style="margin:0;padding-left: 10px;" class="am-u-lg-2 am-u-md-2 am-u-sm-2">状态</th>
	                                            <th style="margin:0;" class="am-u-lg-4 am-u-md-4 am-u-sm-6">操作</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                    <?php //pr($evaluation_rule_info); ?>
	                                    <?php if(isset($evaluation_rule_info) && sizeof($evaluation_rule_info)>0){foreach($evaluation_rule_info as $k=>$v){
	                                        if($v['EvaluationRule']['question_type']!=$info_k){continue;}?>
	                                        <?php if(isset($v['EvaluationRule']['id'])){ ?>
	                                        <tr>
	                                            <!-- <td style="margin:0;padding:10px 0;" class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['EvaluationRule']['child_evaluation_code']; ?></td> -->
	                                            <td style="margin:0;padding:10px 0;" class="am-u-lg-6 am-u-md-6 am-u-sm-4"><?php echo $v['Evaluation']; ?>（<?php echo $v['EvaluationRule']['proportion']; ?>题）</td>
	                                            <td style="margin:0;padding:10px;" class="am-u-lg-2 am-u-md-2 am-u-sm-2"></td>
	                                            <td style="margin:0;padding:10px 0;" class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_rule(<?php echo $v['EvaluationRule']['id']; ?>);">
	                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                                </a>
	                                                <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/evaluations/remove_rule/<?php echo $v['EvaluationRule']['id'] ?>');">
	                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                                </a>
	                                            </td>
	                                        </tr>
	                                        <?php } ?>
	                                    <?php }}
	                                    if(isset($evaluation_question_info) && sizeof($evaluation_question_info)>0){foreach($evaluation_question_info as $k=>$v){
	                                        if($v['EvaluationQuestion']['question_type']!=$info_k){continue;}?>
	                                        <?php //pr($v); ?>
	                                        <tr>
	                                            <!-- <td style="margin:0;padding:10px 0;" class="am-u-lg-3 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;padding-top: 0;"><input type="checkbox" name="checkboxes_<?php echo $info_k;?>[]" value="<?php echo $v['EvaluationQuestion']['code']?>"  data-am-ucheck /><?php echo $v['EvaluationQuestion']['code']; ?></label></td> -->
	                                            <td style="margin:0;padding:10px 0;" class="am-u-lg-6 am-u-md-6 am-u-sm-4"><?php echo htmlspecialchars($v['EvaluationQuestion']['name']); ?></td>
	                                            <td style="margin:0;padding:10px;" class="am-u-lg-2 am-u-md-2 am-u-sm-2">
	                                                <?php if ($v['EvaluationQuestion']['status'] == 1) {?>
	                                                    <span class="am-icon-check am-yes"></span>
	                                                <?php }elseif($v['EvaluationQuestion']['status'] == 0){ ?>
	                                                    <span class="am-icon-close am-no"></span>
	                                                <?php } ?>
	                                            </td>
	                                            <td style="margin:0;padding:10px 0;" class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php if($organizations_id!=''){echo $html->url('evaluation_questions_view/'.$v['EvaluationQuestion']['id'].'?organizations_id='.$organizations_id);}else{echo $html->url('evaluation_questions_view/'.$v['EvaluationQuestion']['id']);} ?>">
	                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                                </a>
	                                                <!-- <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('evaluation_questions_view/'.$v['EvaluationQuestion']['id']); ?>">
	                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                                </a> -->
	                                                <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/evaluations/remove_question/<?php echo $v['EvaluationQuestion']['id'] ?>');">
	                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                                </a>
	                                            </td>
	                                        </tr>
	                                    <?php }}else{?>
	                                    <td class="am-text-center" style="padding-top: 35px;">暂无题目</td>
	                                    <?php } ?>
	                                    </tbody>
	                                </table>
	                                <div class="am-cf"></div>
	                            </div>
	                        <?php }?>
	                        </div>
						</div>
	                </div>
	            </div>
	        </div>
	        <?php echo $form->end(); ?>
	    </div>
	</div>
</div>
<div class="am-modal am-modal-no-btn" id="add_condtion">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="condtion_title">添加前置条件</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[Precondition][id]" id="cond_id" value="" />
                <input type="hidden" name="data[Precondition][object_code]" value="<?php echo $evaluation_info['Evaluation']['code'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group" id="condtion_params">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left" style="padding-left:0;margin-bottom: 5px;">条件类型</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8" style="padding-left:0;">
    						<input type="hidden" id="edit_params" name="edit_params" value=""/>
                            <select data-am-selected id='params' name="data[Precondition][params]" onchange="changeType(this)">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($condition_resource as $tid=>$t){ if(!in_array($tid,$condition_code)){?>
                                    <option value="<?php echo $tid;?>"><?php echo $t;?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group" id="change1" style="display: none;">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left" style="padding-left:0;margin-bottom: 5px;">条件值</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-8" style="padding-left:0;position: relative;"><input type="text" id="value" name="data[Precondition][value]" value=""/><span style="position: absolute;right: -10px;top: 8px;">天</span></div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-form-group" id="change2" style="display: none;">
                        <?php foreach($level_list as $lv_k=>$lv_v){?>
                            <label class="am-checkbox am-success" style="padding-top:0px">
                                <input type="checkbox" class="checkbox" name="data[Precondition][value][]" value="<?php echo $lv_v["AbilityLevel"]["id"];?>"  data-am-ucheck/>
                                <?php echo $lv_v["Ability"]["name"].$lv_v["AbilityLevel"]["name"];?>
                            </label>
                        <?php }?>
                    </div>
                    <div class="am-form-group" id="change3" style="display: none;">
                        <div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding-left: 0px;">
                            <div id="relative_evaluation" style="text-align: left!important;"></div>
                            <table class="am-table" style="margin-bottom: 10px;">
                                <tr>
                                    <td colspan="3">
                                        <input style="width:200px;float:left;margin-right:5px;margin-bottom: 5px;margin-top: 10px;" type="text" name="evaluation_keyword" id="evaluation_keyword" />
                                        <input style="margin-top: 12px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm " value="<?php echo $ld['search']?>" onclick="searchevaluation();" />
                                    </td>
                                </tr>
                            </table>
                            <div class="am-u-lg-10 am-u-md-10 am-u-sm-12">
                                <label class='am-show-sm-only'><?php //echo $ld['option_products']?></label>
                                <div id="evaluation_select" class="related_dt" style="text-align: left!important;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div id="condtion_check" style="text-align: left;color: red;"></div>
                    </div>
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="upload_question" style="font-size: 1.5rem;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">批量上传题目</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('/evaluations',array('action'=>'/preview/'.$evaluation_info['Evaluation']['code'],'class'=>' am-form am-form-horizontal',"enctype"=>"multipart/form-data"));?>
            <div class="am-panel-bd">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-form-label" style="padding-top:21px;">上传批量csv文件</label>
                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-12">

                        <div class="am-form-group am-form-file" style="margin-top: 10px;text-align: left;">
                            <button type="button" class="am-btn am-btn-default am-btn-sm">
                            <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
                            <input type="file" multiple name="evaluation_question" onchange="checkFile()" id="evaluation_question">
                            <div id="evaluation_question_text" style="margin-top: 10px;"></div>
                        </div>

                        <!-- <p style="margin:10px 0px;"><input name="evaluation_question" id="evaluation_question" size="40" type="file" style="height:22px;" onchange="checkFile()"/></p> -->
                        <p style="padding:6px 0;">注意上传文件编码格式UTF-8编码（CSV文件中一次上传数量最好不要超过1000，CSV文件大小最好不要超过500K.）</p>
                    </div>
                </div>
                <?php if(isset($profile_info['Profile'])){?>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label"></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                <?php echo $html->link('下载csv样例',"/evaluation_questions/download_csv_example/",'',false,false);?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div class="am-text-left">
                    <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">确定</button>
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
                                <?php if(isset($evaluation_list)&&sizeof($evaluation_list)>0){ foreach ($evaluation_list as $tid=>$t){ ?>
                                    <option value="<?php echo $t['Evaluation']['code'];?>"><?php echo $t['Evaluation']['name'];?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationRule][question_type]" value="0" checked/>单选</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationRule][question_type]" value="1"/>多选</label>
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
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_rule_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_question">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="question_title">添加题目</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[EvaluationQuestion][evaluation_code]" value="<?php echo $evaluation_info['Evaluation']['code'];?>">
                <div class="am-panel-bd">
                    <!-- <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">编码</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-8"><input type="text" onchange="check_code(this)" name="data[EvaluationQuestion][code]" id="question_code" value=""></div>
                    </div> -->
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">题目</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
                            <textarea cols="30" id="question_elm1" name="data[EvaluationQuestion][name]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script type="text/javascript">
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor=K;
                                    K.create("#question_elm1", {
                                            width:'98%',
                                            items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
                                            cssPath : '/css/index.css',filterMode : false,
                                            afterBlur:function () { this.sync(); }
                                        }
                                    );
                                });
                            </script>
                        </div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-8 am-text-left">
                            <!-- <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" value="0" checked/>单选</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" value="1"/>多选</label> -->
                            <label class="am-radio-inline">
                                <input type="radio" name="data[EvaluationQuestion][question_type]" value="0" data-am-ucheck>
                                单选
                            </label>
                            <label class="am-radio-inline">
                                <input type="radio" name="data[EvaluationQuestion][question_type]" value="1" data-am-ucheck checked>
                                多选
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-8 am-text-left">
                            <!-- <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="0"/>无效</label> -->
                            <label class="am-radio-inline">
                                <input type="radio" name="data[EvaluationQuestion][status]" value="1" data-am-ucheck>
                                有效
                            </label>
                            <label class="am-radio-inline">
                                <input type="radio" name="data[EvaluationQuestion][status]" value="0" data-am-ucheck checked>
                                无效
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">正确答案</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" id="right_answer" name="data[EvaluationQuestion][right_answer]" value=""/></div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">题目解析</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
                            <textarea cols="30" id="question_elm2" name="data[EvaluationQuestion][analyze]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script type="text/javascript">
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor=K;
                                    K.create("#question_elm2", {
                                            width:'98%',
                                            items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
                                            cssPath : '/css/index.css',filterMode : false,
                                            afterBlur:function () { this.sync(); }
                                        }
                                    );
                                });
                            </script>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">&nbsp;</label>
                        <div id="question_check" class="am-u-lg-6 am-u-md-6 am-u-sm-8" style="text-align: left;color: red;"></div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-text-left">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">&nbsp;</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
                            <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_question_submit(this)"><?php echo $ld['confirm']; ?></button>
                        </div> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function formsubmit(){
        var id = '<?php echo isset($evaluations_id)?$evaluations_id:''; ?>'
        var keyword=document.getElementById('keyword').value;
        //var score=document.getElementById('score').value;
        var start_score_time = document.getElementsByName('start_score_time')[0].value;
        var end_score_time = document.getElementsByName('end_score_time')[0].value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "status="+status+"&keyword="+keyword+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&start_score_time="+start_score_time+"&end_score_time="+end_score_time;
        window.location.href = encodeURI(web_base+"/evaluations/edit/"+id+"?"+url);
    }

    function cla(btn){
        $(btn).prev().val('');
    }

    var code_check=true;
    function check_code(obj){
        code_check=false;
        var code=obj.value;
        if(code==""){return false;}
        if(code!="<?php echo $evaluation_info['Evaluation']['code'];?>"){
            $.ajax({url: web_base+"/evaluations/ajax_check_code",
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
    function add_condtion(){
        $("#change1").hide();
        $("#change2").hide();
        $("#change3").hide();
        $("#add_condtion select[name='data[Precondition][params]'] option:eq(0)" ).attr('selected',true);
        $("#add_condtion select[name='data[Precondition][params]']").trigger('changed.selected.amui');
        $("#relative_evaluation").html("");
        $("#condtion_title").html("添加前置条件");
        $("#condtion_params").show();
        $("#add_condtion .am-checkbox input").attr('checked',false);
        $("#value").val("");
        $("#edit_params").val("");
        $("#add_condtion").modal('open');
    }

    function changeType(obj){
        $("input[name='data[Precondition][value][]']").each(function(){
            $(this).attr("checked",false);
        });
        $("input[name='data[Precondition][value]']").val("0");
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

    function ajax_modify_submit(btn){
        var params_obj = document.getElementById("params");
        if($("#condtion_title").html()=="添加前置条件"){
            if(params_obj.value==""){
                $('#condtion_check').text("条件类型不能为空");
                return false;
            }
            if($("#value").val()=="" && params_obj.value=="cycle"){
                $('#condtion_check').text("条件值不能为空");
                return false;
            }
            if($("#evaluation_select").html()=="" && params_obj.value=="parent_evaluation"){
                $('#condtion_check').text("前置评测不能为空");
                return false;
            }
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: web_base+"/evaluations/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    // seevia_alert(data.message);
                    // window.location.reload();
                    $('#add_condtion').modal('close');
                    seevia_alert_func(jump_reload,data.message);
                }else{
                    // seevia_alert(data.message);
                    $('#condtion_check').text(data.message);
                }
            }
        });
    }

    function add_rule(){
        $("#rule_title").html("添加规则");
        $("#rule_id").val("");
        $("#proportion").val("0");
        $("#score").val("0");
        $("#add_rule select[name='data[EvaluationRule][child_evaluation_code]'] option:eq(0)" ).attr('selected',true);
        $("#add_rule select[name='data[EvaluationRule][child_evaluation_code]']").trigger('changed.selected.amui');
        $("#add_rule .am-radio-inline input[value='0']").attr('checked',true);
        $("#add_rule").modal('open');
    }

    function ajax_modify_rule_submit(btn){
        var rule_code=$("#child_evaluation_code").val();
        if(rule_code==""){
            seevia_alert("请选择题库");
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: web_base+"/evaluations/ajax_modify_rule",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    seevia_alert(data.message);
                    window.location.reload();
                }else{
                    seevia_alert(data.message);
                }
            }
        });
    }

    function add_question(){
        $("#add_question").modal('open');
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

    function ajax_modify_question_submit(btn){
        //console.log($('textarea[name="data[EvaluationQuestion][analyze]"]'));
        if($('textarea[name="data[EvaluationQuestion][name]"]').val()==''){
            $('#question_check').text('题目不能为空！');
            return false;
        }
        if($('input[name="data[EvaluationQuestion][right_answer]"]').val()==''){
            $('#question_check').text('正确答案不能为空！');
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: web_base+"/evaluations/ajax_modify_question",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    // seevia_alert(data.message);
                    // window.location.reload();
                    $('#add_question').modal('close');
                    seevia_alert_func(jump_reload,data.message);
                }else{
                    $('#question_check').text(data.message);
                    // seevia_alert(data.message);
                }
            }
        });
    }

    function searchevaluation(){
        var condition_id = document.getElementById("cond_id").value;
        var evaluation_keyword = document.getElementById("evaluation_keyword");//搜索关键字
        var sUrl = web_base+"/evaluations/searchEvaluation/";//访问的URL地址
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
                                selhtml+="<dl style='padding:5px 0;' onclick=\"add_relation_evaluation('"+result.content[i]['Evaluation'].id+"')\"><span class='am-icon-plus'></span> "+result.content[i]['Evaluation'].name+"</dl>";
                            }
                            evaluation_select_sel.innerHTML = selhtml;
                        }
                        return;
                    }
                    if(result.flag=="2"){
                        seevia_alert('搜索不到结果');
                    }
                }
            });
        }
    }
    function add_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("cond_id").value;
        var code="<?php echo $evaluation_info['Evaluation']['code'];?>";
        var newhtml = "";
        var sUrl = web_base+"/evaluations/add_relation_evaluation/";//访问的URL地址
        $.ajax({
            type: "POST",
            url:sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id,code:code},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data' style='padding:10px 0;'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' style='cursor: pointer;' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    $("#cond_id").val(result.condition_id);
                    return;
                }
                if(result.flag=="2"){
                    seevia_alert(result.content);
                }
            }
        });
    }

    function delete_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("cond_id").value;
        var sUrl = web_base+"evaluations/delete_relation_evaluation/";//访问的URL地址
        var newhtml = "";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data' style='padding:10px 0;'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' style='cursor: pointer;' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    $("#cond_id").val(result.condition_id);
                    return;
                }
                if(result.flag=="2"){
                    seevia_alert(result.content);
                }
            }
        });
    }

    function edit_condtion(id){
        $("#value").val("");
        $("#edit_params").val("");
        $.ajax({
            url: web_base+"/evaluations/ajax_condition_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    var obj;
                    var params=data.data.Precondition.params;
                    $("#edit_params").val(params);
                    if(params=="cycle"){
                        $("#change2").hide();
                        $("#change1").show();
                        $("#change3").hide();
                        $("#add_condtion .am-checkbox input").attr('checked',false);
                    }else if(params=="ability_level"){
                        $("#change1").hide();
                        $("#change2").show();
                        $("#change3").hide();
                        var strs= new Array();
                        var str=data.data.Precondition.value;
                        strs=str.split(",");
                        $.each(strs,function (index,value) {
                            $("#add_condtion .am-checkbox input[value="+value+"]").attr('checked',true);
                        });
                    }else if(params=="parent_evaluation"){
                        $("#change1").hide();
                        $("#change2").hide();
                        $("#change3").show();
                        $("#add_condtion .am-checkbox input").attr('checked',false);
                    }else{
                        $("#change1").hide();
                        $("#change2").hide();
                        $("#change3").hide();
                        $("#add_condtion .am-checkbox input").attr('checked',false);
                    }
                    $("#cond_id").val(id);
                    $("#value").val(data.data.Precondition.value);
                    add_relation_evaluation(0);
                    $("#condtion_title").html("编辑前置条件");
                    $("#condtion_params").hide();
                    $("#add_condtion").modal('open');
                }else{
                    seevia_alert(data.message);
                }
            }
        });
        $("#add_condtion").modal('open');
    }

    function edit_rule(id){
        $.ajax({
            url: web_base+"/evaluations/ajax_rule_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    if (data.data.EvaluationRule.question_type == 1){
                        $("#add_rule .am-radio-inline input[value='1']").attr('checked',true);
                    }
                    if (data.data.EvaluationRule.question_type == 0){
                        $("#add_rule .am-radio-inline input[value='0']").attr('checked',true);
                    }
                    $("#add_rule select[name='data[EvaluationRule][child_evaluation_code]'] option[value='"+data.data.EvaluationRule.child_evaluation_code+"']" ).attr('selected',true);
                    $("#add_rule select[name='data[EvaluationRule][child_evaluation_code]']").trigger('changed.selected.amui');
                    $("#rule_title").html("编辑规则");
                    $("#rule_id").val(id);
                    $("#proportion").val(data.data.EvaluationRule.proportion);
                    $("#score").val(data.data.EvaluationRule.score);
                    $("#add_rule").modal('open');
                }else{
                    seevia_alert(data.message);
                }
            }
        });
    }
    function upload_question(){
        $("#upload_question").modal('open');
    }

    function delete_share(share_id){
        if(confirm('是否确认取消分享？')){
            $.ajax({
                type: "POST",
                url:web_base+'/evaluations/delete_share/'+share_id,
                dataType: 'json',
                data: {},
                success: function (data) {
                   if(data.code == 1){
                    seevia_alert('删除成功！');
                    window.location.reload();
                   }else{
                    seevia_alert(data.message);
                   }
                }
            });
        }else{
            
        }
    }

    function evaluation_category_code_select(value){
        if(value=='-1'){
            $('#evaluation_category_code_zidingyi').css('display','');
        }else{
            $('#evaluation_category_code_zidingyi').css('display','none');
        }
    }

    function ajax_upload_media(obj,obj_id){
        if($(obj).val()!=""){
            var fileName_arr=$(obj).val().split('.');
            var fileType=fileName_arr[fileName_arr.length-1];
            var fileTypearray=Array('jpg','JPG','jpeg','JPEG','gif','GIF','png','PNG');
            ajaxFileUpload(obj_id);
            console.log(obj_id);
        }
    }

    function ajaxFileUpload(img_id){
        var org_id = '<?php echo $evaluation_info['Evaluation']['id'] ?>';
        console.log(org_id);
        console.log(img_id);
        $.ajaxFileUpload({
            url:'/courses/ajax_upload_media',
            secureuri:false,
            fileElementId:img_id,
            data:{'org_id':org_id,'org_code':img_id},
            dataType: 'json',
            success: function (data){
                $('#'+img_id).siblings('input[type="hidden"]').val(data.img_url);
                var url = 'http://'+window.location.host+data.img_url;
                //alert(url);
                $("#img_logo").attr('src',url);
                $("#img_logo").attr('data-rel',url);
                $("#img_logo").show();
                console.log(data);
            }
        });
        return false;
    }

    function checkFile() {
        var obj = document.getElementById('evaluation_question');
        var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
        console.log(obj.value);
        if(suffix != 'csv'&&suffix != 'CSV'){
            seevia_alert("CSV文件格式错误！");
            obj.value="";
            $('#evaluation_question_text').text('');
            return false;
        }
        $('#evaluation_question_text').text(obj.value);
    }

    $(document).ready(function(){
        if($(window).width()<600){
            $('#accordion').css('padding','0px');
        }else{
            $('#accordion').css('padding','0 12px');
        }
    })
    $(window).resize(function(){
        if($(window).width()<600){
            $('#accordion').css('padding','0px');
        }else{
            $('#accordion').css('padding','0 12px');
        }
    });
</script>