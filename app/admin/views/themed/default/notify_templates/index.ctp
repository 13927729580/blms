
<?php echo $form->create('',array('action'=>'/',"type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));
?>
<ul class="am-avg-lg-3 am-avg-md-2  am-avg-sm-1">
	<li class="am-margin-top-xs">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['system'] ?></label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="system_code" id='system_code_select'>
						<option value=""><?php echo $ld['please_select']; ?></option>
						<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
						<option value="<?php echo $v; ?>" <?php echo isset($system_code)&&$system_code==$v?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
		</div>
	</li>
	<li class="am-margin-top-xs">
		<label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text"><?php echo $ld['module'] ?></label>
		<div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
			<input type='text' name="module_code" value="<?php echo isset($module_code)?$module_code:''; ?>" />
		</div>
	</li>
	<li class="am-margin-top-xs" >
		<div class="am-u-sm-3 ">&nbsp;</div>
		<div class="am-u-sm-3 ">
			<button class="am-btn am-btn-success am-btn-sm am-radius">搜索</button>
		</div>
	</li>
</ul>
<?php echo $form->end();?>

<div class="am-cf am-text-right">
	<?php if($svshow->operator_privilege("configvalues_view")) { echo $html->link($ld['email_setting'],"/notify_templates/notify_config/website_emailsever",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view am-btn-xs")); } ?>
  <?php if($svshow->operator_privilege("configvalues_view")) { echo $html->link($ld['mail_type'],"/mail_send_histories/",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view am-btn-xs")); } ?>
	<?php if($svshow->operator_privilege("configvalues_view")) { echo $html->link($ld['sms_setting'],"/notify_templates/notify_config/website_sms",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view am-btn-xs")); } ?>
  <?php if($svshow->operator_privilege("configvalues_view")) { echo $html->link($ld['sms_logs'],"/sms_send_histories/",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view am-btn-xs")); } ?>
	<?php if($svshow->operator_privilege("notify_template_add")) { ?>
		<a class="am-btn am-btn-warning am-seevia-btn-add am-btn-xs am-radius" style="color:#fff" href="<?php echo $html->url('/notify_templates/view/0') ?>"><span class="am-icon-plus"></span><?php echo $ld['add'] ?></a>
	<?php } ?>
</div>

<?php echo $form->create('notify_templates',array('action'=>'/','id'=>'remove_notify_templates','name'=>'PageForm')) ?>
<div class="am-panel am-panel-default am-margin-top-xs">
	<div class="am-panel-hd am-cf">
  	<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
  		<label class="am-checkbox am-success" style="margin:0px;">
      <input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox">
      <?php echo $ld['template_code'] ?>
      </label>
    
    </div>
	
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['system'] ?></div>
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['module'] ?></div>
	
    <div class="am-u-lg-3 am-u-sm-4 am-u-sm-4 am-show-lg-only"><?php echo $ld['template_description'] ?></div>
  	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo '状态' ?></div>
  	<div class="am-u-lg-4 am-u-md-6 am-u-sm-6"><?php echo $ld['operate'] ?></div>
   </div>
  	<?php if (isset($notify_template_list)&&sizeof($notify_template_list)>0) { foreach ($notify_template_list as $k => $v) { ?>
	<div class="am-panel-bd am-cf" style="border-bottom:1px solid #ddd">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
		<label class="am-checkbox am-success" style="margin:0;">
      <input class="delete_ched" name="checkboxes[]" value="<?php echo $v['NotifyTemplate']['id'] ?>" type="checkbox" data-am-ucheck>
        <?php echo $v['NotifyTemplate']['code'] ?>&nbsp;
    </label>
    
    </div>
	
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><span ondblclick="system_modified(this,'<?php echo $v['NotifyTemplate']['id']; ?>')"><?php echo trim($v['NotifyTemplate']['system_code'])==''?'-':$v['NotifyTemplate']['system_code'];?></span>&nbsp;</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><span ondblclick="module_modified(this,'<?php echo $v['NotifyTemplate']['id']; ?>')"><?php echo trim($v['NotifyTemplate']['module_code'])==''?'-':$v['NotifyTemplate']['module_code']; ?></span>&nbsp;</div>
	
    <div class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-text-break am-show-lg-only">
     <?php echo $v['NotifyTemplate']['description'] ?>&nbsp;
    </div>
    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-break">
      <?php if ($v['NotifyTemplate']['status'] == 0) { ?>
      <span style="cursor:pointer;" onclick="change_state(this,'notify_templates/toggle_on_status',<?php echo $v['NotifyTemplate']['id'];?>)" class="am-icon-close am-no"></span>
      <?php } ?>
      <?php if($v['NotifyTemplate']['status'] == 1) { ?>
      <span style="cursor:pointer;" onclick="change_state(this,'notify_templates/toggle_on_status',<?php echo $v['NotifyTemplate']['id'];?>)" class="am-icon-check am-yes"></span>
      <?php } ?>
    </div>
    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6">
      <?php if($svshow->operator_privilege("notify_template_edit")) { ?>
    	<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/notify_templates/view/'.$v['NotifyTemplate']['id']); ?>" style="margin-bottom:0.5rem;"><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit'] ?></a>
      <?php } ?>
      <?php if($svshow->operator_privilege("notify_template_remove")) { ?>
    	<a href="javascript:void(0);" onclick="notify_remove('<?php echo $v['NotifyTemplate']['id'] ?>')" class="am-btn am-btn-default am-btn-xs am-text-danger" style="margin-bottom:0.5rem;"><span class="am-icon-trash-o"></span><?php echo $ld['delete']; ?></a>
      <?php } ?>
      <a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/notify_templates/debugging/'.$v['NotifyTemplate']['code']); ?>" style="margin-bottom:0.5rem;"><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['send_and_test']; ?></a>
    </div>
	</div>
	<?php }} ?>
	<?php if (empty($notify_template_list)) { ?>
	<div class="am-panel-bd am-cf am-text-center" style="border-bottom:1px solid #ddd">
	<?php echo $ld['no_data'] ?>
	</div>
	<?php } ?>
</div>
<div class="am-u-lg-5 am-u-md-6 am-u-sm-12 am-hide-sm-only" style="margin-left:7px;">
            <div class="am-fl">
          <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
            <input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox">
            <?php echo $ld['select_all']?>
          </label>
            </div>
            <div class="am-fl">
            <select name="" id="notify_templates_type" data-am-selected>
              <option value="0"><?php echo $ld['please_select'] ?></option>
              <?php if($svshow->operator_privilege("notify_template_remove")) { ?>
              <option value="delete"><?php echo $ld['batch_delete']?></option>
              <?php } ?>
            </select>
                 
                     <div class="am-fr" style="margin-left:3px;"><button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="diachange()"><?php echo $ld['submit']?></button></div>
            </div> 
</div>

<?php echo $this->element('pagers') ?>
<?php echo $form->end();?>

<script>
  function diachange(){
  var a=document.getElementById("notify_templates_type");
  if(a.value!='0'){
    for(var j=0;j<a.options.length;j++){
      if(a.options[j].selected){
        var vals = a.options[j].text ;
      }
    }
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    var image="";
    for( i=0;i<=parseInt(id.length)-1;i++ ){
      if(id[i].checked){
        j++;
      }
    }
    if( j>=1 ){
    //  layer_dialog_show('确定'+vals+'?','batch_action()',5);
      if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
      {
        batch_action();
      }
    }else{
    //  layer_dialog_show('请选择！！','batch_action()',3);
     alert('请选择');
    }
  }
}

function batch_action(){
var post_data = $("#remove_notify_templates").serializeArray();
$.ajax({
  url:admin_webroot+"notify_templates/batch_remove",
  dataType:"json",
  type:"POST",
  data:post_data,
  success:function (data){
    if (data.flag == 1) {
      alert(data.message);
      window.location.href = window.location.href;
    }else{

    }
  }
})
}

	function notify_remove (id) {
  if (confirm('确认删除')) {
    $.ajax({
    	url:admin_webroot+"notify_templates/remove/"+id,
    	type:"POST",
    	dataType:"json",
    	success:function (data) {
    		if (data.flag == 1) {
    			window.location.href = admin_webroot+"notify_templates";
    		}else{
    			alert(data.message);
    		}
    	}

    })
  };
}

function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "val="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        type:"POST",
        data: postData,
        dataType:"json",
        success:function(data){
            if(data.flag == 1){
                if(val==0){
                    $(obj).removeClass("am-icon-check am-yes");
                    $(obj).addClass("am-icon-close am-no");
                }
                if(val==1){
                    $(obj).removeClass("am-icon-close am-no");
                    $(obj).addClass("am-icon-check am-yes");
                }
            }

        }
    });
}



function system_modified(obj,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "select"){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
  	var SELECT = document.createElement("SELECT");
  	var SystemCodeOptions = document.getElementById("system_code_select").options;
  	for(var i=0;i<=SystemCodeOptions.length-1;i++){
  		var select_option=SystemCodeOptions[i];
  		SELECT.options.add(new Option(select_option.textContent,select_option.value,true,val==select_option.value?true:false));
  	}
  	obj.innerHTML = "";
	obj.appendChild(SELECT);
	SELECT.focus();
	
	SELECT.onchange=function(){
		var sel_index=SELECT.selectedIndex;
		var val = SELECT.options[sel_index].value;
		$.ajax({
			cache: true,
			type: "POST",
			url:admin_webroot+'notify_templates/system_modified',
			data:{'id':id,'val':Utils.trim(val)},
			async: false,
			success: function(data) {
				try{
					var result= JSON.parse(data);
					if(result.flag == 1){
						var result_content = result.content==''?'-':result.content;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}else{
						alert(result.content);
						obj.innerHTML = org;
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = org;
				}
			}
		}); 
	};
}

function module_modified(obj,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
  	var txt = document.createElement("INPUT");
	txt.type = "text" ;
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.className = "input_text" ;
	txt.style.width = (obj.offsetWidth + 12) + "px" ;
	txt.style.minWidth = "20px" ;
  	
  	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();
  	
  	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
		var evt = Utils.fixEvent(e);
		var obj = Utils.srcElement(e);
		if(evt.keyCode == 13){
			obj.blur();
			return false;
		}
		if(evt.keyCode == 27){
			obj.parentNode.innerHTML = org;
		}
	 }
	
	txt.onblur=function(){
		$.ajax({
			cache: true,
			type: "POST",
			url:admin_webroot+'notify_templates/module_modified',
			data:{'id':id,'val':Utils.trim(txt.value)},
			async: false,
			success: function(data) {
				try{
					var result= JSON.parse(data);
					if(result.flag == 1){
						var result_content = Utils.trim(result.content)==''?'-':result.content;
						if(Browser.isIE){
							obj.innerText=Utils.trim(result_content);
						}else{
							obj.innerHTML=Utils.trim(result_content);
						}
					}else{
						alert(result.content);
						obj.innerHTML = org;
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = org;
				}
			}
		}); 
	};
}
</script>