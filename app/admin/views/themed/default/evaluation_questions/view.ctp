<form method='POST' class='am-form am-form-horizontal'>
	<input type="hidden" name="data[EvaluationQuestion][id]" value="<?php echo isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['id']:'0'; ?>">
	<input type="hidden" name="data[EvaluationQuestion][evaluation_code]" value="<?php echo isset($evaluation_info['Evaluation'])?$evaluation_info['Evaluation']['code']:''; ?>">
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'] ?></label>
		<div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[EvaluationQuestion][code]" value="<?php echo isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['code']:''; ?>"></div>
		<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">题目</label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
			<?php echo $this->element('editor',array('editorName'=>"data[EvaluationQuestion][name]",'editorId'=>'question_elm1_'.rand(0,10),'editorValue'=>isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['name']:'')); ?>
		</div>
		<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type'] ?></label>
		<div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
			<select name="data[EvaluationQuestion][question_type]" onchange="question_type_modify(this)">
			<?php if(isset($info_resource['question_type'])){foreach($info_resource['question_type'] as $k=>$v){?>
			<option value="<?php echo $k; ?>" <?php echo (isset($evaluation_question_info['EvaluationQuestion'])&&$evaluation_question_info['EvaluationQuestion']['question_type']==$k)||(!isset($evaluation_question_info['EvaluationQuestion'])&&isset($_REQUEST['question_type'])&&$_REQUEST['question_type']==$k)?'selected':''; ?>><?php echo $v; ?></option>
			<?php }} ?>
			</select>
		</div>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'] ?></label>
		<div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left">
			<label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="1" <?php echo isset($evaluation_question_info['EvaluationQuestion'])&&$evaluation_question_info['EvaluationQuestion']['status']=='1'||!isset($evaluation_question_info['EvaluationQuestion'])?'checked':''; ?>/>有效</label>
			<label class="am-radio-inline"><input type="radio" name="data[EvaluationQuestion][status]" value="0" <?php echo isset($evaluation_question_info['EvaluationQuestion'])&&$evaluation_question_info['EvaluationQuestion']['status']=='0'?'checked':''; ?>/>无效</label>
		</div>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">分值</label>
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" name="data[EvaluationQuestion][score]" value="<?php echo isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['score']:'1'; ?>"/></div>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">正确答案</label>
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" id="right_answer" name="data[EvaluationQuestion][right_answer]" value="<?php echo isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['right_answer']:''; ?>"/></div>
		<em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">题目解析</label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
		<?php echo $this->element('editor',array('editorName'=>"data[EvaluationQuestion][analyze]",'editorId'=>'question_elm2_'.rand(0,10),'editorValue'=>isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['analyze']:'')); ?>
		</div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['sort']; ?></label>
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
			<input type='text' name="data[EvaluationQuestion][orderby]" value="<?php echo isset($evaluation_question_info['EvaluationQuestion']['orderby'])?$evaluation_question_info['EvaluationQuestion']['orderby']:'50'; ?>" />
		</div>
	</div>
    	<div class="am-form-group">
        	<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_question_submit(this)"><?php echo $ld['confirm']; ?></button>
    	</div>
</form>
<script type='text/javascript'>
function question_type_modify(select){
	var question_type=$(select).val();
	var right_answer_em=$("input#right_answer").parents('div.am-form-group').find('em');
	if(question_type=='2'){
		right_answer_em.hide();
	}else{
		right_answer_em.show();
	}
}

function ajax_modify_question_submit(btn){
	    var postForm=$(btn).parents('form');
	    var question_code=$(postForm).find("input[name='data[EvaluationQuestion][code]']").val().trim();
	    var question_name=$(postForm).find("textarea[name='data[EvaluationQuestion][name]']").val().trim();
	    var question_type=$(postForm).find("select[name='data[EvaluationQuestion][question_type]']").val();
	    if(question_code==''){
	    		alert('请输入编码');
	    		return false;
	    }
	    if(question_name==''){
	    		alert('请输入题目');
	    		return false;
	    }
	    if(question_type!='2'){
	    		var right_answer=$("input#right_answer").val().trim();
	    		if(right_answer==''){
	    			alert('请输入正确答案');
	    			return false;
	    		}
	    }
	    $(btn).button('loading');
	    var postData=postForm.serialize();
	    $.ajax({
	        url: admin_webroot+"evaluation_questions/view",
	        type:"POST",
	        data:postData,
	        dataType:"json",
	        success: function(data){
			$(btn).button('reset');
			if(data.code=='1'){
				alert(data.message);
				window.location.reload();
			}else{
				alert(data.message);
			}
	        }
	    });
}
</script>