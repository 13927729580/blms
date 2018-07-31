<div class='user_course_note'>
	<ul class='am-list'>
		<li>
			<div class='am-g'>
				<div class='am-u-lg-4 am-u-md-4 am-u-sm-5'>课程</div>
				<div class='am-u-lg-5 am-u-md-5 am-u-sm-6'>笔记</div>
				<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only">回复数</div>
				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'><?php echo $ld['view']; ?></div>
			</div>
		</li>
		<?php if(isset($user_note_list)&&sizeof($user_note_list)>0){foreach($user_note_list as $v){ ?>
		<li>
			<div class='am-g'>
				<div class='am-u-lg-4 am-u-md-4 am-u-sm-5'><a href="<?php echo $html->url('/courses/view/'.$v['CourseNote']['course_id']); ?>"><?php echo $v['Course']['name']; ?></a>&nbsp;<a href="<?php echo $html->url('/courses/detail/'.$v['CourseNote']['course_id'].'/'.$v['CourseNote']['course_class_id']); ?>"><?php echo $v['CourseClass']['name']; ?></a></div>
				<div class='am-u-lg-5 am-u-md-5 am-u-sm-6'><?php echo $v['CourseNote']['note'];echo trim($v['CourseNote']['media'])!=''&&file_exists(WWW_ROOT.trim($v['CourseNote']['media']))?"&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($v['CourseNote']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo isset($course_note_reply_list[$v['CourseNote']['id']])?$course_note_reply_list[$v['CourseNote']['id']]:0; ?></div>
				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'><a class="am-btn am-btn-primary am-btn-xs am-radius" href="<?php echo $html->url('/courses/user_course_note_detail/'.$v['CourseNote']['id']); ?>"><span class="am-icon-chevron-right"></span></a></div>
			</div>
		</li>
		<?php }}else{ ?>
		<li>
			<div class='am-g am-padding-lg am-text-center'>暂无笔记</div>
		</li>
		<?php } ?>
	</ul>
	<?php if(isset($user_note_list)&&sizeof($user_note_list)>0){ ?>
	<div class='am-g'><?php echo $this->element('pager'); ?></div>
	<?php } ?>
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
.user_course_note .am-list li{border-bottom:1px solid #ccc;margin-bottom:1px;padding:1rem 7px;}
.user_course_note .am-list li:first-child{font-size:14px;}
.user_course_note .am-list li:last-child{border-bottom:0px;}
.user_course_note .am-list li a,.user_course_note .am-list li a:hover{color:#333;}
.user_course_note .am-list li a.am-btn,.user_course_note .am-list li a.am-btn:hover{color:#fff;}
</style>
<script type='text/javascript'>
function PreviewCourseMedia(mediaPath){
	$("#CourseMedia video").attr("src", mediaPath);
	$("#CourseMedia").modal();
}
</script>