<?php //pr($user_course_assignments); ?>
<div class='user_assignment_detail'>
	<div class='am-g am-padding-sm'>
		<div class='am-fl am-padding-xs'><?php echo $html->link($user_course_assignments['Course']['name'],'/courses/view/'.$user_course_assignments['CourseAssignment']['course_id']); ?></div>
		<div class='am-fl am-padding-xs'><?php echo $html->link($user_course_assignments['CourseClass']['name'],'/courses/detail/'.$user_course_assignments['CourseAssignment']['course_id'].'/'.$user_course_assignments['CourseClass']['id']); ?></div>
		<div class='am-u-lg-12 am-padding-xs am-padding-left-sm'><?php echo $user_course_assignments['CourseClassWare']['description']; ?></div>
	</div>
	<div class='am-g am-padding-sm'>
		<div class='am-u-lg-6 am-padding-left-sm'><?php echo isset($assignment_user_info['User'])?($assignment_user_info['User']['first_name']!=''?$assignment_user_info['User']['first_name']:$assignment_user_info['User']['name']):''; ?></div>
		<div class='am-u-lg-6 am-padding-left-sm am-text-right'><?php echo date('Y-m-d',strtotime($user_course_assignments['CourseAssignment']['modified'])); ?></div>
		<div class='am-u-lg-12 am-padding-xs am-padding-left-sm'><?php echo $user_course_assignments['CourseAssignment']['content'];echo trim($user_course_assignments['CourseAssignment']['media'])!=''&&file_exists(WWW_ROOT.trim($user_course_assignments['CourseAssignment']['media']))?"&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($user_course_assignments['CourseAssignment']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?></div>
		<div class='am-cf'></div>
	</div>
	<div class='am-g am-padding-sm'>
		<?php echo $form->create('/users',array('action'=>'user_assignment_detail/'.$user_course_assignments['CourseAssignment']['id'],'class'=>' am-form am-form-horizontal'));?>
		<input type='hidden' name="data[CourseAssignmentScore][course_assignment_id]" value="<?php echo $user_course_assignments['CourseAssignment']['id']; ?>" />
		<input type='hidden' name="data[CourseAssignmentScore][score]" value="0" />
		<div class='assignment_score'>
			<div class='am-g'>
			<?php
				for($i=0;$i<5;$i++){
					echo "<i class='am-icon am-icon-star-o am-icon-md'></i>";
				}
			?>
			</div>
			<div class='am-g am-padding-sm'>
				<textarea name="data[CourseAssignmentScore][content]"></textarea>
			</div>
			<div class='am-g am-padding-sm am-text-right'>
				<button type='button' class='am-btn am-btn-success am-btn-sm' onclick="ajax_assignment_score(this)"><?php echo $ld['submit']; ?></button>
			</div>
		</div>
		<?php echo $form->end();?>
	</div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="CourseMedia">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">&nbsp;
      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		<video controls="controls">你当前的浏览器不支持!</video>
    </div>
  </div>
</div>
<style type='text/css'>
.user_assignment_detail h2{font-weight:400;color:#333;font-size:24px;}
.user_assignment_detail .am-list{border-top:1px solid #ccc;}
.user_assignment_detail .am-list li{border-bottom:1px solid #ccc;margin-bottom:1px;padding:1rem 7px;}
.user_assignment_detail .am-list li:first-child{font-size:14px;}
.user_assignment_detail .am-list li:last-child{border-bottom:0px;}
.user_assignment_detail .am-list li div[class*=am-u-]{padding-top:0.5rem;padding-bottom:0.5rem;}
.user_assignment_detail div.am-fl a,.user_assignment_detail div.am-fl a:hover{color:#333;font-size:24px;}
#CourseMedia video{max-width:100%;max-height:100%;}
.assignment_score i.am-icon{margin-left:10px;cursor:pointer;}
.assignment_score i.am-icon.am-icon-star-o{color:#ccc;}
.assignment_score i.am-icon.am-icon-star{color:#dd514c;}
.assignment_score textarea{height:100px;resize:none;}
</style>
<script type='text/javascript'>
$(function(){
	$('.assignment_score i.am-icon').mouseover(function(){
		$(this).removeClass('am-icon-star-o').addClass('am-icon-star');
		$(this).prevAll('i.am-icon').each(function(index,item){
			$(item).removeClass('am-icon-star-o').addClass('am-icon-star');
		});
		$(this).nextAll('i.am-icon').each(function(index,item){
			$(item).removeClass('am-icon-star').addClass('am-icon-star-o');
		})
	}).mouseout(function(){
		//$(this).removeClass('am-icon-star').addClass('am-icon-star-o');
		$(this).nextAll('i.am-icon').each(function(index,item){
			$(item).removeClass('am-icon-star').addClass('am-icon-star-o');
		})
	}).click(function(){
		$(this).removeClass('am-icon-star-o').addClass('am-icon-star');
		$(this).prevAll('i.am-icon').each(function(index,item){
			$(item).removeClass('am-icon-star-o').addClass('am-icon-star');
		});
		$(this).nextAll('i.am-icon').each(function(index,item){
			$(item).removeClass('am-icon-star').addClass('am-icon-star-o');
		})
	});
});

function PreviewCourseMedia(mediaPath,mediaMimeType){
	if (typeof(mediaMimeType)!='undefined'&&/(audio|video)\/(.*)$/.test(mediaMimeType)){
		$("#CourseMedia video").attr("src",mediaPath);
		$("#CourseMedia").modal();
	}else{
		window.open(mediaPath);
	}
}

function ajax_assignment_score(btn){
	var postForm=$(btn).parents('form');
	var postUrl=postForm.attr('action');
	var score=$(".assignment_score i.am-icon.am-icon-star").length;
	if(score==0){
		seevia_alert('请先打分');
		return;
	}
	postForm.find("input[type='hidden'][name='data[CourseAssignmentScore][score]']").val(score);
	$.ajax({
		url:postUrl,
		type:'POST',
		data:postForm.serialize(),
		dataType:'json',
		success:function(result){
			seevia_alert(result.message);
			if(result.code=='1'){
				window.location.reload();
			}
		}
	});
}
</script>