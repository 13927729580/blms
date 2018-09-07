<div class="am-g">
<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">&nbsp;</div>
<div class="am-u-sm-10 am-u-md-8 am-u-lg-10 am-padding-right-0">
<?php echo $form->create('/users',array('action'=>'register','class'=>'am-form','id'=>'ajax_registerform','name'=>'user_register','type'=>'POST'));?>
	<div class="errors" style="margin-top: 25px;"></div>
	<?php if(isset($configs['registration_invitation_code'])&&trim($configs['registration_invitation_code'])!=''){ ?>
	<div class="am-form-group">
		<div>
			<div class="am-input-group">
				<input type="text" class="am-form-field" name="invitation_code" chkRules="nnull:请输入邀请码" value="" placeholder="请输入邀请码" >
				<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="check_register_invitation_code(this)">校验</button></span>
			</div>
			<em style="float:left;margin-left:0.3rem;"><font color="red" style="font-style:normal;"></font></em>
			<div class="am-cf"></div>
		</div>
	</div>
	<?php 
		     }
		if(isset($configs['user_register_mode'])&&$configs['user_register_mode']=='1'){?>
	<div class="am-form-group">
	  <div><input type="text" name="data[Users][mobile]" id="user_mobile" chkRules="nnull:<?php echo $ld['phone_can_not_be_empty']?>;mobile:<?php echo $ld['phone_incorrectly_completed']?>;ajax:check_input('mobile','user_mobile')" value="<?php echo isset($this->data['Users'])?$this->data['Users']['mobile']:'';?>" placeholder="请输入手机号" style="float:left;" />
	  	<em style="float:left;margin-left:0.3rem;"><font color="red" style="font-style:normal;"></font></em>
	  	<div class="am-cf"></div>
	  </div>
	</div>
	<div class="am-form-group">
		<div class="am-input-group">
	    		<input type="text" name="data[Users][mobile_code]" class="am-form-field" id="mobile_code" value="" chkRules="nnull:<?php echo $ld['please_enter_the_code']?>" placeholder="获取验证码" />
			<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="mobile_code_send('#ajax_registerform #user_mobile')">获取验证码</button></span>
		</div>
	</div>
	<?php }elseif(isset($configs['user_register_mode'])&&$configs['user_register_mode']=='0'){ ?>
	<div class="am-form-group">
		<input class="register_user_names" type="text" placeholder="请输入邮箱" name="data[Users][email]" id="user_emails"  chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php echo $ld['accounts_incorrectly_completed']?>" />
	</div>
	<div class="am-form-group">
		<div class="am-input-group">
			<input type="text" placeholder="请输入验证码" class="am-form-field" name="data[Users][email_code]" chkRules="nnull:验证码错误" />
			<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="email_code_send('#ajax_registerform #user_emails',this)">获取验证码</button></span>
		</div>
	</div>
	<?php }else{?>
			<div class="am-form-group">
				<div><input type="text" name="data[Users][mobile]" id="user_mobile" chkRules="nnull:'';mobile_mail:'手机号或邮箱格式错误';ajax:check_input('mobile','user_mobile')" value="<?php echo isset($this->data['Users'])?$this->data['Users']['mobile']:'';?>" placeholder="请输入手机号或邮箱" style="float:left;" />
					<em style="float:left;margin-left:0.3rem;"><font color="red" style="font-style:normal;"></font></em>
					<div class="am-cf"></div>
				</div>
			</div>
			<div class="am-form-group">
				<div class="am-input-group">
					<input type="text" name="data[Users][mobile_code]" class="am-form-field" id="mobile_code" value="" chkRules="nnull:<?php echo $ld['please_enter_the_code']?>" placeholder="获取验证码" />
					<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="mobile_code_send('#ajax_registerform #user_mobile')">获取验证码</button></span>
				</div>
			</div>
	<?php } ?>
	<div class="am-form-group">
		<input class="am-hide" type="password"  name="md5password"  id="md5pwd" value=""  />
		<input class="input1"  type="password" name="data[Users][password]"  id="register_pwd" placeholder="请输入密码" />
	</div>
	<div class="am-form-group">
		<input class="input1"  type="password" name="data[Users][confirm_password]"  id="confirm_pwd" placeholder="请再次输入密码" />
	</div>
	<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
	<div class="am-form-group">
		<div style="height: 45px;">
			<div class='am-fl am-form-icon am-form-feedback'>
				<input type="hidden" id="ck_authnum" value="" />
				<input type="text" style="width:160px;" class="keywordauthen am-form-field" name="data[Users][authnum]" chkRules="authnum:验证码错误" id="ajax_register_authnums" placeholder="请输入验证码"/>
				<span></span>
			</div>
			<div class="authentication" style="float:left;width:100px;line-height:30px;text-align:right;">
				<img id='authnum_ajax_register' align='absmiddle' src="<?php echo $webroot;?>securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_ajax_register');" style="margin-left:0rem;text-align:right;" />
				<a style="margin:0 0 0 5px;color:#65c5b3;" href="javascript:change_captcha('authnum_ajax_register');"><span class="am-icon-refresh"></span></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="am-form-group">
		<label class='am-checkbox'><input type='checkbox' data-am-ucheck onclick="$('#ajax_registerform .reg_email').attr('disabled',!this.checked);" />我已阅读并同意<a href='javascript:void(0);' onclick='load_modal_user_service(this)'>《会员服务条款》</a></label>
	</div>
	<div class="am-form-group" >
		<button type='button' class='am-btn am-btn-primary am-btn-block reg_email' disabled='true' onclick="ajax_register()" style="width:100%;"><?php echo $ld['register']?></button>
	</div>
	<div class="am-form-group">
		<div class="am-fr">
			<a href="<?php echo $html->url('/users/forget_password'); ?>"><?php echo $ld['forget_password'] ?></a>
		</div>
	</div>
	<div class="mashangzhuce" style="">
		<?php echo $ld['already_a_member']?> <a onclick="ajax_login_show()" href="javascript:void(0);"><?php echo $ld['login']?></a>
	</div>
<?php echo $form->end();?>
</div>
<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">&nbsp;</div>
</div>
<script type="text/javascript">
$(function(){
	$("#ajax_registerform input[type='checkbox']").uCheck();
	if(document.getElementById('authnum_ajax_register')){
		//更新验证码
		change_captcha('authnum_ajax_register');
	}
	auto_check_form("ajax_registerform",false);
	
	if($("#ajax_registerform input[name='invitation_code']").length>0){
		$("#ajax_registerform div.am-form-group:gt(0)").hide();
	}
})

var ajax_register_authnum_status=false;
<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='0'){ ?>
ajax_register_authnum_status=true;
<?php }?>

$("#ajax_registerform .register_user_names").blur(function(){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.\w{2,4}$/; //验证邮箱的正则表达式
	if(!reg.test($(this).val())){
		$("#ajax_registerform .errors").css("height","23px");
		$("#ajax_registerform .errors").html(j_aler_erro_mail);
		$("#ajax_registerform .reg_email").attr("onclick","return false");
		return false;
	}else{
		$("#ajax_registerform .errors").html("&nbsp;");
		$("#ajax_registerform .errors").css("height","0px");
		$("#ajax_registerform .reg_email").attr("onclick","ajax_register()");
		$.ajax({ 
			url:web_base+"/users/check_email",
			type:"POST",
			data:{'email':$(this).val()},
			dataType:"json",
			success: function(data){
				ajax_register_lock=false;
				var email_code_input=$("#ajax_registerform input[name='data[Users][email_code]']");
				var email_code_btn=$(email_code_input).parent().find('button');
				if(data.code=='2'){
					email_code_btn.attr('disabled',false);
				}else{
					$("#ajax_registerform .errors").css("height","23px");
					$("#ajax_registerform .errors").html("<?php echo $ld['email_already_exists']; ?>");
					$("#ajax_registerform .reg_email").attr("onclick","return false");
					if(document.getElementById('authnum_ajax_register')){
						change_captcha('authnum_ajax_register');
						ajax_register_authnum_status=false;
					}
				}
			}
		});
	}
});

$("#ajax_registerform #register_pwd").blur(function(){
	if($(this).val()!="" && $("#ajax_registerform #confirm_pwd").val()!=""){
		if($(this).val()==$("#confirm_pwd").val()){
			$("#ajax_registerform .errors").html("&nbsp;");
			$("#ajax_registerform .errors").css("height","0px");
		}else{
			$("#ajax_registerform .errors").html(js_password_different);
			$("#ajax_registerform .errors").css("height","23px");
		}
	}
});
$("#ajax_registerform #confirm_pwd").blur(function(){
	if($(this).val()!="" && $("#ajax_registerform #register_pwd").val()!=""){
		if($(this).val()==$("#ajax_registerform #register_pwd").val()){
			$("#ajax_registerform .errors").html("&nbsp;");
			$("#ajax_registerform .errors").css("height","0px");
		}else{
			$("#ajax_registerform .errors").html(js_password_different);
			$("#ajax_registerform .errors").css("height","23px");
		}
	}
});

$("#ajax_registerform #ajax_register_authnums").blur(function(){
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

var ajax_register_lock=false;
function ajax_register(){
	if(ajax_register_lock){return false;}
	if($("#ajax_registerform #register_pwd").val()!="" && $("#ajax_registerform .register_user_names").val()!=""){
		if($("#ajax_registerform #register_pwd").val()==$("#ajax_registerform #confirm_pwd").val()){
			if(!ajax_register_authnum_status){
				$("#ajax_registerform .errors").html("验证码错误").css("height","23px");
				return false;
			}
			$(".errors").html("&nbsp;");
			$(".errors").css("height","0px");
			ajax_register_lock=true;
			$.ajax({
					url:web_base+"/users/register",
					type:"POST",
					data:$("#ajax_registerform").serialize()+"&is_ajax=true",
					dataType:"json", 
					context: $("#ajax_registerform .errors"), 
					success: function(data){
						ajax_register_lock=false;
						if(data.error_no==1){
							$("#ajax_registerform .errors").html(data.message);
							$("#ajax_registerform .errors").css("height","23px");
							<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
							change_captcha('authnum_ajax_register');
							ajax_register_authnum_status=false;
							<?php } ?>
						}else{
							window.location.href=web_base+data.back_url;
						}
      				}
      		});
		}else{
			$("#ajax_registerform .errors").html("<?php echo $ld['the_two_passwords_do_not_match']?>");
			$("#ajax_registerform .errors").css("height","23px");
		}
	}else{
		$("#ajax_registerform .errors").html(js_name_pwd_not_empty);
		$("#ajax_registerform .errors").css("height","23px");
	}
}

function load_modal_user_service(link){
	$(link).parents('div.am-modal-bd').hide();
	$(link).parents('.am-modal').on("close.modal.amui",function(){
		$(this).find('div.user_service').remove();
		$(this).find('div.am-modal-bd').show();
		$(this).find('div.user_service_action').remove();
	});
	$.ajax({
		url:web_base+'/user_service',
		type:"GET",
		dataType:"html",
		success:function(result){
			var result_content=$(result).find('div.static_pages').html();
			var result_object=$(link).parents('.am-modal-dialog');
			var result_html="<div class='am-modal-bd user_service'>"+result_content+"</div>";
			result_html+="<div class='am-modal-bd am-text-center user_service_action'><button type='button' class='am-btn am-btn-xs am-btn-success' onclick=\"user_service_modal_action(this)\">同意</button>&nbsp;&nbsp;<button type='button' class='am-btn am-btn-xs am-btn-danger' onclick=\"$(this).parents('.am-modal').modal('close');\">拒绝</button></div>";
			result_object.append(result_html);
		}
	});
}

function user_service_modal_action(btn){
	$(btn).parents('.am-modal-dialog').find('div.user_service').remove();
	$(btn).parents('.am-modal-dialog').find('div.am-modal-bd').show();
	$(btn).parents('.am-modal-dialog').find("form input[type='checkbox']").uCheck('check');
	$(btn).parents('.am-modal-dialog').find('.reg_email').attr('disabled',false);
	$(btn).parent().remove();
}

function check_register_invitation_code(btn){
	var register_form=$(btn).parents('form');
	var invitation_code=$(register_form).find("input[name='invitation_code']").val();
	if(invitation_code!=''){
		$.ajax({
			url:web_base+'/users/ajax_check_invitation_code',
			type:"POST",
			data:{'invitation_code':invitation_code},
			dataType:"JSON",
			success:function(result){
				if(result.code=='1'){
					$(register_form).find(".errors").html('');
					$(register_form).find("div.am-form-group").show();
				}else{
					$(register_form).find(".errors").html('邀请码错误');
				}
			}
		});
	}
}
</script>