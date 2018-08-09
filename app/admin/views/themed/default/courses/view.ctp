<style type='text/css'>
form.am-form .am-form-label{text-align: left;}
.am-radio-inline{padding-top: 0!important;}
#course_class_detail.am-modal .am-modal-bd{max-height:450px;overflow-y: scroll;}
#course_class_detail.am-modal .am-modal-bd::-webkit-scrollbar{width:2px;}
#course_class_detail.am-modal .am-modal-bd::-webkit-scrollbar-track{background:#fff;}
#class form .am-nav > li > a:hover,#class form .am-nav > li > a:focus{background:none;}
#class form .am-tab-panel{min-height:400px;}
#class form .am-active .am-btn-default.am-dropdown-toggle,#class form .am-btn-default.am-active,#class form  .am-btn-default:active{background:none;}
#inivate_user{width: 540px;margin-left: -270px;}
#chapter form,#class form,#ware form{max-height:500px;overflow-y:scroll;}
#ware .am-btn-group label.am-btn{text-align:left;}
</style>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/courses',array('action'=>'view/'.$course_info["Course"]["id"],'id'=>'course_edit_form','name'=>'course_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
        <input type="hidden" name="data[Course][id]" id="_id" value="<?php echo $course_info['Course']['id'];?>" />
        <!-- 导航 -->
        <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#course_chapter">章节列表</a></li>
			<li><a href="#course_ware_list">课件列表</a></li>
			<li><a href="#precondition">前置条件</a></li>
			<?php if($svshow->operator_privilege("user_learning")){ ?>
			<li><a href="#user">用户课程记录</a></li>
			<?php } ?>
		</ul>
        </div>
        <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
            <?php if(isset($course_info['Course'])&&!empty($course_info['Course'])&&isset($can_to_read)&&$can_to_read){ ?>
            <button style="margin-right: 0;" type="button" class="am-btn am-btn-secondary am-btn-sm am-btn-bottom am-radius"  data-am-modal="{target: '#inivate_user',closeViaDimmer:0}">邀请学习</button>
            <?php } ?>
            <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_submit'] ?></button>
            <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 导航结束 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课程级别</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <?php if(isset($course_info['Course']['user_id'])&&$course_info['Course']['user_id']==0){echo '系统级别';}else{echo '个人级别';} ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课程类型</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select id="course_type_code" name="data[Course][course_type_code]" data-am-selected>
                                <?php if(empty($course_info['Course']['course_type_code'])&&sizeof($course_type)>1){?>
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                <?php }?>
                                <?php foreach ($course_type as $tid=>$t){ ?>
                                    <option value="<?php echo $t['CourseType']['code'];?>" <?php if($course_info['Course']['course_type_code']==$t['CourseType']['code'])echo "selected"?>><?php echo $t['CourseType']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group am-hide">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">可见性</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="0" <?php if($course_info['Course']['visibility']==0)echo "checked"?>>公开</label>
                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="2" <?php if($course_info['Course']['visibility']==2)echo "checked"?>>限定</label>
                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="1" <?php if($course_info['Course']['visibility']==1)echo "checked"?>>仅自己</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课程分类</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select id="course_type_code" name="data[Course][course_category_code]" data-am-selected>
                                <?php if(empty($course_info['Course']['course_category_code'])&&sizeof($course_category)>1){?>
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                <?php }?>
                                <?php foreach ($course_category as $tid=>$t){ ?>
                                    <option value="<?php echo $t['CourseCategory']['code'];?>" <?php if($course_info['Course']['course_category_code']==$t['CourseCategory']['code'])echo "selected"?>><?php echo $t['CourseCategory']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">难度级别</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select name='data[Course][level]' data-am-selected>
                                <?php if(isset($resource_info['course_level'])&&sizeof($resource_info['course_level'])>0){foreach($resource_info['course_level'] as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php if($course_info['Course']['level']==$k)echo "selected"?>><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" onchange="check_course_code(this)" name="data[Course][code]" id="code" value="<?php echo $course_info['Course']['code'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[Course][name]" id="name" value="<?php echo $course_info['Course']['name'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][status]" <?php if($course_info['Course']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][status]" <?php if($course_info['Course']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">是否推荐</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][recommend_flag]" <?php if($course_info['Course']['recommend_flag'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][recommend_flag]" <?php if($course_info['Course']['recommend_flag'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">是否公开</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_public]" <?php if($course_info['Course']['allow_public'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_public]" <?php if($course_info['Course']['allow_public'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">允许报名</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" <?php if($course_info['Course']['allow_learning'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" <?php if($course_info['Course']['allow_learning'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" <?php if($course_info['Course']['allow_learning'] == 2){?>checked="checked"<?php }?> value="2"/>无需报名</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['meta_description']; ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <textarea name='data[Course][meta_description]'><?php echo isset($course_info['Course']['meta_description'])?$course_info['Course']['meta_description']:'';?></textarea>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            	<?php echo $this->element('editor',array('editorName'=>"data[Course][description]",'editorId'=>'elm','editorValue'=>isset($course_info['Course']['description'])?$course_info['Course']['description']:'')); ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">图片</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input id="img" type="text" name="data[Course][img]" value="<?php echo $course_info['Course']['img'];?>" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('img')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image($course_info['Course']['img'],array('id'=>'show_img'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['price'] ?></label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="price" name="data[Course][price]" value="<?php echo $course_info['Course']['price'];?>"/></div>
                        <div class='am-u-lg-4 am-u-md-5 am-u-sm-5 am-padding-top-xs'>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][must_buy]" <?php if($course_info['Course']['must_buy'] == 0){?>checked="checked"<?php }?> value="0"/>满足条件或购买</label>
                        	<label class="am-radio-inline"><input type="radio" name="data[Course][must_buy]" <?php if($course_info['Course']['must_buy'] == 1){?>checked="checked"<?php }?> value="1"/>满足条件并购买</label>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">总时长（分）</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="hour" name="data[Course][hour]" value="<?php echo $course_info['Course']['hour'];?>"/></div>
                        <?php if(isset($courseware_hour)&&$courseware_hour>0){ ?><label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课时合计<?php echo $courseware_hour; ?>分钟</label><?php  } ?>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">可获经验值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="experience_value" name="data[Course][experience_value]" value="<?php echo $course_info['Course']['experience_value'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">点击数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Course][clicked]" value="<?php echo $course_info['Course']['clicked'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Course_chapter_pancel'}">章节列表</h4>
            </div>
            <div id="Course_chapter_pancel" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd" id="style">
                    <p class='am-text-right'>
                	   <a class="mt am-btn am-btn-danger am-radius am-btn-sm am-btn-bottom" href="javascript:void(0);" onclick="batch_remove_chapter(<?php echo $course_info['Course']['id']; ?>)">
                            <span class="am-icon-trash-o"></span>&nbsp;<?php echo $ld['batch_delete']; ?>
                        </a>
                        <a class="mt am-btn am-btn-secondary am-radius am-btn-sm am-btn-bottom" href="javascript:void(0);" onclick="learning_plan(this,<?php echo $course_info['Course']['id']; ?>)">
                            <span class="am-icon-calendar"></span>&nbsp;学习计划
                        </a>
                        <a class="mt am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" href="<?php echo $html->url('/courses/export_course/'.$course_info['Course']['code']); ?>">
                            <span class="am-icon-plus"></span>&nbsp;导出
                        </a>
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="upload_course();">
                            <span class="am-icon-plus"></span>&nbsp;上传
                        </a>
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_chapter();">
                            <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>
                        </a>
                    </p>
                    <div id="course_chapter">&nbsp;</div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#course_ware_pancel'}">课件列表&nbsp;</h4>
		</div>
		<div id="course_ware_pancel" class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd">
				<p class='am-text-right'>
					<a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_ware('');">
                            		<span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>
                        		</a>
           			</p>
				<div id="course_ware_list"></div>
			</div>
		</div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Condition_pancel'}">前置条件&nbsp;</h4>
            </div>
            <div id="Condition_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="precondition" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <?php if(!isset($resource_info['course_condition'])||empty($resource_info['course_condition'])||(isset($resource_info['course_condition'])&&sizeof($resource_info['course_condition'])>sizeof($course_condition))){?>
                        <p style="text-align:right;">
                            <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_precondtion(this,'<?php echo $course_info['Course']['code']; ?>');">
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
                        <?php if(isset($course_condition) && sizeof($course_condition)>0){foreach($course_condition as $k=>$v){ ?>
                            <tr >
                                <td><?php echo isset($resource_info['course_condition'][$v['Precondition']['params']])?$resource_info['course_condition'][$v['Precondition']['params']]:$v['Precondition']['params']; ?></td>
                                <td><?php if($v['Precondition']['params']=="parent_course"){echo isset($parent_course_list)?$parent_course_list:$v['Precondition']['value'];}else if($v['Precondition']['params']=="ability_level"){echo isset($ability_level_list)?implode(',',$ability_level_list):$v['Precondition']['value'];}else{echo $v['Precondition']['value'];}?></td>
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
        <?php if($svshow->operator_privilege("user_learning")){ ?>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#progress_list'}">课程记录</h4>
            </div>
            <div id="progress_list" class="am-panel-collapse am-collapse am-in">
                <div id="user" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
	                        <tr>
						<th>课程名称</th>
						<th>学习者姓名</th>
						<th>开始学习时间</th>
						<th>最后学习时间</th>
						<th>学习进度</th>
						<th>查看</th>
	                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($course_class_log) && sizeof($course_class_log)>0){foreach($course_class_log as $k=>$v){ ?>
                            <tr>
					<td><?php echo $v['Course']['name']; ?></td>
					<td><?php echo isset($v['User']['name'])?$v['User']['name']:''; ?></td>
					<td><?php echo isset($user_read_detail[$v['UserCourseClass']['user_id']])?$user_read_detail[$v['UserCourseClass']['user_id']]['first_read']:'-'; ?></td>
					<td><?php echo isset($user_read_detail[$v['UserCourseClass']['user_id']])?$user_read_detail[$v['UserCourseClass']['user_id']]['last_read']:'-'; ?></td>
					<td>已学：<?php echo isset($couse_class_detail_list[$v['UserCourseClass']['user_id']])?$couse_class_detail_list[$v['UserCourseClass']['user_id']]:0; ?>/<?php echo isset($couse_class_total)?$couse_class_total:0; ?></td>
                                <td>
                                    <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" onclick="course_class_detail(<?php echo $v['UserCourseClass']['id']; ?>)"><span class="am-icon-eye"></span>查看</a>
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

<div class="am-modal am-modal-no-btn" id="upload_course">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">批量上传</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('/courses',array('action'=>'preview/'.$course_info['Course']['code'],'class'=>' am-form am-form-horizontal',"enctype"=>"multipart/form-data"));?>
            <div class="am-panel-bd">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:21px;"><?php echo $ld['csv_file_bulk_upload']?></label>
                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                        <p style="margin:10px 0px;"><input name="course_class" id="course_class" size="40" type="file" style="height:22px;width: 100%;" onchange="checkFile()"/></p>
                        <p style="padding:6px 0;"><?php echo $ld['articles_upload_file_encod']?></p>
                    </div>
                </div>
                <?php if(isset($profile_info['Profile'])){?>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                <?php echo $html->link($ld['download_example_batch_csv'],"/courses/download_csv_example/".$course_info['Course']['code'],'',false,false);?>
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

<div class="am-modal am-modal-no-btn" id="chapter">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">添加章节</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[CourseChapter][id]" id="chapter_id" value="">
                <input type="hidden" name="data[CourseChapter][course_code]" value="<?php echo $course_info['Course']['code'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][code]" onchange="check_chapter_code(this)" id="chapter_code" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][name]" id="chapter_name" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    				<?php echo $this->element('editor',array('editorName'=>"data[CourseChapter][description]",'editorId'=>'chapter_elm')); ?>
                        </div>
   			   <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['orderby'] ?></label>
                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="chapter_orderby" name="data[CourseChapter][orderby]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-text-left">
    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">&nbsp;</label>
    				<div class="am-u-lg-5 am-u-md-4 am-u-sm-4">
                        		<button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
   				</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="class">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="class_title">添加课时</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                	<input type="hidden" name="data[CourseClass][id]" id="class_id" value="">
    			<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="javascript: void(0)">基本信息</a></li>
					<li><a href="javascript: void(0)">前置条件</a></li>
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-active">
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">章节</label>
							<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
								<select name='data[CourseClass][chapter_code]' id='class_chapter_code' data-am-selected="{maxHeight:300,}">
									<option value='0'><?php echo $ld['please_select'] ?></option>
								</select>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">品牌</label>
							<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
								<select name='data[CourseClass][brand_code]' data-am-selected="{maxHeight:300,}">
									<option value=' '><?php echo $ld['none']; ?></option>
									<?php if(isset($BrandList)&&sizeof($BrandList)>0){foreach($BrandList as $v){ ?>
									<option value="<?php echo $v['Brand']['code']; ?>"><?php echo $v['BrandI18n']['name']; ?></option>
									<?php }} ?>
								</select>
							</div>
						</div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'] ?></label>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][code]" onchange="check_class_code(this)" id="class_code" value=""></div>
			                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name'] ?></label>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][name]" id="class_name" value=""></div>
			                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['author'] ?></label>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][author]" value=""></div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">时长（分）</label>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][courseware_hour]" value=""></div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['price'] ?></label>
			                        <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><input type="text" name="data[CourseClass][price]" value="0"></div>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-padding-top-xs">
							<label class="am-radio-inline"><input type="radio" name="data[CourseClass][must_buy]" checked value="0"/>满足条件或购买</label>
							<label class="am-radio-inline"><input type="radio" name="data[CourseClass][must_buy]"  value="1"/>满足条件并购买</label>
			                        </div>
			                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
			                    </div>
			                    <div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">是否试读</label>
							<div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left am-padding-top-xs">
								<label class="am-radio-inline"><input type="radio" name="data[CourseClass][is_probation]" value="1" />是</label>
								<label class="am-radio-inline"><input type="radio" name="data[CourseClass][is_probation]" value="0" checked/>否</label>
							</div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
			                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left am-padding-top-xs">
			                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="1" checked/>有效</label>
			                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="0"/>无效</label>
			                        </div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">课时显示模板</label>
			                        <div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
			                            <select name='data[CourseClass][template]' data-am-selected>
			                                <option value='order'>排序显示</option>
			                                <option value='tab'>页签切换显示</option>
			                                <option value='page'>分页显示</option>
			                            </select>
			                        </div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description'] ?></label>
			                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			                        	<?php echo $this->element('editor',array('editorName'=>"data[CourseClass][description]",'editorId'=>'class_elm')); ?>
			                        </div>
			                        <div class='am-cf'></div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['label_01'] ?></label>
			                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7"><textarea name="data[CourseClass][tag]" style="outline:none;resize:none;height:200px;"></textarea></div>
			                        <div class='am-cf'>一行一个</div>
			                    </div>
			                    <div class="am-form-group">
			                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orderby'] ?></label>
			                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="class_orderby" name="data[CourseClass][orderby]" value="0"/></div>
			                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
			                    </div>
					</div>
					<div class="am-tab-panel" id='course_class_condition'>
						
					</div>
				</div>
    			</div>
                	<div class="am-panel-bd">
                    	<div class="am-form-group">
                    		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                    		<div class="am-u-lg-5 am-u-md-4 am-u-sm-4 am-text-left">
                        			<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_class_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                        		</div>
                    	</div>
                	</div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="ware">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="ware_title">添加课件</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[CourseClassWare][id]" id="ware_id" value="">
                <div class="am-panel-bd">
    				<div class="am-u-lg-3 am-padding-left-0 am-padding-right-0">
    					<div class="am-btn-group am-btn-group-stacked" data-am-button>
                    		<?php if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){foreach($resource_info['courseware_type'] as $k=>$v){ ?>
						<label class="am-btn am-btn-secondary am-margin-bottom-xs" data-ware-type="<?php echo $k; ?>" onclick="select_courseware_type('<?php echo $k; ?>')">
							<input type="radio" class='needsclick' name="data[CourseClassWare][type]" value="<?php echo $k; ?>"  ><i class='am-icon am-icon-'></i> <?php echo $v; ?>
						</label>
                    		<?php }} ?>
						<label class="am-btn am-btn-secondary" data-ware-type="scorm" onclick="select_courseware_type('scorm')">
							<input type="radio" class='needsclick' name="data[CourseClassWare][type]" value="scorm" ><i class='am-icon am-icon-'></i>  SCORM
						</label>
                    		</div>
    				</div>
    				<div class="am-u-lg-9 am-padding-left-0 am-padding-right-0">
    					<div class="am-form-group">
    						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
    						<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="display:none;">
    							<div id="select_option" class='am-margin-top-0'></div>
    						</div>
    						<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
							<input type="text" id="ware_info" name="data[CourseClassWare][ware]" value="">
							<input type='file' name="courseware" id="courseware" onchange="uploadcourse(this,'<?php echo intval(ini_get('upload_max_filesize')); ?>')" />
						</div>
    					</div>
		    			<div class="am-form-group">
		    				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">课时</label>
		    				<div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
		    					<select name="data[CourseClassWare][course_class_code]" data-am-selected="{maxHeight:150}">
		    						<option value='0'><?php echo $ld['please_select']; ?></option>
		    					</select>
		    				</div>
		    			</div>
		    			<div class="am-form-group">
		                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'] ?></label>
		                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClassWare][code]" id="ware_code" value=""></div>
		                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
		                    </div>
		                    <div class="am-form-group">
		                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name'] ?></label>
		                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClassWare][name]" id="ware_name" value=""></div>
		                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
		                    </div>
		                    <div class="am-form-group am-hide">
		                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">时长（分）</label>
		                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><input type="text" id="ware_hour" name="data[CourseClassWare][hour]" value="0"/></div>
		                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
		                    </div>
		                    <div class="am-form-group">
		                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
		                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left">
		                            <label class="am-radio-inline"><input type="radio" name="data[CourseClassWare][status]" value="1" checked/>有效</label>
		                            <label class="am-radio-inline"><input type="radio" name="data[CourseClassWare][status]" value="0"/>无效</label>
		                        </div>
		                    </div>
		                    <div class="am-form-group">
		                    	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description'] ?></label>
		                    	<div class='am-u-lg-9 am-u-md-8 am-u-sm-8'>
							<div class="am-g"><?php echo $this->element('editor',array('editorName'=>"data[CourseClassWare][description]",'editorId'=>'ware_elm')); ?></div>
		                    	</div>
		                    </div>
		                    <div class="am-form-group">
		                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orderby'] ?></label>
		                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="ware_orderby" name="data[CourseClassWare][orderby]" value="0"/></div>
		                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
		                    </div>
		                    <div class="am-form-group am-text-left">
		                    	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
	                        		<div class="am-u-lg-5 am-u-md-4 am-u-sm-4">
	                        			<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_ware_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
	                        		</div>
	                    	</div>
    				</div>
    				<div class='am-cf'></div>
	                    
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="learning_plan">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">学习计划</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">

        </div>
    </div>
</div>
			
<div class="am-modal am-modal-no-btn" id="course_class_detail">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">学习记录</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
			<table class='am-table'>
				<thead>
					<tr>
						<th class='am-text-center'>课时</th>
						<th class='am-text-center'>时长(分)</th>
						<th class='am-text-center'>开始时间</th>
						<th class='am-text-center'>结束时间</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
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


<div class="am-modal am-modal-no-btn" id="inivate_user">
	<div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">邀请学习</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('/courses',array('action'=>'inivate_user','class'=>' am-form am-form-horizontal'));?>
    		  <input type='hidden' name="course_id" value="<?php echo $course_info['Course']['id']; ?>" />
                <div class="am-form-group am-margin-bottom-xs">
                    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']; ?></label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<div class="am-input-group am-margin-top-0">
					<input type="text" class="am-form-field" name="user_mobile"  value="">
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-secondary am-btn-sm" type="button" onclick="ajax_inivate_user_list(this)">搜索</button>
					</span>
				</div>
                    </div>
                </div>
    		  <div class="am-form-group">
                    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<select name="user_id">
					<option value="0"><?php echo $ld['please_select'] ?></option>
				</select>
                    </div>
                </div>
                <div class="am-form-group">
    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left">
                    	<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="ajax_inivate_user(this)">邀请</button>
    			</div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type='text/javascript'>
$(function(){
	$('#ware .am-btn-group label.am-btn').each(function(){
		var ware_type=$(this).attr('data-ware-type');
		var ware_icon=$(this).find('i.am-icon');
		if(ware_type=='txt'){
			ware_icon.addClass('am-icon-file-text-o');
		}else if(ware_type=='pdf'){
			ware_icon.addClass('am-icon-file-pdf-o');
		}else if(ware_type=='gallery'){
			ware_icon.addClass('am-icon-file-image-o');
		}else if(ware_type=='local_video'){
			ware_icon.addClass('am-icon-file-video-o');
		}else if(ware_type=='external_video'){
			ware_icon.addClass('am-icon-video-camera');
		}else if(ware_type=='youkuid'){
			ware_icon.addClass('am-icon-film');
		}else if(ware_type=='evaluation'){
			ware_icon.addClass('am-icon-cog');
		}else if(ware_type=='down'){
			ware_icon.addClass('am-icon-download');
		}else if(ware_type=='iframe'){
			ware_icon.addClass('am-icon-windows');
		}else if(ware_type=='assignment'){
			ware_icon.addClass('am-icon-file-word-o');
		}else if(ware_type=='activity'){
			ware_icon.addClass('am-icon-users');
		}else if(ware_type=='scorm'){
			ware_icon.addClass('am-icon-laptop');
		}
	});
});
	var course_code_check=true;
	function check_course_code(obj){
        course_code_check=false;
        var code=obj.value;
        if(code!="<?php echo $course_info['Course']['code'];?>" && code!=""){
            $.ajax({url: admin_webroot+"courses/check_code",
                type:"POST",
                data:{'code':code},
                dataType:"json",
                success: function(data){
                    try{
                        if(data.code==1){
                            course_code_check=true;
                        }else{
                            alert(data.msg);
                        }
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                }
            });
        }else{
            course_code_check=true;
        }
    }
    
    function chechk_form(){
    	if(course_code_check==false){
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
    
    ajax_course_detail('chapter');
    ajax_course_detail('ware');
    
    function ajax_course_detail(view_model){
    		var course_code=$("input[name='data[Course][code]']").val();
    		$.ajax({
			url: admin_webroot+"courses/ajax_course_detail",
			type:"POST",
			data:{'course_code':course_code,'view_model':view_model},
			dataType:"html",
			success: function(result){
				if(view_model=='chapter'){
					$('#course_chapter').html(result);
				}else if(view_model=='ware'){
					$('#course_ware_list').html(result);
				}
			}
            });
    }
    
    function add_chapter(){
	        $("#chapter_title").html("添加章节");
	        $("#chapter_id").val("");
	        $("#chapter_code").val("");
	        $("#chapter_name").val("");
	        if(typeof(UM)!='undefined'){
	        	//console.log(UM);
		        var editor=UMEditorList['chapter_elm'];
		        editor.setContent("");
	        }else if(typeof(KindEditor)!='undefined'){
	        	KindEditor.html("#chapter_elm","");
	        }
	        $("#chapter textarea[name='data[CourseChapter][description]']").val("");
	        $("#chapter_orderby").val("0");
	        $("#chapter").modal('open');
    }

    function edit_chapter(id){
        $.ajax({
            url: admin_webroot+"course_chapters/ajax_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    if (data.data.CourseChapter.status == 1){
                        $("#chapter .am-radio-inline input[value='1']").attr('checked',true);
                    }
                    if (data.data.CourseChapter.status == 0){
                        $("#chapter .am-radio-inline input[value='0']").attr('checked',true);
                    }
                    $("#chapter_title").html("编辑章节");
                    $("#chapter_id").val(id);
                    $("#chapter_code").val(data.data.CourseChapter.code);
                    $("#chapter_name").val(data.data.CourseChapter.name);
                      if(typeof(UM)!='undefined'){
			        var editor=UMEditorList['chapter_elm'];
			        editor.setContent(data.data.CourseChapter.description);
		        }else if(typeof(KindEditor)!='undefined'){
		        	KindEditor.html("#chapter_elm",data.data.CourseChapter.description);
		        }
                    $("#chapter textarea[name='data[CourseChapter][description]']").val(data.data.CourseChapter.description);
                    $("#chapter_orderby").val(data.data.CourseChapter.orderby);
                    $("#chapter").modal('open');
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function upload_course(){
        $("#upload_course").modal('open');
    }

	var chapter_code_check=true;
    function check_chapter_code(obj){
        chapter_code_check=false;
        var code=obj.value;
        if(code!=""){
            $.ajax({url: admin_webroot+"course_chapters/check_code",
                type:"POST",
                data:{'code':code},
                dataType:"json",
                success: function(data){
                    try{
                        if(data.code==1){
                            chapter_code_check=true;
                        }else{
                            alert(data.msg);
                        }
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                }
            });
        }else{
            chapter_code_check=true;
        }
    }

    function ajax_modify_submit(btn){
		if(chapter_code_check==false){
            alert("code已存在");
            return false;
        }
        var name_obj = document.getElementById("chapter_name");
        var code_obj = document.getElementById("chapter_code");
        if(code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        if(name_obj.value==""){
            alert("标题不能为空");
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"course_chapters/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                	ajax_course_detail('chapter');
                    alert(data.message);
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function add_class(code){
    	 ajax_pre_couse_class();
        ajax_load_chapter(code);
        $("#class select[name='data[CourseClass][template]'] option:eq(0)" ).attr('selected',true);
        $("#class select[name='data[CourseClass][template]']").trigger('changed.selected.amui');
        $("#class select[name='data[CourseClass][brand_code]'] option:eq(0)" ).attr('selected',true);
        $("#class select[name='data[CourseClass][brand_code]']").trigger('changed.selected.amui');
        $("#class_title").html("添加课时");
        $("#class_id").val("");
        $("#class_code").val("");
        $("#class_name").val("");
        $("#class input[name='data[CourseClass][author]']").val('');
        $("#class textarea[name='data[CourseClass][tag]']").val('');
        $("#class input[name='data[CourseClass][price]']").val('0');
        $("#class input[type='radio'][name='data[CourseClass][must_buy]'][value='0']").attr('checked',true);
        $("#class input[name='data[CourseClass][courseware_hour]']").val('0');
        if(typeof(UM)!='undefined'){
		var editor=UMEditorList['class_elm'];
		editor.setContent('');
	}else if(typeof(KindEditor)!='undefined'){
		KindEditor.html("#class_elm",'');
	}
        $("#class textarea[name='data[CourseClass][description]']").val("");
        $("#class_orderby").val("0");
        $("#class").modal('open');
    }

    function edit_class(id){
        $.ajax({
            url: admin_webroot+"course_classes/ajax_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    if (data.data.CourseClass.status == 1){
                        	$("#class .am-radio-inline input[name='data[CourseClass][status]'][value='1']").attr('checked',true);
                    }else{
                        	$("#class .am-radio-inline input[name='data[CourseClass][status]'][value='0']").attr('checked',true);
                    }
                    if (data.data.CourseClass.is_probation == 1){
                        	$("#class .am-radio-inline input[name='data[CourseClass][is_probation]'][value='1']").attr('checked',true);
                    }else{
                        	$("#class .am-radio-inline input[name='data[CourseClass][is_probation]'][value='0']").attr('checked',true);
                    }
                    ajax_load_chapter(data.data.CourseClass.chapter_code);
			$("#class select[name='data[CourseClass][template]'] option[value='"+data.data.CourseClass.template+"']" ).attr('selected',true);
			$("#class select[name='data[CourseClass][template]']").trigger('changed.selected.amui');
			$("#class_title").html("编辑课时");
			$("#class_id").val(id);
			$("#class_code").val(data.data.CourseClass.code);
			$("#class_name").val(data.data.CourseClass.name);
			$("#class input[name='data[CourseClass][price]']").val(data.data.CourseClass.price);
			$("#class input[type='radio'][name='data[CourseClass][must_buy]'][value='"+data.data.CourseClass.must_buy+"']").attr('checked',true);
			$("#class input[name='data[CourseClass][author]']").val(data.data.CourseClass.author);
        		$("#class input[name='data[CourseClass][courseware_hour]']").val(data.data.CourseClass.courseware_hour);
			$("#class textarea[name='data[CourseClass][tag]']").val(data.data.CourseClass.tag);
			if(typeof(UM)!='undefined'){
				var editor=UMEditorList['class_elm'];
				editor.setContent(data.data.CourseClass.description);
			}else if(typeof(KindEditor)!='undefined'){
				KindEditor.html("#class_elm",data.data.CourseClass.description);
			}
                    $("#class textarea[name='data[CourseClass][description]']").val(data.data.CourseClass.description);
                    $("#class_orderby").val(data.data.CourseClass.orderby);
                    if(typeof(data.data.CourseClass.brand_code)!='undefined'){
                    	$("#class select[name='data[CourseClass][brand_code]'] option[value='"+data.data.CourseClass.brand_code+"']" ).attr('selected',true);
                    	$("#class select[name='data[CourseClass][brand_code]']").trigger('changed.selected.amui');
                    }
    	 		ajax_pre_couse_class(data.data.CourseClass.id);
                    $("#class").modal('open');
                }else{
                    alert(data.message);
                }
            }
        });
    }

	var class_code_check=true;
    function check_class_code(obj){
        class_code_check=false;
        var code=obj.value;
        if(code!=""){
            $.ajax({url: admin_webroot+"course_classes/check_code",
                type:"POST",
                data:{'code':code},
                dataType:"json",
                success: function(data){
                    try{
                        if(data.code==1){
                            class_code_check=true;
                        }else{
                            alert(data.msg);
                        }
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                }
            });
        }else{
            class_code_check=true;
        }
    }

    function ajax_class_modify_submit(btn){
		if(class_code_check==false){
            alert("code已存在");
            return false;
        }
        var name_obj = document.getElementById("class_name");
        var code_obj = document.getElementById("class_code");
        if(code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        if(name_obj.value==""){
            alert("标题不能为空");
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"course_classes/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                	ajax_course_detail('chapter');
                	ajax_course_detail('ware');
                    alert(data.message);
                    $("#class").modal('close');
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function add_ware(code){
    	 ajax_load_class(code);
    	 $("#ware input[type='radio'][name='data[CourseClassWare][type]']:checked").attr('checked',false);
    	 $("#ware div.am-btn-group-stacked label.am-btn.am-active").removeClass('am-active');
    	 var default_ware_type=$("#ware input[type='radio'][name='data[CourseClassWare][type]']:eq(0)");
    	 default_ware_type.attr('checked',true);
    	 default_ware_type.parent().addClass('am-active');
    	 select_courseware_type(default_ware_type.val());
    	 
        $("#ware_title").html("添加课件");
        $("#ware_id").val("");
        $("#ware_code").val("");
        $("#ware_name").val("");
        $("input[name='data[CourseClassWare][ware]']").val('');
        $("#courseware").val("");
        $("#ware_hour").val("0");
	if(typeof(UM)!='undefined'){
		var editor=UMEditorList['ware_elm'];
		editor.setContent('');
	}else if(typeof(KindEditor)!='undefined'){
		KindEditor.html("#ware_elm",'');
	}
        $("#ware textarea[name='data[CourseClassWare][description]']").val("");
        $("#ware_orderby").val("0");
        $("#ware").modal('open');
    }

    function edit_ware(id){
        $.ajax({
            url: admin_webroot+"course_classes/ajax_ware_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    if (data.data.CourseClassWare.status == 1){
                        $("#ware .am-radio-inline input[value='1']").attr('checked',true);
                    }
                    if (data.data.CourseClassWare.status == 0){
                        $("#ware .am-radio-inline input[value='0']").attr('checked',true);
                    }
                    $("#ware input[type='radio'][name='data[CourseClassWare][type]']:checked").attr('checked',false);
		    	 $("#ware div.am-btn-group-stacked label.am-btn.am-active").removeClass('am-active');
		    	 var default_ware_type=$("#ware input[type='radio'][name='data[CourseClassWare][type]'][value='"+data.data.CourseClassWare.type+"']");
		    	 default_ware_type.attr('checked',true);
		    	 default_ware_type.parent().addClass('am-active');
		    	 select_courseware_type(data.data.CourseClassWare.type);
                    $("#ware_title").html("编辑课件");
                    ajax_load_class(data.data.CourseClassWare.course_class_code);
                    $("#ware_id").val(id);
                    $("#ware_code").val(data.data.CourseClassWare.code);
                    $("#ware_name").val(data.data.CourseClassWare.name);
                    $("#ware_hour").val(data.data.CourseClassWare.hour);
			if(typeof(UM)!='undefined'){
				var editor=UMEditorList['ware_elm'];
				editor.setContent(data.data.CourseClassWare.description);
			}else if(typeof(KindEditor)!='undefined'){
				KindEditor.html("#ware_elm",data.data.CourseClassWare.description);
			}
                    $("#ware textarea[name='data[CourseClassWare][description]']").val(data.data.CourseClassWare.description);
                    $("#ware_orderby").val(data.data.CourseClassWare.orderby);
                    $("input[name='data[CourseClassWare][ware]']").val(data.data.CourseClassWare.ware);
                    $("#ware").modal('open');
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function ajax_ware_modify_submit(btn){
    	 var course_code= document.getElementById("ware_code").value;
    	 if(course_code==''){
    	 	alert('编码不能为空');
    	 	return false;
    	 }
        var name_obj = document.getElementById("ware_name");
        if(name_obj.value==""){
            	alert("标题不能为空");
            	return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"course_classes/ajax_ware_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
			ajax_course_detail('chapter');
			ajax_course_detail('ware');
			alert(data.message);
			$("#ware").modal('close');
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function select_courseware_type(courseware_type){
    	 var courseware_type_text=$("#ware .am-btn-group label[data-ware-type='"+courseware_type+"']").text().trim();
    	 var formGroup=$("#select_option").parent().parent();
    	 formGroup.find("label.am-form-label").html(courseware_type_text);
    	 formGroup.show();
        if(courseware_type=='txt'){
        	formGroup.hide();
		$("input[name='data[CourseClassWare][ware]']").parent().hide();
		$("#select_option option").remove();
		$("#select_option").parent().hide();
        }else if(courseware_type=='evaluation' || courseware_type=='activity'){
        	$("input[name='data[CourseClassWare][ware]']").parent().hide();
        	$("#courseware").hide();
		$.ajax({
	            type: "GET",
	            url: admin_webroot+"course_classes/changeware/"+courseware_type,
	            dataType: 'html',
	            success: function (result) {
	            		var ware=$("#ware_info").val();
	                    $("#select_option").html(result);
	                    $("#select_option").parent().show();
	                    $("#ware select[name='ware_list'] option[value='"+ware+"']" ).attr('selected',true);
	                    $("#ware select[name='ware_list']").trigger('changed.selected.amui');
	            }
	        });
        }else{
		$("#select_option option").remove();
		$("#select_option").parent().hide();
		$("input[name='data[CourseClassWare][ware]']").parent().show();
            if(courseware_type=='external_video' || courseware_type=='youkuid'|| courseware_type=='iframe'){
                	$("#courseware").hide();
            }else if(courseware_type=='scorm'){
            	$("#courseware").show();
                	$("#courseware").attr('accept','application/zip').attr('multiple',false);
            }else if(courseware_type=='pdf'){
                	$("#courseware").show();
                	$("#courseware").attr('accept','application/pdf').attr('multiple',false);
            }else if(courseware_type=='gallery'){
                	$("#courseware").show();
                	$("#courseware").attr('accept','image/*,application/pdf').attr('multiple','multiple');
            }else if(courseware_type=='down'||courseware_type=='local_video'){
            		$("#courseware").show();
            }
        }
    }

    function uploadcourse(obj,MaxFileSize){
        var files = obj.files;
        var post_data = new FormData();
        var FileTypeList=[];
        if (files && files.length){
            for(var i=0;i<files.length;i++){
                var file = files[i];
                var fileType=file.type.split("/")[0];
                FileTypeList.push(fileType);
                var file_name=file.name;
                var reader = new FileReader();//新建一个FileReader
                reader.readAsText(file, "UTF-8");//读取文件
                reader.onload = function(e){ //读取完文件之后会回来这里
                    var file_size=Math.round(e.total/1024/1024);
                    if(file_size>MaxFileSize){
                        alert('最大文件限制为'+MaxFileSize+'M,'+file_name+'当前为'+file_size+'M');
                        return false;
                    }
                }
                post_data.append("courseware[]",file);
            }
        }else{
            return false;
        }
        var UploadFileType=checkWareFile();
	for(var i=0;i<FileTypeList.length;i++) {
		var items=FileTypeList[i];
		//判断元素是否存在于new_arr中，如果不存在则插入到new_arr的最后
		if($.inArray(items,UploadFileType)==-1) {
			UploadFileType.push(items);
		}
	}
	if(UploadFileType.length>1){
		alert('当前仅支持上传多个同类型文件');
		return false;
	}
        var courseware_type=$("select[name='data[CourseClassWare][type]']").val();
        post_data.append("courseware_type",courseware_type);
        var course_class_id=$("input[name='data[CourseClassWare][id]']").val();
        post_data.append("course_class_id",course_class_id);
        var xhr = null;
        if (window.XMLHttpRequest){// code for all new browsers
            xhr=new XMLHttpRequest();
        }else if (window.ActiveXObject){// code for IE5 and IE6
            xhr=new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            alert("Your browser does not support XMLHTTP.");return false;
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
                eval("var result="+xhr.responseText);
                if(result.code=='1'){
                    $("input[name='data[CourseClassWare][ware]']").val(result.message);
                    $(obj).val('');
                }else{
                    alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
        };
        xhr.open("POST", admin_webroot+'course_classes/ajax_upload_course');
        xhr.send(post_data);
    }
    // /media/courseware/a303636771166220f57bc4e206798991.jpg;/media/courseware/72515802d21c1ad7096f6cbb133da6cd.png
    function checkWareFile(){
    		var UploadFileType=[];
    		var filePath=$("input[name='data[CourseClassWare][ware]']").val().trim();
    		if(filePath!=""){
    			var filePathList=filePath.split(';');
    			for(var i=0;i<filePathList.length;i++) {
    				var fileMimeType=getContentType(filePathList[i]);
    				UploadFileType.push(fileMimeType);
    			}
    		}
    		return UploadFileType;
    }
    
    function getContentType(url) {
    		var MineType;
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if( xhr.readyState === 4 && xhr.status === 200 ) {
				MineType= xhr.getResponseHeader("Content-Type");
				MineType=MineType.split("/")[0];
			}
		}
		xhr.open("HEAD", url, false);
		xhr.send();
		return MineType;
    }

    function checkFile() {
        var obj = document.getElementById('course_class');
        var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
        if(suffix != 'csv'&&suffix != 'CSV'){
            alert("<?php echo $ld['file_format_csv']?>");
            obj.value="";
            return false;
        }
    }

    function learning_plan(btn,course_id){
    	 if(btn!=null)$(btn).button('loading');
        $.ajax({
            url: admin_webroot+"courses/learning_plan/"+course_id,
            type:"GET",
            data:{},
            dataType:"html",
            success: function(data){
			if(btn!=null)$(btn).button('reset');
			$("#learning_plan .am-modal-bd").html(data);
			$("#learning_plan select").selected({maxHeight:300,searchBox:'1',noSelectedText:j_please_select});
			if (!$("#learning_plan").hasClass('am-modal-active')) {
				$("#learning_plan").modal({'closeViaDimmer':false,'width':600});
			}
            }
        });
    }
    
    function course_class_detail(user_course_class_id){
		$.ajax({
			url: admin_webroot+"courses/course_log_detail/"+user_course_class_id,
			type:"POST",
			data:{},
			dataType:"json",
			success: function(data){
				if(data.code=='1'){
					$('#course_class_detail table tbody').html('');
					$.each(data.user_course_class,function(index,item){
						var LogHtml="<tr>";
						LogHtml+="<td>"+item['CourseClass']['name']+"</td>";
						LogHtml+="<td>"+Math.ceil(parseInt(item['UserCourseClassDetail']['read_time'])/60)+"</td>";
						LogHtml+="<td>"+item['UserCourseClassDetail']['created']+"</td>";
						LogHtml+="<td>"+item['UserCourseClassDetail']['modified']+"</td>";
						LogHtml+="</tr>";
						$('#course_class_detail table tbody').append(LogHtml);
					});
					if (!$("#course_class_detail").hasClass('am-modal-active')) {
						$("#course_class_detail").modal({'closeViaDimmer':false});
					}
				}
			}
		});
    }
    
    function batch_remove_chapter(course_id){
    		var batch_remove_chapter_ids=[];
    		var batch_remove_class_ids=[];
    		var batch_remove_ware_ids=[];
    		$("#course_chapter_list input[name='CourseChapter[]']:checked").each(function(){
    			batch_remove_chapter_ids.push($(this).val());
    		});
    		$("#course_chapter_list input[name='CourseClass[]']:checked").each(function(){
    			batch_remove_class_ids.push($(this).val());
    		});
    		$("#course_chapter_list input[name='CourseClassWare[]']:checked").each(function(){
    			batch_remove_ware_ids.push($(this).val());
    		});
	    	if(batch_remove_chapter_ids.length>0||batch_remove_class_ids.length>0||batch_remove_ware_ids.length>0){
    			if(confirm(js_confirm_deletion)){
	    			$.ajax({
					url: admin_webroot+"courses/ajax_batch_remove_chapter/",
					type:"POST",
					data:{'course_id':course_id,'chapter_ids':batch_remove_chapter_ids,'class_ids':batch_remove_class_ids,'ware_ids':batch_remove_ware_ids},
					dataType:"json",
					success: function(result){
						alert(result.message);
						if(result.code=='1')window.location.reload();
					}
				});
	    		}
    		}
    }
    
    function ajax_load_chapter(default_chapter){
    		default_chapter=typeof(default_chapter)!='undefined'?default_chapter:'';
    		var course_code=$("input[name='data[Course][code]']").val();
    		$.ajax({
			url: admin_webroot+"courses/ajax_course_class",
			type:"POST",
			data:{'course_code':course_code},
			dataType:"json",
			success: function(result){
				$("#class select[name='data[CourseClass][chapter_code]'] option").remove();
				if(result.code=='1'){
					$.each(result.data,function(index,item){
						var optgroup="<option value='"+item['CourseChapter']['code']+"' "+(default_chapter==item['CourseChapter']['code']?'selected':'')+">"+item['CourseChapter']['name']+"</option>";
						$("#class select[name='data[CourseClass][chapter_code]']").append(optgroup);
					});
				}else{
					var optgroup="<option value='0'>"+j_please_select+"</option>";
					$("#class select[name='data[CourseClass][chapter_code]']").append(optgroup);
				}
				$("#class select[name='data[CourseClass][chapter_code]']").trigger('changed.selected.amui');
			}
		});
    }
    
    function ajax_load_class(default_course){
    		default_course=typeof(default_course)!='undefined'?default_course:'';
    		var course_code=$("input[name='data[Course][code]']").val();
    		$.ajax({
			url: admin_webroot+"courses/ajax_course_class",
			type:"POST",
			data:{'course_code':course_code},
			dataType:"json",
			success: function(result){
				if(result.code=='1'){
					$("#ware select[name='data[CourseClassWare][course_class_code]'] *").remove();
					$.each(result.data,function(index,item){
						var optgroup="<optgroup label='"+item['CourseChapter']['name']+"'>";
						$.each(item.CourseClass,function(index2,item2){
							optgroup+="<option value='"+item2['code']+"' "+(default_course==item2['code']?'selected':'')+">"+item2['name']+"</option>";
						});
						optgroup+="</optgroup>";
						$("#ware select[name='data[CourseClassWare][course_class_code]']").append(optgroup);
					});
				}
				$("#ware select[name='data[CourseClassWare][course_class_code]']").trigger('changed.selected.amui');
			}
		});
    }
    
    function ajax_pre_couse_class(course_class_id){
    		course_class_id=typeof(course_class_id)!='undefined'?course_class_id:0;
    		var course_code=$("input[name='data[Course][code]']").val();
    		$.ajax({
			url: admin_webroot+"courses/ajax_course_detail",
			type:"POST",
			data:{'course_code':course_code,'view_model':'course_class_condition','course_class_id':course_class_id},
			dataType:"html",
			success: function(result){
				$("#class #course_class_condition").html(result);
			}
		});
    }
    
    
    function add_precondtion(btn,evaluation_code){
    		$(btn).button('loading');
    		$.ajax({
			url: admin_webroot+"preconditions/add/course/"+evaluation_code,
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
    
	function ajax_inivate_user_list(btn){
		var user_mobile=$("#inivate_user input[type='text'][name='user_mobile']").val().trim();
		if(user_mobile!=''){
			var user_select=$("#inivate_user select[name='user_id']");
			$.ajax({
				url: admin_webroot+"courses/ajax_inivate_user_list",
				type:"POST",
				data:{'user_mobile':user_mobile},
				dataType:"json",
				success: function(result){
					if(result.code=='1'){
						user_select.find("option[value!='0']").remove();
						$.each(result.data,function(index,item){
							user_select.append("<option value='"+item['id']+"'>"+(item['first_name']!=null&&item['first_name']!=''?item['first_name']:item['name'])+'/'+item['mobile']+"</option>");
						});
						if(result.data.length==1){
							$(user_select).find("option:last-child").prop('selected',true);
						}
					}else{
						user_select.find("option[value!='0']").remove();
						user_select.append("<option value='-1'>创建该用户</option>");
						$(user_select).find("option:last-child").prop('selected',true);
					}
				}
			});
		}
	}

	function ajax_inivate_user(btn){
		var user_mobile=$("#inivate_user input[type='text'][name='user_mobile']").val().trim();
		var inivate_user_id=$("#inivate_user select[name='user_id']").val();
		if(inivate_user_id=='-1'&&user_mobile!=''){
			if(!/^1[3-9]\d{9}$/.test(user_mobile)){
				alert('手机号格式错误');
				return false;
			}else{
				if(!confirm('确认创建该用户?')){
					return false;
				}
			}
		}else if(user_mobile==''||inivate_user_id=='0'){
			alert('请选择邀请用户');
			return false;
		}
		var PostData=$(btn).parents('form').serialize();
		$.ajax({
				url: admin_webroot+"courses/ajax_inivate_user",
				type:"POST",
				data:PostData,
				dataType:"json",
				success: function(result){
					alert(result.message);
					if(result.code=='1'){
						$("#inivate_user").modal('close');
					}
				}
			});
	}
</script>