<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_course_classes',array('action'=>'/','id'=>'user_course_class_form','name'=>'user_course_class','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>""));?>
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#class_list">课时列表</a></li>
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
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $course_class_info['User']['name'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo htmlspecialchars($course_class_info['Course']['name'])?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'];?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <?php if ($course_class_info['UserCourseClass']['status'] == 1) {?>
                                学习中
                            <?php }elseif($course_class_info['UserCourseClass']['status'] == 2){ ?>
                                已完成
                            <?php }elseif($course_class_info['UserCourseClass']['status'] == 3){ ?>
                                已评价
                            <?php } ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">阅读次数</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $course_class_info['UserCourseClass']['readcount'];?></div>
                    </div>
                    <?php if($course_class_info['UserCourseClass']['status'] == 3){?>
                        <div class="am-form-group">
                            <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">评价</label>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                                <?php foreach($informationresource_infos['course_comment'] as $kk=>$vv){
                                    echo $vv.": ".$comment_list['CourseComment'][$kk]."</br>";
                                }?>
                            </div>
                        </div>
                    <?php }?>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['start_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $course_class_info['Course']['created'];?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Class_list_pancel'}">课时列表&nbsp;</h4>
            </div>
            <div id="Class_list_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="class_list" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['name']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($class_list) && sizeof($class_list)>0){foreach($class_list as $k=>$v){?>
                            <tr>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['CourseClass']['name']; ?></td>
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

<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
            <li><a data-am-collapse="{parent: '#accordion'}" href="#class_list">课时列表</a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content" id="accordion">
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['user_name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo $course_class_info['User']['name'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo htmlspecialchars($course_class_info['Course']['name'])?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['status'];?></label>
                        <div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
                            <?php if ($course_class_info['UserCourseClass']['status'] == 1) {?>
                                学习中
                            <?php }elseif($course_class_info['UserCourseClass']['status'] == 2){ ?>
                                已完成
                            <?php }elseif($course_class_info['UserCourseClass']['status'] == 3){ ?>
                                已评价
                            <?php } ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3">阅读次数</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo $course_class_info['UserCourseClass']['readcount'];?></div>
                    </div>
                    <?php if($course_class_info['UserCourseClass']['status'] == 3){?>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3">评价</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <?php foreach($informationresource_infos['course_comment'] as $kk=>$vv){
                                    echo $vv.": ".$comment_list['CourseComment'][$kk]."</br>";
                                }?>
                            </div>
                        </div>
                    <?php }?>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['start_time'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo $course_class_info['Course']['created'];?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#class_list'}">课时列表</h4>
            </div>
            <div id="class_list" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['name']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($class_list) && sizeof($class_list)>0){foreach($class_list as $k=>$v){?>
                            <tr>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['CourseClass']['name']; ?></td>
                            </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>