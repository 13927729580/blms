<?php
	if(isset($last_course_class_data)||isset($next_course_class_data)){
?>
<div class='fast_other_class'>
	<div class='am-u-lg-5 am-u-md-5 am-text-left am-hide-sm-only'><?php echo isset($last_course_class_data['CourseClass'])?("<a href='".$html->url('/courses/detail/'.$course_id.'/'.$last_course_class_data['CourseClass']['id'])."'><i class='am-icon am-icon-chevron-left am-margin-right-xs'></i>".$last_course_class_data['CourseClass']['name']."</a>"):'&nbsp;'; ?></div>
	<div class='am-u-sm-4 am-text-left am-show-sm-only'><?php echo isset($last_course_class_data['CourseClass'])?("<a href='".$html->url('/courses/detail/'.$course_id.'/'.$last_course_class_data['CourseClass']['id'])."' title='".$last_course_class_data['CourseClass']['name']."'><i class='am-icon am-icon-chevron-left'></i></a>"):'&nbsp;'; ?></div>
	<div class='am-u-lg-2 am-u-md-2 am-u-sm-4'>&nbsp;</div>
	<div class='am-u-lg-5 am-u-md-5 am-text-right am-hide-sm-only'><?php echo isset($next_course_class_data['CourseClass'])?("<a href='".$html->url('/courses/detail/'.$course_id.'/'.$next_course_class_data['CourseClass']['id'])."'>".$next_course_class_data['CourseClass']['name']."<i class='am-icon am-icon-chevron-right am-margin-left-xs'></i></a>"):'&nbsp;'; ?></div>
	<div class='am-u-sm-4 am-text-right am-show-sm-only'><?php echo isset($next_course_class_data['CourseClass'])?("<a href='".$html->url('/courses/detail/'.$course_id.'/'.$next_course_class_data['CourseClass']['id'])."' title='".$next_course_class_data['CourseClass']['name']."'><i class='am-icon am-icon-chevron-right'></i></a>"):'&nbsp;'; ?></div>
	<div class='am-cf'></div>
</div>
<?php
	}
?>
<div class='course_class_detail'>
	<div class='course_class_title'><?php echo $course_class_detail['CourseClass']['name']; ?></div>
	<?php if(isset($course_class_detail['CourseClass']['author'])&&trim($course_class_detail['CourseClass']['author'])!=''){ ?><div class='am-g'>作者:<?php echo $course_class_detail['CourseClass']['author']; ?></div><?php } ?>
	<?php if(isset($course_class_detail['CourseClass']['tag'])&&trim($course_class_detail['CourseClass']['tag'])!=''){ ?><div class='am-g'>标签:<?php echo $course_class_detail['CourseClass']['tag']; ?></div><?php } ?>
	<div class='am-cf'>&nbsp;</div>
	<?php 
			if(isset($course_ware_list)&&sizeof($course_ware_list)>0){foreach($course_ware_list as $v){
	?>
		<h4 class='am-margin-top-xs am-margin-bottom-xs'><?php echo $v['CourseClassWare']['name']; ?></h4>
	<?php
				if($v['CourseClassWare']['type']=='down'){
					echo $html->link('下载',$v['CourseClassWare']['ware'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-radius'));
				}else if($v['CourseClassWare']['type']=='evaluation'){
	?>
	<div class='ware_iframe' data-rel="<?php echo $html->url('/evaluations/start_evaluation/'.$v['CourseClassWare']['ware'].'?ajax=1'); ?>"></div>
	<?php
				}else if($v['CourseClassWare']['type']=='external_video'){
					echo $html->link('开始播放',$v['CourseClassWare']['ware'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-radius'));
				}else if($v['CourseClassWare']['type']=='txt'){
	?>
		<div class='course_class_txt'><?php echo $v['CourseClassWare']['description']; ?></div>
	<?php
				}else if($v['CourseClassWare']['type']=='pdf'){
	?>
	<div class='course_class_video'>
		<iframe src="<?php echo $html->url('/courses/courseware/'.$v['CourseClassWare']['id']); ?>"></iframe>
	</div>
	<?php
				}else if($v['CourseClassWare']['type']=='gallery'&&$v['gallery_total']>0){
	?>
		<div class='am-slider am-slider-default course_class_gallery' data-am-flexslider="{slideshow:false}">
			<ul class='am-slides'>
			<?php
				for($gallery_key=0;$gallery_key<$v['gallery_total'];$gallery_key++){
			?>
				<li><img src="<?php echo $html->url('/courses/courseware/'.$v['CourseClassWare']['id'].'/'.$gallery_key); ?>" /></li>
			<?php
				}
			?>
			</ul>
		</div>
	<?php
				}else if($v['CourseClassWare']['type']=='local_video'){
	?>
			<div class='course_class_video'>
				<div class='am-u-lg-9'>
					<input type='hidden' value="<?php echo $v['CourseClassWare']['ware']; ?>" />
					<div id='course_class_video'>
						<div class='course_ware_error'>暂无视频!</div>
					</div>
				</div>
				<div class='am-u-lg-3 course_chapter_list'>
					<?php if(isset($course_chapter_list)&&sizeof($course_chapter_list)>0){ ?>
					<ul class='am-list'>
						<?php foreach($course_chapter_list as $vv){ ?>
						<li>
							<div class='am-g'>
								<div class='am-u-lg-5'><a href="<?php echo $html->url('/courses/detail/'.$course_data['Course']['id'].'/'.$vv['id']); ?>"><img src="<?php echo $configs['shop_default_img']; ?>" ></a></div>
								<div class='am-u-lg-7'><?php echo $html->link($vv['name'],'/courses/detail/'.$course_data['Course']['id'].'/'.$vv['id']); ?></div>
								<div class='am-cf'></div>
							</div>
						</li>
						<?php } ?>
					</ul>
					<?php } ?>
				</div>
				<div class='am-cf'></div>
			</div>
	<?php
				}else if($v['CourseClassWare']['type']=='iframe'){
	?>
		<div class='course_class_video'>
			<iframe src="<?php echo $v['CourseClassWare']['ware']; ?>"></iframe>
		</div>
	<?php
				}else if($v['CourseClassWare']['type']=='assignment'){
	?>
			<div class='course_class_assignment'>
				<?php echo $v['CourseClassWare']['description']; ?>
				<div class='am-g am-padding-sm assignment_detail'>
					<form class='am-form assignment_detail_form' method="POST">
						<input type='hidden' name='data[CourseAssignment][course_id]' value="<?php echo $course_id; ?>" />
						<input type='hidden' name='data[CourseAssignment][course_ware_id]' value="<?php echo $v['CourseClassWare']['id']; ?>" />
						<div class='am-form-group'>
							<div class='am-u-lg-12 am-padding-xs'><textarea name="data[CourseAssignment][content]"><?php echo isset($v['assignment_detail'])?$v['assignment_detail']['content']:''; ?></textarea></div>
							<div class='am-u-lg-8 am-padding-xs'><input type='file' class='am-fl' onchange='loadAssignmentMedia(this)' accept="audio/*,video/*,image/*,application/pdf,application/pdf,application/zip" /><?php
								$assignment_media="am-icon-eye";
								if(preg_match("/(audio|video)\/(.*)$/",mime_content_type(WWW_ROOT.$v['assignment_detail']['media']))){
									$assignment_media="am-icon-youtube-play";
								}else if(preg_match("/(image|IMAGE)\/(.*)$/",mime_content_type(WWW_ROOT.$v['assignment_detail']['media']))){
									$assignment_media="am-icon-image";
								}
									if(isset($v['assignment_detail']['media'])&&trim($v['assignment_detail']['media'])!=''&&file_exists(WWW_ROOT.$v['assignment_detail']['media'])&&is_file(WWW_ROOT.$v['assignment_detail']['media'])){ ?><a href='javascript:void(0);' class='am-fl assignment_file_preview' onclick="PreviewCourseMedia('<?php echo $v['assignment_detail']['media']; ?>','<?php echo mime_content_type(WWW_ROOT.$v['assignment_detail']['media']); ?>')"><i class="<?php echo 'am-icon '.$assignment_media; ?>"></i></a><?php } ?><a href='javascript:void(0);' class='am-fl' style='display:none;' data-am-modal="{target: '#CourseMedia', closeViaDimmer: 0}"><i class='am-icon'></i></a></div>
							<div class='am-u-lg-4 am-padding-xs am-text-right'><button type='button' class='am-btn am-btn-sm am-btn-success am-radius' onclick="ajax_course_assignment(this)">提交</button></div>
							<div class='am-cf'></div>
						</div>
					</form>
					<div class='assignment_score am-padding-sm'>
						<?php if(isset($CourseAssignmentScoreList)&&sizeof($CourseAssignmentScoreList)>0){ ?>
						<table class='am-table'>
							<thead>
								<th colspan='3'>老师评语</th>
							</thead>
							<tbody>
								<?php foreach($CourseAssignmentScoreList as $vv){ ?>
								<tr>
									<td><?php 
												if($vv['CourseAssignmentScore']['reply_from']=='0'){
													echo isset($reply_user_list[$vv['CourseAssignmentScore']['reply_from_id']])?($reply_user_list[$vv['CourseAssignmentScore']['reply_from_id']]['first_name']!=''?$reply_user_list[$vv['CourseAssignmentScore']['reply_from_id']]['first_name']:$reply_user_list[$vv['CourseAssignmentScore']['reply_from_id']]['name']):'';
												}
												if($vv['CourseAssignmentScore']['reply_from']=='1'){
													//echo isset($reply_operator_list[$vv['CourseAssignmentScore']['reply_from_id']])?$reply_operator_list[$vv['CourseAssignmentScore']['reply_from_id']]:'';
													echo $ld['administrator'];
												}
										 ?></td>
									<td><?php for($assignment_score=1;$assignment_score<=$vv['CourseAssignmentScore']['score'];$assignment_score++)echo "<span class='am-icon am-icon-star'></span>"; ?></td>
									<td><?php echo date('Y-m-d',strtotime($vv['CourseAssignmentScore']['modified'])); ?></td>
								</tr>
								<tr>
									<td colspan='3'><?php echo $vv['CourseAssignmentScore']['content']; ?></td>
								</tr>
								<?php } ?>
							</body>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
	<?php
				}else if($v['CourseClassWare']['type']=='activity'){
	?>
			<div class='ware_iframe' data-rel="<?php echo $html->url('/activities/view/'.$v['CourseClassWare']['ware'].'?ajax=1'); ?>"></div>
	<?php
				}else if($v['CourseClassWare']['type']=='scorm'){
	?>
		<div class='course_class_video'>
			<iframe src="<?php echo $html->url('/courses/scorm/'.$course_id.'/'.$course_class_detail['CourseClass']['id'].'/'.$v['CourseClassWare']['id']); ?>"></iframe>
		</div>
	<?php
				}
	?>
		<hr >
	<?php 	}} ?>
	<input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
	<input type="hidden" id="course_class_id" name="course_class_id" value="<?php echo $course_class_detail['CourseClass']['id']; ?>">
	<div id="courses_comment" class="am-cf"></div>
</div>
<style type='text/css'>
.course_class_detail,.fast_other_class{max-width:1200px;margin:0 auto!important;padding:0.5rem;}
.course_class_title{margin:5px auto;font-size:20px;}
.course_class_pdf embed{width:100%;min-height:700px;}
.course_class_video{margin-bottom:1rem;min-height:350px;}
.course_class_video iframe{width:100%;min-height:600px;}
.course_class_video>div{padding:0px;}
.course_ware_error{text-align:center;height:100%;font-size:20px;padding:10rem 0;color:red;}
.course_chapter_list{overflow-y:scroll;height:100%;}
.course_chapter_list li{padding:0.5rem 0px;}
.course_chapter_list div{padding-left: 0.5rem;padding-right:0.5rem;}
.course_chapter_list img{width:100%;height:100%;}
.assignment_score table.am-table > tbody > tr > td{border-top:0px;border-bottom: 1px solid #ddd;}
.assignment_score table.am-table > tbody > tr:nth-child(odd) > td{border-bottom:0px;}
.assignment_score table.am-table > tbody > tr:last-child > td{border-bottom:0px;}
.assignment_score span.am-icon-star{color:#dd514c;}
.ware_iframe{width:100%;margin:0 auto;}
.ware_iframe>div.am-g{margin:0 auto;}
</style>
<script src="<?php echo $webroot.'plugins/ckplayer/ckplayer.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script type='text/javascript'>
var wechat_shareTitle="<?php echo $course_class_detail['CourseClass']['name'] ?>";
var wechat_lineLink=location.href.split('#')[0];
var course_id = $('#course_id').val();
var course_class_id = $('#course_class_id').val();

courses_comment();
function courses_comment(){

	var postData={'course_id':course_id,'course_class_id':course_class_id};
	$.ajax({ 
		url: web_base+"/courses/courses_comment/",
		dataType:"html",
		data:postData,
		type:"POST",
		success: function(data){
			$('#courses_comment').html(data);
			$("#courses_comment input[type='radio']").uCheck();
	    }
	});
}

$(function(){
	var mediaHeight=$('.course_class_video').height();
	$('.course_chapter_list').css('min-height',mediaHeight+'px');
	
	$(".ware_iframe").each(function(){
		var ajax_link=$(this).attr('data-rel');
		var ware_iframe=$(this);
		$.ajax({ 
			url: ajax_link,
			dataType:"html",
			data:{},
			type:"GET",
			success: function(data){
				$(ware_iframe).html(data);
		    	}
		});
	});
});

var server_host=window.location.origin;
if(document.getElementById('course_class_video')){
	var media_file=$('#course_class_video').parent().find("input[type='hidden']").val().trim();
	if(media_file!=''){
		loadmedia(media_file);
	}
}
function loadmedia(mediafile){
	var flashvars={
		f:server_host+mediafile,//视频地址
		a:'',//调用时的参数，只有当s>0的时候有效
		s:'0',//调用方式，0=普通方法（f=视频地址），1=网址形式,2=xml形式，3=swf形式(s>0时f=网址，配合a来完成对地址的组装)
		c:'0',//是否读取文本配置,0不是，1是
		x:'',//调用配置文件路径，只有在c=1时使用。默认为空调用的是ckplayer.xml
		i:'',//初始图片地址
		d:'',//暂停时播放的广告，swf/图片,多个用竖线隔开，图片要加链接地址，没有的时候留空就行
		u:'',//暂停时如果是图片的话，加个链接地址
		l:'',//前置广告，swf/图片/视频，多个用竖线隔开，图片和视频要加链接地址
		r:'',//前置广告的链接地址，多个用竖线隔开，没有的留空
		t:'',//视频开始前播放swf/图片时的时间，多个用竖线隔开
		y:'',//这里是使用网址形式调用广告地址时使用，前提是要设置l的值为空
		z:'',//缓冲广告，只能放一个，swf格式
		e:'8',//视频结束后的动作，0是调用js函数，1是循环播放，2是暂停播放并且不调用广告，3是调用视频推荐列表的插件，4是清除视频流并调用js功能和1差不多，5是暂停播放并且调用暂停广告
		v:'80',//默认音量，0-100之间
		p:'1',//视频默认0是暂停，1是播放，2是不加载视频
		h:'0',//播放http视频流时采用何种拖动方法，=0不使用任意拖动，=1是使用按关键帧，=2是按时间点，=3是自动判断按什么(如果视频格式是.mp4就按关键帧，.flv就按关键时间)，=4也是自动判断(只要包含字符mp4就按mp4来，只要包含字符flv就按flv来)
		q:'',//视频流拖动时参考函数，默认是start
		m:'',//让该参数为一个链接地址时，单击播放器将跳转到该地址
		o:'',//当p=2时，可以设置视频的时间，单位，秒
		w:'',//当p=2时，可以设置视频的总字节数
		g:'',//视频直接g秒开始播放
		j:'',//跳过片尾功能，j>0则从播放多少时间后跳到结束，<0则总总时间-该值的绝对值时跳到结束
		//k:'32|63',//提示点时间，如 30|60鼠标经过进度栏30秒，60秒会提示n指定的相应的文字
		//n:'这是提示点的功能，如果不需要删除k和n的值|提示点测试60秒',//提示点文字，跟k配合使用，如 提示点1|提示点2
		wh:'4:3',//宽高比，可以自己定义视频的宽高或宽高比如：wh:'4:3',或wh:'1080:720'
		lv:'0',//是否是直播流，=1则锁定进度栏
		loaded:'loadedHandler',//当播放器加载完成后发送该js函数loaded
		//调用播放器的所有参数列表结束
		//以下为自定义的播放器参数用来在插件里引用的
		my_title:'演示视频标题文字',
		my_url:encodeURIComponent(window.location.href)//本页面地址
	};
	var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};//这里定义播放器的其它参数如背景色（跟flashvars中的b不同），是否支持全屏，是否支持交互
	var video=[(server_host+mediafile)+'->video/mp4'];
	CKobject.embed(server_host+'/plugins/ckplayer/ckplayer.swf','course_class_video','ckplayer_course_class_video','100%','100%',true,flashvars,video,params);
}

//如果你不需要某项设置，可以直接删除，注意var flashvars的最后一个值后面不能有逗号
function loadedHandler(){
	//CKobject.getObjectById('ckplayer_course_class_ware').videoPlay();
}

//开始播放
function videoLoadJs(s){
	//console.log("执行了播放");
}

$(function(){
	//$("img.lazy").lazyload();
	
	$("body").bind("contextmenu",function(){//取消右键事件
	    	return false;
	}).bind('copy',function(){//复制
		return false;
	});
	
	$("embed").bind("contextmenu",function(){//取消右键事件
	    	return false;
	}).bind('copy',function(){//复制
		return false;
	});
	
})
var cookie_key='course_read_time'+course_id+course_class_id;
var user_course_read=function(course_id,course_class_id){

	var course_read_time=getCookie(cookie_key);
	course_read_time=course_read_time==null?0:parseInt(course_read_time);
	course_read_time++;
	setCookie(cookie_key,course_read_time);
	setTimeout(function(){
		user_course_read(course_id,course_class_id);
	},1000);
};

var ajax_user_course_read=function(course_id,course_class_id){
	//alert('123');

	var course_read_time=getCookie(cookie_key);
	//alert(getCookie(cookie_key));
	course_read_time=course_read_time==null?0:parseInt(course_read_time);
	setCookie(cookie_key,0);
	$.ajax({
		url:web_base+"/courses/ajax_course_read_time",
		type:'POST',
		data:{'course_id':course_id,'course_class_id':course_class_id,'course_read_time':course_read_time},
		dataType:'json',
		success:function(data){
			if(data.code=='1'){
				setTimeout(function(){
					ajax_user_course_read(course_id,course_class_id);
				},30000);
			}
		}
	});
}
setCookie(cookie_key,0);
user_course_read($('#course_id').val(),$('#course_class_id').val());
ajax_user_course_read($('#course_id').val(),$('#course_class_id').val());

function loadAssignmentMedia(fileBox){
	$(fileBox).parent().find('a.assignment_file_preview').hide();
	var prebtn=$(fileBox).parent().find('a:last-child');
	$(prebtn).hide();
	var uploadfile=fileBox.files[0];
	var uploadfileType=uploadfile.type;
	var reader = new FileReader();
	reader.readAsText(uploadfile, 'UTF-8');
	reader.onload = function (e) {
		if(reader.readyState==2){//加载完成
			var fileSize=Math.round(e.total/1024/1024/1024);
			if(fileSize>10){
                        	alert('最大文件限制为10M,当前为'+fileSize+'M');
                        	return false;
                    }
                    $("#CourseMedia div.am-modal-bd *").hide();
                    $("#CourseMedia div.am-modal-bd video,#CourseMedia div.am-modal-bd img").attr('src','');
                    if (/(audio|video)\/(.*)$/.test(uploadfileType)){
				var fileResult = reader.result;
				$(prebtn).find('i').removeClass('am-icon-image').addClass('am-icon-youtube-play');
				$(prebtn).show();
				$("#CourseMedia div.am-modal-bd video").attr("src", window.URL.createObjectURL(uploadfile)).show();
                    }else if(/(image|IMAGE)\/(.*)$/.test(uploadfileType)){
                    	$(prebtn).find('i').removeClass('am-icon-youtube-play').addClass('am-icon-image');
                    	$(prebtn).show();
                    	$("#CourseMedia div.am-modal-bd img").attr("src", window.URL.createObjectURL(uploadfile)).show();
			}
		}
	}
}

function ajax_course_assignment(btn){
	var postForm=$(btn).parents('form');
	var assignment_content=$(postForm).find('textarea').val();
	var formData= new FormData();
	$.each(postForm.serializeArray(),function (i,field) {  
		formData.append(field.name,field.value);
	});
	var assignmentFileList=$(postForm).find("input[type='file']");
	if(assignmentFileList.length>0){
		var assignmentFile=assignmentFileList[0].files;
		if(assignmentFile.length>0)formData.append('AssignmentMedia',assignmentFile[0]);
	}
	if(assignment_content==''&&((assignmentFileList.length>0&&assignmentFileList.val()=='')||assignmentFileList.length==0)){
		seevia_alert('请填写内容');
		return;
	}
	$(btn).button('loading');
	var xhr = null;
	if (window.XMLHttpRequest){// code for all new browsers
		xhr=new XMLHttpRequest();
	}else if (window.ActiveXObject){// code for IE5 and IE6
		xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		alert("Your browser does not support XMLHTTP.");return false;
	}
	xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
            	$(btn).button('reset');
			eval("var result="+xhr.responseText);
			if(result.code=='1'){
				seevia_alert(result.message);
			}
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
            $(addBtn).button('reset');
        };
        xhr.open("POST", web_base+"/courses/ajax_course_assignment");
        xhr.send(formData);
}
</script>