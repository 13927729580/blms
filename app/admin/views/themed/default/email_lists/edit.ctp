<?php
/*****************************************************************************
 * SV-Cart 添加杂志模板
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}

    .am-list>li{margin-bottom:0;border-style: none;}
    .admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
    .am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
    .scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
    .am-sticky-placeholder{margin-top: 10px;}
    .scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
    .scrollspy-nav ul {margin: 0;padding: 0;}
    .scrollspy-nav li {display: inline-block;list-style: none;}
    .scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
    .scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
    .crumbs{padding-left:0;margin-bottom:22px;}
    .btnouter{margin:0;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<?php echo $form->create('email_lists',array('action'=>'/edit/'.$this->data['MailTemplate']['id'],'id'=>'email_lists_form',"name"=>"email_lists",'onsubmit'=>'return mailtemplates_check();'));?>
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){
    foreach ($backend_locales as $k => $v){?>
        <input id="MailTemplateI18n<?php echo $k;?>Locale" name="data[MailTemplateI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo  $v['Language']['locale'];?>">
        <?php if(isset($this->data['MailTemplateI18n'][$v['Language']['locale']])){?>
            <input id="MailTemplateI18n<?php echo $k;?>Id" name="data[MailTemplateI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo  $this->data['MailTemplateI18n'][$v['Language']['locale']]['id'];?>">
        <?php }?>
        <input id="MailTemplateI18n<?php echo $k;?>MailTemplateId" name="data[MailTemplateI18n][<?php echo $k;?>][mail_template_id]" type="hidden" value="<?php echo  $this->data['MailTemplate']['id'];?>">
        <?php }}?>

<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" style="width: 100%;">
    <!-- 导航 -->
    <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
        <ul>
            <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
            <li><a href="#plain_text"><?php echo $ld['plain_text_message_content']?></a></li>
            <li><a href="#html_email"><?php echo $ld['html_email_content']?></a></li>
            <li><a href="#sms"><?php echo $ld['sms_content']?></a></li>
            <li><a href="#send"><?php echo $ld['send_and_test']?></a></li>
        </ul>
    </div>
    <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
        <input style="margin-left: 0;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" /> 
        <input style="margin-left: 0;" class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
    </div>
    <!-- 导航结束 -->
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <input id="mailid" type="hidden" name="data[MailTemplate][id]" value="<?php echo $this->data['MailTemplate']['id'];?>" />
                <table class="am-table">
                    <tr><th style="padding-top:15px;width: 20%;"><?php echo $ld['email_code']?></th>
                        <td><input style="width:50%;" type="text"  id="data_mailtemplate_code" name="data[MailTemplate][code]" value="<?php echo $this->data['MailTemplate']['code'];?>" /> </td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px;"rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input style="width:50%;float:left;" type="text" id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][title]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['title'];?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['email_help']?></th>
                    </tr>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><input style="width:50%;float:left;" type="text"  id="title<?php echo $v['Language']['locale']?>" name="data[MailTemplateI18n][<?php echo $k;?>][description]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['description'];?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?></td>
                        </tr>
                        <?php }}?>
                    <tr>
                        <th style="padding-top:15px;"><?php echo $ld['display']?></th>
                        <td><label class="am-radio am-success"><input type="radio" name="data[MailTemplate][status]" style="margin-left:0px;" data-am-ucheck value="1" <?php if($this->data['MailTemplate']['status']){?>checked<?php }?> ><?php echo $ld['yes'];?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input name="data[MailTemplate][status]" type="radio" value="0" data-am-ucheck <?php if($this->data['MailTemplate']['status']==0){?>checked<?php }?> ><?php echo $ld['no']?></label>
                        </td>
                    </tr>
                </table>
                
            </div>
        </div>
    </div>
    <div id="plain_text" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['plain_text_message_content']?></h4>
        </div>
        <div id="plain_text_message_content" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
                        if($configs["show_edit_type"]){?>
                            <tr> 
                                <td><span class="ckeditorlanguage" style="position: absolute;right: 15%;"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_text_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['MailTemplateI18n'][$k]['text_body'])?$this->data['MailTemplateI18n'][$k]['text_body']:"";?></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#email_list_text_id<?php echo $v['Language']['locale'];?>', {
                                            	width:'80%',
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </td>
                            </tr>
                        <?php }else{?>
                            <tr>
                                <td><span class="ckeditorlanguage" style="position: absolute;right: 15%;"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_text_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10"><?php echo isset($this->data['MailTemplateI18n'][$v['Language']['locale']]['text_body'])?$this->data['MailTemplateI18n'][$v['Language']['locale']]['text_body']:"";?></textarea>
                            </tr>
                        <?php }?>
                        <?php }}?>
                </table>

            </div>
        </div>
    </div>
    <div id="html_email" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['html_email_content']?></h4>
        </div>
        <div id="html_email_content" class="am-panel-collapse am-collapse am-in">
        	
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){ ?>
                        <tr>
                            <td><span class="ckeditorlanguage" style="position: absolute;right: 15%;"><?php echo $v['Language']['name'];?></span><textarea cols="80" id="email_list_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][html_body]" rows="20" style="width:auto;"><?php echo $this->data['MailTemplateI18n'][$v['Language']['locale']]['html_body'];?></textarea>
                                <script>
                                    var editor;
                                    KindEditor.ready(function(K) {
                                        editor = K.create('#email_list_id<?php echo $v['Language']['locale'];?>', {width:'80%',
                                                items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                                                    'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                                                    'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                                                    'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                                                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                                                    'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false
                                            }
                                        );
                                    });
                                </script>
                            </td>
                        </tr>
                        <?php }}?>
                </table>

            </div>
        </div>
      </div>
      				
    <div id="sms" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['sms_content']?></h4>
        </div>
        <div id="sms_content" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <tr>
                            <td><span class="ckeditorlanguage" style="position: absolute;right: 15%;"><?php echo $v['Language']['name'];?></span><textarea style="width: 80%" cols="80" id="sms_list_id<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][sms_body]" rows="20" style="width:auto;"><?php echo $this->data['MailTemplateI18n'][$k]['sms_body'];?></textarea>
                            </td>
                        </tr>
                        <?php }}?>
                </table>

            </div>
        </div>
    </div>
    <div id="send" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['send_and_test']?></h4>
        </div>
        <div id="send_and_test" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="padding-top:15px;width: 20%;"><?php echo $ld['email']?></th>
                        <td>
                            <input style="width:200px;float:left;margin-right:5px;" type="text" id="email" name="email">  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="test_allemail()" value="<?php echo $ld['send_test_email']?>"  name="saveedit" />
                            (例:zhangsan@seevia.cn)
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px"><?php echo $ld['mobile']?></th>
                        <td>
                            <input style="width:200px;float:left;margin-right:5px;" type="text" id="mobile" name="mobile">  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="checksms()" value="<?php echo $ld['send_and_test']?>"  name="saveedit" />
                        </td>
                    </tr>
                    <tr>
                        <th style="padding-top:18px"><?php echo $ld['send_object']?></th>
                        <td >
                            <input id="toppri" value="0" type="hidden" name="data[MailTemplate][toppri]">
                            <select data-am-selected="{dropUp: 1}" style="width:200px;float:left;margin-right:5px;" id="usermode" name="data[MailTemplate][usermode]" onchange="check_user(this)">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <!-- <option value="user_email_flag"><?php echo $ld['subscriber']?></option> -->
                                <option value="newsletter_user"><?php echo $ld['magazine_subscribers']?></option>
                                <option value="user_all"><?php echo $ld['subscriber']?></option>
                            </select>
                            <select style="width:200px;float:left;margin-right:5px;display:none;" name="group_id" id="group_id">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($group_list) && sizeof($group_list)>0){foreach($group_list as $gk=>$gv){?>
                                    <option value="<?php echo $gk;?>" ><?php echo $gv;?></option>
                                <?php }}?>
                            </select>
                            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm only_email" value="<?php echo $ld['just_send_mail']?>" name="only_email" onclick="only_send_email()">
							<span style="margin-left:10px;"><input class="am-btn am-btn-success am-radius am-btn-sm only_send_sms" type="button" value="<?php echo $ld['just_send_sms']?>" onclick="only_send_sms()"></span>
							<span style="margin-left:10px;"><input type="button" id="emlandmsg" class="am-btn am-btn-success am-radius am-btn-sm send_emailandsms" value="<?php echo $ld['send_email_and_sms']?>" onclick="send_emailandsms()"></span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<style>
@media screen and (max-width: 881px){
#emlandmsg{
    margin-top:10px;
    margin-left:-10px;
}
} 

</style>
<script>
function check_user(e){
	if(e.value=="newsletter_user"){
		document.getElementById("group_id").style.display="inline";
		$(".only_send_sms").prop("disabled",true);
		$(".send_emailandsms").prop("disabled",true);
	}else{
		document.getElementById("group_id").style.display="none";
		$(".only_send_sms").prop("disabled",false);
		$(".send_emailandsms").prop("disabled",false);
	}
}

function checkemail(){
	var email = document.getElementById("email").value;
	var mailid = document.getElementById("mailid").value;
	if(email==""){
		alert('E-mail不能为空');
	}else{
		document.email_lists.action=admin_webroot+"email_lists/send_email_test/"+email+"/"+mailid+"/";
		document.email_lists.onsubmit= "";
		document.email_lists.submit();
	}
}

function checksms(){
	var sms = document.getElementById("mobile").value;
	if(sms.trim()==""){
		alert('手机号不能为空');
	}else{
		var sUrl = admin_webroot+"email_lists/send_sms_test";
		$.ajax({
			type: "POST",
			url: sUrl,
			dataType: 'json',
			data: $("#email_lists_form").serialize(),
			success: function (data) {
					if(data.code=='1'){
						alert("<?php echo $ld['send_success']?> "+sms);
				  }else{
					alert("<?php echo $ld['send_failed']; ?>");
				  }
			}
		});
	}
}

function only_send_email(){
	if(confirm(confirm_exports+"<?php echo $ld['just_send_mail']; ?>?")){
		document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/only_send_email";
		document.email_lists.onsubmit= "";
		document.email_lists.submit();
	}
}

function only_send_sms(){
	if(confirm(confirm_exports+"<?php echo $ld['just_send_sms']; ?>?")){
		document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/only_send_sms";
		document.email_lists.onsubmit= "";
		document.email_lists.submit();
	}
}

function send_emailandsms(){
	if(confirm(confirm_exports+"<?php echo $ld['send_email_and_sms']; ?>?")){
		document.email_lists.action=admin_webroot+"email_lists/insert_email_queue/send_emailandsms";
		document.email_lists.onsubmit= "";
		document.email_lists.submit();
	}
}

//发送测试邮件
function test_allemail(){
	var receiver_emails = document.getElementById('email');
	if(receiver_emails.value.trim()!=""){
		var email=receiver_emails.value;
		var sUrl = admin_webroot+"email_lists/send_email_test";
		$.ajax({
			type: "POST",
			url: sUrl,
			dataType: 'json',
			data: $("#email_lists_form").serialize(),
			success: function (data) {
					if(data.code=='1'){
							alert("<?php echo $ld['congratulations_message_successfully_sent']?> "+email);
				  }else{
						alert("<?php echo $ld['send_mail_failed']; ?>");
				  }
			}
		});
	}
}

//发送订阅邮件通知
function mail_notice(){
	var email_id = document.getElementById('mailid').value;
	var sUrl = admin_webroot+"email_lists/test_mail_notice";
	$.ajax({
		type: "POST",
		url: sUrl,
		dataType: 'json',
		data: {email_id: email_id},
		success: function (json) {
			alert("<?php echo $ld['sent']; ?> ");
		}
	});
}
</script>