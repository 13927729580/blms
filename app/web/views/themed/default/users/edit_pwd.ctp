<style>.am-form-horizontal .am-form-label{padding-right:0px;}</style>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/md5.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<!-- <div class="am-cf am-user">
	<h3>修改密码</h3>
</div> -->
<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
	<span style="float:left;"><?php echo '修改密码' ?></span>
	<div class="am-cf"></div>
</div>
<div class="am-u-ser-edit-pwd">
	<?php echo $form->create('Users',array('action'=>'edit_pwd','name'=>"user_edit_pwd","id"=>"edit_pwd_form",'class'=>"am-form am-form-horizontal","type"=>"post",'enctype'=>'multipart/form-data','onsubmit'=>'return(check_form(this));'));?>
	<input type="hidden"  name="data[User][id]"  value="<?php echo  $user_list['User']['id'];?>"/>
	<input type="hidden" id="old_pwd" name="data[User][password2]"  value="<?php echo $user_list['User']['password'];?>"/>
	<?php if(trim($user_list['User']['password'])!=""){ ?>
	  <div class="am-form-group am-g">
	    <label  class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label "><?php echo $ld['current_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input style="border-radius:3px;" type="password"  name="data[User][password1]" id="password1"  chkRules="nnull:<?php echo $ld['original_password_not_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>;edit_pwd:<?php echo $ld['password_error'] ?>" />
			<em style="margin-top: -4px;"><font color="red">*</font><font></font></em>
	    </div>
	  </div>
	<?php } ?>
	  <div class="am-form-group am-g">
	    <label  class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><font color="red"></font><?php echo $ld['new_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input style="border-radius:3px;margin-left:0;padding-left: 8px;" type="password"  name="data[User][password]" id="password"  chkRules="nnull:<?php echo $ld['new_password_not_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" />
			<em style="margin-top: -3px;"><font color="red">*</font><font></font></em>
	    </div>
	  </div>
	  
	  <div class="am-form-group am-g">
	    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><font color="red"></font><?php echo $ld['confirm_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input style="border-radius:3px;" type="password"  name="data[User][confirm_password]" id="confirm_password"  chkRules="nnull:<?php echo $ld['confirm_password_can_not_be_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;cpwd:<?php echo $ld['the_two_passwords_do_not_match']?>:password" />
			<em style="margin-top: -3px;"><font color="red">*</font><font></font></em>
	    </div>
	  </div>
	  
	  
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <button type="submit" class="am-btn am-btn-primary am-btn-secondary" style="width: 118px;height: 35px;"><?php echo $ld['user_save'] ?></button>
	    </div>
	  </div>
	<?php echo $form->end();?>
</div>

<script type="text/javascript">
  $(document).ready(function(){
	auto_check_form("edit_pwd_form",false);
	var windowHeight = $(window).height();
	$("#edit_pwd_form").parent().css("min-height",(windowHeight*0.7)+"px");
});
</script>
