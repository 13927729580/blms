<form method='POST' class='am-form am-form-horizontal'>
    <div class="am-panel-bd">
        <table class="am-table  table-main">
            <thead>
	            <tr>
	                <th width='10%'><label class="am-checkbox am-success" style="padding-top:0px;"><input data-am-ucheck  type="checkbox" onclick="checkAllOption(this)" /><?php echo $ld['name']?></label></th>
	                <th><?php echo $ld['description'];?></th>
	                <th class='am-text-center'><?php echo $ld['status']?></th>
	                <th class='am-text-center' width='8%'><?php echo $ld['sort']?></th>
	            </tr>
            </thead>
            <tbody>
    			<?php $question_name_key=0;for($question_name='A';$question_name<'F';$question_name++){ ?>
	            <tr>
	                <td><label class="am-checkbox am-success am-padding-top-0"><input type="checkbox" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][checked]'; ?>" value="<?php echo $question_name; ?>" data-am-ucheck <?php echo isset($evaluation_question_option_list[$question_name])?'checked':''; ?> /><input type="text" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][name]'; ?>" value="<?php echo $question_name; ?>" readonly><input type="hidden" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][evaluation_question_code]'; ?>" value="<?php echo isset($evaluation_question_info['EvaluationQuestion'])?$evaluation_question_info['EvaluationQuestion']['code']:''; ?>"><input type="hidden" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][id]'; ?>" value="<?php echo isset($evaluation_question_option_list[$question_name])?$evaluation_question_option_list[$question_name]['id']:0; ?>"></label></td>
	                <td><input type="text" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][description]'; ?>" value="<?php echo isset($evaluation_question_option_list[$question_name])?$evaluation_question_option_list[$question_name]['description']:''; ?>"></td>
	                <td><label class="am-radio-inline"><input type="radio" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][status]'; ?>" <?php echo isset($evaluation_question_option_list[$question_name])&&$evaluation_question_option_list[$question_name]['status']=='1'||!isset($evaluation_question_option_list[$question_name])?'checked':''; ?> value="1"/>有效</label>
	                    <label class="am-radio-inline"><input type="radio" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][status]'; ?>" value="0" <?php echo isset($evaluation_question_option_list[$question_name])&&$evaluation_question_option_list[$question_name]['status']=='0'?'checked':''; ?>/>无效</label></td>
	                <td><input type="text" name="<?php echo 'data[EvaluationOption]['.$question_name_key.'][orderby]'; ?>" value="<?php echo isset($evaluation_question_option_list[$question_name])?$evaluation_question_option_list[$question_name]['orderby']:'0'; ?>"></td>
	            </tr>
	            <?php $question_name_key++;} ?>
            </tbody>
        </table>
        <div class="am-text-left am-margin-lg">
            <button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
        </div>
    </div>
</form>
<style type='text/css'>
#view_question_option table td label.am-checkbox span.am-ucheck-icons{margin-top:0.5rem;}
#view_question_option table td label.am-radio-inline{margin-top:0.5rem;}
</style>
<script type='text/javascript'>
function checkAllOption(checkbox){
	if(checkbox.checked){
		$(checkbox).parents('table').find("tbody input[type='checkbox']").uCheck('check');
	}else{
		$(checkbox).parents('table').find("tbody input[type='checkbox']").uCheck('uncheck');
	}
}

function ajax_modify_submit(btn){
        var postForm=$(btn).parents('form');
        var QuestionCheck=0;
        $(postForm).find("tbody input[type='checkbox']:checked").each(function(){
        		var option_row=$(this).parents('tr');
        		var option_description=$(option_row).find('td:eq(1) input[type="text"]').val().trim();
        		console.log(option_description);
        		if(option_description!=''){
        			QuestionCheck++;
        		}
        });
        if(QuestionCheck==0)return;
        $(btn).button('loading');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"evaluation_options/view",
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