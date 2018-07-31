<style>
.am-u-lg-4.am-u-md-3.am-u-sm-4.am-form-label{padding-left: 0;}
.am-u-lg-7.am-u-md-8.am-u-sm-8{padding:0;}
</style>
<div class="am-g am-container">
	<div class="am-u-lg-3 am-u-md-2 am-u-sm-1">&nbsp;</div>
	<div class="am-user-forget-password am-u-sm-12 am-u-md-8 am-u-lg-5">
	<?php echo $form->create('/users',array('action'=>'forget_password','class'=>'am-form am-form-horizontal','id'=>'forget_form','name'=>'forget','type'=>'POST'));?>
		<div class="am-form-detail">
			<div class="am-form-group center" style="padding: 0;">
	         		 <!-- <label style="width: 100%;text-align: center;"><?php echo $ld['forget_password']; ?></label> -->
	         	<h1 class="am-text-center" style="padding:0 0 18px 0;margin-bottom: 0;"><b><?php echo $ld['forget_password']; ?></b></h1>
			</div>
			<div class="am-form-group">
          		<!-- <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['user_id'] ?></label> -->
          		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
    				<input style="margin-left: 14%;width: 72%;" type="text" id="user_sn" placeholder="<?php echo $ld['mobile'].'/'.$ld['email']; ?>" placeholder="<?php echo $ld['user_id'] ?>"/>
    				<!-- <em><font color="red">*</font></em> -->
    			</div>
        	</div>
			<div class="am-form-group">
				<!-- <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['verify_code'] ?></label> -->
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
					<input type="text" id="verify_code" value="" style="margin-left: 14%;width: 72%;" placeholder="<?php echo $ld['verify_code'] ?>"/>
					<!-- <em><font color="red">*</font></em> -->
					<input style="position: absolute;top: 3px;right: 16%;height: 31px;width: 67px;" class="am-btn am-btn-primary am-btn-sm" type="button" value="<?php echo $ld['send'] ?>" onclick="verify_code_send()"/>
				</div>
				<div class="am-u-sm-4 am-text-left">
					
				</div>
			</div>
			<div class="am-form-group">
	          		<!-- <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['new_password'] ?></label> -->
	          		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	    				<input style="margin-left: 14%;width: 72%;" type="password" id="user_password" placeholder="<?php echo $ld['new_password'] ?>"/>
	    				<!-- <em><font color="red">*</font></em> -->
	    			</div>
	        	</div>
			<div class="am-form-group">
	          		<!-- <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['confirm_password'] ?></label> -->
	          		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	    				<input style="margin-left: 14%;width: 72%;" type="password"  id="confirm_password" placeholder="<?php echo $ld['confirm_password'] ?>" placeholder="<?php echo $ld['confirm_password'] ?>"/>
	    				<!-- <em><font color="red">*</font></em> -->
				</div>
	        	</div>
	    		<!-- <div class="am-form-group">
          			<label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
			     	<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin:0 auto;width: 80%;" id="sure_groud">
						<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['submit']; ?>" onclick="ajax_forget_password()" style="margin-right:10px;" />
					</div>
		    	</div> -->
		    	<div class="am-form-group" style="margin-top: 40px;">
	          		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	    				<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['submit']; ?>" onclick="ajax_forget_password()" style="margin-left: 14%;width: 72%;" />
	    			</div>
	        	</div>
	        </div>
		</div>
	<?php echo $form->end();?>
	</div>
	<div class="am-u-lg-3 am-u-md-2 am-u-sm-1">&nbsp;</div>
</div>
<style type='text/css'>
.am-form-group>.am-input-group input[type='text']{position: relative;z-index: 2;float: left;width: 100%;margin-bottom:0;border-top-left-radius:7px;border-bottom-left-radius:7px;}
.am-form-group .am-input-group-btn{border:1px solid #ccc;border-left-width:0px;border-top-right-radius:7px;border-bottom-right-radius:7px;padding-right:3px;}
.am-form-group .am-input-group-btn>button{border-radius:7px;padding:5px;font-size:1rem;height:auto;}
.am-form-group .am-input-group-btn:last-child>.am-btn{border-top-left-radius:7px;border-bottom-left-radius:7px;}
.center{padding-left:40%}
.am-user-forget-password .am-form-group input{margin-left:0px;min-width:auto;}
.am-user-forget-password .am-form-group input[type='password']{float: left;width: 70%;}
#forget_form .am-form-detail .am-g .am-u-lg-10.am-u-md-9.am-u-sm-8 .am-form-group>.am-input-group input[type='text'] {
    border-radius: 3px;
}
#forget_form .am-form-detail .am-g .am-u-lg-10.am-u-md-9.am-u-sm-8 .am-form-group .am-input-group-btn {
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
}
#forget_form .am-form-detail .am-g .am-u-lg-10.am-u-md-9.am-u-sm-8 .am-form-group .am-input-group-btn:last-child>.am-btn {
    border-bottom-left-radius: 3px;
    border-top-left-radius: 3px;
}
#sure_groud{
	width: 131px;
}

</style>
<script type="text/javascript">
function verify_code_send(){
	var user_sn=$("#forget_form #user_sn").val().trim();
	if(user_sn!=""){
		var mail_reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
		var mobile_reg=/^1[3-9]\d{9}$/;
		if(mail_reg.test(user_sn)){
			ajax_forget_password_mail("#forget_form #user_sn");
		}else if(mobile_reg.test(user_sn)){
			mobile_code_send("#forget_form #user_sn");
		}else{
			seevia_alert(j_format_is_incorrect);
		}
	}
}

function ajax_forget_password_mail(input_str){
	var email=$(input_str).val();
	$.ajax({
		url: web_base+"/users/ajax_forget_password_mail",
		type:"POST",
		dataType:"json", 
		data: {'email':email},
		success: function(data){
			if(data.code=='1'){
				seevia_alert(send_success);
			}else if(data.message!=""){
				seevia_alert(data.message);
			}else{
				seevia_alert(send_failed);
			}
		}
	});
}

function ajax_forget_password(){
	var user_sn_type="";
	var user_sn=$("#forget_form #user_sn").val().trim();
	var verify_code=$("#forget_form #verify_code").val().trim();
	var password=$("#forget_form #user_password").val().trim();
	var confirm_password=$("#forget_form #confirm_password").val().trim();
	if(user_sn==""){
		return false;
	}else{
		var mail_reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
		var mobile_reg=/^1[3-9]\d{9}$/;
		if(mail_reg.test(user_sn)){
			user_sn_type="email";
		}else if(mobile_reg.test(user_sn)){
			user_sn_type="mobile";
		}else{
			seevia_alert(j_format_is_incorrect);
			return false;
		}
	}
	if(verify_code==''){
		seevia_alert("<?php echo $ld['please_enter_the_code']; ?>");
		return false;
	}
	if(password==""){
		seevia_alert("<?php echo $ld['new_password_not_empty']; ?>");
		return false;
	}
	if(password!=confirm_password){
		seevia_alert(js_password_different);
		return false;
	}
	if(user_sn_type=="email"){
		var post_data={'email':user_sn,'verify_code':verify_code,'password':password,'confirm_password':confirm_password};
	}else if(user_sn_type=='mobile'){
		var post_data={'mobile':user_sn,'verify_code':verify_code,'password':password,'confirm_password':confirm_password};
	}else{
		return false;
	}
	$.ajax({
		url: web_base+"/users/forget_password",
		type:"POST",
		dataType:"json", 
		data: post_data,
		success: function(data){
			seevia_alert(data.message);
			if(data.code=='1'){
				window.location.href=web_base+"/users/login";
			}
		}
	});
	
}
</script>