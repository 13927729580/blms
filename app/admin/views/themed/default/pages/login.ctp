<script src="<?php echo $webroot.'plugins/md5.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-login ">
	<h2 style="color:#5eb95e;text-align:center;"><?php 
			if($configs['admin_logo'] ==""){
				$configs['admin_logo']="/admin/skins/default/img/logo.png";
			}
			if(isset($configs['admin_logo'])&&trim($configs['admin_logo'])!=''){ ?>
			<?php	echo $html->image($configs['admin_logo'],array('style'=>'max-height:50px;'));?>
		<?php } ?>
			<?php 
		 if(!empty($configs['admin_detail'])){echo $configs['admin_detail'];}else{echo $configs['shop_name']."&nbsp;-&nbsp;".$ld['ecommerce_plaform'];}
	?></h2>
		
		<hr>
	<center>
	<div  style="width:300px;">
	<?php echo $form->create('/users',array('action'=>'login','id'=>'login_form','class'=>'am-form am-form-horizontal ','name'=>'user_login','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
    <?php if(isset($backend_locales) && sizeof($backend_locales)>1){?>
    <div class="am-form-detail">
		<div class="am-form-group" style="<?php echo isset($login_error)&&$login_error!=''?'margin-bottom:0;':''; ?>">
    	<!--用户名输入框-->
    		 <div class="am-input-group am-input-group-success">
			  <span class="am-input-group-label"><i class="am-icon-buysellads"></i></span>
			  <select  name="locale" id="locale" onchange="change_locale(this)" style="height:38px;">
                <?php foreach($backend_locales as $v){ ?><option value="<?php echo $v['Language']['locale']?>" <?php if(isset($backend_locale)&&$v['Language']['locale']==$backend_locale){echo 'selected';}?>><span class="lang"><?php echo $v['Language']['name']?></span></option><?php } ?>
              </select>
			</div>
        </div>
	</div>
    <?php }else{ ?><input type="hidden" name="locale" id="locale" value="<?php echo $backend_locales[0]['Language']['locale']?>"/>
	<?php }?>
	<div class="am-form-detail">
		<div class="am-form-group" style="<?php echo isset($login_error)&&$login_error!=''?'margin-bottom:0;':''; ?>">
    	<!--用户名输入框-->
    		 <div class="am-input-group am-input-group-success">
			  <span class="am-input-group-label"><i class="am-icon-user"></i></span>
			  <input type="text" id="operator_id" class="am-form-field" placeholder="<?php echo $ld['login_id'] ?>">
			</div>
        </div>
	</div>
	
	<div class="am-form-detail">
		<div class="am-form-group">
        <!--密码输入框-->
        	<div class="am-input-group ">
			  <span class="am-input-group-label"><i class="am-icon-lock"></i></span>
			  <input type="password" class="am-form-field" id="operator_pwd" placeholder="<?php echo $ld['login_password'] ?>">
			</div>
       </div>
	</div>
	
	<div class="am-form-detail captcha" id="vcode" style="<?php echo isset($count_login)&&$count_login>=2?'':'display:none;';?>">
		<div class="am-form-group am-input-group">
		 <span class="am-input-group-label"><i class="am-icon-warning"></i></span>
	          	<input type="text" class="am-form-field" chkRules="authnum:验证码错误" maxlength="4" id="authnum" name="captcha_num" /><input type="hidden" value="" id="ck_login_authnum" />
	    <span class="am-input-group-btn"><img id="authnum_img" onclick="change_captcha()" align='absmiddle' src="<?php echo $admin_webroot;?>authnums/get_authnums/?1234" /></span>
		</div>
			<div class="authnum_msg">&nbsp;</div>
	</div>
	
	<div class="am-form-detail">
		<div class="am-form-group" style="margin:10 0 10 0;text-align:left">
<label class="rememberme am-checkbox am-success" >
  <input type="checkbox" id="cookie_session"  checked="checked" name="cookie_session" value="1" data-am-ucheck><?php echo $ld['login_auto']?></label>
        </div>
	</div>
	<div class="am-form-detail">
		<div class="am-form-group">
     		<button type="button" id="login_check_id" data-am-loading="{spinner: 'circle-o-notch',loadingText: '<?php echo $ld['loading'] ?>'}" class="am-btn am-btn-success am-radius am-btn-block" onclick="ajax_login_check()"><?php echo $ld['login_btn']?></button>
        </div>
	</div>
	<?php echo $form->end();?>
		</div>
	</center>

</div>


<script type="text/javascript">
var locale = document.getElementById("locale").value;
$(function(){
	if($("#authnum_img").attr("src",admin_webroot+"securimages/index/?1234")){
		$("#authnum_img").trigger("click");
	}
	var not_enable_vcode=$("#vcode").is(":hidden");
	if(!not_enable_vcode){
		get_captcha_number();
	}
	document.getElementById("operator_id").focus();
	document.onkeydown = function(evt){
		var evt = window.event?window.event:evt;
		if(evt.keyCode==13){
			var UserName = document.getElementById('operator_id').value;
			var UserPassword = document.getElementById('operator_pwd').value;
			if(document.getElementById('vcode').style.display=="block"){
				var UserCaptcha = document.getElementById('authnum').value;
				if(UserName != "" && UserPassword != "" && UserCaptcha != ""){
					ajax_login_check();
				}
			}else{
				if(UserName != "" && UserPassword != ""){
					ajax_login_check();
				}
			}
		}
	}
	ajax_operator_channel();
})
    

//聚焦，获取验证码
function show_login_captcha(){
	if(document.getElementById("authnum")){
		document.getElementById('vcode').style.display=="block";
		document.getElementById("authnum").value = "";
		change_captcha();
	}
}


//点击，获取验证码
function change_captcha(){
	if(document.getElementById("authnum")){
		document.getElementById("authnum_img").src = admin_webroot+"authnums/get_authnums/?"+Math.random();
		setTimeout("get_captcha_number();",2000);
	}
}

//获取验证码值
function get_captcha_number(){
	$.ajax({ url:admin_webroot+"authnums/get_authnumber/?"+Math.random(),
			type:"POST",
			dataType:"html",
			data: {},
			success: function(data){
				if(document.getElementById("ck_login_authnum")){
					document.getElementById("ck_login_authnum").value=data;
				}
			}
		});
}

function change_locale(obj){
	window.location.href=admin_webroot+"?backend_locale="+obj.value;
}

</script>