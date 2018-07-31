<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/course_classes',array('action'=>'view/'.$course_class_info["CourseClass"]["id"],'id'=>'course_class_edit_form','name'=>'course_class_edit','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
        <input type="hidden" name="data[CourseClass][id]" id="_id" value="<?php echo $course_class_info['CourseClass']['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
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
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][code]" id="code" value="<?php echo $course_class_info['CourseClass']['code'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][name]" id="name" value="<?php echo $course_class_info['CourseClass']['name'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">时长（小时）</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="courseware_hour" name="data[CourseClass][courseware_hour]" value="<?php echo $course_class_info['CourseClass']['courseware_hour'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" <?php if($course_class_info['CourseClass']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" <?php if($course_class_info['CourseClass']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">课件类型</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <select name='data[CourseClass][courseware_type]' data-am-selected onchange="select_courseware_type(this.value)">
                                <?php if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){foreach($resource_info['courseware_type'] as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php echo $course_class_info['CourseClass']['courseware_type']==$k?'selected':''; ?>><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">课件</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input type="text" name="data[CourseClass][courseware]" value="<?php echo $course_class_info['CourseClass']['courseware'];?>">
                            <input type='file' name="courseware" id="courseware" onchange="uploadcourse(this)" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <textarea cols="30" id="elm" name="data[CourseClass][description]" rows="10" style="width:auto;height:300px;"><?php echo @$course_class_info['CourseClass']['description'];?></textarea>
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
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="orderby" name="data[CourseClass][orderby]" value="<?php echo $course_class_info['CourseClass']['orderby'];?>"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>
<script type='text/javascript'>
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
                	var coursewareinfo=$("input[name='data[CourseClass][courseware]']").val().trim();
                	if(courseware_type=='gallery'&&coursewareinfo!=''){
                		coursewareinfo+=";"+result.message;
                	}else{
                		coursewareinfo=result.message;
                	}
                    $("input[name='data[CourseClass][courseware]']").val(coursewareinfo);
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