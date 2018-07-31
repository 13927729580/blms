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
    <?php echo $form->create('/course_chapters',array('action'=>'add/'.$code,'id'=>'course_chapter_add_form','name'=>'course_add','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
    <input type="hidden" name="data[CourseChapter][id]" id="_id" value="" />
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
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][code]" id="code" value=""></div>
                		<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][name]" id="name" value=""></div>
                		<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                            <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                                <div class="am-form-group">
                                    <textarea cols="80" id="elm" name="data[CourseChapter][description]" rows="10" style="width:auto;height:300px;"></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm', {width:'100%',
                                                langType : '',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['orderby'] ?></label>
                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="orderby" name="data[CourseChapter][orderby]" value="0"/></div>
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
<script>
function chechk_form(){
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
</script>