<?php
	$google_translate_code="zh-cn";
	if(isset($backend_locales)){
		foreach($backend_locales as $v){
			if($v['Language']['locale']==$backend_locale){
				$google_translate_code=$v['Language']['google_translate_code'];
			}
		}
	}
?>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
	<ul class="am-list admin-sidebar-list" style="z-index: 100" data-am-scrollspy-nav="{offsetTop: 60}"  data-am-sticky="{top:60}">
	 	<li><a href="#notify_information"><?php echo $ld['basic_information'] ?></a></li>
	</ul>
</div>

<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-panel-group">

	<div class="am-panel am-panel-default" id="notify_information">
		<div class="am-panel-hd am_hd_background" style="border-bottom:1px solid #ddd;font-weight:600">
			<?php echo $ld['basic_information'] ?>
		</div>
		<div class="am-panel-bd am-cf">

			<?php echo $form->create('Mail',array('class'=>'am-form','action'=>'send_product_mail/'.(isset($id)?$id:""),'id'=>'mailForm','name'=>'mailForm','onsubmit'=>"return checkmail();"));?>


<div id="tablemain" class="tablemain">
	<div class="am-form-group am-g" style="margin-top:5px;">
  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['title']; ?></label>
  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
  		<input type="text" name="data[mail][title]" id="mail_title" value="" >
  		</div>
  		<div class="am-u-sm-3 am-u-md-3 am-u-lg-5"><em>*</em></div>
	</div>
	<div class="am-form-group am-g" style="margin-top:5px;">
  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['recipients']; ?></label>
  		<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
  			<input type="text" name="data[mail][receiver_email]" id="mail_receiver_email" value="" >
			<?php echo $ld['Email_address_format_desc'] ?>
  		</div>
  		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
			<select name="user_category_id" id='user_category_id' data-am-selected="{maxHeight:300}">
				<option value='0'><?php echo $ld['user_category'] ?></option>
				<?php if(isset($user_category_list)&&sizeof($user_category_list)>0){foreach($user_category_list as $k=>$v){ ?>
				<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
				<?php }} ?>
			</select>
		</div>
		<div class='am-u-sm-1'><em>*</em></div>
	</div>

	<div class="am-form-group am-g" style="margin-top:5px;">
  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['Cc']; ?></label>
  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
  		<input type="text" name="data[mail][cc_email]" value="" >
  		</div>
  		<div class="am-u-sm-3 am-u-md-3 am-u-lg-5">&nbsp;</div>
	</div>
	
	<div class="am-form-group am-g" style="margin-top:5px;">
  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['Bcc']; ?></label>
  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
  		<input type="text" name="data[mail][bcc_email]" value="" >
  		</div>
  		<div class="am-u-sm-3 am-u-md-3 am-u-lg-5">&nbsp;</div>
	</div>


	<div class="am-form-group am-g" style="margin-top:5px;">
  		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="line-height:37px;"><?php echo $ld['html_email_content'] ?></label>
  		<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
  		<textarea id="mail_html_body" name="data[mail][html_body]"><?php echo isset($html_body)?$html_body:''; ?></textarea>
  		<?php if($show_edit_type){ ?>
		<script type="text/javascript">
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('#mail_html_body', {
				langType : '<?php echo $google_translate_code; ?>',cssPath : '/css/index.css',filterMode : false});
			});
		</script>
		<?php }else{ echo $ckeditor->load("mail_html_body");} ?>
  		</div>
  		<div class="am-u-sm-3 am-u-md-3 am-u-lg-5">&nbsp;</div>
	</div>
	<div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center am-margin-top-sm">
		<input type="submit" class="am-btn am-margin-right-sm am-btn-success am-btn-sm am-radius" value="<?php echo $ld['log_send_email'];?>" /><input class="am-btn am-btn-default am-btn-sm am-radius" type="reset" value="<?php echo $ld['d_reset']?>" />
	</div>
</div>

<?php echo $form->end();?>
		</div>
	</div>
</div>
<style type='text/css'>
#notify_information th{width:15%;}
#notify_information td input[type=text]{width:50%;}
#notify_information em{color:red;}
</style>
<script type="text/javascript">
function checkmail(){
	var mailflag=true;
	var mail_title=document.getElementById("mail_title").value;
	var mail_receiver_email=document.getElementById("mail_receiver_email").value;
	var mail_html_body=document.getElementById("mail_html_body").value;
	var user_category_id=document.getElementById("user_category_id").value;
	
	if(mail_title==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['title']) ?>");
		mailflag=false;
	}else if(mail_receiver_email==""&&user_category_id==0){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['recipients']) ?>");
		mailflag=false;
	}else if(mail_html_body==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['html_email_content']) ?>");
		mailflag=false;
	}
	if(mailflag){
		$.ajax({
			url: admin_webroot+"mails/send_product_mail/<?php echo isset($id)?$id:""; ?>",
			type:'post',
			dataType:"json",
			data:$('#mailForm').serialize(),
			context: $("#mailForm"), 
			success: function(data){
				if(data.code==1){
					window.location.href="/admin/products/";
				}else{
					alert(data.msg);
				}
			}
		});
	}
	return false;
}
</script>