<div class='user_course_note_detail'>
	<div class='am-g am-padding-sm'>
		<div class='am-fl am-padding-xs'><?php echo $html->link($user_note_detail['Course']['name'],'/courses/view/'.$user_note_detail['CourseNote']['course_id']); ?></div>
		<div class='am-fl am-padding-xs'><?php echo $html->link($user_note_detail['CourseClass']['name'],'/courses/detail/'.$user_note_detail['CourseNote']['course_id'].'/'.$user_note_detail['CourseNote']['course_class_id']); ?></div>
		<div class='am-u-lg-12 am-padding-xs am-padding-left-sm'><?php echo $user_note_detail['CourseNote']['note'];echo trim($user_note_detail['CourseNote']['media'])!=''&&file_exists(WWW_ROOT.trim($user_note_detail['CourseNote']['media']))?"&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($user_note_detail['CourseNote']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?></div>
		<div class='am-cf'></div>
	</div>
	<?php ?>
	<h2 class='am-margin-sm am-padding-xs'>课程笔记</h2>
	<ul class='am-list am-margin-sm note_reply_list'>
		<?php
			if(isset($note_reply_list)&&sizeof($note_reply_list)>0){foreach($note_reply_list as $v){
		?>
		<li>
			<div class='am-g'>
				<div class='am-u-lg-6'><?php echo $v['CourseNoteReply']['reply_from']=='0'?(isset($ReplyUserList[$v['CourseNoteReply']['reply_from_id']])?($ReplyUserList[$v['CourseNoteReply']['reply_from_id']]['first_name']!=''?$ReplyUserList[$v['CourseNoteReply']['reply_from_id']]['first_name']:$ReplyUserList[$v['CourseNoteReply']['reply_from_id']]['name']):$v['CourseNoteReply']['reply_from_id']):$ld['administrator']; ?>&nbsp;</div>
				<div class='am-u-lg-6 am-text-right'><?php echo date('Y-m-d',strtotime($v['CourseNoteReply']['created'])); ?></div>
				<div class='am-u-lg-12 am-padding-left-lg'><?php echo $v['CourseNoteReply']['content'];echo trim($v['CourseNoteReply']['media'])!=''&&file_exists(WWW_ROOT.trim($v['CourseNoteReply']['media']))?"&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($v['CourseNoteReply']['media'])."','".(mime_content_type(WWW_ROOT.trim($v['CourseNoteReply']['media'])))."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?></div>
				<div class='am-cf'></div>
			</div>
		</li>
		<?php
			}}else{
		?>
		<li><div class='am-padding-lg am-margin-top-lg am-margin-bottom-lg am-text-center'>暂无回复</div></li>
		<?php
			}
		?>
	</ul>
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
.user_course_note_detail h2{font-weight:400;color:#333;font-size:24px;}
.user_course_note_detail .am-list{border-top:1px solid #ccc;}
.user_course_note_detail .am-list li{border-bottom:1px solid #ccc;margin-bottom:1px;padding:1rem 7px;}
.user_course_note_detail .am-list li:first-child{font-size:14px;}
.user_course_note_detail .am-list li:last-child{border-bottom:0px;}
.user_course_note_detail .am-list li div[class*=am-u-]{padding-top:0.5rem;padding-bottom:0.5rem;}
.user_course_note_detail div.am-fl a,.user_course_note_detail div.am-fl a:hover{color:#333;font-size:24px;}
#CourseMedia video{max-width:100%;max-height:100%;}
</style>
<script type='text/javascript'>
function PreviewCourseMedia(mediaPath,mediaMimeType){
	if (typeof(mediaMimeType)!='undefined'&&/(audio|video)\/(.*)$/.test(mediaMimeType)){
		$("#CourseMedia video").attr("src",mediaPath);
		$("#CourseMedia").modal();
	}else{
		window.open(mediaPath);
	}
}
</script>