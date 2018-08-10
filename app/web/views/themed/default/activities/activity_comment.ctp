<?php if(constant("Product")=="AllInOne"){?>
<?php echo $htmlSeevia->css(array('embed.default')); ?>
<!--屏蔽的关键字-->
<?php $wordarr=""; if(isset($sm['word'])&&$sm['word']!=""){
	foreach($sm['word'] as $k=>$v){
		$wordarr.=$v['BlockWord']['word'].",";
	}
}?>
<style>
	.icon_sw_face{top:2px;}
	.icon_sw_img{top:2px;}
	#ds-thread #ds-reset .ds-post-options{border-right:1px solid #ccc;margin-right: 0;}
</style>
<script src="<?php echo $webroot.'plugins/handlebars.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div id="page_comment">
<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<div class="am-g am-g-fixed" id="Commodity_review" style="margin:auto;">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head">评论</div>
	<div  class="am-panel-bd">
	  <div class="listbox am-padding-horizontal-sm">
      <!--商品评论-->
    	<div class="comment" style="width:100%;margin:0 auto 16px;">
    		<?php if(!empty($_SESSION['User']['User']['id'])){if((isset($activity_id)&&$activity_id>0&&isset($activity_user))||!isset($activity_id)){ ?>
			<form id="comment_form" name="comment_form" enctype="multipart/form-data" method="POST">
			 <!-- action="/pages/ajax_add_page_comment" -->
			<!-- <div id="comment_title"><?php echo $ld['comment'] ?></div> -->
			<div id="ds-thread" class="am-cf" style="margin-top: 5px;">
			  <div id="ds-reset">
				<div class="ds-textarea-wrapper ds-rounded-top" >
				  <input type="hidden" name="data[Comment][type_id]" id="type_id" value="<?php echo isset($activity_id)?$activity_id:'0'; ?>">
				  <!-- <?php pr($article_id) ?> -->
				  <input type="hidden" name="data[Comment][parent_id]" value="<?php echo isset($comment_reply['Comment']['parent_id'])?$comment_reply['Comment']['parent_id']:'0'; ?>">
				  <textarea  style="resize:none;font-size:1.3rem;" onkeyup="strLenCalc($(this),'checklen',280);" class="am-input-sm" id="contenttext"  title="" <?php if(empty($_SESSION['User']['User']['id'])){echo " disabled='disabled'";}?>></textarea>
				  <input type="hidden" name="data[Comment][content]" id="hid" value="" />
				  <input type="hidden" name="user_id" value="<?php if(!empty($_SESSION['User']['User']['id'])){echo $_SESSION['User']['User']['id'];}?>">
				  <input type="hidden" name="rank" value="5">
				</div>
				<div class="ds-post-toolbar">
				  <div class="ds-post-options ds-gradient-bg" style="border-right:1px solid #ccc;margin-right: 0;">

					<?php if(isset($configs['comment_captcha'])&&$configs['comment_captcha']=='1'){ ?>
					<div class="authentication">
						<img id='authnum_comment' align='absmiddle' src="<?php echo $webroot; ?>securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" title="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_comment');" />
					</div>
					<div class="am-form-icon am-form-feedback">
						<input type="hidden" id="ck_authnum" value="" />
						<input type="text" style="width:65px;border: 1px solid #ccc;height:20px;padding-left: 0.5em !important;padding-right:0em !important;" class="am-form-field" name="data[Comment][authnum]" id="authnums_Comment" /><span style="line-height:0;right: 0;"></span>
					</div>
					<div class="nane" style="float:right;width:42px;height:23px;padding:8px 0 0 0px;"><?php echo $ld['verify_code'] ?> </div>
					<?php } ?>
					
					<div class="am-cf" style="float:right;margin-right:20px;line-height:26px;">
						<input type="radio" style="" name="data[Comment][is_public]" value="0" checked /><span style="margin-right:5px;"><?php echo $ld['public'];?></span>
    						<input type="radio" style="" name="data[Comment][is_public]" value="1" /><span><?php echo $ld['anonymity'];?></span>
					</div>

				  </div>
				 
				  <div class="ds-toolbar-buttons">
				  </div>
				</div>
			  </div>
			   <button id="res_btn" style="margin-top:10px;" class="ds-post-button am-fr am-btn am-btn-secondary am-btn-sm" type="button" onclick="add_comment()"><?php echo $ld['comment'] ?></button>
			</div>
			
			</form>
			<?php }}else{?>
			  <?php if(empty($_SESSION['User']['User']['id'])){echo $ld['please_login']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['login']."</a> ".$ld['perhaps']." <a href='javascript:void(0)' onclick='ajax_login_show();'>".$ld['register']."</a>";}else{?>
				<p><?php //echo $ld['please_buy_before_comment'];?></p>
			  <?php }?>
			<?php }?>
		</div>
		
		<div class="am-comments-list am-comments-list-flip" id="product_comments">
            
            <div class="am-list-news-bd">
                <ul id="maodian" class="am-comments-list am-comments-list-flip events-list">
	                <?php foreach($comment_infos as $k){?>
						<li class="am-comment" style="margin-bottom: 50px;">
							<a style="cursor: default;" href="javascript:void(0);">
							  <img id="photo" title="<?php echo $k['User']['name'] ?>" src="<?php echo $k['User']['img01'] ?>" class="am-comment-avatar" width="48" height="48"/>
							</a>
							<div class="am-comment-main">
						      <header class="am-comment-hd">
						    	<div class="am-comment-meta">
						          <a style="cursor: default;" href="javascript:void(0);" class="am-comment-author"><?php echo $k['User']['name'] ?></a>
						          评论于 <time><?php echo $k['Comment']['created'] ?></time>
						    	</div>
						      </header>
						      <div class="am-comment-bd">
								<?php echo $k['Comment']['content'] ?>
							  </div>
						  	</div>
						</li>
					<?php }?>
                	
                </ul>
            </div>
            <div class="pull-action pull-up am-hide am-text-center am-btn-block am-btn-default" style="cursor: pointer;">More...</div>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>

<script type="text/javascript">
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
	// if(curlen >= len) {
	// 	$("#"+checklen).html(Math.floor((curlen-len)/2)).css('color', '#000000');
	// 	if(img_size1){
	// 		$("#res_btn").bind("click",add_comment);
	// 	}else{
	// 		$("#res_btn").unbind("click",add_comment);
	// 	}
	// } else {
	// 	$("#"+checklen).html(Math.ceil((len-curlen)/2)).css('color', '#FF0000');
	// 	$("#res_btn").unbind("click",add_comment);
	// }
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
function add_comment(){
	if($("#contenttext").val()==""){
		seevia_alert("<?php echo $ld['comment_content_empty']?>");
		return false;
	}else{
		//判断是否含有屏蔽的词
		var word=$("#word").val();
		var con=CheckKeyword(word,$("#contenttext").val());
		con=replace_content(con);
		$("#hid").val(con);
		if(img_size1){
			var authnum_msg="Error";
			//var authnum_msg_div=$("#authnums_Comment").parent().parent().parent().find(".authnum_msg");
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
			if(authnum_msg==""){
				
				ajax_add_activity_comment();
			}
			
		}
	}
}
function ajax_add_activity_comment(){
	$.ajax({ 
		url: web_base+"/activities/ajax_add_activity_comment",
		data:$('#comment_form').serialize(),
		dataType:"json",
		type:"POST",
		success: function(data){
			seevia_alert('评论成功')
			if(data.code=='1'){
	            activity_comment();
	            change_captcha('authnum_comment');
	        }
	    }
	});
}
//评论验证码
change_captcha('authnum_comment',true);
$("#authnums_Comment").blur(function(){
	var authnum_msg="Error";
	//var authnum_msg_div=$("#authnums_Comment").parent().parent().parent().find(".authnum_msg");
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
</script>
<?php }?>