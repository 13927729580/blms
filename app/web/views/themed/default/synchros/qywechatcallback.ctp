<?php //pr($response); ?>
<div class='am-g'>
	<div class="am-cf am-g am-g-fixed">
		<div class="am-u-lg-5 am-u-md-12 am-u-sm-12" style="padding-top: 20px;">
			<figure data-am-widget="figure" class="am am-figure am-figure-default am-text-center" data-am-figure="{ pureview:1}" style="margin-bottom: 10px;">
			<?php echo $html->image($response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:($webroot.'theme/default/img/no_head.png'),array(' data-rel'=>$response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:'/theme/default/img/no_head.png','style'=>'width:50%;height:50%;display: inline;'));  ?>
			</figure>
		</div>
              <div class="am-u-lg-5 am-u-md-12 am-u-sm-12">
        			<?php echo $form->create('/synchros',array('action'=>'qywechatcallback','class'=>'am-form','type'=>'POST'));?>
        			<input type="hidden" name="data[u_id]" value="<?php echo $response['auth']['uid']; ?>" />
        			<input type="hidden" name="data[api_type]" value="<?php echo $response['auth']['provider']; ?>" />
        			<input type="hidden" name="data[corpid]" value="<?php echo isset($response['corp_info']['corpid'])?$response['corp_info']['corpid']:''; ?>" />
        			<input type="hidden" name="data[user_name]" value="<?php echo isset($response['auth']['info']['name'])&&$response['auth']['info']['name']!=''?$response['auth']['info']['name']:''; ?>">
        			<input type="hidden" name="data[img]" value="<?php echo $response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:'/theme/default/img/no_head.png'; ?>" />
        			<div class="am-form-detail">
        				<?php if(isset($website_user)&&!empty($website_user)){ ?>
        				<div class="am-form-group">
        					<p class='am-form-label am-text-center'><?php
        							if($website_user['mobile']!=''){
        								$user_sn=$website_user['mobile'];
        							}else if($website_user['email']!=''){
        								$user_sn=$website_user['email'];
        							}else{
        								$user_sn=$website_user['user_sn'];
        							}
        							echo "请先使用{$user_sn}登录之后再进行<a href='".$html->url('/users/bind')."'>账号绑定</a>";
        					?></p>
        				</div>
        				<?php }else{ ?>
        				<div class="am-form-group">
        					<p class='am-form-label am-text-center'>来自企业微信的<?php echo $response['auth']['info']['name']; ?>，你好!&nbsp;请绑定手机</p>
        				</div>
        				<div class="am-form-group">
	        				<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-text-right' style="margin-top: 6px;"><?php echo $ld['mobile']; ?>：</label>
	        				<div class='am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left'>
	        					<input style="width: 70%;min-width: 0px;" type='text' class='am-margin-left-0' name='data[mobile]' value="" />
	        				</div>
	        				<div class='am-cf'></div>
        		        </div>
        			 	<div class="am-form-group">
	        				<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-text-right' style="margin-top: 6px;"><?php echo $ld['verify_code']; ?>：</label>
	        				<div class='am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-left' style="padding-right: 0;">
	        					<input style="min-width: 0px;width: 100%;" type='text' class='am-margin-left-0' name='data[verify_code]' value="" />
	        					
	        				</div>
	        				<div class='am-u-lg-6 am-u-md-6 am-u-sm-5' style="padding:0;">
								<button style="margin-top: 3px;margin-left: 10px;" type='button' class='am-btn am-btn-secondary am-btn-sm' onclick="send_mobile_verify_code(this)"><?php echo $ld['send'] ?></button>
	        				</div>
	        				<div class='am-cf'></div>
        		        </div>
        		        	<div class="am-form-group">
        		          		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-form-label am-text-center"><input style="width: 90%;display: inline-block;" class="am-btn am-btn-success am-btn-block"type="button" value="<?php echo $ld['login']; ?>" onclick='bind_qywechat_user(this)' /></div>
        					<div class='am-cf'></div>
        		        	</div>
        		        	<?php } ?>
        			</div>
        			<?php echo $form->end();?>
        		</div>
            </div>
	</div>
</div>
<script type='text/javascript'>
var verify_code_wait=180;
function verify_code_time(o) {
	  if (verify_code_wait == 0) {
		   o.removeAttribute("disabled");   
		   o.innerHTML="发送";
		   verify_code_wait = 180;
	  } else { 
		   o.setAttribute("disabled", true);
		   o.innerHTML="重新发送(" + verify_code_wait + ")";
		   verify_code_wait--;
		   setTimeout(function() {
		    	verify_code_time(o);
		   },
		   1000)
	  }
}
 
function send_mobile_verify_code(btn){
	var mobile_form=$(btn).parents('form');
	var mobile=$(mobile_form).find("input[name='data[mobile]']").val().trim();
	if(mobile!=''&&/^1[3-9]\d{9}$/.test(mobile)){
		verify_code_time(btn);
		$.ajax({
			url: web_base+"/authnums/index",
			type:"POST",
			dataType:"json", 
			data: {'mobile':mobile},
			success: function(data){
				if(data.code=='1'){
					seevia_alert(send_success);
				}else if(data.message!=""){
					verify_code_wait=0;
					seevia_alert(data.message);
				}else{
					verify_code_wait=0;
					seevia_alert(send_failed);
				}
			}
		});
	}
}

function bind_qywechat_user(btn){
	var mobile_form=$(btn).parents('form');
	var mobile=$(mobile_form).find("input[name='data[mobile]']").val().trim();
	if(mobile!=''&&/^1[3-9]\d{9}$/.test(mobile)){
		$.ajax({
			url: web_base+"/synchros/qywechatcallback",
			type:"POST",
			dataType:"json", 
			data: $(mobile_form).serialize(),
			success: function(data){
				if(data.code=='1'){
					var back_redirect=function(){
						window.location.href=web_base+data.back_url;
					};
					seevia_alert_func(back_redirect,data.message);
				}else{
					seevia_alert(data.message);
				}
			},complete:function(result){
				
			}
		});
	}
}

</script>