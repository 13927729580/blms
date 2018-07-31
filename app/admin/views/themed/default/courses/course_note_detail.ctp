<?php
	//pr($course_note_info);
?>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion">
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}">
			<ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			   	<li><a href="#note_reply">笔记回复</a></li>
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
			    				<?php echo $course_note_info['Course']['name'];  ?>&nbsp;/&nbsp;<?php echo $course_note_info['CourseChapter']['name'];  ?>&nbsp;/&nbsp;<?php echo $course_note_info['CourseClass']['name'];  ?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">用户</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo isset($course_note_user_info['User'])?($course_note_user_info['User']['first_name']!=''?$course_note_user_info['User']['first_name']:$course_note_user_info['User']['name']):'';  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">笔记内容</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_note_info['CourseNote']['note'];  ?>&nbsp;<?php echo $course_note_info['CourseNote']['media']!=''?"<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($course_note_info['CourseNote']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">记录时间</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $course_note_info['CourseNote']['created'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		
				</div>
			</div>
		</div>
		<div id="note_reply"  class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title">笔记回复</h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd">
					<div class='am-g am-form'>
						<form class='am-form am-form-horizontal'>
							<input type='hidden' name="data[CourseNoteReply][course_note_id]" value="<?php echo $course_note_info['CourseNote']['id'];  ?>" />
							<div class='am-form-group'>
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-top-xs am-padding-right-0">回复内容</label>
								<div class='am-u-lg-9 am-u-md-9 am-u-sm-9'><textarea name="data[CourseNoteReply][content]"></textarea></div>
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-3">&nbsp;</label>
								<div class='am-u-lg-9 am-u-md-9 am-u-sm-9 am-margin-top-xs'><input type='file' id="CourseNoteReply_media" onchange='loadCourseMedia(this)' accept="audio/*,video/*" /><a href='javascript:void(0);' id='PreviewCourseMedia' data-am-modal="{target: '#CourseMedia', closeViaDimmer: 0}">预览</a></div>
								<div class='am-cf'></div>
							</div>
							<div class='am-form-group'>
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-top-xs am-padding-right-0">&nbsp;</label>
								<div class='am-u-lg-9 am-u-md-9 am-u-sm-9'><button type='button' class='am-btn am-btn-success am-radius am-btn-sm' onclick="ajax_course_note_reply(this)">回复</button></div>
								<div class='am-cf'></div>
							</div>
						</form>
					</div>
					
					<table class='am-table'>
						<thead>
							<tr>
								<th>回复人</th>
								<th>回复内容</th>
								<th>回复时间</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($course_note_reply_list)&&sizeof($course_note_reply_list)>0){foreach($course_note_reply_list as $v){ ?>
							<tr>
								<td><?php echo $v['CourseNoteReply']['reply_from']=='1'?"(".$ld['operator'].")":'';
											if($v['CourseNoteReply']['reply_from']=='0'){
												echo isset($course_note_reply_from_list['User'][$v['CourseNoteReply']['reply_from_id']])?($course_note_reply_from_list['User'][$v['CourseNoteReply']['reply_from_id']]['first_name']!=''?$course_note_reply_from_list['User'][$v['CourseNoteReply']['reply_from_id']]['first_name']:$course_note_reply_from_list['User'][$v['CourseNoteReply']['reply_from_id']]['name']):'-';
											}else{
												echo isset($course_note_reply_from_list['Operator'][$v['CourseNoteReply']['reply_from_id']])?$course_note_reply_from_list['Operator'][$v['CourseNoteReply']['reply_from_id']]:'-';
											} ?></td>
								<td><?php echo $v['CourseNoteReply']['content']!=''?$v['CourseNoteReply']['content']:"";echo "&nbsp;";echo $v['CourseNoteReply']['media']!=''?"<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($v['CourseNoteReply']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''; ?></td>
								<td><?php echo $v['CourseNoteReply']['created']; ?></td>
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
#PreviewCourseMedia{display:none;}
#CourseMedia video{max-width:100%;max-height:100%;}
</style>
<script type='text/javascript'>
function PreviewCourseMedia(mediaPath){
	$("#CourseMedia video").attr("src", mediaPath);
	$("#CourseMedia").modal();
}

function ajax_course_note_reply(bth){
	var PostForm=$(bth).parents('form');
	var PostText=$(PostForm).find('textarea').val();
	var PostFile=$(PostForm).find("input[type='file']").val();
	if(PostText==''&&PostFile=='')return;
	$(bth).button('loading');
	var xhr = null;
	if (window.XMLHttpRequest){// code for all new browsers
		xhr=new XMLHttpRequest();
	}else if (window.ActiveXObject){// code for IE5 and IE6
		xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		alert("Your browser does not support XMLHTTP.");return false;
	}
	var formData= new FormData();  
	$.each($(PostForm).serializeArray(),function (i,field) {  
		formData.append(field.name,field.value);
	});
	if(document.getElementById('CourseNoteReply_media')){
		var CourseNoteReply_media=document.getElementById('CourseNoteReply_media').files;
		if(CourseNoteReply_media.length>0){
			formData.append('CourseNoteReply_media',CourseNoteReply_media[0]);
		}
	}
	xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
            		$(bth).button('reset');
			eval("var result="+xhr.responseText);
			if(result.code=='1'){
				window.location.reload();
			}else{
				alert(result.message);
			}
            }
        };
        xhr.onerror=function(evt){
		console.log(j_object_transform_failed);
		$(bth).button('reset');
        };
        xhr.open("POST", admin_webroot+"courses/ajax_course_note_reply");
        xhr.send(formData);
}

function loadCourseMedia(fileBox){
	$('#PreviewCourseMedia').hide();
	var uploadfile=fileBox.files[0];
	var reader = new FileReader();
	reader.readAsText(uploadfile, 'UTF-8');
	reader.onload = function (e) {
		if(reader.readyState==2){//加载完成
			var fileSize=Math.round(e.total/1024/1024/1024);
			if(fileSize>10){
                        	alert('最大文件限制为10M,当前为'+fileSize+'M');
                        	return false;
                    }
			var fileResult = reader.result;
			$('#PreviewCourseMedia').show();
			$("#CourseMedia video").attr("src", window.URL.createObjectURL(uploadfile));
		}
	}
}
</script>