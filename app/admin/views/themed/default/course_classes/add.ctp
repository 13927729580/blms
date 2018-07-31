<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
</style>
<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
        </ul>
    </div>
    <?php echo $form->create('/course_classes',array('action'=>'add/'.$code,'id'=>'course_class_add_form','name'=>'course_class_add','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
    <input type="hidden" name="data[CourseClass][id]" id="_id" value="" />
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
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][code]" id="code" value=""></div>
                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[CourseClass][name]" id="name" value=""></div>
                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">时长（小时）</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="courseware_hour" name="data[CourseClass][courseware_hour]" value="0"/></div>
                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
				    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[CourseClass][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">课件类型</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                        		<select name='data[CourseClass][courseware_type]' data-am-selected onchange="select_courseware_type(this.value)">
                        			<?php if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){foreach($resource_info['courseware_type'] as $k=>$v){ ?>
                        			<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        			<?php }} ?>
                        		</select>
                        	</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">课件</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                    		<input type="text" name="data[CourseClass][courseware]" value="">
                    		<input type='file' name="courseware" id="courseware" onchange="uploadcourse(this)" />
                        	</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                            <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                                <div class="am-form-group">
                                    <textarea cols="80" id="elm" name="data[CourseClass][description]" rows="10" style="width:auto;height:300px;"></textarea>
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
                        <div class="am-u-lg-5 am-u-md-4 am-u-sm-4"><input type="text" id="orderby" name="data[CourseClass][orderby]" value="0"/></div>
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