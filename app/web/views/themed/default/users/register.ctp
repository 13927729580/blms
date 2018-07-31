<div class="am-container am-register">
<?php if(isset($configs['enable_registration_closed']) && $configs['enable_registration_closed']==1){echo "<div class='box register_pause'>$ld[register_pause]</div>";}else{?>
<?php echo $form->create('/users',array('action'=>'register','id'=>'register_form','class'=>'am-form am-form-horizontal','name'=>'user_register','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
	<div class="am-form-detail">
		<div class="am-form-group">
			<label class="am-u-sm-6 am-form-label">&nbsp;</label>
			<div class="am-u-sm-6"><label>注册</label></div>
		</div>
		<?php if(isset($configs['registration_invitation_code'])&&trim($configs['registration_invitation_code'])!=''){ ?>
		<div class="am-form-group">
			<label class="am-u-sm-3 am-form-label">&nbsp;</label>
			<div class="am-u-sm-9">
				<div class="am-input-group">
					<input type="text" class="am-form-field" name="invitation_code" chkRules="nnull:请输入邀请码" value="" placeholder="请输入邀请码" >
					<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' onclick="register_invitation_code_check(this)">校验</button></span>
				</div>
			</div>
		</div>
	<?php 
		     } if(isset($configs['user_register_mode'])&&$configs['user_register_mode']=='1'){?>
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label"><?php //echo $ld['mobile'] ?></label>
          <div class="am-u-sm-9"><input type="text" name="data[Users][mobile]" id="user_mobile" chkRules="nnull:<?php echo $ld['phone_can_not_be_empty']?>;mobile:<?php echo $ld['phone_incorrectly_completed']?>;ajax:check_input('mobile','user_mobile')" value="<?php echo isset($this->data['Users'])?$this->data['Users']['mobile']:'';?>" /><em><font color="red">*</font><font></font></em></div>
        </div>
		<div class="am-form-group">
			<label class="am-u-sm-3 am-form-label"><?php echo $ld['mobile_codes'] ?></label>
			<div class="am-u-sm-4">
				<input type="text" name="data[Users][mobile_code]" id="mobile_code" value="" chkRules="nnull:<?php echo $ld['please_enter_the_code']?>" /><em><font color="red">*</font><font></font>&nbsp;</em>
			</div>
			<div class="am-u-sm-4 am-text-left">
				<input class="am-btn am-btn-primary am-btn-sm" type="button" value="<?php echo $ld['send'] ?>" onclick="mobile_code_send('#register_form #user_mobile')"  />
			</div>
		</div>
		<?php }else{?>
		<div class="am-form-group">
			<label class="am-u-sm-3 am-form-label">&nbsp;</label>
			<div class="am-u-sm-9"><input type="text" placeholder="请输入邮箱" style="border-radius:7px;margin-left: 0;" name="data[Users][email]" id="user_emails" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php $ld['e-mail_incorrectly']?>;ajax:check_input('sn_email','user_emails')" value="<?php echo isset($this->data['Users'])?$this->data['Users']['email']:'';?>" /><em><font></font></em></div>
		</div>
        	<div class="am-form-group">
			<label class="am-u-sm-3 am-form-label">&nbsp;</label>
			<div class="am-u-sm-9 keywordauthen am-form-icon am-form-feedback" >
				<input type="text" style="border-radius:7px;margin-left: 0;width: 48%;" placeholder="请输入验证码" class="am-form-field" name="data[Users][email_code]" chkRules="nnull:验证码错误" />
				<span class="am-btn am-btn-primary" style="display: inline-block;position: absolute;" onclick="email_code_send('#register_form #user_emails')">获取验证码</span>
			</div>
		</div>
		<?php } ?>
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label">&nbsp;</label>
          <div class="am-u-sm-9"><input type="password" placeholder="请输入密码" name="data[Users][password]" style="border-radius:7px;" id="user_password" chkRules="nnull:<?php echo $ld['login_password_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" /><em><font></font></em></div>
        </div>
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label">&nbsp;</label>
          <div class="am-u-sm-9"><input type="password" style="border-radius:7px;" placeholder="请再次输入密码" name="data[Users][confirm_password]" id="confirm_password" chkRules="nnull:<?php echo $ld['confirm_password_can_not_be_empty']?>;min4:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;cpwd:<?php echo $ld['the_two_passwords_do_not_match']?>:user_password" /><em><font></font></em></div>
        </div>
	<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
		<div class="am-form-group am-margin-bottom-sm">
          <label class="am-u-sm-3 am-form-label">&nbsp;</label>
	      <div class="am-u-sm-3 keywordauthen am-form-icon am-form-feedback">
	        <input type="hidden" id="ck_authnum" value="" />
	        <input type="text" style="border-radius:7px;margin-left:0px;" placeholder="请输入验证码" class="am-form-field" name="data[Users][authnum]" id="authnums" chkRules="authnum:验证码错误" /><span style="right:16px;"></span>
		  </div>
		  <div class="am-u-sm-5 authentication">
			<img id='authnum_img' align='absmiddle' src="<?php echo $webroot;?>securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_img');" />&nbsp;<a href="javascript:change_captcha('authnum_img');" style="color:#42C3EF"><?php echo $ld['not_clear']?></a><em></em>
		  </div>
	    </div>
        <div class="am-form-group  am-margin-bottom-0 am-margin-top02" style="display:none;">
        	<label class="am-u-sm-3 am-form-label" style="padding-top:0px;">&nbsp;</label>
        	<div class="am-u-sm-9 authnum_msg">&nbsp;</div>
        </div>
	<?php } ?>
	<div class="am-form-group am-margin-bottom-0">
		<label class="am-u-sm-3 am-form-label" style="padding-top:0px;">&nbsp;</label>
		<div class="am-u-sm-9 am-padding-left-0">
			<label class='am-checkbox'><input type='checkbox' data-am-ucheck onclick="$('#register_form button[name=login]').attr('disabled',!this.checked);" />我已阅读并同意<a href='javascript:void(0);' data-am-modal="{target: '#user_service', closeViaDimmer: 0}">《会员服务条款》</a></label>
		</div>
	</div>
	<div class="am-form-group am-margin-top3">
		<label class="am-u-sm-3 am-form-label">&nbsp;</label>
		<div class="am-u-sm-9">
			<button type='button' class='am-btn am-btn-secondary am-btn-sm' name='login' disabled='true' style="border-radius:7px;width:70%"><?php echo $ld['register'] ?></button>
		</div>
	</div>
		<!--<div class="am-form-group am-margin-top3">
          <div class="am-u-sm-9"><?php echo $ld['already_a_member']?> <a id="log" onclick="ajax_login_show()"  href="javascript:void(0);"><?php echo $ld['login']?></a></div>
        </div>-->
	</div>
<?php echo $form->end();}?>
	<hr />
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="user_service">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd user_service">&nbsp;</div>
    <div class='am-modal-bd am-text-center user_service_action'>
    		<button type='button' class='am-btn am-btn-xs am-btn-success' onclick="user_service_action(this)">同意</button>&nbsp;&nbsp;<button type='button' class='am-btn am-btn-xs am-btn-danger' onclick="$(this).parents('.am-modal').modal('close');">拒绝</button></div>
  </div>
</div>
<style type='text/css'>
.am-register div.am-input-group{width:70%;}
.am-register div.am-input-group input[type='text']{float:none;width:100%;margin-left:0px;border-top-left-radius:7px;border-bottom-left-radius:7px;}
.am-register div.am-input-group button{border-top-right-radius:7px;border-bottom-right-radius:7px;}
</style>
<script type="text/javascript">
$(function(){
	if($("#register_form input[name='invitation_code']").length>0){
		$("#register_form div.am-form-group:gt(1)").hide();
	}
});

 $(document).ready(function(){
	change_captcha('authnum_img',true);
	auto_check_form("register_form",false);
});
load_user_service();
function load_user_service(){
	$.ajax({
		url:web_base+'/user_service',
		type:"GET",
		dataType:"html",
		success:function(result){
			var result_content=$(result).find('div.static_pages').html();
			$('#user_service .am-modal-bd.user_service').html(result_content);
		}
	});
}

function user_service_action(btn){
	$('#register_form input[type="checkbox"]').uCheck('check');
	$('#register_form button[name=login]').attr('disabled',false);
	$(btn).parents('.am-modal').modal('close');
}

function register_invitation_code_check(btn){
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
					$(register_form).find("div.am-form-group").show();
				}else{
					seevia_alert('邀请码错误');
				}
			}
		});
	}
}
</script>