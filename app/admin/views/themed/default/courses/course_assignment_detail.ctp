<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion">
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}">
			<ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			   	<li><a href="#assignment_score">评分</a></li>
			</ul>
		</div>
		<div id="basic_information"  class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">课程</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_assignment_info['Course']['name'];  ?>&nbsp;/&nbsp;<?php echo $course_assignment_info['CourseChapter']['name'];  ?>&nbsp;/&nbsp;<?php echo $course_assignment_info['CourseClass']['name'];  ?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">用户</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo isset($course_assignment_user_info['User'])?($course_assignment_user_info['User']['first_name']!=''?$course_assignment_user_info['User']['first_name']:$course_assignment_user_info['User']['name']):'';  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">作业</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_assignment_info['CourseAssignment']['content'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		<?php if(trim($course_assignment_info['CourseAssignment']['media'])!=''){ ?>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">附件</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_assignment_info['CourseAssignment']['media']!=''?"<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($course_assignment_info['CourseAssignment']['media'])."','".mime_content_type(WWW_ROOT.$course_assignment_info['CourseAssignment']['media'])."')\"><i class='am-icon am-icon-youtube-play am-icon-md'></i></a>":''; ?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		<?php } ?>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">提交时间</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_assignment_info['CourseAssignment']['modified'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
				</div>
			</div>
		</div>
		<div id="assignment_score"  class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title">评分</h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd">
					<?php echo $form->create('/courses',array('action'=>'course_assignment_detail/'.$course_assignment_info['CourseAssignment']['id'],'class'=>' am-form am-form-horizontal assignment_score'));?>
					<input type='hidden' name="data[CourseAssignmentScore][course_assignment_id]" value="<?php echo $course_assignment_info['CourseAssignment']['id']; ?>" />
					<input type='hidden' name="data[CourseAssignmentScore][score]" value="0" />
					<div class='am-g am-padding-sm'>
					<?php
						for($i=0;$i<5;$i++){
							echo "<i class='am-icon am-icon-star-o am-icon-md'></i>";
						}
					?>
					</div>
					<div class='am-g am-padding-sm'>
						<p class='am-text-left am-margin-bottom-xs'>评语</p>
						<textarea name="data[CourseAssignmentScore][content]"></textarea>
					</div>
					<div class='am-g am-padding-sm am-text-left'>
						<button type='button' class='am-btn am-btn-success am-btn-sm' onclick="ajax_assignment_score(this)"><?php echo $ld['submit']; ?></button>
					</div>
					<?php echo $form->end();?>
					<table class='am-table'>
						<thead>
							<tr>
								<th width='20%'>评分人</th>
								<th width='15%'>分值</th>
								<th>回复内容</th>
								<th width='20%'>操作时间</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($CourseAssignmentScoreList)&&sizeof($CourseAssignmentScoreList)>0){foreach($CourseAssignmentScoreList as $v){ ?>
							<tr>
								<td><?php 
											if($v['CourseAssignmentScore']['reply_from']=='0'){
												echo isset($reply_user_list[$v['CourseAssignmentScore']['reply_from_id']])?($reply_user_list[$v['CourseAssignmentScore']['reply_from_id']]['first_name']!=''?$reply_user_list[$v['CourseAssignmentScore']['reply_from_id']]['first_name']:$reply_user_list[$v['CourseAssignmentScore']['reply_from_id']]['name']):'';
											}
											if($v['CourseAssignmentScore']['reply_from']=='1'){
												echo isset($reply_operator_list[$v['CourseAssignmentScore']['reply_from_id']])?$reply_operator_list[$v['CourseAssignmentScore']['reply_from_id']]:'';
												echo "(".$ld['administrator'].")";
											}
									 ?></td>
								<td><?php for($score_key=0;$score_key<$v['CourseAssignmentScore']['score'];$score_key++)echo "<i class='am-icon am-icon-star am-icon-sm am-text-danger'></i>"; ?></td>
								<td><?php echo $v['CourseAssignmentScore']['content']; ?></td>
								<td><?php echo date('Y-m-d',strtotime($v['CourseAssignmentScore']['modified'])); ?></td>
							</tr>
							<?php }} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
			alert(result.message);
			if(result.code=='1'){
				window.location.reload();
			}
		}
	});
}
</script>