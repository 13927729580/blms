<style>
    #chapter form{max-height:300px;overflow-y:scroll;}
    #class form{max-height:300px;overflow-y:scroll;}
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
    .am-panel-bd{padding: 1.25rem!important;}
    .listtable_div_top {border-top: 1px solid #ddd;}
    .am-g .am-panel .am-panel-hd{padding:8px 15px;}
    .am-radio-inline{padding-top: 0!important;}
    .am-u-lg-2.am-u-md-2.am-u-sm-12{margin-bottom: 10px;text-align: left;}
    .am-u-lg-3.am-u-md-3.am-u-sm-12{margin-bottom: 10px;}
    @media screen and (max-width: 400px) {
        .am-modal{min-width: 310px!important;left: 44%;}
    }
    <?php if($organizations_id!=''){ ?>
    .am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
    .am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
    .am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
    .am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
    <?php } ?>
</style>
<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-g am-g-fixed">
	<?php if(isset($organizations_name)&&!empty($organizations_name)){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>
	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-product <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" style="padding:0;">
	    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	        <?php if($organizations_id!=''){$aa = '?organizations_id='.$organizations_id;}else{$aa = '';} ?>
	        <?php echo $form->create('/courses',array('action'=>'edit/'.$course_info["Course"]["id"].$aa,'id'=>'course_edit_form','name'=>'course_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
	        <input type="hidden" name="data[Course][id]" id="_id" value="<?php echo $course_info['Course']['id'];?>" />
	        <input type="hidden" name="data[Course][code]" value="<?php echo $course_info['Course']['code'];?>">
	        <div class="btnouter am-text-right" data-am-sticky="{top:'50px',animation:'slide-top'}" style="margin-bottom:0;">
	            <?php if(isset($this->params['url']['user_id'])==0){ ?>
	            <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius">确认</button>
	            <?php } ?>
	            <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius">重置</button>
	        </div>
	        <!-- 导航结束 -->
	        <?php //pr($course_info); ?>
	        <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	          <span style="float:left;"><?php echo isset($course_info['Course']['name'])?$course_info['Course']['name']:''; ?></span>
	          <div class="am-cf"></div>
	        </div>
	        <div class="am-panel am-panel-default course_detail" style="margin-top: 10px;">
	            <div class="am-panel-hd" style="font-size: 15px;">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}">基本信息&nbsp;</h4>
	            </div>
	            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in" style="padding-top: 10px;">
	                <div id="basic_information" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd" style="padding: 0px!important;">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">课程类型</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select id="course_type_code" name="data[Course][course_type_code]" data-am-selected="{maxHeight:100}" onchange="course_type_code_select(this.value)">
	                                <?php if(empty($course_info['Course']['course_type_code'])&&sizeof($course_type)>1){?>
	                                    <option value=''><?php echo $ld['please_select'];?></option>
	                                <?php }?>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($course_type as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['CourseType']['code'];?>" <?php if($course_info['Course']['course_type_code']==$t['CourseType']['code'])echo "selected"?>><?php echo $t['CourseType']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="course_type_code_zidingyi" class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="display: none;">
	                            <input type="text" style="padding:5px;" name="course_type_code_1">
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 3px;">可见性</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="0" <?php if($course_info['Course']['visibility']==0)echo "checked"?>>公开</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="2" <?php if($course_info['Course']['visibility']==2)echo "checked"?>>限定</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="1" <?php if($course_info['Course']['visibility']==1)echo "checked"?>>仅自己</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">课程分类</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select id="course_type_code" name="data[Course][course_category_code]" data-am-selected="{maxHeight:100}" onchange="course_category_code_select(this.value)">
	                                <?php if(empty($course_info['Course']['course_category_code'])&&sizeof($course_category)>1){?>
	                                    <option value=''><?php echo $ld['please_select'];?></option>
	                                <?php }?>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($course_category as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['CourseCategory']['code'];?>" <?php if($course_info['Course']['course_category_code']==$t['CourseCategory']['code'])echo "selected"?>><?php echo $t['CourseCategory']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="course_category_code_zidingyi" class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="display: none;">
	                            <input type="text" style="padding:5px;" name="course_category_code_1">
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <?php //pr($resource_info); ?>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">难度级别</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select name='data[Course][level]' data-am-selected="{maxHeight:100}">
	                                <?php if(isset($resource_info['course_level'])&&sizeof($resource_info['course_level'])>0){foreach($resource_info['course_level'] as $k=>$v){ ?>
	                                    <option value="<?php echo $k; ?>" <?php if($course_info['Course']['level']==$k)echo "selected"?>><?php echo $v; ?></option>
	                                <?php }} ?>
	                            </select>
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;">名称</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" name="data[Course][name]" id="name" value="<?php echo $course_info['Course']['name'];?>"></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 3px;"><?php echo $ld['status'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][status]" <?php if($course_info['Course']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][status]" <?php if($course_info['Course']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 4px;">是否推荐</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][recommend_flag]" <?php if($course_info['Course']['recommend_flag'] == 1){?>checked="checked"<?php }?> value="1"/>是</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][recommend_flag]" <?php if($course_info['Course']['recommend_flag'] == 0){?>checked="checked"<?php }?> value="0"/>否</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 9px;">图片</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-form-file">
	                            <div class="am-form-group am-form-file">
	                                <button type="button" class="am-btn am-btn-default am-btn-sm">
	                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
	                                <<span class="" style="font-size:12px;">(推荐尺寸150*150)</span>
	                                <input type="file" multiple name="org_logo" onchange="ajax_upload_media(this,this.id)" id="org_logo">
	                                <input type="hidden" multiple name="data[Course][img]" value="<?php echo $course_info['Course']['img'] ?>">
	                            </div>
	                            <?php if(isset($course_info['Course']['img'])&&$course_info['Course']['img']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$course_info['Course']['img'] ?>" data-rel="<?php echo $server_host.$course_info['Course']['img'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            <img src="" data-rel="" alt="" id="img_logo" style="display:none;max-width:100%;">
	                            <?php } ?>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label"><?php echo '简单描述' ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
	                        	<textarea name="data[Course][meta_description]" rows="10" style="height:300px;"><?php echo @$course_info['Course']['meta_description'];?></textarea>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">描述</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
	                            <textarea cols="30" id="elm" name="data[Course][description]" rows="10" style="width:auto;height:300px;"><?php echo @$course_info['Course']['description'];?></textarea>
	                            <script>
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
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">图片</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                            <input id="img" type="text" name="data[Course][img]" value="<?php echo $course_info['Course']['img'];?>" />
	                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('img')" value="选择图片"  style="margin-top:5px;"/>
	                            <div class="img_select" style="margin:5px;">
	                                <?php echo $html->image($course_info['Course']['img']!=''?$v['Course']['img']:"/theme/default/images/default.png",array('id'=>'show_img','style'=>'width:150px;'))?>
	                            </div>
	                        </div>
	                    </div> -->
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;"><?php echo $ld['price'] ?></label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="price" name="data[Course][price]" value="<?php echo $course_info['Course']['price'];?>"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;">总时长（分钟）</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6" style="padding-top: 13px;"><?php echo intval($course_info['Course']['hour']);?></div>
	                    </div>
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">可获经验值</label>
	                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="experience_value" name="data[Course][experience_value]" value="<?php echo $course_info['Course']['experience_value'];?>"/></div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">点击数</label>
	                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Course][clicked]" value="<?php echo $course_info['Course']['clicked'];?>"/></div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div> -->
	                    <input type="hidden" id="experience_value" name="data[Course][experience_value]" value="<?php echo $course_info['Course']['experience_value'];?>"/>
	                    <input type="hidden" id="clicked" name="data[Course][clicked]" value="<?php echo $course_info['Course']['clicked'];?>"/>
	                </div>
	            </div>
	        </div>
	        <div class="am-panel am-panel-default course_detail">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" style="line-height: 28px;position: relative;">
	                章节列表
	                <div style="position:absolute;right: 0;top: 0;">
	                    <?php if(isset($course_chapter_data) && sizeof($course_chapter_data)>0){?>
	                    <a style="font-size: 12px;color: #fff;" class="mt am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" href="<?php echo $html->url('/courses/download_csv_example/'.$course_info['Course']['code']); ?>">
	                        <span class="am-icon-plus"></span>导出
	                    </a>
	                    <?php } ?>
	                    <a style="font-size: 12px;color: #fff;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="upload_course();">
	                        <span class="am-icon-plus"></span>上传
	                    </a>
	                    <a style="font-size: 12px;color: #fff;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_chapter();">
	                        <span class="am-icon-plus"></span><?php echo $ld['add'] ?>
	                    </a>
	                </div>
	                </h4>
	            </div>
	            <div id="Course_chapter_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="course_chapter" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd" id="style">
	                    <p style="text-align:right;">
	                    <?php //pr($course_chapter_data); ?>
	                        
	                    </p>
	                    <div id="tablelist">
	                        <div class="am-panel-group am-panel-tree" id="accordion">
	                            <div class="listtable_div_btm">
	                                <div class="am-panel-hd">
	                                    <div class="am-panel-title">
	                                        <div style="width: 40%;display: inline-block;">名称</div>
	                                        <div style="width: 15%;display: inline-block;">排序</div>
	                                        <div style="width: 10%;display: inline-block;">状态</div>
	                                        <div style="width: 28%;display: inline-block;">操作</div>
	                                        <div style="clear:both;"></div>
	                                    </div>
	                                </div>
	                            </div>
	                            <?php if(isset($course_chapter_data) && sizeof($course_chapter_data)>0){foreach($course_chapter_data as $k=>$v){?>
	                                <?php //pr($v) ?>
	                                <div>
	                                    <div class="listtable_div_top am-panel-body" >
	                                        <div class="am-panel-bd fuji">
	                                            <div style="width: 40%;display: inline-block;">
	                                                <label data-am-collapse="{parent: '#accordion', target: '#course_<?php echo $v['CourseChapter']['code']?>'}" class="<?php echo (isset($v['CourseClass'])&&$v['CourseClass'][0]['id']!='')?"am-icon-plus":"am-icon-minus";?>"></label>
	                                                <?php echo $v['CourseChapter']['name']; ?>
	                                            </div>
	                                            <div style="width: 15%;display: inline-block;"><?php echo $v['CourseChapter']['orderby']; ?></div>
	                                            <div style="width: 10%;display: inline-block;">
	                                                <?php if ($v['CourseChapter']['status'] == 1) {?>
	                                                    <span class="am-icon-check am-yes"></span>
	                                                <?php }elseif($v['CourseChapter']['status'] == 0){ ?>
	                                                    <span class="am-icon-close am-no"></span>
	                                                <?php } ?>
	                                            </div>
	                                            <div style="width: 28%;display: inline-block;">
	                                                <a style="margin-bottom: 5px;" class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"  onclick="add_class('<?php echo $v['CourseChapter']['code']; ?>');">
	                                                    <?php echo $ld['add'] ?>课时
	                                                </a>
	                                                <a style="margin-bottom: 5px;" class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_chapter(<?php echo $v['CourseChapter']['id']; ?>)">
	                                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                                </a>
	                                                <a style="margin-bottom: 5px;" class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/course_chapters/remove/<?php echo $v['CourseChapter']['id'] ?>');">
	                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                                </a>
	                                            </div>
	                                            <div style="clear:both;"></div>
	                                        </div>
	                                        <?php //pr($course_chapter_data); ?>
	                                        <?php if(isset($course_chapter_data[$v['CourseChapter']['code']]['CourseClass'])&& sizeof($course_chapter_data[$v['CourseChapter']['code']]['CourseClass'])>0&&$course_chapter_data[$v['CourseChapter']['code']]['CourseClass'][0]['id']!=''){?>
	                                            <div class="am-panel-collapse am-collapse am-panel-child" id="course_<?php echo $v['CourseChapter']['code']?>">
	                                                <?php $j=0; foreach($course_chapter_data[$v['CourseChapter']['code']]['CourseClass'] as $kk=>$vv){$j++;?>
	                                                    <div class="am-panel-bd am-panel-childbd" style="padding-top: 0!important;">
	                                                        <div style="width: 40%;display: inline-block;">
	                                                            <label data-am-collapse="{parent: '#accordion', target: '#course_<?php echo $vv['code']?>'}" class="am-icon-minus"  style="padding-left:30px;"></label>
	                                                            <?php echo $vv['name']; ?>
	                                                        </div>
	                                                        <div style="width:15%;display: inline-block;"><?php echo $vv['orderby']; ?></div>
	                                                        <div style="width: 10%;display: inline-block;">
	                                                            <?php if ($vv['status'] == 1) {?>
	                                                                <span class="am-icon-check am-yes"></span>
	                                                            <?php }elseif($vv['status'] == 0){ ?>
	                                                                <span class="am-icon-close am-no"></span>
	                                                            <?php } ?>
	                                                        </div>
	                                                        <div style="width: 27%;display: inline-block;">
	                                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" style="margin-bottom: 5px;" onclick="edit_class(<?php echo $vv['id']; ?>)">
	                                                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                                            </a>
	                                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;"  style="margin-bottom: 5px;" onclick="list_delete_submit(web_base+'/course_classes/remove/<?php echo $vv['id'] ?>');">
	                                                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                                            </a>
	                                                        </div>
	                                                        <div style="clear:both;"></div>
	                                                    </div>
	                                                <?php }?>
	                                            </div>
	                                        <?php }?>
	                                    </div>
	                                </div>
	                            <?php }}else{?>
	                                <div>
	                                    <div style="padding:10px;text-align: center;border-top: 1px solid #ccc;">暂无数据</div>
	                                </div>
	                            <?php }?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
</div>
<div class="am-modal am-modal-no-btn" id="upload_course" style="font-size: 1.4rem;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">批量上传</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('courses',array('action'=>'/preview/'.$course_info['Course']['code'],'class'=>' am-form am-form-horizontal',"enctype"=>"multipart/form-data"));?>
            <div class="am-panel-bd">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:21px;">上传批量csv文件</label>
                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">

                        <div class="am-form-group am-form-file" style="margin-top: 10px;text-align: left;">
                            <button type="button" class="am-btn am-btn-default am-btn-sm">
                            <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
                            <input type="file" multiple name="course_class" onchange="checkFile()" id="course_class">
                            <div id="course_class_text" style="margin-top: 10px;"></div>
                        </div>
                        <!-- <p style="margin:10px 0px;">
                            <input name="course_class" id="course_class" size="40" type="file" style="height:22px;" onchange="checkFile()"/>
                        </p> -->
                        <p style="padding:6px 0;">注意上传文件编码格式UTF-8编码（CSV文件中一次上传数量最好不要超过1000，CSV文件大小最好不要超过500K.）</p>
                    </div>
                </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                <?php echo $html->link('下载csv样式',"/courses/download_csv_example/".$course_info['Course']['code'],'',false,false);?>
                            </div>
                        </div>
                    </div>
                <div class="am-text-left">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top:21px;">&nbsp;</label>
                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                        <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">提交</button>
                    </div>
                    <div class="am-cf"></div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="chapter" style="font-size: 1.4rem;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">添加章节</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[CourseChapter][id]" id="chapter_id" value="">
                <input type="hidden" name="data[CourseChapter][course_code]" value="<?php echo $course_info['Course']['code'];?>">
    		  <input type="hidden" name="data[CourseChapter][code]" id="chapter_code" value="">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">名称</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-10"><input type="text" name="data[CourseChapter][name]" id="chapter_name" value=""></div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">状态</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8 am-text-left" style="margin-top: 5px;">
                            <!-- <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="0"/>无效</label> -->
                            <label class="am-radio-inline">
                                <input type="radio" name="data[CourseChapter][status]" value="1" data-am-ucheck>
                                有效
                            </label>
                            <label class="am-radio-inline">
                                <input type="radio" name="data[CourseChapter][status]" value="0" data-am-ucheck checked>
                                无效
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">描述</label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12">
                            <textarea cols="35" id="chapter_elm" name="data[CourseChapter][description]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script type="text/javascript">
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor=K;
                                    K.create("#chapter_elm", {
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
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">顺序</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" id="chapter_orderby" name="data[CourseChapter][orderby]" value="0"/></div>
                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
                    </div>
                    <div class="am-form-group" style="margin-bottom: 0;">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">&nbsp;</label>
                        <div id="chapter_check" class="am-u-lg-6 am-u-md-6 am-u-sm-8" style="color: red;text-align: left;"></div>
                    </div>
                    <div class="am-text-left">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label am-text-left">&nbsp;</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)">确认</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="class" style="font-size: 1.4rem;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="class_title">添加课时</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[CourseClass][id]" id="class_id" value="">
                <input type="hidden" name="data[CourseClass][chapter_code]" id="class_chapter_code" value="">
    		  <input type="hidden" name="data[CourseClass][code]" id="class_code" value="">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">名称</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-10"><input type="text" name="data[CourseClass][name]" id="class_name" value=""></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left" style="padding-right: 0px;">时长（分）</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-10"><input type="text" id="courseware_hour" name="data[CourseClass][courseware_hour]" value="0" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">状态</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8 am-text-left">
                            <!-- <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="0"/>无效</label> -->
                            <label class="am-radio-inline">
                                <input type="radio" name="data[CourseClass][status]" value="1" data-am-ucheck>
                                有效
                            </label>
                            <label class="am-radio-inline">
                                <input type="radio" name="data[CourseClass][status]" value="0" data-am-ucheck checked>
                                无效
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">课件类型</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
                            <select name='data[CourseClass][courseware_type]' data-am-selected onchange="select_courseware_type(this.value)">
                                <?php if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){foreach($resource_info['courseware_type'] as $k=>$v){ ?>
                                <?php if($k==773){$k='txt';}if($k==772){$k='txt';}if($k==771){$k='pdf';}if($k==791){$k='gallery';}if($k==823){$k='local_video';}if($k==825){$k='external_video';}if($k==827){$k='youkuid';} ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">课件</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                            <input type="text" name="data[CourseClass][courseware]" value="">
                            <!-- <input type='file' name="courseware" id="courseware" onchange="uploadcourse(this)" /> -->
                            <div class="am-form-group am-form-file" style="margin-top: 10px;text-align: left;">
                                <button type="button" class="am-btn am-btn-default am-btn-sm">
                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
                                <input type="file" multiple name="courseware" onchange="uploadcourse(this)" id="courseware">
                                <div id="courseware_text" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">描述</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
                            <textarea cols="35" id="class_elm" name="data[CourseClass][description]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script type="text/javascript">
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor=K;
                                    K.create("#class_elm", {
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
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">排序</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" id="class_orderby" name="data[CourseClass][orderby]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group" style="margin-bottom: 0;">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-text-left">&nbsp;</label>
                        <div id="class_check" class="am-u-lg-6 am-u-md-6 am-u-sm-8" style="color: red;text-align: left;"></div>
                    </div>
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_class_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function chechk_form(){
        var name_obj = document.getElementById("name");
        var code_obj = document.getElementById("code");
        if(name_obj.value==""){
            seevia_alert("标题不能为空");
            return false;
        }
        return true;
    }

    function add_chapter(){
        $("#chapter_title").html("添加章节");
        $("#chapter_id").val("");
        $("#chapter_code").val("");
        $("#chapter_name").val("");
        editor.html("#chapter_elm","");
        $("#chapter textarea[name='data[CourseChapter][description]']").val("");
        $("#chapter_orderby").val("0");
        $("#chapter").modal('open');
    }

    function edit_chapter(id){
        $.ajax({
            url: web_base+"/course_chapters/ajax_edit/"+id,
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
                    editor.html("#chapter_elm",data.data.CourseChapter.description);
                    $("#chapter textarea[name='data[CourseChapter][description]']").val(data.data.CourseChapter.description);
                    $("#chapter_orderby").val(data.data.CourseChapter.orderby);
                    $("#chapter").modal('open');
                }else{
                    seevia_alert(data.message);
                }
            }
        });
        $("#chapter").modal('open');
    }

    function upload_course(){
        $("#upload_course").modal('open');
    }

    function ajax_modify_submit(btn){
        var name_obj = document.getElementById("chapter_name");
        if(name_obj.value==""){
            $('#chapter_check').text('标题不能为空');
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url:web_base+"/course_chapters/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    $('#chapter').modal('close');
                    seevia_alert_func(jump_reload,data.message);
                    //window.location.reload();
                }else{
                    $('#chapter_check').text(data.message);
                    //seevia_alert(data.message);
                }
            }
        });
    }

    function add_class(code){
        $("#class_chapter_code").val(code);
        $("#class select[name='data[CourseClass][courseware_type]'] option:eq(0)" ).attr('selected',true);
        $("#class select[name='data[CourseClass][courseware_type]']").trigger('changed.selected.amui');
        $("#class_title").html("添加课时");
        $("#class_id").val("");
        $("#class_code").val("");
        $("#class_name").val("");
        $("input[name='data[CourseClass][courseware]']").val('');
        $("#courseware").val("");
        $("#courseware_hour").val("0");
        editor.html("#class_elm","");
        $("#class textarea[name='data[CourseClass][description]']").val("");
        $("#class_orderby").val("0");
        $("#class").modal('open');
    }
    function edit_class(id){
        $.ajax({
            url: web_base+"/course_classes/ajax_edit/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    if (data.data.CourseClass.status == 1){
                        $("#class .am-radio-inline input[value='1']").attr('checked',true);
                    }
                    if (data.data.CourseClass.status == 0){
                        $("#class .am-radio-inline input[value='0']").attr('checked',true);
                    }
                    $("#class_chapter_code").val(data.data.CourseClass.chapter_code);
                    $("#class select[name='data[CourseClass][courseware_type]'] option[value='"+data.data.CourseClass.courseware_type+"']" ).attr('selected',true);
                    $("#class select[name='data[CourseClass][courseware_type]']").trigger('changed.selected.amui');
                    $("#class_title").html("编辑课时");
                    $("#class_id").val(id);
                    $("#class_code").val(data.data.CourseClass.code);
                    $("#class_name").val(data.data.CourseClass.name);
                    $("input[name='data[CourseClass][courseware]']").val(data.data.CourseClass.courseware);
                    $("#courseware_hour ").val(parseInt(data.data.CourseClass.courseware_hour));
                    editor.html("#class_elm",data.data.CourseClass.description);
                    $("#class textarea[name='data[CourseClass][description]']").val(data.data.CourseClass.description);
                    $("#class_orderby").val(data.data.CourseClass.orderby);
                    $("#class").modal('open');
                }else{
                    seevia_alert(data.message);
                }
            }
        });
        $("#class").modal('open');
    }

    function ajax_class_modify_submit(btn){
        var name_obj = document.getElementById("class_name");
        if(name_obj.value==""){
            $('#class_check').text('标题不能为空');
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        console.log(postData);
        $.ajax({
            url: web_base+"/course_classes/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    // seevia_alert(data.message);
                    // window.location.reload();
                    $('#class').modal('close');
                    seevia_alert_func(jump_reload,data.message);
                }else{
                    $('#class_check').text(data.message);
                }
            }
        });
    }

    function select_courseware_type(courseware_type){
        if(courseware_type=='txt'){
            	$("input[name='data[CourseClass][courseware]']").parent().parent().hide();
        }else{
        	 $("input[name='data[CourseClass][courseware]']").parent().parent().show();
	        if(courseware_type=='external_video'||courseware_type=='youkuid'){
	        	$("#courseware").hide();
	        }else if(courseware_type=='pdf'){
	        	$("#courseware").show();
	        	$("#courseware").attr('accept','application/pdf').attr('multiple',false);
	        }else if(courseware_type=='gallery'){
	        	$("#courseware").show();
	            	$("#courseware").attr('accept','image/*').attr('multiple','multiple');
	        }
        }
    }

    function uploadcourse(obj){
        var files = obj.files;
        var post_data = new FormData();
        if (files && files.length){
            for(var i=0;i<files.length;i++){
                var file = files[i];
                var file_name=file.name;
                var reader = new FileReader();//新建一个FileReader
                reader.readAsText(file, "UTF-8");//读取文件
                reader.onload = function(e){ //读取完文件之后会回来这里
                    var file_size=Math.round(e.total/1024/1024);
                    if(file_size>5){
                        seevia_alert('最大文件限制为5M,'+file_name+'当前为'+file_size+'M');
                        return false;
                    }
                }
                post_data.append("courseware[]",file);
            }
        }else{
            return false;
        }
        var courseware_type=$("select[name='data[CourseClass][courseware_type]']").val();
        post_data.append("courseware_type",courseware_type);
        var course_class_id=$("input[name='data[CourseClass][id]']").val();
        post_data.append("course_class_id",course_class_id);
        var xhr = null;
        if (window.XMLHttpRequest){// code for all new browsers
            xhr=new XMLHttpRequest();
        }else if (window.ActiveXObject){// code for IE5 and IE6
            xhr=new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            seevia_alert("Your browser does not support XMLHTTP.");return false;
        }
        //console.log(xhr);
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
                eval("var result="+xhr.responseText);
                if(result.code=='1'){
                    $("input[name='data[CourseClass][courseware]']").val(result.message);
                    $(obj).val('');
                }else{
                    seevia_alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
        };
        xhr.open("POST", web_base+'/course_classes/ajax_upload_course');
        xhr.send(post_data);
    }
    
    function checkFile() {
		var obj = document.getElementById('course_class');
		var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
        console.log(obj.value);
		if(suffix != 'csv'&&suffix != 'CSV'){
	 		seevia_alert("CSV文件格式错误！");
	 		obj.value="";
            $('#course_class_text').text('');
	 		return false;
		}
        $('#course_class_text').text(obj.value);
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
    function select_img(id_str){
        window.open(web_base+'/image_spaces/select_image/'+id_str+"/", 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
    }

    function delete_share(share_id){
        var aa = function(){
            $.ajax({
                type: "POST",
                url:web_base+'/courses/delete_share/'+share_id,
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
        }
        seevia_alert_func(aa,"确定删除？");
    }

    function course_type_code_select(value){
        if(value=='-1'){
            $('#course_type_code_zidingyi').css('display','');
        }else{
            $('#course_type_code_zidingyi').css('display','none');
        }
    }

    function course_category_code_select(value){
        if(value=='-1'){
            $('#course_category_code_zidingyi').css('display','');
        }else{
            $('#course_category_code_zidingyi').css('display','none');
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
        var org_id = '<?php echo $course_info['Course']['id'] ?>';
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