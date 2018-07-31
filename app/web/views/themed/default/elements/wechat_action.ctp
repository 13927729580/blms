<?php
    if(!empty($open_wechat_info)){
	if(isset($open_config)){
		echo "<div id='wechat_bottom_info' class='am-hide'>".(isset($open_config['BOTTOM-AREA-INFORMATION'])?$open_config['BOTTOM-AREA-INFORMATION']['value']:'')."</div>";
	}
        $wechat_navigations=array();
        if(isset($navigations['T'])){
            foreach($navigations['T'] as $v){
                $wechat_navigations[]=$v['NavigationI18n']['name'];
            }
        }
?>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
	var wechat_shop_description="<?php echo $configs['shop_description']; ?>";
	var wechat_navigations_description="<?php echo implode(" ",$wechat_navigations);  ?>";
	var wechat_appId="<?php echo isset($open_wechat_info['app_id'])?$open_wechat_info['app_id']:''; ?>";
	var wechat_signature=null;
	var wechat_hideMenu=[
		'menuItem:share:appMessage',
		'menuItem:share:timeline',
		'menuItem:share:qq',
		'menuItem:share:weiboApp',
		'menuItem:favorite',
		'menuItem:share:facebook',
		'menuItem:share:QZone'
	];
	wechat_hideMenu=[];
	
	if(typeof(wechat_shareTitle)=="undefined"){var wechat_shareTitle="<?php echo $configs['shop_title']; ?>";}
	if(typeof(wechat_imgUrl)=="undefined"){var wechat_imgUrl="<?php echo $server_host.$configs['shop_logo']; ?>";}
	if(typeof(wechat_lineLink)=="undefined"){var wechat_lineLink="<?php echo $server_host; ?>";}
	if(typeof(wechat_descContent)=="undefined"||wechat_descContent==""){
		if(window.location.href=="<?php echo $server_host; ?>/"){
			var wechat_descContent=wechat_shop_description!=''?wechat_shop_description:wechat_navigations_description;
		}else{
			var wechat_descContent=wechat_navigations_description;
		}
	}
	if(wechat_imgUrl=="<?php echo $server_host; ?>"){
		wechat_imgUrl="<?php echo $server_host.$configs['shop_logo']; ?>"
	}
	if(wechat_descContent.length>150){
		wechat_descContent=wechat_descContent.substr(0,150)+"...";
	}
	if(wechat_lineLink!=''&&typeof(js_login_user_data)!='undefined'&&js_login_user_data!=null){
		var login_user_id=js_login_user_data['User']['id'];
		if(wechat_lineLink.indexOf('?')>0){
			wechat_lineLink=wechat_lineLink+"&share_from="+login_user_id;
		}else{
			wechat_lineLink=wechat_lineLink+"?share_from="+login_user_id;
		}
	}
    	$.ajax({
		type: "POST",
		url:web_base+"/opens/signature",
		data:{'page':location.href.split('#')[0]},
		dataType:"html",
		async: false,
		success: function(data) {
			wechat_signature=data;
		},
		complete:function(){
			wx_config_load();
		}
    	});

function wx_config_load(){
    wx.config({
        debug: false,//这里是开启测试，如果设置为true，则打开每个步骤，都会有提示，是否成功或者失败
        appId: wechat_appId,
        timestamp: "<?php echo strtotime(date('Y-m-d')) ?>",//这个一定要与上面的php代码里的一样。
        nonceStr: "<?php echo strtotime(date('Y-m-d')) ?>",//这个一定要与上面的php代码里的一样。
        signature: wechat_signature,
        jsApiList: [
          // 所有要调用的 API 都要加到这个列表中
            'hideMenuItems',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'scanQRCode'
        ]
    });
    
    wx.ready(function(){
	if(wechat_hideMenu.length>0){
		wx.hideMenuItems({menuList:wechat_hideMenu});
	}
	 
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: wechat_shareTitle, // 分享标题
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function (result) {
                // 用户确认分享后执行的回调函数
                if(typeof(result.errMsg)!='undefined'&&new RegExp(':ok').test(result.errMsg)){
               	ajax_share_log();
               }
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function (result) {
                // 用户确认分享后执行的回调函数
                if(typeof(result.errMsg)!='undefined'&&new RegExp(':ok').test(result.errMsg)){
               	ajax_share_log();
                }
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        
        //分享到QQ
        wx.onMenuShareQQ({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function (result) { 
               // 用户确认分享后执行的回调函数
               if(typeof(result.errMsg)!='undefined'&&new RegExp(':ok').test(result.errMsg)){
               	ajax_share_log();
               }
            },
            cancel: function () { 
               // 用户取消分享后执行的回调函数
            }
        });
        
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title: wechat_shareTitle, // 分享标题
            desc: wechat_descContent, // 分享描述
            link: wechat_lineLink, // 分享链接
            imgUrl: wechat_imgUrl, // 分享图标
            success: function (result) { 
               // 用户确认分享后执行的回调函数
               if(typeof(result.errMsg)!='undefined'&&new RegExp(':ok').test(result.errMsg)){
               	ajax_share_log();
               }
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
    });
}

function ajax_share_log(){
	$.ajax({
		type: "POST",
		url:web_base+"/user_shares/ajax_share_log",
		data:{'share_link':wechat_lineLink,'share_title':wechat_shareTitle},
		dataType:"json",
		async: false,
		success: function(data) {
			if(data.code!='1'){
				console.log(data.message);
			}
		}
    	});
}

ajax_check_wechat_subscribe();

function ajax_check_wechat_subscribe(){
	if(document.getElementById('wechat_bottom_info')){
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.match(/MicroMessenger/i) == 'micromessenger'&&ua.match(/Mobile/i) == 'mobile') {
			if(ua.match(/wxwork/i) == 'wxwork')return;
			$.ajax({
				type: "POST",
				url:web_base+"/synchros/ajax_check_wechat_subscribe",
				data:{},
				dataType:"json",
				async: false,
				success: function(data) {
					if(data.code!='1'){
						$('#wechat_bottom_info').removeClass('am-hide');
					}
				}
		    	});
	    	}
	}
}
</script>
<?php } ?>