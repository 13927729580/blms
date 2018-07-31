<?php
//pr($question_info);
$question_data = array();
if(isset($question_info['UserQuestionOption'])&&!empty($question_info['UserQuestionOption'])){foreach 	($question_info['UserQuestionOption'] as $k=> $v){
	
	
	$question_data[$v['name']]=$v;
	//pr($question_data);
 }}
 //pr($question_data['A']['id']);
?>
<style>
.am-btn.am-radius{border-radius:5px;}
.tixing_form textarea
{
border-radius:5px;
margin-left:10px;
}
.user_questions_detail
{
    margin: 20px 0 50px 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 0 0 15px #ccc;
    padding: 35px 20px 30px 20px;
}
.am-form-detail div label{margin-top:10px;}
.am-form-detail select
{
	border-radius: 5px;
    margin-left: 10px;
}
.am-text-right{
	font-weight: normal;
	padding:0;
}
</style>
<form method='post' action="/evaluation_questions/view" class='am-form am-form-detail tixing_form' onsubmit="return  questions_save()">
	<?php if($question_info['UserQuestion']['status']==0){?>
	<div class="user_questions_detail">
			<div class="am-form-group">
				<label style="font-weight: normal;" class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "题目:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<input type="hidden" type="text" name="data[UserQuestion][id]" value="<?php echo isset($question_info['UserQuestion']['id'])?$question_info['UserQuestion']['id']:''; ?>"/>
					<textarea rows="4"class="timu" type='text' name='data[UserQuestion][name]' ><?php echo isset($question_info['UserQuestion']['name'])?htmlspecialchars($question_info['UserQuestion']['name']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			
			<!--类型-->
			<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "类型:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<select class="yuanjiao leixing" name='data[UserQuestion][question_type]' >
							<option value=''><?php echo $ld['please_select'] ?></option>
							<option value="0" <?php if($question_info['UserQuestion']['question_type']==0){echo 'selected';}?>><?php echo '单选';?></option>
							<option value="1" <?php if($question_info['UserQuestion']['question_type']==1){echo 'selected';}?>><?php echo '多选';?></option>
					</select>
				</div>
				<div class='am-cf'></div>
			</div>
			<!--题目答案-->
				<?php for($k='A';$k<='E';$k++){ 
						?>
				<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo $k;?>:</label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<input type="hidden" name="data[UserQuestionOption][<?php echo $k;?>][id]"  value="<?php echo isset($question_data[$k]['id'])?$question_data[$k]['id']:'';?>" />
					<input type="hidden" name="data[UserQuestionOption][<?php echo $k;?>][name]" value="<?php echo $k;?>" />
					<textarea type="text" name="data[UserQuestionOption][<?php echo $k;?>][description]"  class="timu_daan"><?php echo isset($question_data[$k]['description'])?htmlspecialchars($question_data[$k]['description']):'';?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
				<?php }?>
			<!--正确答案-->
				<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "正确答案:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea   class="zhengque" name='data[UserQuestion][right_answer]' value="" ><?php echo isset($question_info['UserQuestion']['right_answer'])?htmlspecialchars($question_info['UserQuestion']['right_answer']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			<!--答案解析-->
				<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "答案解析:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea  class="jiexi" name='data[UserQuestion][analyze]'  ><?php echo isset($question_info['UserQuestion']['analyze'])?htmlspecialchars($question_info['UserQuestion']['analyze']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
				<div class="am-form-group" style="margin-top:50px;">
			<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'>&nbsp;</label>
			<div class='am-u-lg-6 am-u-sm-9 am-u-md-9 am-text-center'>
				<input type="hidden" class="tiaozhuan" name="backup" />
				<input style="padding:7px 23px;font-size:14px;" type='submit' class='am-btn am-btn-primary am-radius'  value="<?php echo $ld['ok']; ?>" ;/>
				
				<button  style="padding:7px 23px;font-size:14px;" type='reset' class='am-btn am-radius'><?php echo '重置'; ?></button>
			</div>
			<div class='am-cf'></div>
		</div>
	</div>
	<?php }else{ ?>
	<div class="user_questions_detail">
			<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "题目:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea placeholder="请选择" type='text' name='data[UserQuestion][skill]' value="" readonly><?php echo isset($question_info['UserQuestion']['name'])?htmlspecialchars($question_info['UserQuestion']['name']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			
			<!--类型-->
			<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "题目:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<select class="yuanjiao" name='data[UserQuestion][position]' readonly>
							<option value='2'><?php echo $ld['please_select'] ?></option>
							<option value='0'><?php echo '单选' ?></option>
							<option value='1'><?php echo '多选' ?></option>
					</select>
				</div>
				<div class='am-cf'></div>
			</div>
			<!--题目答案-->
			<?php if(isset($question_info['UserQuestionOption'])){foreach($question_info['UserQuestionOption'] as $k => $v){?>
				<div class="am-form-group">
					<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right timu_div'><?php echo $v['name']?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea  type='text' name='data[UserExperience][skill]' value="" readonly><?php echo htmlspecialchars($v['description'])?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			<?php }}?>
			<!--正确答案-->
				<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "正确答案:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea   name='data[UserQuestion][skill]' value="" readonly><?php echo isset($question_info['UserQuestion']['right_answer'])?htmlspecialchars($question_info['UserQuestion']['right_answer']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			<!--答案解析-->
				<div class="am-form-group">
				<label class='am-u-lg-1 am-u-sm-3 am-u-md-2 am-text-right'><?php echo "答案解析:"; ?></label>
				<div class='am-u-lg-6 am-u-sm-8 am-u-md-9'>
					<textarea  name='data[UserQuestion][skill]'  readonly><?php echo isset($question_info['UserQuestion']['analyze'])?htmlspecialchars($question_info['UserQuestion']['analyze']):''; ?></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
		</div>
	</div>
	<?php }?>
</form>
<script type="text/javascript">
function questions_save(obj)
{
	var tishu = 0;
	$('.tixing_form').find(".timu_daan").each(function(){
		if($(this).val()!='')
		{
			tishu++;
		}
	});
	if($('.tixing_form').find('.timu').val()=='')
	{
		alert('请输入题目');
		return false;
	}
	if(tishu<2)
	{
		alert('至少输入两个题目答案');
		return false;
	}
		if($('.tixing_form').find('.leixing option:selected').val()=='')
	{
		alert('请输入题目类型');
		return false;
	}
		if($('.tixing_form').find('.zhengque').val()=='')
	{
		alert('请输入正确答案');
		return false;
	}

}
</script>