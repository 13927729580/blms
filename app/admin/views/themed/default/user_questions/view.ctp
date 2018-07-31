<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/',array('action'=>'/','id'=>'user_question_form','name'=>'user_question','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>""));?>
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#option">选项列表</a></li>
                </ul>
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['user_name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $user_question_info['User']['name'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">题库标签</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo isset($user_question_info['UserQuestion']['tag'])?htmlspecialchars($user_question_info['UserQuestion']['tag']):'-';?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo htmlspecialchars($user_question_info['UserQuestion']['name'])?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'];?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <?php if ($user_question_info['UserQuestion']['status'] == 1) {?>
                                已审核
                            <?php }elseif($user_question_info['UserQuestion']['status'] == 0){ ?>
                                未审核
                            <?php } ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['type'];?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <?php if ($user_question_info['UserQuestion']['question_type'] == 1) {?>
                                多选
                            <?php }elseif($user_question_info['UserQuestion']['question_type'] == 0){ ?>
                                单选
                            <?php } ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">正确答案</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $user_question_info['UserQuestion']['right_answer'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">题目解析</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo htmlspecialchars($user_question_info['UserQuestion']['analyze']);?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">上传时间</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $user_question_info['UserQuestion']['created'];?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Option_pancel'}">选项列表&nbsp;</h4>
            </div>
            <div id="Option_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="option" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['name']?></th>
                            <th class="am-u-lg-4 am-u-md-3 am-u-sm-2"><?php echo $ld['description'];?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['orderby']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($user_question_info['UserQuestionOption']) && sizeof($user_question_info['UserQuestionOption'])>0){foreach($user_question_info['UserQuestionOption'] as $k=>$v){?>
                            <tr>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['name']; ?></td>
                                <td class="am-u-lg-4 am-u-md-3 am-u-sm-2"><?php echo htmlspecialchars($v['description']); ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                    <?php if ($v['status'] == 1) {?>
                                        <span class="am-icon-check am-yes"></span>
                                    <?php }elseif($v['status'] == 0){ ?>
                                        <span class="am-icon-close am-no"></span>
                                    <?php } ?>
                                </td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['orderby']; ?></td>
                            </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>