var ua = window.navigator.userAgent.toLowerCase();
var WechatBrower=false;
var IOSSystem=false;
if(ua.match(/MicroMessenger/i) == 'micromessenger'&&ua.match(/Mobile/i) == 'mobile'){
	WechatBrower=true;
}
if(ua.match(/applewebkit/i) == 'applewebkit'){
	IOSSystem=true;
}
var PageContent=null;
var AudioPlay=true;
$(function(){
	if(document.getElementById('page_content')){
		PageContent=$("#page_content");
	}
	//微信弹层
	if(document.getElementById('ohcard_qcode')){
		var QRCode = $.AMUI.qrcode;
		$('#ohcard_qcode').html(new QRCode({text:location.href}));
	}
});

//微信登录提示弹层
function WechatModal(){
	var BrowerFlag=false;
	if(!WechatBrower){
		if(document.getElementById('ohcard_qcode')){
			$("#ohcard_qcode").parents('.am-modal').modal({closeViaDimmer:true});
		}
	}else{
		BrowerFlag=true;
	}
	return BrowerFlag;
}

function GetPage(pageUrl){
	if(!WechatModal())return false;
	$.ajax({
		type: "GET",
		url:web_base+pageUrl,
		data:{},
		dataType:"json",
		async: false,
		beforeSend: function(){
			$.AMUI.progress.set(1);
		},
		success: function(result) {
			$.AMUI.progress.inc();
			if(result.code=='1'&&typeof(result.html)!='undefined'){
				PageContent.fadeOut();
				PageContent.html(result.html);
				PageContent.fadeIn();
				if(typeof(result.message)!='undefined'&&result.message!=''&&result.message!='success'&&result.message!=null)alert(result.message);
				if(typeof(result.PageTitle)!='undefined'&&result.PageTitle!='')UpdatePageTitle(result.PageTitle);
			}else{
				alert(result.message);
			}
		},
		complete:function(){
			$.AMUI.progress.done(true);
		}
	});
}

function UpdatePageTitle(title){
	document.title = title;
	if (/ip(hone|od|ad)/i.test(navigator.userAgent)) {
		var i = document.createElement('iframe');
		i.src = web_base+'/theme/image/logo.png';
		i.style.display = 'none';
		i.onload = function() {
			setTimeout(function(){
				i.remove();
			}, 9);
		}
		document.body.appendChild(i);
    }
}

function experience_init(btn){
	if(!WechatModal())return false;
	$(btn).button('loading');
	GetPage(web_base+'/ohcards/index');
	$(btn).button('reset');
}

function bgmuicplay(playarea){
	var audio=$(playarea).find('audio')[0];
	if(typeof(audio)!='undefined'){
		var playicon=$(playarea).find('i.am-music');
		if(audio.paused){//播放
			AudioPlay=true;
			ExperienceMusicInit();
			//playicon.addClass('am-icon-spin');
//			$(playarea).find('audio').each(function(index,item){
//				item..play();
//			});
		}else{//暂停
			//playicon.removeClass('am-icon-spin');
			$(playarea).find('audio').each(function(index,item){
				item.pause();
			});
			AudioPlay=false;
		}
	}
}

function ExperiencePlay(){
	if(AudioPlay){
		ExperienceMusicInit();
	}
}

function GuidePlay(){
	if(document.getElementById('guide_music')){
		document.getElementById('guide_music').play();
	}
}

function AudioPlayInit(audio_id){
	if(document.getElementById(audio_id)){
		document.getElementById(audio_id).play();
		if(!document.getElementById(audio_id).paused){
			document.getElementById(audio_id).pause();
		}
	}
}

function playAudio(audio_id){
	if(document.getElementById(audio_id)){
		document.getElementById(audio_id).play();
	}
}

function pauseAudio(audio_id){
	if(document.getElementById(audio_id)&&!document.getElementById(audio_id).paused){
		document.getElementById(audio_id).pause();
	}
}

function ohcard_rule_detail(ohcard_rule_code){
	window.location.href=web_base+"/ohcards/view/"+ohcard_rule_code;
}

var ExperienceMusicInt=null;
function ExperienceMusicInit(){
	if(document.getElementById('experience_music')&&AudioPlay){
		document.getElementById('experience_music').volume=0.1;
		document.getElementById('experience_music').play();
		var currentTime=$(PageContent).data('ExperienceMusic');
		if(typeof(currentTime)!='undefined')document.getElementById('experience_music').currentTime=currentTime;
		ExperienceMusicInt=setInterval("ExperienceMusicInterval()",1);
	}else if(ExperienceMusicInt!=null){
		window.clearInterval(ExperienceMusicInt);
	}
}

function ExperienceMusicInterval(){
	if(document.getElementById('experience_music')){
		if(!document.getElementById('experience_music').paused)$(PageContent).data('ExperienceMusic',document.getElementById('experience_music').currentTime);
	}else if(ExperienceMusicInt!=null){
		window.clearInterval(ExperienceMusicInt);
	}
}

function start_ohcard(btn,ohcard_rule_code){
	if(!WechatModal())return false;
	$(btn).button('loading');
	$("div.boot_page *").fadeOut();
	$("div.boot_page").html('');
	$('#ohcard_guide').tmpl({}).appendTo('div.boot_page');
	$("#page_content").css('background','#eee');
	ExperienceMusicInit();
	GuidePlay();
	$(btn).button('reset');
}

var OHCardList=[];
var OhcardRuleConfigList=[];
var OhcardRuleCode=0;
function go_ohcard(btn,ohcard_rule_code){
	if(!WechatModal())return false;
	if(document.getElementById('guide_music')&&!document.getElementById('guide_music').paused){
		document.getElementById('guide_music').pause();
	}
	var AjaxResultStr=$(btn).parent().find("textarea").val().trim();
	if(AjaxResultStr=='')return;
	var AjaxResult=JSON.parse(AjaxResultStr);
	if(AjaxResult.code!='1')return;
	OhcardRuleCode=ohcard_rule_code;
	var AjaxResultData=AjaxResult.data;
	OHCardList=[],OhcardRuleConfigList=[];
	if(typeof(AjaxResultData.OhcardRule.OhcardRuleConfig)!='undefined'){
		OhcardRuleConfigList=AjaxResultData.OhcardRule.OhcardRuleConfig;
	}
	if(typeof(AjaxResultData.OhcardRule.Ohcard)!='undefined'){
		$.each(AjaxResultData.OhcardRule.Ohcard,function(ohcard_type_id,ohcard_list){
			for(var CardDetail in ohcard_list){
				var CardId=ohcard_list[CardDetail]['id'];
				eval("OHCardList["+CardId+"]="+JSON.stringify(ohcard_list[CardDetail])+";")
			}
		});
	}
	if(document.getElementById('guide_music')&&!document.getElementById('guide_music').paused){
		document.getElementById('guide_music').pause();
	}
	$("#page_content").css('background','#eee');
	var LastOhcardRuleKey=-1;
	var UserCardId=0;
	if(typeof(AjaxResultData.LastOhcardLog)!='undefined'){
		var LastOhcardRule=AjaxResultData.LastOhcardLog.UserCard.UserCardDetail;
		LastOhcardRuleKey=LastOhcardRule.length;
		if(LastOhcardRuleKey>=0&&LastOhcardRuleKey<OhcardRuleConfigList.length)UserCardId=AjaxResultData.LastOhcardLog.UserCard.id;
	}
	if($(".ohcard_head").length>0){
		$(btn).parent().parent().remove();
		var OhcardDetailObject=document.createElement("div");
		$('#ohcard_view').tmpl(AjaxResultData).appendTo($(OhcardDetailObject));
		$(OhcardDetailObject).find("div.ohcard_head").remove();
		$('#page_content div.boot_page').removeClass('boot_page').addClass('ohcard_detail').append($(OhcardDetailObject).find("div.ohcard_detail").html());
	}else{
		$("div.boot_page").fadeOut().remove('');
		$('#ohcard_view').tmpl(AjaxResultData).appendTo('div#page_content');
		ExperienceMusicInit();
	}
	if(UserCardId>0){
		$("div.ohcard_init form:lt("+LastOhcardRuleKey+")").remove();
		user_ohcard_actions(AjaxResultData.LastOhcardLog.UserCard);
		$("div.ohcard_init form input[type='hidden'][name='data[UserCardDetail][user_card_id]']").each(function(){
			$(this).val(AjaxResultData.LastOhcardLog.UserCard.id);
		});
	}else{
		ohcard_step(ohcard_rule_code);
	}
	OHCardInit(ohcard_rule_code);
}

function ohcard_step(ohcard_rule_code){
	$.ajax({
		url:web_base+"/ohcards/step/"+ohcard_rule_code,
		type:"POST",
		data:{
			'data[UserCardDetail][id]':0,
			'data[UserCardDetail][user_card_id]':0
		},
		dataType:"json",
		async: false,
		success: function(result){
			if(result.code=="1"){
				var resData=result.data;
				user_ohcard_actions(resData.UserCard);
				$("div.ohcard_init form input[type='hidden'][name='data[UserCardDetail][user_card_id]']").each(function(){
					$(this).val(resData.UserCard.id);
				});
			}else{
				alert(result.message);
			}
	    	}
	});
}

function user_ohcard_actions(UserCard){
	if(!document.getElementById('user_ohcard_actions'))return;
	if(typeof(UserCard.UserCardDetail)=='undefined'||UserCard.UserCardDetail.length<=0)return;
	if(typeof(OhcardRuleConfigList)=='undefined'||OhcardRuleConfigList.length<=0)return;
	var UserCardDetail=UserCard.UserCardDetail;
	var UserCardDetailList=[],OhcardLog=[];
	$.each(UserCardDetail,function(index,item){
		eval("UserCardDetailList["+item.ohcard_rule_config_id+"]="+JSON.stringify(item)+";")
	});
	var CardTotal=0;
	$.each(OhcardRuleConfigList,function(index,item){
		var ohcard_rule_config_id=item.id;
		if(typeof(UserCardDetailList[ohcard_rule_config_id])!='undefined'){
			var OhcardAction={
				'OhcardRuleConfig':item,
				'UserCardDetail':UserCardDetailList[ohcard_rule_config_id]
			};
			OhcardLog.push(OhcardAction);
			if(item.action_type=='card'||item.action_type=='select_card')CardTotal++;
		}
	});
	var result={
		'UserCard':UserCard,
		'OhcardLog':OhcardLog,
		'CardTotal':CardTotal,
		'NextOhcardConfig':UserCardDetail<OhcardRuleConfigList?'1':'0'
	};
	var OhcardTotal=$("#user_ohcard_actions ul.user_ohcard_logs").attr("data-OhcardLength");
	if(typeof(OhcardTotal)=='undefined')OhcardTotal=0;
	var OhcardRuleLogObject=document.createElement("div");
	$('#ohcard_actions').tmpl(result).appendTo($(OhcardRuleLogObject));
	var NewOhcardTotal=result.CardTotal;
	if((OhcardTotal==0&&NewOhcardTotal==0)||OhcardTotal!=NewOhcardTotal){
		$("#user_ohcard_actions").html(OhcardRuleLogObject.innerHTML);
	}else{
		$(OhcardRuleLogObject).find("li.user_card_list").remove();
		var OhcardRuleLog=$(OhcardRuleLogObject).find("ul").html();
		$("#user_ohcard_actions ul.user_ohcard_logs li:gt(0)").remove();
		$("#user_ohcard_actions ul.user_ohcard_logs").append(OhcardRuleLog);
	}
	setTimeout(function () {
		scrollToEnd();
	}, 1000);
}

function ohcard_turn(btn,turn_code,rotate){
	var ohcardImg=$(btn).parents("li").find("img");
	var ohcardImgRotate=$(ohcardImg).data('Rotate');
	if(typeof(ohcardImgRotate)=='undefined')ohcardImgRotate=0;
	if(typeof(rotate)=='undefined')rotate=180;
	if(turn_code=="left"){
		ohcardImgRotate-=rotate;
	}else if(turn_code=="right"){
		ohcardImgRotate+=rotate;
	}else{
		ohcardImgRotate=0;
	}
	ohcardImg.css({transform:"rotate("+ohcardImgRotate+"deg)"}).data("Rotate",ohcardImgRotate);
}

function scrollToEnd(){//滚动到底部
	$('.ohcard_detail').scrollTop($('.ohcard_detail')[0].scrollHeight);
}

function OhcardPreview(img){
	if(!WechatBrower)return;
	var nowImgurl=img.src;
	WeixinJSBridge.invoke("imagePreview",{
		"urls":[nowImgurl],
		"current":nowImgurl
	});
}

function shareImgShow(){
	$(".ohcard_share_detail i").remove();
	$(".ohcard_share_detail img").show();
}

function OHCardInit(ohcard_rule_code){
	var defaultRuleConfig=$("div.ohcard_init form:visible")[0];
	if(typeof(defaultRuleConfig)=='undefined')return;
	var user_card_id=$(defaultRuleConfig).find("input[type='hidden'][name='data[UserCardDetail][user_card_id]']").val();
	var ohcard_action_type=$(defaultRuleConfig).find("input[type='hidden'].ohcard_action_type").val();
	var ohcard_type_id=$(defaultRuleConfig).find("input[type='hidden'][name='data[UserCardDetail][ohcard_type_id]']").val();
	var ohcard_rule_config_id=$(defaultRuleConfig).find("input[type='hidden'][name='data[UserCardDetail][ohcard_rule_config_id]']").val();
	var guide_music_src=$(defaultRuleConfig).find("input[type='hidden'].guide_music").val();
	if(guide_music_src!=''){
		if(!document.getElementById('guide_music')){
			$("div.bgmusic").append("<audio id='guide_music' src='"+guide_music_src+"'></audio>");
		}else{
			document.getElementById('guide_music').src=guide_music_src;
		}
	}
	var feedback_music_src=$(defaultRuleConfig).find("input[type='hidden'].feedback_music").val();
	if(feedback_music_src!=''){
		if(!document.getElementById('feedback_music')){
			$("div.bgmusic").append("<audio id='feedback_music' src='"+feedback_music_src+"'></audio>");
		}else{
			document.getElementById('feedback_music').src=feedback_music_src;
		}
	}
	GuidePlay();
	if(ohcard_action_type=='card'){
		$("#user_ohcard_actions").hide();
		var $ohcard = $(defaultRuleConfig).find( 'ul.ohcard_default_card'),baraja=null;
		var $ohcardshow = $(defaultRuleConfig).find( 'ul.ohcard_list');
		var ohcard_close_time=function(){
			var ohcard_close=setInterval(function(){
				cardtransform=$ohcard.find("li:last-child").css('transform');
				if(cardtransform=='none'){
					$ohcard.hide();
					$ohcardshow.show();
					window.clearInterval(ohcard_close);
				}
			},100);
		};
		var CardCreating=false;
		var ajax_create_card=function(){
			if(CardCreating)return;
			var ohcardImg=null;
			$.ajax({
				url:web_base+"/ohcards/step/"+ohcard_rule_code,
				type:"POST",
				data:$(defaultRuleConfig).serialize(),
				dataType:"json",
				async: false,
				beforeSend: function(){
					CardCreating=true;
				},
				success: function(result){
					if(result.code=="1"){
						var resData=result.data;
						user_ohcard_actions(resData.UserCard);
						ohcardImg=resData.Ohcard.card;
						$ohcardshow.find("li:first-child img:nth-child(1)").after("<img src='"+ohcardImg+"' onclick='OhcardPreview(this)' onload='OHCardImageLoad(this)' />");
						$("div.ohcard_init form input[type='hidden'][name='data[UserCardDetail][user_card_id]']").each(function(){
							$(this).val(resData.UserCard.id);
						});
					}else{
						alert(result.message);
						if(result.code=="2"){
							OHCardInit(ohcard_rule_code);
						}
					}
			    	}
			});
		}
		$ohcardshow.hide();
		$ohcard.show().bind('click',function(){
			if(user_card_id==0){
				alert('您今天已完成体验,请明天再来');
				return;
			}
			var cardtransform=$ohcard.find("li:last-child").css('transform');
			if(cardtransform!='none'&&baraja!=null){
				AudioPlayInit('feedback_music');
				ohcard_close_time();
				setTimeout(ajax_create_card,3000);
			}else{
				return;
			}
		});
		baraja = $ohcard.baraja();
		baraja.fan({
			speed : 4000,
			easing : 'ease-out',
			range : 360,
			direction : 'right',
			origin : { x : 50, y : 100 },
			center : false
		});
	}else if(ohcard_action_type=='question'){
		$("#user_ohcard_actions").show();
		setTimeout(function(){
			$(defaultRuleConfig).find("div.ohcard_message").fadeIn();
		},3000);
		$("#user_ohcard_actions").show();
		scrollToEnd();
	}
}

function OHCardImageLoad(ohcardImg){
	var defaultRuleConfig=$(ohcardImg).parents('form');
	var $ohcardshow = $(defaultRuleConfig).find( 'ul.ohcard_list');
	setTimeout(function () {
		$ohcardshow.find("li:first-child img:nth-child(1)").hide();
		$ohcardshow.find("li:first-child div").fadeIn();
		$ohcardshow.find("li:first-child img:nth-child(2)").fadeIn();
		$(defaultRuleConfig).find("div.am-form-group").fadeIn();
		playAudio('feedback_music');
	},3000);
}


function ajax_submit_post(btn){
	var ohcard_form=$(btn).parents('form');
	var ohcard_action_type=$(ohcard_form).find("input[type='hidden'].ohcard_action_type").val();
	var user_card_id=$(ohcard_form).find("input[type='hidden'][name='data[UserCardDetail][user_card_id]']").val();
	var ajax_result=null;
	$.ajax({
		url:web_base+"/ohcards/step/"+OhcardRuleCode,
		type:"POST",
		data:$(ohcard_form).serialize(),
		dataType:"json",
		async: false,
		beforeSend: function(){
			if(typeof(btn)!='undefined')$(btn).button('loading');
			$.AMUI.progress.set(1);
		},
		success: function(result){
			$.AMUI.progress.inc();
			ajax_result=result;
			if(result.code!='1'){
				alert(result.message);
			}
	    	},
		complete:function(){
			if(typeof(btn)!='undefined')$(btn).button('reset');
			$.AMUI.progress.done(true);
		}
	});
	if(ajax_result.code=='1'){
		$("div.ohcard_init form input[type='hidden'][name='data[UserCardDetail][user_card_id]']").each(function(){
			$(this).val(ajax_result.data.UserCard.id);
		});
		user_ohcard_actions(ajax_result.data.UserCard);
		var loadtime=1000;
		var NextRuleLoad=function(){
			if($("div.ohcard_init form").length>1){
				$(ohcard_form).remove();
				OHCardInit(OhcardRuleCode);
			}else{
				OhcardShareAction(ajax_result.data.UserCard.id);
			}
		};
		if(typeof(btn)!='undefined'&&ohcard_action_type=='question'){
			$(btn).attr('disabled',true);
			$(ohcard_form).find("input[name='data[UserCardDetail][message]']").val('');
			$(ohcard_form).find("div.am-form-group").remove();
			if(document.getElementById('feedback_music')){
				document.getElementById('feedback_music').play();
				loadtime=(parseInt(document.getElementById('feedback_music').duration)+2)*1000;
			}
		}
		AudioPlayInit('guide_music');
		AudioPlayInit('closing_remark_music');
		setTimeout(NextRuleLoad,loadtime);
	}
}
function ajax_submit_card(btn){
	pauseAudio('guide_music');
	var ohcard_form=$(btn).parents('form');
	var ohcard_action_type=$(ohcard_form).find("input[type='hidden'].ohcard_action_type").val();
	if(ohcard_action_type=='card'){
		var user_card_id=$(ohcard_form).find("input[type='hidden'][name='data[UserCardDetail][user_card_id]']").val();
		$(ohcard_form).fadeOut().remove();
		OHCardInit(OhcardRuleCode);
		return;
	}
	if(ohcard_action_type=='question'||ohcard_action_type=='card_question'){
		var oh_card_message=$(ohcard_form).find("input[name='data[UserCardDetail][message]']").val().trim();
		if(oh_card_message==''){
			$("#OhCardQuestionMessage").modal({
        			relatedTarget: this,
        			closeViaDimmer:false,
        			onConfirm: function(options){
        				var ohcard_form=$("div.ohcard_init form:first-child");
        				var SubmitBtn=$(ohcard_form).find("div.am-input-group button.am-btn-danger");
        				ajax_submit_post(SubmitBtn);
        			},
        			onCancel: function() {
			          	$('#OhCardQuestionMessage').modal('close');
			       }
        		});
			return;
		}
	}
	ajax_submit_post(btn);
}

function OhcardShareAction(user_card_id){
	//user_ohcard_actions(user_card_id);
	var ImageShareAction=function(){
		setTimeout(function () {
			$("#user_ohcard_actions,div.ohcard_init").fadeOut().remove();
			$("div.boot_page>div:last-child").fadeOut().remove();
			var OhcardShare=$('#ohcard_share').tmpl({});
			$("div.ohcard_detail").append(OhcardShare);
			$("div.boot_page").append(OhcardShare);
			var shareImg=new Image();
			shareImg.onload=function(){
				$(".ohcard_share_detail i").remove();
				$(".ohcard_share_detail").append(shareImg);
			}
			shareImg.src=web_base+"/ohcards/OhcardShareImage/"+user_card_id;
		}, 1000);
	};
	if(document.getElementById('closing_remark_music')){
		$(".user_ohcard_logs #closing_remark").show();
		document.getElementById('closing_remark_music').play();
		document.getElementById('closing_remark_music').onended=function(){
			setTimeout(ImageShareAction,2000);
		}
	}else{
		ImageShareAction();
	}
}