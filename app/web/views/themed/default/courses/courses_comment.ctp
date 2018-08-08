<?php echo $htmlSeevia->css(array('embed.default')); ?>
<!--屏蔽的关键字-->
<?php $wordarr=""; if(isset($sm['word'])&&$sm['word']!=""){
	foreach($sm['word'] as $k=>$v){
		$wordarr.=$v['BlockWord']['word'].",";
	}
}?>
<style type='text/css'>
.icon_sw_face{top:2px;}
.icon_sw_img{top:2px;}
#CourseMedia video{max-width:100%;max-height:100%;}
#Commodity_review{width: 98%;margin:0 auto;}
#page_comment #ds-thread #ds-reset .ds-textarea-wrapper{border-bottom:1px solid #ccc;-webkit-border-bottom-left-radius:3px;-webkit-border-bottom-right-radius:3px;}
#page_comment #ds-thread #PreviewCourseMedia{display:none;color:#d82b2b;}
#page_comment #ds-thread .ds-post-toolbar div[class*=am-u-]{padding-left:0px;padding-right:0px;}
#page_comment #ds-thread .ds-post-toolbar label.am-radio{margin-left: 10px;margin-top: 1px;display: inline-block;}
#page_comment .am-close.am-text-danger,#page_comment .am-close.am-text-danger:hover{color:#dd514c;opacity: inherit;float:right;margin-top:-8px;}
#CourseClassComplete .am-modal-hd{padding:2.4rem;}
#CourseClassComplete .am-modal-bd{padding:2.4rem;padding-top:0px;}
#CourseClassComplete .am-modal-bd a:nth-child(1){margin-bottom:2.4rem;}
#CourseClassComplete .am-modal-bd a:nth-child(2),#CourseClassComplete .am-modal-bd a:nth-child(2):hover{color:#666;display:block;}
#ForcedCompleteCourse .am-modal-hd{font-size:3rem;padding-top:3rem;padding-bottom:3rem;}
#ForcedCompleteCourse .am-modal-bd{margin-bottom:3rem;padding-bottom:3rem;}
@media only screen and (max-width: 640px){
	#page_comment .ds-post-toolbar div[class*=am-u-]:first-child div:nth-child(2){width:68%;}
	#page_comment .ds-post-toolbar input[type='file']{max-width:100%}
	#CourseClassComplete .am-modal-hd,#CourseClassComplete .am-modal-bd{padding-left:0px;padding-right:0px;}
	#CourseClassComplete .am-modal-bd a:nth-child(1){display:block;word-wrap:break-word;white-space:initial;width:91%;}
}
</style>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/handlebars.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div id="page_comment">
<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<div class="am-g am-g-fixed" id="Commodity_review">
  <div class="am-panel am-panel-default am-margin-top-sm">
	<div class="am-panel-hd my-head">笔记记录</div>
	<div  class="am-panel-bd">
	  <div class="listbox">
      <!--商品评论-->
    	<div class="comment" style="width:100%;margin:0 auto 16px;">
    		<?php if(!empty($_SESSION['User']['User']['id'])){?>
			<form id="comment_form" name="comment_form" enctype="multipart/form-data" method="POST">
			<input type="hidden" name="data[CourseNote][course_id]" value="<?php echo isset($course_id)?$course_id:''; ?>">
			<input type="hidden" name="data[CourseNote][course_class_id]" value="<?php echo isset($course_class_id)?$course_class_id:''; ?>">
			<div id="ds-thread" class="am-cf am-margin-top-sm">
				<div id="ds-reset">
					<div class="ds-textarea-wrapper ds-rounded-top" >
						<textarea  style="resize:none;font-size:1.3rem;" onkeyup="strLenCalc($(this),'checklen',280);" class="am-input-sm" id="contenttext"  title="" <?php if(empty($_SESSION['User']['User']['id'])){echo " disabled='disabled'";}?>></textarea>
						<input type="hidden" name="data[CourseNote][note]" id="hid" value="" />
						<input type="hidden" name="data[CourseNote][user_id]" value="<?php if(!empty($_SESSION['User']['User']['id'])){echo $_SESSION['User']['User']['id'];}?>">
					</div>
				</div>
				<div class='ds-post-toolbar am-padding-top-xs'>
					<div class='am-u-lg-6 am-u-md-6 am-u-sm-12'>
						<div class="am-fl am-text-left">提交文件:</span></div>
						<div class="am-fl am-text-left"><input type='file' id='CourseNote_media' name="data[CourseNote][media]" onchange='loadCourseMedia(this)' accept="audio/*,video/*" /></div>
						<div class="am-fl am-text-left"><a href='javascript:void(0);' id='PreviewCourseMedia' data-am-modal="{target: '#CourseMedia', closeViaDimmer: 0}">预览</a></div>
						<div class='am-cf'></div>
					</div>
					<div class='am-u-lg-6 am-u-md-6 am-u-sm-12'>
						<div class='am-fr am-margin-left-sm am-hide-sm-only'>
							<button class="am-btn am-btn-default am-btn-radius am-btn-sm" type="button" data-am-loading="{loadingText: '处理中...'}" onclick="add_comment(this,'1')">保存</button>
							<?php if(isset($user_course_class['UserCourseClassDetail']['status'])&&$user_course_class['UserCourseClassDetail']['status']!='1'){ ?>
							<button class="am-btn am-btn-success am-btn-sm am-margin-right-sm"  type="button" data-am-loading="{loadingText: '处理中...'}" onclick="add_comment(this,'0');complete_course_class(this,'<?php echo $user_course_class['UserCourseClass']['course_id']; ?>','<?php echo $user_course_class['UserCourseClassDetail']['course_class_id']; ?>');">完成</button>
							<?php } ?>
						</div>
						<?php if(isset($configs['comment_captcha'])&&$configs['comment_captcha']=='1'){ ?>
						<div class='am-fr am-margin-left-sm am-padding-top-xs'>
							<div class="am-fl am-padding-right-xs"><?php echo $ld['verify_code'] ?> </div>
							<div class='am-fl am-padding-right-xs'>
								<div class="am-form-icon am-form-feedback">
									<input type="hidden" id="ck_authnum" value="" />
									<input type="text" style="width:65px;border: 1px solid #ccc;height:20px;padding-left: 0.5em !important;padding-right:0em !important;" class="am-form-field" name="data[CourseNote][authnum]" id="authnums_Comment" /><span style="line-height:0;right: 0;"></span>
								</div>
							</div>
							<div class='am-fl'><img id='authnum_comment' align='absmiddle' src="<?php echo $html->url('/securimages/index?time='.time()); ?>" alt="<?php echo $ld['not_clear']?>" title="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_comment');" /></div>
							<div class='am-cf'></div>
						</div>
						<?php } ?>
						<div class='am-fr am-padding-top-xs am-u-lg-3 am-u-md-4 am-u-sm-12'>
		    					<label class="am-radio am-danger">
								<input type="radio" name="data[CourseNote][is_public]" value="0" data-am-ucheck checked>
								<span><?php echo $ld['public'];?></span>
							</label>
		    					<label class="am-radio am-danger">
								<input type="radio" name="data[CourseNote][is_public]" value="1" data-am-ucheck>
								<span><?php echo $ld['privacy'];?></span>
							</label>
						</div>
						<div class='am-show-sm-only am-u-sm-12 am-text-center'>
							<button class="am-btn am-btn-default am-btn-radius am-btn-sm" type="button" data-am-loading="{loadingText: '处理中...'}" onclick="add_comment(this,'1')">保存</button>
							<?php if(isset($user_course_class['UserCourseClassDetail']['status'])&&$user_course_class['UserCourseClassDetail']['status']!='1'){ ?>
							<button class="am-btn am-btn-success am-btn-sm am-margin-right-sm"  type="button" data-am-loading="{loadingText: '处理中...'}" onclick="add_comment(this,'0');complete_course_class(this,'<?php echo $user_course_class['UserCourseClass']['course_id']; ?>','<?php echo $user_course_class['UserCourseClassDetail']['course_class_id']; ?>');">完成</button>
							<?php } ?>
						</div>
						<div class='am-cf'></div>
					</div>
					<div class='am-cf'></div>
				</div>
			</div>
			</form>
			<?php }else{?>
			  <?php if(empty($_SESSION['User']['User']['id'])){echo $ld['please_login']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['login']."</a> ".$ld['perhaps']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['register']."</a>";}else{?>
				<p><?php //echo $ld['please_buy_before_comment'];?></p>
			  <?php }?>
			<?php }?>
		</div>
		
		<div class="am-comments-list am-comments-list-flip" id="product_comments">
            
            <div class="am-list-news-bd">
                <ul id="maodian" class="am-comments-list am-comments-list-flip events-list">
	                <?php if(isset($course_note)&&sizeof($course_note)>0){ foreach($course_note as $v){
	                			$user_note_name=isset($user_note_list[$v['CourseNote']['user_id']]['name'])?$user_note_list[$v['CourseNote']['user_id']]['name']:'';
	                			if(!(isset($_SESSION['User']['User']['id'])&&$_SESSION['User']['User']['id']==$v['CourseNote']['user_id'])){
	                				$user_note_name=mb_substr($user_note_name, 0, 1, 'utf-8').'***'.(mb_strlen($user_note_name,'utf-8')>2?mb_substr($user_note_name, -1, 1, 'utf-8'):'');
	                			}
	            	   ?>
				<li class="am-comment" style="margin-bottom: 50px;">
					<a style="cursor: default;" href="javascript:void(0);"><img id="photo" title="<?php echo $user_note_name; ?>" src="<?php echo isset($user_note_list[$v['CourseNote']['user_id']]['img01'])?$user_note_list[$v['CourseNote']['user_id']]['img01']:''; ?>" class="am-comment-avatar" width="48" height="48"/></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
							<div class="am-comment-meta am-u-sm-9">
								<a href="javascript:void(0);" class="am-comment-author"><?php echo $user_note_name; ?></a>
								记录于 <time><?php echo $v['CourseNote']['created'] ?></time>
							</div>
							<div class="am-comment-meta am-u-sm-3 am-text-center">
								<?php if(isset($_SESSION['User']['User']['id'])&&$_SESSION['User']['User']['id']==$v['CourseNote']['user_id']){ ?>
								<a href="javascript: void(0);" class="am-close am-close-spin am-text-danger" data-am-modal-close onclick="ajax_remove_course_note(<?php echo $v['CourseNote']['id']; ?>)">&times;</a>
								<?php } ?>
							</div>
							<div class='am-cf'></div>
						</header>
						<div class="am-comment-bd">
							<?php echo $v['CourseNote']['note'];echo trim($v['CourseNote']['media'])!=''&&file_exists(WWW_ROOT.trim($v['CourseNote']['media']))?"&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($v['CourseNote']['media'])."')\"><i class='am-icon am-icon-youtube-play am-text-danger'></i></a>":''; ?>
						</div>
				  	</div>
				</li>
			<?php }}?>
                </ul>
            </div>
            <div class="pull-action pull-up am-hide am-text-center am-btn-block am-btn-default" style="cursor: pointer;">More...</div>
		</div>
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

<div class="am-modal am-modal-no-btn" tabindex="-1" id="CourseClassComplete">
	<div class="am-modal-dialog">
		<div class="am-modal-hd">恭喜您完成了[<?php echo isset($course_class_detail['CourseClass'])?$course_class_detail['CourseClass']['name']:'';  ?>]&nbsp;
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd am-text-center">
			<a href="javascript:void(0);" class='am-btn am-btn-success am-radius am-margin-left-sm'>继续学习下一节</a>
			<a href="<?php echo $html->url('/courses/view/'.(isset($course_id)?$course_id:0)); ?>">返回目录</a>
		</div>
	</div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="ForcedCompleteCourse">
	<div class="am-modal-dialog">
		<div class="am-modal-hd">你点击的太快了,确定要完成当前课程?
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd am-text-center">
			<button type='button' class='am-btn am-btn-danger am-radius am-margin-left-sm' onclick="ajax_forced_complete(this,'<?php echo $user_course_class['UserCourseClass']['course_id']; ?>','<?php echo $user_course_class['UserCourseClassDetail']['course_class_id']; ?>');">确认完成</button>
			<button type='button' class='am-btn am-btn-default am-radius am-margin-left-sm' onclick="$('#ForcedCompleteCourse').modal('close');">我再看看</button>
		</div>
	</div>
</div>

<script type="text/javascript">
function complete_course_class(btn,course_id,course_class_id){
	$(btn).button('loading');
	$.ajax({
		url:web_base+"/courses/ajax_complete_course_class",
		type:'POST',
		data:{'course_id':course_id,'course_class_id':course_class_id},
		dataType:'json',
		success:function(data){
			$(btn).button('reset');
			if(data.code=='1'){
				var next_course_class=typeof(data.next_course_class)!='undefined'?parseInt(data.next_course_class.id):0;
				if(next_course_class>0){
					$("#CourseClassComplete .am-modal-bd a:first-child").show();
					$("#CourseClassComplete .am-modal-bd a:first-child").attr('href',web_base+'/courses/detail/'+course_id+'/'+next_course_class);
					var defaultHtml=$("#CourseClassComplete .am-modal-bd a:first-child").text();
					$("#CourseClassComplete .am-modal-bd a:first-child").html(defaultHtml+"<br class='am-show-sm-only'>["+data.next_course_class.name+"]&nbsp;&nbsp;<i class='am-icon am-icon-angle-double-right'></i>");
				}else{
					$("#CourseClassComplete .am-modal-bd a:first-child").hide();
					$("#CourseClassComplete .am-modal-bd a:first-child").attr('href','javascript:void(0);');
				}
				$("#CourseClassComplete").modal({closeViaDimmer:0});
				$(btn).hide().remove();
			}else if(data.code=='2'){
				$('#ForcedCompleteCourse').modal({closeViaDimmer:0});
			}else{
				seevia_alert(data.message);
			}
		}
	});
}

function ajax_forced_complete(btn,course_id,course_class_id){
	$(btn).button('loading');
	$.ajax({
		url:web_base+"/courses/ajax_complete_course_class",
		type:'POST',
		data:{'course_id':course_id,'course_class_id':course_class_id,'forcible_operation':'1'},
		dataType:'json',
		success:function(data){
			$(btn).button('reset');
			$('#ForcedCompleteCourse').modal('close');
			if(data.code=='1'){
				var next_course_class=typeof(data.next_course_class)!='undefined'?parseInt(data.next_course_class.id):0;
				if(next_course_class>0){
					$("#CourseClassComplete .am-modal-bd a:first-child").show();
					$("#CourseClassComplete .am-modal-bd a:first-child").attr('href',web_base+'/courses/detail/'+course_id+'/'+next_course_class);
					var defaultHtml=$("#CourseClassComplete .am-modal-bd a:first-child").text();
					$("#CourseClassComplete .am-modal-bd a:first-child").html(defaultHtml+"<br class='am-show-sm-only'>["+data.next_course_class.name+"]&nbsp;&nbsp;<i class='am-icon am-icon-angle-double-right'></i>");
				}else{
					$("#CourseClassComplete .am-modal-bd a:first-child").hide();
					$("#CourseClassComplete .am-modal-bd a:first-child").attr('href','javascript:void(0);');
				}
				$("#CourseClassComplete").modal({closeViaDimmer:0});
			}else{
				seevia_alert(data.message);
			}
		}
	});
}


var j_no_comments="<?php echo $ld['no_comments']; ?>";
//评论分享
function checktoken(type){
	$.ajax({ 
		url: web_base+"/synchros/checktoken/"+type,
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.flag==0){
				window.location.href=web_base+'/synchros/opauth/'+type;
			}else if(data.status=='1'){
				$("#"+type+"_icon").attr("style","");
			}else if(data.status=='0'){
				$("#"+type+"_icon").attr("style","filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;");
			}
	    }
	});
}
//显示表情框
var clicks = true;
$("#biaoqing").click(function(){
	if($(".expression").css("display")=="block"){
		$(".expression").css("display","none");
	}
	else{
		$(".expression").css("display","block");
		clicks=false;
	}
});
document.body.onclick = function(){
    if(clicks){
       	$(".expression").css("display","none");
    }
    clicks = true;
}
//显示上传图片框
$("#pictureUploadButton").click(function(){
	$("#pictureFile").css("display","block");
});
var dobj=$("#pictureFile");
$(document).mousedown(function(event){
  	if(event.target.name!=$(dobj).attr("name")){
		$(dobj).hide(100);
 	}
});
$("#pictureFilebox span").click(function(){
	$("#pictureFile").css("display","none");
});
//选中表情事件
$(".picks").click(function(){
	var ids=$(this).attr("id");
	var titles=$(this).children().attr("title");
	if($("#contenttext").val()==""){
		$("#contenttext").val(titles);
		$("#contenttext").html(titles);
		//strLenCalc($(".input_detail"),'checklen',280);
	}
	else{
		$("#contenttext").val($("#contenttext").val()+titles);
		$("#contenttext").html($("#contenttext").val()+titles);
	}
});
//检测评论字数
function strLenCalc(obj, checklen, maxlen) {
	var v = obj.val(), charlen = 0, maxlen = !maxlen ? 200 : maxlen, curlen = maxlen, len = v.length;
	for(var i = 0; i < v.length; i++) {
		if(v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
			curlen -= 1;
		}
	}
}
var Url="/theme/default/img/gif/";//表情图片路径
//表情数组
var Expression=new Array("/微笑","/撇嘴","/好色","/发呆","/得意","/流泪","/害羞","/睡觉","/尴尬","/呲牙","/惊讶","/冷汗","/抓狂","/偷笑","/可爱","/傲慢","/犯困","/流汗","/大兵","/咒骂","/折磨/","/衰","/擦汗","/抠鼻","/鼓掌","/坏笑","/左哼哼","/右哼哼","/鄙视","/委屈","/阴险","/亲亲","/可怜","/爱情","/飞吻","/怄火","/回头","/献吻","/左太极");
//多次替换
String.prototype.replaceAll = function (findText, repText){
    var newRegExp = new RegExp(findText, 'gm');
    return this.replace(newRegExp, repText);
}
//表情文字替换
function replace_content(con){
	var content="";
	for(var i=0;i<Expression.length;i++){
		content = con.replaceAll(Expression[i], "<img src=" + Url + "F_"+(i+1)+".gif />");
	}
	return content;
}
var img_size1=true;
function filesize(target,obj) {
	var max_file_size=2000;
	var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
	try{
		if(lastname(obj)){
			var fileSize = 0; 
			if (isIE && !target.files) { 
				var filePath = target.value; 
				var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
				var file = fileSystem.GetFile (filePath); 
				fileSize = file.Size; 
			} else { 
				fileSize = target.files[0].size; 
			} 
			var size = fileSize / 1024; 
			if(size>max_file_size){
				seevia_alert("图片最大限制2M");
				img_size1=false;
			}else{
				img_size1=true;
			}
		}
	}catch(e){
		img_size1=false;
		seevia_alert("请将工具 -- internet选项 -- 安全 -- 自定义级别对未标记为可安全执行脚本的activex空间初始化并执行脚本  设置为启用");
	}
}
function lastname(obj){ 
	var filepath = document.getElementById(obj).value;  
	var re = /(\\+)/g; 
	var filename=filepath.replace(re,"#");
	//对路径字符串进行剪切截取
	var one=filename.split("#");
	//获取数组中最后一个，即文件名
	var two=one[one.length-1];
	//再对文件名进行截取，以取得后缀名
	var three=two.split(".");
	 //获取截取的最后一个字符串，即为后缀名
	var last=three[three.length-1];
	//添加需要判断的后缀名类型
	var tp = "jpg、jpeg、gif、png、JPG、JPEG、GIF、PNG";
	var rs=tp.indexOf(last);
	if(rs>=0){
	 return true;
	 }else{
	 	seevia_alert("文件格式错误");
	 	document.getElementById(obj).value = "";
	 	return false;
	  }
}
function add_comment(btn,operator_flag){
	$(btn).button($.AMUI.utils.parseOptions($(btn).data('amLoading')));
	if(operator_flag=='1'&&($("#contenttext").val()==""&&$('#CourseNote_media').val()=='')){
		seevia_alert("请填写笔记内容");
		return false;
	}else if($("#contenttext").val()!=""||$('#CourseNote_media').val()!=''){
		//判断是否含有屏蔽的词
		var word=$("#word").val();
		var con=CheckKeyword(word,$("#contenttext").val());
		con=replace_content(con);
		$("#hid").val(con);
		if(img_size1){
			if(document.getElementById('authnums_Comment')){
				var authnum_msg="Error";
				var authnum_val=$("#authnums_Comment").val().trim();
				var ck_auth_num=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").length;
				
				if(authnum_val.length==0){
					$("#authnums_Comment").parent().removeClass("am-form-success");
					$("#authnums_Comment").parent().removeClass("am-form-error");
					$("#authnums_Comment").parent().addClass("am-form-warning");
					$("#authnums_Comment").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
					$("#authnums_Comment").parent().find("span").addClass("am-icon-warning").css("display","block");
				}else if(ck_auth_num>0){
					var ck_auth=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").val();
					if(ck_auth.trim().length>0){
						if(authnum_val.toLowerCase()!=ck_auth){
				    			$("#authnums_Comment").parent().removeClass("am-form-success");
				    			$("#authnums_Comment").parent().removeClass("am-form-warning");
				    			$("#authnums_Comment").parent().addClass("am-form-error");
				    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
				    			$("#authnums_Comment").parent().find("span").addClass("am-icon-times").css("display","block");
						}else{
				    			$("#authnums_Comment").parent().removeClass("am-form-error");
				    			$("#authnums_Comment").parent().removeClass("am-form-warning");
				    			$("#authnums_Comment").parent().addClass("am-form-success");
				    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
				    			$("#authnums_Comment").parent().find("span").addClass("am-icon-check").css("display","block");
							authnum_msg="";
						}
					}
				}
			}else{
				var authnum_msg='';
			}
			if(authnum_msg==""){
				ajax_add_activity_comment(btn);
			}
		}
	}
}
function ajax_add_activity_comment(addBtn){
	$(addBtn).button('loading');
	var xhr = null;
	if (window.XMLHttpRequest){// code for all new browsers
		xhr=new XMLHttpRequest();
	}else if (window.ActiveXObject){// code for IE5 and IE6
		xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		alert("Your browser does not support XMLHTTP.");return false;
	}
	var formData= new FormData();  
	$.each($("#comment_form").serializeArray(),function (i,field) {  
		formData.append(field.name,field.value);
	});
	if(document.getElementById('CourseNote_media')){
		var CourseNote_media=document.getElementById('CourseNote_media').files;
		if(CourseNote_media.length>0){
			formData.append('CourseNoteMedia',CourseNote_media[0]);
		}
	}
	xhr.onreadystatechange = function(){
	            if (xhr.readyState == 4 && xhr.status == 200){
				eval("var result="+xhr.responseText);
				if(result.code=='1'){
					courses_comment();
					if(document.getElementById('authnums_Comment'))change_captcha('authnum_comment');
				}
				$(addBtn).button('reset');
	            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
            $(addBtn).button('reset');
        };
        xhr.open("POST", web_base+"/courses/ajax_add_course_note");
        xhr.send(formData);
}
//评论验证码
if(document.getElementById('authnum_comment'))change_captcha('authnum_comment',true);
$("#authnums_Comment").blur(function(){
	var authnum_msg="Error";
	var authnum_val=$("#authnums_Comment").val().trim();
	var ck_auth_num=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").length;
	if(authnum_val.length==0){
		$("#authnums_Comment").parent().removeClass("am-form-success");
		$("#authnums_Comment").parent().removeClass("am-form-error");
		$("#authnums_Comment").parent().addClass("am-form-warning");
		$("#authnums_Comment").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$("#authnums_Comment").parent().find("span").addClass("am-icon-warning").css("display","block");
	}else if(ck_auth_num>0){
		var ck_auth=$("#authnums_Comment").parent().parent().find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
    			$("#authnums_Comment").parent().removeClass("am-form-success");
    			$("#authnums_Comment").parent().removeClass("am-form-warning");
    			$("#authnums_Comment").parent().addClass("am-form-error");
    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
    			$("#authnums_Comment").parent().find("span").addClass("am-icon-times").css("display","block");
			}else{
    			$("#authnums_Comment").parent().removeClass("am-form-error");
    			$("#authnums_Comment").parent().removeClass("am-form-warning");
    			$("#authnums_Comment").parent().addClass("am-form-success");
    			$("#authnums_Comment").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
    			$("#authnums_Comment").parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
			}
		}
	}
});

var img = document.querySelectorAll('#photo');
for(var i = 0;i<img.length;i++){
	if(img[i].getAttribute('src') ==''){
		img[i].setAttribute('src','/theme/default/img/no_head.png')
	}
}

function loadCourseMedia(fileBox){
	$('#PreviewCourseMedia').hide();
	var uploadfile=fileBox.files[0];
	var reader = new FileReader();
	reader.readAsText(uploadfile, 'UTF-8');
	reader.onload = function (e) {
		if(reader.readyState==2){//加载完成
			var fileSize=Math.round(e.total/1024/1024);
			if(fileSize>10){
                        	seevia_alert('最大文件限制为10M,当前为'+fileSize+'M');
                        	$(fileBox).val('');
                        	return false;
                    }
			var fileResult = reader.result;
			$('#PreviewCourseMedia').show();
			$("#CourseMedia video").attr("src", window.URL.createObjectURL(uploadfile));
		}
	}
}

$("#CourseMedia").on('opened.modal.amui', function(){
	var MediaAudio = $("#CourseMedia video")[0];
	if (MediaAudio.paused){
		MediaAudio.play();
	}else {
		MediaAudio.pause();
	}
}).on('close.modal.amui', function(){
	var MediaAudio = $("#CourseMedia video")[0];
	if(!MediaAudio.paused){
		MediaAudio.pause();
	}
});

function PreviewCourseMedia(mediaPath){
	$("#CourseMedia video").attr("src", mediaPath);
	$("#CourseMedia").modal();
}

function ajax_remove_course_note(course_note_id){
	seevia_confirm(function(){
		$.ajax({ 
			url: web_base+"/courses/remove_note/"+course_note_id,
			dataType:"json",
			type:"POST",
			success: function(data){
				if(data.flag=='1'){
					seevia_alert('删除成功');
					courses_comment();
				}else{
					seevia_alert('删除失败');
				}
		    	},
		    	complete:function(){
		    		$("#seevia_confirm").modal('close');
		    	}
		});
	},'确认删除?');
}
</script>