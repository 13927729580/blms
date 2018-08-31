<style type='text/css'>
.am-contact{max-width:1200px;margin:3rem auto;}
.am-contact h3{color: #333;margin:0 auto;text-align:center;}
.am-contact .am-form-detail .am-form-group input[type="text"], .am-contact .am-form-detail .am-form-group select{border-radius:3px;}
.am-icon-list-ul:before {content: "\f0ca";margin-right: 5px;}
.am-icon-user-md:before{margin-right: 5px;}
.am-icon-envelope:before{margin-right: 5px;}
.am-icon-mobile-phone:before, .am-icon-mobile:before{margin-right: 5px;}
.am-contact .am-form-detail em{margin-top:-3px;}
.am_new_form input[type="text"]{margin-left:0px;}
</style>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-contact">
	<div class="am-cf am-contact-us">
		<h3><?php echo $ld['contact_us'] ?></h3>
		<hr>
	</div>
	<?php echo $form->create('Contacts', array('id'=>"contact_form",'action' => '/index/','name'=>'ContactForm','class'=>'am-form am-form-horizontal','onsubmit'=>'return(check_form(this));')); ?>
	<div class="am-form-detail">
<?php if(!isset($contact_us_type)&&isset($contact_us_type_data)&&is_array($contact_us_type_data)&&sizeof($contact_us_type_data)>0) { ?>
<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label am-icon-list-ul"><?php echo $ld['type'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<select name="data[Contact][type]" onchange="contact_type(this)">
                <?php foreach($contact_us_type_data as $k=>$v){ ?><option value="<?php echo $k; ?>" <?php echo isset($contact_us_type)&&$contact_us_type==$k?'selected':''; ?>><?php echo $v; ?></option><?php } ?>
        </select>
    	  </div>
        </div>
  <?php }else if(isset($contact_us_type)&&isset($contact_us_type_data)&&is_array($contact_us_type_data)&&sizeof($contact_us_type_data)>0){ ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label"><?php echo $ld['type'] ?></label>
          <label class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-label am-text-left">
            <?php echo isset($contact_us_type_data[$contact_us_type])?$contact_us_type_data[$contact_us_type]:'' ?>
            <input type="hidden" name="data[Contact][type]" value="<?php echo $contact_us_type; ?>" />
    	  </label>
        </div>
        <?php } ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label am-icon-user-md"><?php echo $ld['contact'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" chkRules="nnull:<?php echo $ld['no_empty_contactor']?>" defaultNote="<?php echo $ld['enter_contact']?>" onpropertychange="this.value=this.value.replace(/[^\u4E00-\u9FA5A-Za-z]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5A-Za-z]/g,''))" size="32" class="input" name="data[Contact][contact_name]" id="ContactContactName" value="" style="margin-left:0;" /><em  class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label am-icon-envelope"><?php echo $ld['e-mail'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		 <input type="text" size="32" class="input" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php $ld['e-mail_incorrectly']?>;" name="data[Contact][email]" id="ContactEmail" value="" style="margin-left:0;" /><em  class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label am-icon-mobile am-icon-md"><?php echo $ld['mobile'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    		<input type="text" size="32" class="input" name="data[Contact][mobile]" chkRules="nnull:<?php echo $ld['phone_can_not_be_empty']?>;mobile:<?php echo $ld['phone_incorrectly_completed']?>;length11:<?php echo $ld['mobile_number_length']?>" id="ContactMobile" value="" style="margin-left:0;" /><em class="l1"><font color="red">*</font> <font></font>&nbsp;</em>
    	  </div>
        </div>
        <div class="am_new_form">
          
        </div>
        	
        	        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label am-icon-mobile am-icon-md"><?php echo $ld['message'] ?></label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
            <textarea  name="data[Contact][content]" id="ContactContent">
            </textarea>
    	<em class="l1"><font color="red">*</font>&nbsp;</em>
    	  </div>
        </div>
        	
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
            <input id="save_btn" class="am-btn am-btn-primary am-btn-sm am-fl am-btn-secondary" style="width:100px;height: 32px;margin-left:0;" type="button" value="<?php echo $ld['submit']?>" />
    	  </div>
        </div>

	</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
auto_check_form("contact_form",false);
$("#save_btn").click(function(){
  if(check_form()){
  	  var save_btn=$(this);
  	  $(save_btn).button('loading');
	  $.ajax({
		    type: "POST",
		    url:web_base+"/contacts/index/",
		    data:$('#contact_form').serialize(),// 你的formid
		    dataType:"json",
		    async: false,
		    success: function(data) {
	    			alert(data.msg);
				if(data.code==1){
					location.reload(true);
				}
		    },
		    complete:function(){
		     	$(save_btn).button('reset');
		    }
	  });
  }
});

//****************************************************************
// Description: sInputString 为输入字符串，iType为类型，分别为
// 0 - 去除前后空格; 1 - 去左边空格; 2 - 去右边空格
//****************************************************************
function cTrim(sInputString,iType)
{
	var sTmpStr = ' '
	var i = -1
	if(iType == 0 || iType == 1)
	{
	while(sTmpStr == ' ')
	{
	++i
	sTmpStr = sInputString.substr(i,1)
	}
	sInputString = sInputString.substring(i)
	}
	if(iType == 0 || iType == 2)
	{
	sTmpStr = ' '
	i = sInputString.length
	while(sTmpStr == ' ')
	{
	--i
	sTmpStr = sInputString.substr(i,1)
	}
	sInputString = sInputString.substring(0,i+1)
	}
	return sInputString
}

  var contacts_type = $("select[name='data[Contact][type]']").val();
  function contact_type(index) {
   contacts_type = $(index).val();
   $(".am_new_form").html("");
   ajax_contacts_type();
  }
  
  function  ajax_contacts_type (){
   $.ajax({
    url:web_base+"/contacts/ajax_contact_config/"+contacts_type,
    type:"POST",
    dataType:"json",
    data:"",
    success:function (data) {
      if (data.code == 1) {

      
       $.each(data.data, function (index,concent) {
       	var contact_code = concent.ContactConfig.code;
       	var contact_name = concent.ContactConfigI18n.name;
       	var contact_is_required = concent.ContactConfig.is_required;
        var contact_value_type = concent.ContactConfig.value_type;
        var contact_label = '<label class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-form-label">'+contact_name+'</label>'
        if (contact_value_type == 'text') {
          if (contact_is_required == 1) {
          var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" class="form_yanz" name="data[ContactConfig]['+contact_code+']"type="text" style="margin-left:0;" /><em class="l1"><font class="am-check-icon" color="red">*</font><font color="red" class="am-form-yanzheng"></font>&nbsp;</em></div>'
          }else{
          var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input class="" name="data[ContactConfig]['+contact_code+']"type="text"/></div>'
          }
          $(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         };
         if (contact_value_type == 'textarea') {
           if (contact_is_required == 1) {
            var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><textarea onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" class="form_yanz" name="data[ContactConfig]['+contact_code+']"rows="3"></textarea><em class="l1"><font class="am-check-icon" color="red">*</font><font color="red" class="am-form-yanzheng"></font>&nbsp;</em></div>'
           }else{
            var contact_input =  '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><textarea onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" name="data[ContactConfig]['+contact_code+']"rows="3"></textarea>'
           }
        	$(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         };
         if (contact_value_type == 'numbertext') {
		if (contact_is_required == 1) {
			var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" class="form_yanz" name="data[ContactConfig]['+contact_code+']"type="text"/><em class="l1"><font class="am-check-icon" color="red">*</font><font color="red" class="am-form-yanzheng"></font>&nbsp;</em></div>'
		}else{
			var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input class="" name="data[ContactConfig]['+contact_code+']"type="text"/></div>'
		}
        	$(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         }
         if (contact_value_type == 'file') {
	          if (contact_is_required == 1) {
	            	var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input accept ="image/*" onchange="upload_contact(this,'+"'"+''+contact_name+''+"'"+')" class="form_yanz" type="file"/><input type="hidden" name="data[ContactConfig]['+contact_code+']"  /><em class="l1"><font class="am-check-icon" color="red">*</font><font color="red" class="am-form-yanzheng"></font>&nbsp;</em></div>'
	          }else{
	            	var contact_input = '<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input accept ="image/*" onchange="upload_contact(this,'+"'"+''+contact_name+''+"'"+')" type="file"/><input type="hidden" name="data[ContactConfig]['+contact_code+']"  /></div>'
	          }
        		$(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         };

         if (contact_value_type == 'radio') {
          var contact_input='<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 radio_sex" style="line-height:39px;"></div>';
          $(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
          $.each(concent.ContactConfigOption,function (ind,con) {
            var contact_input_genera = '<div class="am-radio" style="display:inline-block;margin-right:10px;"><label><input type="radio"value="'+ind+'"name="data[ContactConfig]['+contact_code+']">'+con+'</label></div>';
          $(".radio_sex").append(contact_input_genera);
          })
         };

         if (contact_value_type == 'checkbox') {
         var contact_input='<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 check_box" style="line-height:39px;"></div>';
         $(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         $.each(concent.ContactConfigOption,function (ind,con) {
          var contact_input_genera = '<div class="am-checkbox" style="display:inline-block;margin-right:10px;"><label><input type="checkbox" name="data[ContactConfig]['+contact_code+']" value="'+ind+'">'+con+'</label></div>';
          $(".check_box").append(contact_input_genera);
         })
         };

         if (contact_value_type == 'select'){
	         if (contact_is_required == 1) {
		         var contact_input='<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><select onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" class="select_option form_yanz"><option value="">请选择</option>';
		         $.each(concent.ContactConfigOption,function (ind,con) {
			         	var contact_input_genera = '<option value="'+ind+'">'+con+'</option>';
			          	contact_input+=contact_input_genera;
		         });
		         contact_input+='</select><em class="l1"><font class="am-check-icon" color="red">*</font><font color="red" class="am-form-yanzheng"></font>&nbsp;</em></div>';
	         }else{
		         var contact_input='<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><select onblur="change_check(this,'+"'"+''+contact_name+''+"'"+')" class="select_option"><option value="">请选择</option>';
		         $.each(concent.ContactConfigOption,function (ind,con) {
					var contact_input_genera = '<option value="'+ind+'">'+con+'</option>';
					contact_input+=contact_input_genera;
		         });
		         contact_input+='</select></div>';
	         }
	         $(".am_new_form").append('<div class="am-form-group">'+contact_label+contact_input+'</div>');
         }
       }) 
};
    }
   })
  }
  ajax_contacts_type();

 function change_check (ele,name) {
   var change_input = $(ele).val();
   if(change_input == ''){
    $(ele).parent().find(".am-form-yanzheng").html(name+"不能为空");
    $(ele).parent().find(".am-check-icon").html('*')
                                          .css('color','red');
   }else{
    $(ele).parent().find(".am-form-yanzheng").html("");
    $(ele).parent().find(".am-check-icon").html('<span class="am-icon-check" style="right:16px;display:block"></span>')
                                          .css('color','green');
   }
 }
function check_form () {
    if($('#ContactContactName').val()==""){
        alert("联系人不能为空");
        return false;
    }
    if($('#ContactEmail').val()==""){
        alert("E-mail地址不能为空 ");
        return false;
    }
    if($('#ContactMobile').val()==""){
        alert("手机号不能为空");
        return false;
    }
    if($('#ContactContent').val()==""){
        alert("留言不能为空");
        return false;
    }
    return true;
}

function upload_contact(input,name){
	var file = input.files[0];
	var filename = file.name || '';
	var fileType = file.type || '';
	var mpImg = new MegaPixImage(file);
	var resImg =  document.createElement('canvas');
	mpImg.render(resImg, { maxWidth: 150, maxHeight: 150, quality: 0.8},function(){
      var PostFileSrc=resImg.toDataURL();
      var formData = new FormData();
      //添加图片的blob
      if (PostFileSrc) {
        formData.append("contact_file", convertBase64UrlToBlob(PostFileSrc, fileType), filename);
        $.ajax({
              url: web_base+'/contacts/ajax_uplad_contacts',
              method: 'post',
              processData: false,
              contentType: false,
              data: formData,
              dataType:'json',
              success:function(data){
              	$(input).parent().remove('img');
              	if(data.code=='1'){
              		$(input).parent().find("input[type='hiddden']").val(data.file_url);
              		$(input).parent().append("<img src='"+data.file_url+"' style='max-width:150px;max-height:150px;' />");
              	}else{
              		seevia_alert(data.message);
              	}
              }
        });
      }
  });
}


function convertBase64UrlToBlob(urlData, filetype){
  //去掉url的头，并转换为byte
  var bytes = window.atob(urlData.split(',')[1]);
  //处理异常,将ascii码小于0的转换为大于0
  var ab = new ArrayBuffer(bytes.length);
  var ia = new Uint8Array(ab);
  var i;
  for (i = 0; i < bytes.length; i++) {
      ia[i] = bytes.charCodeAt(i);
  }
  return new Blob([ab], {type : filetype});
}
</script>