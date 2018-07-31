<?php
	$page_type=isset($_REQUEST['page_type'])?$_REQUEST['page_type']:'experience';
//pr($informationresource_infos['education_type']);
?>
<style>
.am-form-group
{
margin-bottom:10px;
}
.jingyan_form select
{
padding:5px 5px;
color:#333;
}
.am-form-detail select
{
    min-width: inherit;
}
.jingyan_form label
{
padding-right:10px;
padding-top:5px;
}
@media only screen and (max-width: 640px)
{	
	.user_resume_detail .year_m
	{
	padding:10px 3px 0 3px;
	font-size:12px;
	}
}
.user_resume_detail .year_m
{
padding-top:10px;
}
.am-form-detail select
{
border-radius:3px;
font-size:14px;
}
.am-form-detail select[value='']
{
	
}
.am-form-detail input[type="text"]{color:#333;}
</style>
<form method='post' class='am-form am-form-detail jingyan_form' style="background:#fff;">
  <div class="am-g"><a style="float:right;"href="javascript: void(0)" class="am-close am-close-spin quxiao" data-am-modal-close>&times;</a></div>
	<div class="user_resume_detail">
		<?php if($page_type=='experience'){ ?>
		<input type='hidden' name="data[UserExperience][id]" value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['id']:0; ?>" />
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right' ><?php echo "职业类型:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<select class="yuanjiao" name='data[UserExperience][position]' required style="">
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php if(isset($informationresource_infos['job_type'])&&!empty($informationresource_infos['job_type'])){foreach($informationresource_infos['job_type'] as $k=>$v){ ?>
					<option value="<?php echo $k; ?>" <?php echo isset($UserExperience_data['UserExperience'])&&$UserExperience_data['UserExperience']['position']==$k?'selected':''; ?>><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "行业名称:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input placeholder="请选择" type='text' name='data[UserExperience][company_type]' required value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['company_type']:''; ?>" style="margin-left:0;width:100%;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "公司名称:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input placeholder="请选择" type='text' name='data[UserExperience][company_name]' required value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['company_name']:''; ?>" style="margin-left:0;width:100%;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "公司行业:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input placeholder="请选择" type='text' name='data[UserExperience][company_industry]' required value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['company_industry']:''; ?>" style="margin-left:0;width:100%;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "技能标签:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input placeholder="请选择" type='text' name='data[UserExperience][skill]' value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['skill']:''; ?>" style="margin-left:0;width:100%;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<?php
			$start_time=isset($UserExperience_data['UserExperience'])&&$UserExperience_data['UserExperience']['start_time']!=''?$UserExperience_data['UserExperience']['start_time']:'';
			$start_time_year=$start_time!=''?intval(date("Y",strtotime($start_time))):0;
			$start_time_month=$start_time!=''?intval(date("m",strtotime($start_time))):0;
			$end_time=isset($UserExperience_data['UserExperience'])&&$UserExperience_data['UserExperience']['end_time']!=''?$UserExperience_data['UserExperience']['end_time']:'';
			$end_time_year=$end_time!=''?intval(date("Y",strtotime($end_time))):0;
			$end_time_month=$end_time!=''?intval(date("m",strtotime($end_time))):0;
		?>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right' style="padding-top:10px;"><?php echo "时间段:"; ?></label>
			<div class="am-u-md-9 am-u-sm-8 am-u-lg-5">
			
			<div class='am-u-lg-4 am-u-sm-4 am-u-md-4' style="padding-top:5px;padding-left:0;padding-right:0;">
				<select name="data[UserExperience][start_time][year]" required>
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($year=1970;$year<=date("Y");$year++){ ?>
					<option value="<?php echo $year; ?>" <?php echo $start_time_year==$year?'selected':''; ?>><?php echo $year; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='am-u-lg-1 am-u-sm-1 am-u-md-1 am-text-center year_m' style="padding-left:0.5rem;"><?php echo $ld['years']; ?></div>
			<div class='am-u-lg-4 am-u-sm-4 am-u-md-4' style="padding-top:5px;padding-right:0;">
				<select name="data[UserExperience][start_time][month]">
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($month=1;$month<=12;$month++){ ?>
					<option value="<?php echo $month; ?>" <?php echo $start_time_month==$month?'selected':''; ?>><?php echo $month; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='am-u-lg-1 am-u-sm-1 am-u-md-1 am-text-center year_m' style="padding-left:0.5rem;">月</div>
			<div class='am-u-lg-1 am-u-sm-1 am-u-md-1 am-text-center year_m am-fr' >-</div>
			<div class="am-cf"></div>	
		</div>
					<div class="am-hide-lg-only am-u-sm-4 am-u-md-3">&nbsp;</div>
		<div class="am-u-sm-8 am-u-lg-5 am-u-md-9">
			<div class='am-u-lg-4 am-u-md-4 am-u-sm-4' style="padding-top:5px;padding-left:0;padding-right:0;">
				<select name="data[UserExperience][end_time][year]">
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($year=1970;$year<=date("Y");$year++){ ?>
					<option value="<?php echo $year; ?>" <?php echo $end_time_year==$year?'selected':''; ?>><?php echo $year; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='am-u-lg-1 am-u-sm-1 am-u-md-1 am-text-center year_m' style="padding-left:5px;"><?php echo $ld['years']; ?></div>
			<div class='am-u-lg-4 am-u-md-4 am-u-sm-4' style="padding-top:5px;padding-right:0;">
				<select name="data[UserExperience][end_time][month]">
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($month=1;$month<=12;$month++){ ?>
					<option value="<?php echo $month; ?>" <?php echo $end_time_month==$month?'selected':''; ?>><?php echo $month; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class='am-u-lg-1 am-u-sm-1 am-u-md-1 am-text-center year_m' style="padding-left:5px;"><?php echo $ld['month']; ?></div>
			<div class='am-cf'></div>
			</div>
		
		<!--	<div class='am-u-lg-2 am-u-sm-2 am-u-md-3'>
				<label class='am-checkbox am-success'><input type='checkbox' value='' name="data[UserExperience][end_time]" <?php echo isset($UserExperience_data['UserExperience'])&&$UserExperience_data['UserExperience']['end_time']==""?'checked':''; ?> />至今</label>
			</div>-->
			
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "所属部门:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input placeholder="选填"type='text' name="data[UserExperience][department]" value="<?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['department']:''; ?>" style="margin-left:0;width:100%;"/>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-right'><?php echo "工作内容:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<textarea style="border-radius:3px;" name="data[UserExperience][description]" required><?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['description']:''; ?></textarea>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "工作业绩:"; ?></label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<textarea style="border-radius:3px;" name="data[UserExperience][achievement]"><?php echo isset($UserExperience_data['UserExperience'])?$UserExperience_data['UserExperience']['achievement']:''; ?></textarea>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group" style="margin-top:20px;">
			<label class='am-u-lg-3 am-u-sm-4 am-u-md-3 am-text-right'>&nbsp;</label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input type="hidden" class="tiaozhuan" name="backup" />
				<button style="padding:7px 23px;font-size:14px;margin-right:1rem;" type='button' class='am-btn am-btn-primary am-radius' onclick='user_resume_save(this,3)'><?php echo $ld['ok']; ?></button>
				
				<button style="margin-right:10px;padding:7px 23px;font-size:14px;" type='reset' class='am-btn am-radius'><?php echo '重置'; ?></button>
				
			</div>
			<div class='am-cf'></div>
		</div>
		<?php }else if($page_type=='education'){ ?>
		
		<input type='hidden' name="data[UserEducation][id]" value="<?php echo isset($UserEducation_data['UserEducation'])?$UserEducation_data['UserEducation']['id']:0; ?>" />
		<div class="am-form-group" >
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "学校名称:"; ?></label>
			<div class='am-u-lg-7 am-u-sm-8 am-u-md-7'>
				<input placeholder="请选择" type='text' name="data[UserEducation][school_name]" required value="<?php echo isset($UserEducation_data['UserEducation'])?$UserEducation_data['UserEducation']['school_name']:''; ?>" style="margin-left:0;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "专业名称:"; ?></label>
			<div class='am-u-lg-7 am-u-sm-8 am-u-md-7'>
				<input placeholder="请填写" type='text' name="data[UserEducation][major_type]" required value="<?php echo isset($UserEducation_data['UserEducation'])?$UserEducation_data['UserEducation']['major_type']:''; ?>" style="margin-left:0;" />
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-right'><?php echo "学历:"; ?></label>
			<div class='am-u-lg-7 am-u-sm-8 am-u-md-7'>
				<select name="data[UserEducation][education_id]" required>
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php if(isset($informationresource_infos['education_type'])&&!empty($informationresource_infos['education_type'])){foreach($informationresource_infos['education_type'] as $k=>$v){ ?>
					<option value="<?php echo $k; ?>" <?php echo isset($UserEducation_data['UserEducation'])&&$UserEducation_data['UserEducation']['education_id']==$k?'selected':''; ?>><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
			<div class='am-cf'></div>
		</div>
		<?php
		//pr($UserEducation_data);
			$start_time=isset($UserEducation_data['UserEducation'])&&$UserEducation_data['UserEducation']['start_time']!=''?$UserEducation_data['UserEducation']['start_time']:'';
			//pr($start_time);
			$start_time_year=$start_time!=''?intval(date("Y",strtotime($start_time))):'';
			$start_time_month=$start_time!=''?intval(date("m",strtotime($start_time))):'';
			
			$end_time=isset($UserEducation_data['UserEducation'])&&$UserEducation_data['UserEducation']['end_time']!=''?$UserEducation_data['UserEducation']['end_time']:'';
			$end_time_year=$end_time!=''?intval(date("Y",strtotime($end_time))):'';
			$end_time_month=$end_time!=''?intval(date("m",strtotime($end_time))):'';
		?>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "时间段:"; ?></label>
		<div class='am-u-lg-7 am-u-sm-8 am-u-md-7'>
			<div class='am-u-lg-5 am-u-md-3 am-u-sm-3' style="padding-left:0;padding-right:0;">
				<?php //pr($start_time_year); ?>
				<select name="data[UserEducation][start_time][year]" required>
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($year=1970;$year<=date("Y");$year++){ ?>
					<option value="<?php echo $year; ?>" <?php echo $start_time==$year?'selected':''; ?>><?php echo $year; ?></option>
					<?php } ?>
				</select>
			</div>
		
			
		<div class="am-u-sm-2 am-u-md-1 am-u-lg-2 am-text-center" style="padding-top:5px;padding-left:0;padding-right:0;">-</div>
		
			<div class='am-u-lg-5 am-u-sm-3 am-u-md-3' style="padding-left:0;padding-right:0;">
				<select name="data[UserEducation][end_time][year]" required>
					<option value=''><?php echo $ld['please_select'] ?></option>
					<?php for($year=1970;$year<=date("Y");$year++){ ?>
					<option value="<?php echo $year; ?>" <?php echo $end_time==$year?'selected':''; ?>><?php echo $year; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group">
			<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo "在校经历:"; ?></label>
			<div class='am-u-lg-7 am-u-sm-8 am-u-md-7'>
				<textarea style="border-radius:3px;" name="data[UserEducation][description]" required><?php echo isset($UserEducation_data['UserEducation'])?$UserEducation_data['UserEducation']['description']:''; ?></textarea>
			</div>
			<div class='am-cf'></div>
		</div>
		<div class="am-form-group" style="margin-top:20px;">
			<label class='am-u-lg-3 am-u-sm-4 am-u-md-3 am-text-right'>&nbsp;</label>
			<div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
				<input type="hidden" class="tiaozhuan" name="backup" />
				<button style="padding:7px 23px;font-size:14px;margin-right:1rem;" type='button' class='am-btn am-btn-primary am-radius' onclick='user_resume_save(this,2)'><?php echo $ld['ok']; ?></button>
				
				<button style="margin-right:10px;padding:7px 23px;font-size:14px;" type='reset' class='am-btn am-radius'><?php echo '重置'; ?></button>
				
			</div>
			<div class='am-cf'></div>
		</div>
		<?php } ?>
		
		
	</div>
</form>
<script>
$(function(){
	$('.quxiao').click(function(){
		//alert('ss');
		$('.am-modal').modal('close');
	});
});
</script>