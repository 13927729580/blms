<div class="am-g uploadquestion_preview">
	<?php echo $form->create('courses',array('action'=>'/batch_upload/'.$code));?>
		<table class="am-table table-main">
			<?php if(isset($fields_desc)&&!empty($fields_desc)){ ?>
			<tr>
				<th><label class='am-checkbox am-success'><input type='checkbox' class="select_upload_question" data-am-ucheck  /></label></th>
				<?php foreach($fields_desc as $v){ ?>
				<th><?php echo $v; ?></th>
				<?php } ?>
			</tr>
			<?php } ?>
			<?php if(isset($preview_data)&&!empty($preview_data)){foreach($preview_data as $k=>$question_data){
						if($k==0)continue;
			?>
			<tr>
				<td><label class="am-checkbox am-success" ><input type='checkbox' name="checkbox[]" checked data-am-ucheck  value="<?php echo $k; ?>" /></label></td>
				<?php
						if(isset($fields_desc)&&!empty($fields_desc)){foreach($fields_desc as $kk=>$vv){
								$field_info=isset($fields_array[$kk])?$fields_array[$kk]:$vv;
								$field_codes=explode('.',$field_info);
								$field_model=isset($field_codes[1])?$field_codes[0]:'EvaluationOption';
								$field_name=isset($field_codes[1])?$field_codes[1]:$field_codes[0];
								$field_value=isset($question_data[$field_info])?htmlspecialchars($question_data[$field_info]):'';
				?>
				<td><input type='text' name="data[<?php echo $k; ?>][<?php echo $field_model; ?>][<?php echo $field_name; ?>]" value="<?php echo $field_value; ?>" /></td>
				<?php }} ?>
			</tr>
			<?php }} ?>
		</table>
		<div class='btnouterlist'>
			<div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
				<label class="am-checkbox am-success">
					<input type="checkbox" class="select_upload_question" data-am-ucheck /><?php echo '全选'; ?>
				</label>
			</div>
			<div class="am-u-lg-8">
				<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['submit']?>" />
				<input type="reset"  class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['reset']?>" />
				<span>&nbsp;共计上传<?php echo isset($preview_data)&&sizeof($preview_data)>1?sizeof($preview_data)-1:0; ?>条</span>
			</div>
			<div class="am-cf"></div>
		</div>
	<?php $form->end();?>
</div>
<style type='text/css'>
div.uploadquestion_preview{overflow-x:scroll;}
div.uploadquestion_preview label.am-checkbox{margin-top:5px;}
div.uploadquestion_preview .btnouterlist{width:97%;margin:0 auto;}
div.uploadquestion_preview i.am-icon-comment{color: #dd514c;cursor: pointer;}
</style>
<script type='text/javascript'>
$(function(){
	$('.select_upload_question').click(function(){
		if($(this).prop('checked')){
			$("div.uploadquestion_preview input[type='checkbox'][name='checkbox[]']").uCheck('check');
		}else{
			$("div.uploadquestion_preview input[type='checkbox'][name='checkbox[]']").uCheck('uncheck');
		}
	});
	
	$('.uploadquestion_preview form').submit(function(){
		var check_length=$("div.uploadquestion_preview input[type='checkbox'][name='checkbox[]']:checked").length;
		if(check_length==0){
			seevia_alert('j_please_select');
			return false;
		}
	});
});
</script>