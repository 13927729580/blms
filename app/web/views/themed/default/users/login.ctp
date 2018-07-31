<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<!--
	<script src='http://rescdn.qqmail.com/node/ww/wwopenmng/js/sso/wwLogin-1.0.0.js'></script>
-->
<style type="text/css">
	.space{padding-top:10px}
	.change-right{float:left}
	.change-left{float:right;padding-right:30px}
	.font-color{color:black}
	.del-padding{padding-left:0px}
	.width{width:85%}
	.padding{padding-left:25px}
	.pad-left{margin-left:50px}
	#login_form .am-checkbox, #login_form .am-checkbox-inline {padding-top:0px}
	#qywechat_login .am-tab-panel{text-align:center;}
	#wechat-login{max-width: 420px;height: 420px;}
	#wechat-login #qrimg iframe{margin-top: 10px;}
	#login_tab_2 span{display: inline-block;padding:35px;border:1px solid #ccc;margin-right: 5px;}
	#login_tab_2 span:hover{border:1px solid #3bb4f2;}
	#login_tab_2 .other-login-link{background-repeat: no-repeat;background-size: 100%;width: 50px;height: 50px;margin-right: 0;}
</style>
<div class="am-container am-login">
	<h1 class="am-text-center" style="padding:30px 0 18px 0;"><b><?php echo $ld['member_login'] ?></b></h1>
	<div class="am-g" id="user_wrapper">
		<div class="am-u-lg-3 am-u-md-2 am-u-sm-1">&nbsp;</div>  
		<div id="login_tab_1" class="am-u-sm-10 am-u-md-8 am-u-lg-6" style="margin-left: 10px;">
			<?php echo $form->create('/users',array('action'=>'login','id'=>'login_form','class'=>'am-form am-form-horizontal','name'=>'user_login','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
			<div class="am-form-detail">	
				<div class="am-form-group" style="<?php echo $messege_error!=''?'margin-bottom:0;':''; ?>;margin-bottom:20px;">
		          		<div><input type="text" name="user_name" id="user_names" placeholder="<?php echo $ld['email'].'/'.$ld['user_id'].'/'.$ld['mobile']; ?>" /><i class="am-icon-user" id="i_user" style="top: 10px;"></i><input type="hidden" id="login_type" name="login_type" value="user_sn" /></div>
		        	</div>
		    		<div class="am-form-group" style="position:relative;margin-bottom:20px;">
		          		<div><input type="password" name="password"  id="password" placeholder="<?php echo $ld['password']; ?>" chkRules="nnull:<?php echo $ld['login_password_empty']?>;min4:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" /><i class="am-icon-unlock-alt" id="i_pass"></i></div>
		        	</div>
				<?php if(isset($configs['use_captcha'])&&$configs['use_captcha']=='1'){ ?>
				<div class="am-form-group" style="margin-bottom:0px;">
		          <!-- 验证码前面的字  <label style="width:30%;padding-left:15%;padding-top:4px;"><?php echo $ld['please_enter_the_code'] ?></label>  -->
					<div class="width">
						<input type="hidden" id="ck_authnum" value="" />
						<input type="text" class="am-form-field width" placeholder="验证码" name="data[Users][authnum]" id="authnums" chkRules="authnum:验证码错误" style="width:65%;margin-right:5%;" />
						<span style="position: absolute;right: 40%;margin-top: 5px;"></span>
					</div>  
				  	<div style="padding-top:1%;">
						<img id='authnum_login_img' align='absmiddle' src="<?php echo $webroot;?>securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_login_img');" /><a href="javascript:change_captcha('authnum_login_img');" class="am-icon-refresh" style="margin-left:3px;"></a>
				  	</div>
			    	</div>
		        	<div class="am-form-group" style="margin-bottom:0;display:none;">
			        	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0px;">&nbsp;</label>
			        	<div class="am-u-sm-9 authnum_msg" style="padding-left:6%;">&nbsp;</div>
		        	</div>
				<?php } ?>&nbsp;
				<?php if($messege_error!=""){ ?>
				<div style="margin-top: -10px;">
					<label style="padding-top: 0;" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label messege_error">&nbsp;</label>
					<div style="padding-top: 0;" class="am-u-sm-9 space"><font color='red'><?php echo $messege_error; ?></font></div>
				</div>
		        	<?php } ?>
		        	<div class="am-form-group" style="height:21px;margin-bottom:20px;">
		             	<div class="am-u-sm-9 am-form-label change-right del-padding" id="log_auto" style="padding-top:0;padding-bottom:0;">
						<label class="am-checkbox">
						<input type="checkbox" name="status" value="1" data-am-ucheck >
						自动登录
						</label>
					 </div>
				  </div>
		        </div>
		    	  <div class="am-g">
				<div class="am-u-sm-10 am-u-md-8 am-u-lg-6" id="h_log">
					<input class="am-btn am-btn-primary am-btn-sm am-fl pad-left" name="login" type="submit" style="width:100%" value="<?php echo $ld['login'] ?>" />
				</div>
		        </div>
		        <?php if(isset($syns)&&sizeof($syns)>0||isset($wechat_loginobj['appid'])){ ?>
		        <hr>
		        <div class="am-g">
				<div class="am-u-sm-10 am-u-md-8 am-u-lg-6" style="width: 96%;min-width: 100px;margin: 0 auto;padding-left: 6%;padding-right: 5%;">
					<div class="am-btn am-btn-success am-btn-sm" style="width: 100%;" onclick="login_tab_2()">第三方登录</div>
				</div>
		        </div>
		        <?php } ?>
		        <div id="login_pass" style="margin-top:16px;">
				<a id="sun" class="change-right font-color padding" onclick="ajax_register_show()" href="javascript:void(0);"><h3><?php echo '免费注册' ?></h3></a>
				<label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>  
				<a id="sun"  class="font-color add-space change-left" onclick="window.location.href='<?php echo $html->url('/users/forget_password'); ?>';" href="javascript:void(0);"><h3><?php echo '找回密码' ?></h3></a>
		        </div>
		</div>
		<?php echo $form->end();?>
		<div id="login_tab_2" class="am-u-sm-10 am-u-md-8 am-u-lg-6" style="display: none;">
			<div class="am-other-login" style="padding-left:0px;margin-top:0;margin-bottom:10px;text-align: center;cursor: pointer;">
				<?php if(isset($syns)&&sizeof($syns)>0){foreach($syns as $k=>$v){//pr($v); ?><span><a class="other-login-link <?php echo strtolower($v['UserApp']['type']); ?>" app_key="<?php echo $v['UserApp']['app_key']; ?>" app_id="<?php echo $v['UserApp']['app_id']; ?>" href="<?php echo $html->url('/synchros/opauth/'.strtolower($v['UserApp']['type'])); ?>"></a><div class='am-cf'></div></span><?php }} ?><span><a class="other-login-link wechat" href="javascript:void(0);"></a><div class='am-cf'></div></span>
				<div class='am-cf'></div>
			</div>
			<div class="am-u-sm-10 am-u-md-8 am-u-lg-6" style="width: 96%;min-width: 100px;margin: 0 auto;margin-top:10px;">
			 	<div class="am-text-center" onclick="login_tab_1()" style="width: 100%;cursor: pointer;color: #03a9f4;font-size: 15px;">账号登录</div>
			</div>
		</div>
		<div class="am-u-lg-3 am-u-md-2 am-u-sm-1">&nbsp;</div>
	</div>
	<hr />
</div>

<!-- wechat登录弹窗 start -->
<div class="am-popup" id="wechat-login">
  <div class="am-popup-inner api_wechat" id="qrimg">
    
  </div>
</div>
<!-- wechat登录弹窗 end -->

<!-- wechat登录弹窗 start -->
<div class="am-popup" id="qywechat_login">
  <div class="am-popup-inner">
		<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
			<ul class="am-tabs-nav am-nav am-nav-tabs">
				<li class="am-active"><a href="javascript: void(0)"><?php echo $ld['default']; ?></a></li>
				<li><a href="javascript: void(0)"><?php echo $ld['other'] ?></a></li>
			</ul>
			<div class="am-tabs-bd">
				<div class="am-tab-panel am-active" id='system_qywechatimg'></div>
				<div class="am-tab-panel">
					<div class='am-form'>
						<div class='am-form-group'>
							<div class='am-u-lg-8'>
								<input type='text' value='' placeholder="组织" />
							</div>
							<div class='am-u-lg-4'>
								<button class='am-btn am-btn-sm am-btn-success' onclick="organization_qywechat_loading(this)">加载</button>
							</div>
							<div class='am-cf'></div>
						</div>
					</div>
					<div id='organization_qywechatimg'></div>
				</div>
			</div>
		</div>
  </div>
</div>
<!-- wechat登录弹窗 end -->

<style type="text/css">
ol, ul, li, p, h2, h3, h4, h5, h6, dl, dt, dd, form, input, fieldset, select, textarea, object, embed{margin:0;padding:0;}
li{list-style:none;}
</style>
<script type="text/javascript">
function fgsb(){
	$("#newemail .error").html("&nbsp;");
	var aa=$("#nemail").val();
	if(aa!=""){
	//做提交步骤
		$.ajax({ 
			// url: "<?php echo $html->url('/users/forget_password'); ?>",
			url:web_base+"/users/forget_password",
			type:"POST",
			data:{'email':aa,'is_ajax':'1'},
			success: function(data){
				var result=JSON.parse(data);
				if(result.code==0){
					$("#newemail .error").html(result.forget_error);
				}else{
					$("#newemail").hide();
					$("#forget_error").html(result.result);
					$("#forget_error").show();
				}
		}});
	}
}
//点击忘记密码出来的效果
$(".forget_pwd").click(function(){
	$("#login_title").hide();
	$("#olddenglu").hide();
	$("#forget_error").hide();
	$("#forgetpas").show();
	$("#newemail").show();
	$("#newemail .error").html("&nbsp;");
});
$(".close").click(function(){
	$("#forgetpas").hide();
	$("#newemail").hide();
	$("#login_title").show();
	$("#olddenglu").show();
});

/*
	微信登录
*/
$(".wechat .alipay_go").on("click",function(){
	/*
		判断当前开启的弹窗，记录class名称
	*/
	if($(".dialog_denglu").css("display")=="block"){
		this_dialog_show=".denglu";
		$(".dialog_denglu .close").click();
	}else if($(".dialog_zhuce").css("display")=="block"){
		this_dialog_show=".zhuce";
		$(".dialog_zhuce .close").click();
	}
	$(".dialog_wechat .show_wecaht").click();
});
var login_userid="user_names";
change_captcha('authnum_login_img',true);
$(function(){
	auto_check_form("login_form",false);
	$("#"+login_userid).blur(function(){
		var email_reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		var mobile_reg=/^1[3-9]\d{9}$/;
		var login_type="";
		if($(this).val()!=""){
			if(email_reg.test($(this).val())){
				login_type="email";
			}else if(mobile_reg.test($(this).val())){
				login_type="mobile";
			}else{
				login_type="user_sn";
			}
	    }
	    $(this).parent().find('#login_type').val(login_type);
	});
	
	<?php if(isset($wechat_loginobj)&&!empty($wechat_loginobj)){ ?>
		<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false&& strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false){ ?>
			$(".other-login-link.wechat").parent().css("display","");
			$(".other-login-link.wechat").click(function(){
				window.location.href="<?php echo $html->url('/synchros/opauth/wechat'); ?>";
			});
		<?php }else{ ?>
			cresateqrimg();
	<?php }} ?>
})

/*
	微信登录
*/
function cresateqrimg(){
	var obj = new WxLogin({
      id:"qrimg", 
      appid: "<?php echo isset($wechat_loginobj['appid'])?$wechat_loginobj['appid']:''; ?>", 
      scope: "snsapi_login,snsapi_userinfo", 
      redirect_uri: "<?php echo isset($wechat_loginobj['redirect_uri'])?$wechat_loginobj['redirect_uri']:''; ?>",
      state: "<?php echo isset($wechat_loginobj['state'])?$wechat_loginobj['state']:''; ?>"
    });
	$(".am-other-login .wechat").parent().css("display","");
	//绑定弹窗显示
	$(".am-other-login .wechat").on("click",function(){
		$('#wechat-login').modal("toggle");
	});
}

$("#login_form").blur(function(){
   	var authnum_msg="Error";
	var authnum_val=$(this).val();
	var ck_auth_num=$(this).parent().parent().find("input[id=ck_authnum]").length;
	if(authnum_val.length==0){
		$(this).parent().removeClass("am-form-success");
		$(this).parent().removeClass("am-form-error");
		$(this).parent().addClass("am-form-warning");
		$(this).parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$(this).parent().find("span").addClass("am-icon-warning").css("display","block");
		ajax_register_authnum_status=false;
	}else if(ck_auth_num>0){
		var ck_auth=$(this).parent().parent().find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
	    			$(this).parent().removeClass("am-form-success");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-error");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
	    			$(this).parent().find("span").addClass("am-icon-times").css("display","block");
	    			ajax_register_authnum_status=false;
			}else{
	    			$(this).parent().removeClass("am-form-error");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-success");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
	    			$(this).parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
				ajax_register_authnum_status=true;
			}
		}
	}
});

qy_wechat_load();

function qy_wechat_load(){
	$.ajax({ 
		url:web_base+"/synchros/ajax_qywechat_config",
		type:"POST",
		data:{},
		dataType:'json',
		success: function(result){
			if(result.code=='1'){
				var data=result.data;
				var brower_user_agent=data.user_agent;
				var is_wechat_brower=brower_user_agent.indexOf('MicroMessenger')>=0?true:false;
				var other_login_obj=$("#user_wrapper .wechat").parent();
				if(typeof(data.Config)!='undefined'){
					var system_config=data.Config;
					var redirect_uri=encodeURIComponent("http://"+document.domain+"/synchros/qywechatcallback");
					var request_link="https://open.work.weixin.qq.com/wwopen/sso/3rd_qrConnect?appid="+system_config['CorpID']+"&redirect_uri="+redirect_uri+"&state=SEEVIA&usertype=member";
					other_login_obj.after("<span><a href='"+request_link+"' title='会员登录' class='other-login-link qywechat'></a></span>");
					//request_link="https://open.weixin.qq.com/connect/oauth2/authorize?appid="+system_config['CorpID']+"&redirect_uri="+redirect_uri+"&response_type=code&agentid=1000041&scope=snsapi_privateinfo&state=SEEVIA#wechat_redirect";
					//other_login_obj.after("<span><a href='"+request_link+"' title='会员授权' class='other-login-link qywechat'></a></span>");
				}
			}
		}
	});
}

function login_tab_2(){
	$('#login_tab_1').css('display','none');
	$('#login_tab_2').css('display','');
}
function login_tab_1(){
	$('#login_tab_1').css('display','');
	$('#login_tab_2').css('display','none');
}
</script>