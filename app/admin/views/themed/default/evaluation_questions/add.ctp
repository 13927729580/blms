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
    <?php echo $form->create('/evaluation_questions',array('action'=>'add/'.$code,'id'=>'evaluation_question_add_form','name'=>'evaluation_question_add','type'=>'POST','onsubmit'=>"return check_all();"));?>
    <input type="hidden" name="data[EvaluationQuestion][id]" id="_id" value="" />
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
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" onchange="check_code(this)" name="data[EvaluationQuestion][code]" id="code" value=""></div>
                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">题目</label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                            <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                                <div class="am-form-group">
                                    <textarea cols="80" id="elm1" name="data[EvaluationQuestion][name]" rows="10" style="width:auto;height:300px;"></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm1', {width:'100%',
                                                langType : '',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" value="1"/>多选</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][question_type]" value="0"/>单选</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">正确答案</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="right_answer" name="data[EvaluationQuestion][right_answer]" value=""/></div>
                    	<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
				    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">题目解析</label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                            <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                                <div class="am-form-group">
                                    <textarea cols="80" id="elm2" name="data[EvaluationQuestion][analyze]" rows="10" style="width:auto;height:300px;"></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm2', {width:'100%',
                                                langType : '',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
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
var code_check=true;
function check_code(obj){
	code_check=false;
	var code=obj.value;
	if(code==""){return false;}
	$.ajax({url: admin_webroot+"evaluation_questions/check_code",
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
</script>