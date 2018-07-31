<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<div class='am-g'>
	<!-- <label class='am-label'><?php echo $ld['user_001']; ?></label>
	<hr /> -->
	<div style="text-align:left;font-size:20px;border-bottom:2px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
		<span style="float:left;">第三方账号绑定</span>
		<div class="am-cf"></div>
	</div>
	<ul class='' id='user_bind_list'>
		<?php
			if(isset($user_app_list)&&sizeof($user_app_list)>0){foreach($user_app_list as $v){
				$app_type=strtolower($v['UserApp']['type']);
				$user_app_data=isset($synchro_user[$app_type])?$synchro_user[$app_type]:array();
				$user_app_id=isset($user_app_data['id'])?$user_app_data['id']:0;
				$user_app_status=isset($user_app_data['status'])?$user_app_data['status']:0;
		 ?>
		<li class='am-text-center'>
			<a href="javascript:void(0);" title="<?php echo isset($user_app_data['nick'])&&$user_app_data['nick']!=''?$user_app_data['nick']:$v['UserApp']['name']; ?>" class="<?php echo strtolower($app_type); ?>" >&nbsp;</a>
			<span><?php echo $v['UserApp']['name']; ?></span>
			<a href="javascript:void(0);" class="<?php echo $user_app_status=='0'?' bind_enabled':''; ?>" onclick="bind_user_app(this,'<?php echo strtolower($app_type); ?>','<?php echo $user_app_id; ?>','<?php echo $user_app_status; ?>')"><?php echo isset($user_app_data['nick'])&&$user_app_data['nick']!=''?($user_app_data['nick']."&nbsp;"):'';echo empty($user_app_data)?'绑定':'解绑'; ?></a>
		</li>
		<?php }} ?>
	</ul>
	<div class='am-hide' id='wechat_config'><?php echo isset($wechat_loginobj['appid'])?json_encode($wechat_loginobj):array(); ?></div>
</div>
<!-- wechat登录弹窗 start -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat-login">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-text-center" id="api_wechat">
    </div>
  </div>
</div>
<!-- wechat登录弹窗 end -->
<style type='text/css'>
#user_bind_list{padding:40px 10px;}
#user_bind_list li{min-height:50px;display: inline-block;padding:20px 40px;border:1px solid #ccc;}
#user_bind_list li a:first-child{width:50px;height:50px;display: inline-block;cursor:pointer;}
#user_bind_list li span{display:block;margin:10px auto;}

/*
#user_bind_list li a.bind_enabled{filter: Alpha(opacity=10);-moz-opacity: .1;opacity: 0.3;}
#user_bind_list li a.bind_enabled:hover{opacity:1;}
*/
#user_bind_list li a.qqweibo{background:url("/theme/default/img/qq.jpg");background-repeat: no-repeat;background-size: 100%;}
#user_bind_list li a.sinaweibo{background:url("/theme/default/img/sina.png");background-repeat: no-repeat;background-size: 100%;}
#user_bind_list li a.wechat{background:url("/theme/default/img/wechat.png");background-repeat: no-repeat;background-size: 100%;}
#user_bind_list li a.qq{background: url("/theme/default/img/qie.png");background-repeat: no-repeat;background-size: 100%;}
#user_bind_list li a.qywechat{background: url("/theme/default/img/qywechat.png");background-repeat: no-repeat;background-size: 100%;}
</style>
<script type='text/javascript'>
function bind_user_app(link,app_type,user_app_id,user_app_status){
	if(user_app_id!='0'&&user_app_status=='1'&&!$(link).hasClass('bind_enabled')){
		$.ajax({
			url:web_base+'/users/bind',
			method:'post',
			data:{'user_app_id':user_app_id},
			dataType:'json',
			success:function(result){
				if(result.code=='1'){
					window.location.reload();
				}else{
					alert(result.message);
				}
			}
		});
	}else{
		var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
		if(app_type=='wechat'){
			var wechat_config_txt=$("#wechat_config").text();
			var wechat_config=wechat_config_txt!=''?JSON.parse(wechat_config_txt):{};
			if (userAgent.indexOf("MicroMessenger") > -1&&userAgent.indexOf("Mobile") > -1) {
				window.location.href=web_base+'/synchros/opauth/'+app_type;
			}else{
				var WxLoginObj = new WxLogin({
					id:"api_wechat", 
					appid: wechat_config['appid'],
					scope: "snsapi_login,snsapi_userinfo", 
					redirect_uri: wechat_config['redirect_uri'],
					state:wechat_config['state'],
				});
				$('#wechat-login').modal({closeViaDimmer:false});
			}
		}else if(app_type=='qywechat'){
			var redirect_url=$(link).data('redirect_url');
			if(typeof(redirect_url)!='undefined')window.location.href=redirect_url;
		}else{
			window.location.href=web_base+'/synchros/opauth/'+app_type;
		}
	}
}

qy_wechat_load();
function qy_wechat_load(){
	$.ajax({ 
		url:web_base+"/synchros/ajax_qywechat_config",
		type:"POST",
		data:{},
		dataType:'json',
		success: function(result){
			if(result.code=='1'){
				var data=result.data;
				var brower_user_agent=data.user_agent;
				var is_wechat_brower=brower_user_agent.indexOf('MicroMessenger')>=0?true:false;
				if(typeof(data.Config)!='undefined'){
					var system_config=data.Config;
					var redirect_uri=encodeURIComponent("http://"+document.domain+"/synchros/qywechatcallback");
					var request_link="https://open.work.weixin.qq.com/wwopen/sso/3rd_qrConnect?appid="+system_config['CorpID']+"&redirect_uri="+redirect_uri+"&state=SEEVIA&usertype=member";
					$("#user_bind_list li a.qywechat").data('redirect_url',request_link);
				}
			}
		}
	});
}
</script>