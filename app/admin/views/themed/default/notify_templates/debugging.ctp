<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
	<ul class="am-list admin-sidebar-list" style="z-index: 100" data-am-scrollspy-nav="{offsetTop: 60}"  data-am-sticky="{top:60}">
	 	<li><a href="#notify_information"><?php echo $ld['basic_information'] ?></a></li>
	</ul>
</div>
<form method="post" id='notify_template_debugging'>
<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-panel-group" id='accordion'>
	<div class="am-panel am-panel-default" id="notify_information">
		<div class="am-panel-hd">
			<h4 class='am-panel-title'><?php echo $ld['basic_information'] ?></h4>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd">
				<div class="am-form-detail am-form am-form-horizontal">
					<input type='hidden' id='template_code' value="<?php echo $template_code; ?>" />
						<?php if(isset($notify_template_type_list)&&sizeof($notify_template_type_list)>0){foreach($notify_template_type_list as $v){?>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo isset($Resource_info['notity_type'][$v['NotifyTemplateType']['type']])?$Resource_info['notity_type'][$v['NotifyTemplateType']['type']]:$v['NotifyTemplateType']['type']; ?></label>
						<?php if($v['NotifyTemplateType']['type']!='wechat'){ ?>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-4'>
							<input type="text" class="notity_send_to" name="send_to[<?php echo $v['NotifyTemplateType']['type']; ?>]" placeholder="<?php echo $ld['recipients']; ?>">
						</div>
						<?php }else{ ?>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-4'>
							<select onchange="openmodel_wechat(this)" name="wechat_open_type_id" data-am-selected>
								<option value="0"><?php echo $ld['please_select']; ?></option>
								<?php if(isset($open_type)&sizeof($open_type)>0){foreach($open_type as $vv){?>
								<option value="<?php echo $vv['OpenModel']['open_type_id'];  ?>"><?php echo $vv['OpenModel']['open_type_id'];  ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-4'>
							<select name="send_to[<?php echo $v['NotifyTemplateType']['type']; ?>]" id="wechat_open_user" class='notity_send_to' data-am-selected="{maxHeight:200}">
								<option value="0"><?php echo $ld['please_select']; ?></option>
							</select>
						</div>
						<?php } ?>
					</div>
						<?php } ?>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['send'].$ld['content']; ?></label>
						<div class='am-u-lg-10 am-u-md-9 am-u-sm-8'>
							<textarea id='send_content' name="send_content"></textarea>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3">&nbsp;</label>
						<div class='am-u-lg-10 am-u-md-9 am-u-sm-8'>
							(发送内容格式为:name=value,例如:nickname=张三,多个参数采用换行处理)
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3">&nbsp;</label>
						<div class='am-u-lg-10 am-u-md-9 am-u-sm-8'>
							<button type="button" onclick="notify_template_send()" class="am-btn am-btn-success am-btn-sm am-radius am-margin-top-sm"><?php echo $ld['send']; ?></button>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<style type="text/css">
#accordion .am-form-group {margin-bottom: 1.5rem;}
</style>
<script type="text/javascript">
function openmodel_wechat(obj){
	var open_type_id=$(obj).val();
	$("#wechat_open_user option").remove();
	$("#wechat_open_user").append("<option value='0'>"+j_please_select+"</option>");
	if(open_type_id!="0"){
		$.ajax({
		        url:admin_webroot+"notify_templates/ajax_open_wechat_user/",
		        type:"POST",
		        data: {'open_type_id':open_type_id},
		        dataType:"html",
		        success:function(data){
		        	var open_user_html=data.trim();
		        	if(open_user_html!=""){
		        		var open_user=open_user_html.split("\r\n\r\n");
		        		$(open_user).each(function(index,item){
		        			if(item!=""){
		           				var open_user_data=item.trim().split("\r\n");
		           				$("#wechat_open_user").append("<option value='"+open_user_data[0]+"'>"+open_user_data[1]+"</option>");
		           			}
			           	})
		        	}
		           	$("#wechat_open_user").trigger('change.selected.amui');
		        }
		    });
	}else{
		$("#wechat_open_user").trigger('change.selected.amui');
	}
}

function notify_template_send(){
	var notity_send_to=0;
	var send_content=$('#send_content').val();
	var template_code=$("#template_code").val();
	$(".notity_send_to").each(function(){
		if($(this).val().trim()!="")notity_send_to++;
	});
	if(notity_send_to>0&&send_content!=''){
		$.ajax({
		        url:admin_webroot+"notify_templates/debugging/"+template_code,
		        type:"POST",
		        data: $("#notify_template_debugging").serialize(),
		        dataType:"json",
		        success:function(data){
		           	alert(data.message);
		        }
		    });
	}
}
</script>