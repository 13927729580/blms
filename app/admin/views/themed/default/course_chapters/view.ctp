<style>
    #class form{max-height:300px;overflow-y:scroll;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>

<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/course_chapters',array('action'=>'view/'.$course_chapter_info["CourseChapter"]["id"],'id'=>'course_chapter_edit_form','class'=>'am-form am-form-horizontal','name'=>'course_chapter_edit','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
        <input type="hidden" name="data[CourseChapter][id]" id="_id" value="<?php echo $course_chapter_info['CourseChapter']['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#course_class">课时列表</a></li>
                </ul>
                <div class="am-text-right save_action">
                    <button type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_submit'] ?></button>
                    <button type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_reset'] ?></button>
                </div>
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][code]" id="code" value="<?php echo $course_chapter_info['CourseChapter']['code'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseChapter][name]" id="name" value="<?php echo $course_chapter_info['CourseChapter']['name'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" <?php if($course_chapter_info['CourseChapter']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseChapter][status]" <?php if($course_chapter_info['CourseChapter']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <textarea cols="30" id="elm" name="data[CourseChapter][description]" rows="10" style="width:auto;height:300px;"><?php echo @$course_chapter_info['CourseChapter']['description'];?></textarea>
                            <script>
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor = K.create('#elm', {width:'100%',
                                        langType : '',cssPath : '/css/index.css',filterMode : false});
                                });
                            </script>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['orderby'] ?></label>
                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="orderby" name="data[CourseChapter][orderby]" value="<?php echo $course_chapter_info['CourseChapter']['orderby'];?>"/></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Course_class_pancel'}">课时列表</h4>
            </div>
            <div id="Course_class_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="course_class" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <p style="text-align:right;">
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius"  onclick="add_class();">
                            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
                        </a>
                    </p>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th><?php echo $ld['code']?></th>
                            <th><?php echo $ld['name'];?></th>
                            <th>时长（小时）</th>
                            <th><?php echo $ld['status']?></th>
                            <th><?php echo $ld['operate']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($course_class_info) && sizeof($course_class_info)>0){foreach($course_class_info as $k=>$v){ ?>
                            <tr >
                                <td><?php echo $v['CourseClass']['code']; ?></td>
                                <td><?php echo $v['CourseClass']['name']; ?></td>
                                <td><?php echo $v['CourseClass']['courseware_hour']; ?></td>
                                <td>
                                    <?php if ($v['CourseClass']['status'] == 1) {?>
                                        <span class="am-icon-check am-yes"></span>
                                    <?php }elseif($v['CourseChapter']['status'] == 0){ ?>
                                        <span class="am-icon-close am-no"></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/course_classes/view/'.$v['CourseClass']['id']); ?>">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_classes/remove/<?php echo $v['CourseClass']['id'] ?>');">
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
        <?php echo $form->end(); ?>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="class">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">添加课时</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[CourseClass][chapter_code]" value="<?php echo $course_chapter_info['CourseChapter']['code'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][code]" id="class_code" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][name]" id="class_name" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">时长（小时）</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="courseware_hour" name="data[CourseClass][courseware_hour]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">课件类型</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                            <select name='data[CourseClass][courseware_type]' data-am-selected onchange="select_courseware_type(this.value)">
                                <?php if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){foreach($resource_info['courseware_type'] as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">课件</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                            <input type="text" name="data[CourseClass][courseware]" value="">
                            <input type='file' name="courseware" id="courseware" onchange="uploadcourse(this)" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                            <textarea cols="35" id="class_elm" name="data[CourseClass][description]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script>
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor = K.create('#class_elm', {width:'100%',
                                        langType : '',cssPath : '/css/index.css',filterMode : false});
                                });
                            </script>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['orderby'] ?></label>
                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="orderby" name="data[CourseClass][orderby]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-text-left">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
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

    function add_class(){
        $("#class").modal('open');
    }

    function ajax_modify_submit(btn){
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
                    alert(data.message);
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function select_courseware_type(courseware_type){
        $("#courseware").attr('accept','application/pdf').attr('multiple',false);
        if(courseware_type=='txt'){
            $("input[name='data[CourseClass][courseware]']").parent().parent().hide();
        }else if(courseware_type=='pdf'){
            $("input[name='data[CourseClass][courseware]']").parent().parent().show();
        }else if(courseware_type=='gallery'){
            $("#courseware").attr('accept','image/*').attr('multiple','multiple');
            $("input[name='data[CourseClass][courseware]']").parent().parent().show();
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
                        alert('最大文件限制为5M,'+file_name+'当前为'+file_size+'M');
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
            alert("Your browser does not support XMLHTTP.");return false;
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
                eval("var result="+xhr.responseText);
                if(result.code=='1'){
                    $("input[name='data[CourseClass][courseware]']").val(result.message);
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
</script>