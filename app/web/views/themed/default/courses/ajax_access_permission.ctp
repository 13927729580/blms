<?php if(isset($access_result['code'])&&$access_result['code']=='0'){
			$share_type_list=array('course_class'=>'课时','home'=>$ld['home'],'page'=>$ld['page'],'article'=>$ld['article'],'topic'=>$ld['topics']);
?>
	<div class='am-g course_access_permission'>
	<?php
		if(isset($access_result['access'])&&!empty($access_result['access'])){foreach($access_result['access'] as $k=>$v){
	?>
		<div class='am-form-group'>
			<div class='am-u-lg-10 am-u-md-10 am-u-sm-9 am-padding-left-0'>
			<?php
				if($k=='parent_course'&&!empty($v)){
					echo "已完成以下课程:";
					foreach($v as $kk=>$vv)echo "<p>".$html->link($vv,'/courses/view/'.$kk,array('target'=>'_blank','class'=>'am-margin-left-xs'))."</p>";
				}else if($k=='parent_course_class'&&!empty($v)){
					echo "已完成以下课时:";
					foreach($v as $kk=>$vv)echo "<p>".$html->link($vv,'/courses/view/'.(isset($course_id)?$course_id:0).'/'.$kk,array('target'=>'_blank','class'=>'am-margin-left-xs'))."</p>";
				}else if($k=='shared_access'&&!empty($v)){
					foreach($v as $vv){
						$share_type=isset($vv[3])?$vv[3]:'';
						$access_value=explode('/',$vv[2]);
						echo "<p>分享访问".(isset($share_type_list[$share_type])?$share_type_list[$share_type]:'')."&nbsp;".($html->link($vv[0],$vv[1],array('target'=>'_blank')))."&nbsp;次数".$access_value[1]."次";
					}
				}else if($k=='number_of_apprentice'){
					$access_value=explode('/',$v);
					echo "收徒数量需要".$access_value[1]."个";
				}else if($k=='share_count'&&!empty($v)){
					foreach($v as $vv){
						$share_type=isset($vv[3])?$vv[3]:'';
						$access_value=explode('/',$vv[2]);
						echo "<p>分享".(isset($share_type_list[$share_type])?$share_type_list[$share_type]:'')."&nbsp;".($html->link($vv[0],$vv[1],array('target'=>'_blank')))."&nbsp;次数".$access_value[1]."次";
					}
				}else if($k=='share_registration'&&!empty($v)){
					$access_value=explode('/',$v);
					echo "分享注册用户".$access_value[1].'个';
				}else if($k=='shared_consumption_number'&&!empty($v)){
					$access_value=explode('/',$v);
					echo "分享消费￥".sprintf("%.2f",$access_value[1]);
				}else if($k=='buy'){
					echo "购买课程";
				}
			?>
			</div>
			<div class='am-u-lg-2 am-u-md-2 am-u-sm-3 am-text-center'>
				<span class='am-icon am-icon-check am-text-success'></span>
			</div>
			<div class='am-cf'></div>
		</div>
	<?php
		}}
	?>
	<?php if(is_array($access_result['message'])&&!empty($access_result['message'])){foreach($access_result['message'] as $k=>$v){if($k=='buy')continue; ?>
		<div class='am-form-group'>
			<?php
				if($k=='parent_course'&&!empty($v)){
					echo "请先完成以下课程:";
					foreach($v as $kk=>$vv)echo "<p>".$html->link($vv,'/courses/view/'.$kk,array('target'=>'_blank','class'=>'am-margin-left-xs'))."</p>";
				}else if($k=='parent_course_class'&&!empty($v)){
					echo "请先完成以下课时:";
					foreach($v as $kk=>$vv)echo "<p>".$html->link($vv,'/courses/view/'.(isset($course_id)?$course_id:0).'/'.$kk,array('target'=>'_blank','class'=>'am-margin-left-xs'))."</p>";
				}else if($k=='shared_access'&&!empty($v)){
					foreach($v as $vv){
						$share_type=isset($vv[3])?$vv[3]:'';
						$access_value=explode('/',$vv[2]);
						echo "<p>分享访问".(isset($share_type_list[$share_type])?$share_type_list[$share_type]:'')."&nbsp;".($html->link($vv[0],$vv[1],array('target'=>'_blank')))."&nbsp;次数".$access_value[1]."次,当前已访问".$access_value[0].'次</p>';
					}
				}else if($k=='number_of_apprentice'){
					$access_value=explode('/',$v);
					echo "收徒数量需要".$access_value[1]."个,当前已收".$access_value[0].'个';
				}else if($k=='share_count'&&!empty($v)){
					foreach($v as $vv){
						$share_type=isset($vv[3])?$vv[3]:'';
						$access_value=explode('/',$vv[2]);
						echo "<p>分享".(isset($share_type_list[$share_type])?$share_type_list[$share_type]:'')."&nbsp;".($html->link($vv[0],$vv[1],array('target'=>'_blank')))."&nbsp;次数".$access_value[1]."次,当前已分享".$access_value[0].'次</p>';
					}
				}else if($k=='share_registration'&&!empty($v)){
					$access_value=explode('/',$v);
					echo "分享注册用户需要".$access_value[1]."个,当前已有".$access_value[0].'个';
				}else if($k=='shared_consumption_number'&&!empty($v)){
					$access_value=explode('/',$v);
					echo "分享消费需要￥".sprintf("%.2f",$access_value[1]).",当前已消费￥".sprintf("%.2f",$access_value[0]);
				}else if($k=='max_course_read'){
					$access_value=explode('/',$v);
					echo "当前学习人数已满";
				}
			?>
		</div>
	<?php }}else if(is_string($access_result['message'])){ ?>
	<div class='am-form-group'><?php echo $access_result['message']; ?></div>
	<?php } ?>
<?php if(isset($access_result['message']['buy'])){
			$copy_access_result=$access_result['message'];if(isset($copy_access_result['buy']))unset($copy_access_result['buy']);
			if(!empty($copy_access_result)&&((isset($access_result['course_class_detail']['must_buy'])&&$access_result['course_class_detail']['must_buy']!='1')||(!isset($access_result['course_class_detail'])&&isset($access_result['course_data']['must_buy'])&&$access_result['course_data']['must_buy']!='1'))){
?>
</div>
<p class='am-text-center am-margin-top-sm am-margin-bottom-sm'>或</p>
<div class='am-g course_access_permission'>
	<div class='am-form-group am-text-center am-padding-left-0'>
		<button type='button' class='am-btn am-radius am-btn-danger am-margin-left-xs' onclick="fast_buy_course(this,<?php echo isset($course_id)?$course_id:0; ?>,<?php echo isset($course_class_id)?$course_class_id:0; ?>)">￥<?php echo sprintf("%.2f",$access_result['message']['buy']); ?> 直接购买</button>
	</div>
</div>
	<?php }else{ ?>
	<div class='am-form-group am-text-center am-padding-left-0'>
		<button type='button' class='am-btn am-radius am-btn-danger' onclick="fast_buy_course(this,<?php echo isset($course_id)?$course_id:0; ?>,<?php echo isset($course_class_id)?$course_class_id:0; ?>)">￥<?php echo sprintf("%.2f",$access_result['message']['buy']); ?> 购买</button>
	</div>
</div>
	<?php } ?>
<?php }else{ ?>
</div>
<?php } ?>
<style type='text/css'>
.course_access_permission{border:1px solid #ccc;}
.course_access_permission .am-form-group{margin:1rem auto;font-size:16px;text-align:left;padding-left:2.5rem;}
.course_access_permission a,.course_access_permission a:hover{color:#333;text-decoration: underline;}
@media only screen and (max-width: 640px){
	#ajax_access_permission{width:90%;margin-left:-45%;}
	.course_access_permission .am-form-group{padding-left:1rem;}
}
</style>
<script type='text/javascript'>
function fast_buy_course(btn,course_id,course_class_id){
	$(btn).parents('div.am-modal').modal('close');
	if(course_class_id=='0'){
		virtual_purchase_pay('course',course_id);
	}else{
		virtual_purchase_pay('course_class',course_class_id);
	}
}
</script>
<?php } ?>