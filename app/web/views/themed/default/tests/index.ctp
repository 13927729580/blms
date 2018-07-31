<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-tabs" data-am-tabs="{noSwipe: 1}" id="doc-tab-demo-1">
  <ul class="am-tabs-nav am-nav am-nav-tabs">
    <li class="am-active"><a href="javascript: void(0)">流浪</a></li>
    <li><a href="javascript: void(0)">流浪</a></li>
    <li><a href="javascript: void(0)">再流浪</a></li>
  </ul>

  <div class="am-tabs-bd">
    <div class="am-tab-panel am-active">
      我就这样告别山下的家，我实在不愿轻易让眼泪留下。我以为我并不差不会害怕，我就这样自己照顾自己长大。我不想因为现实把头低下，我以为我并不差能学会虚假。怎样才能够看穿面具里的谎话？别让我的真心散的像沙。如果有一天我变得更复杂，还能不能唱出歌声里的那幅画？ A
    </div>
    <div class="am-tab-panel">
      我就这样告别山下的家，我实在不愿轻易让眼泪留下。我以为我并不差不会害怕，我就这样自己照顾自己长大。我不想因为现实把头低下，我以为我并不差能学会虚假。怎样才能够看穿面具里的谎话？别让我的真心散的像沙。如果有一天我变得更复杂，还能不能唱出歌声里的那幅画？ B
    </div>
    <div class="am-tab-panel">
      我就这样告别山下的家，我实在不愿轻易让眼泪留下。我以为我并不差不会害怕，我就这样自己照顾自己长大。我不想因为现实把头低下，我以为我并不差能学会虚假。怎样才能够看穿面具里的谎话？别让我的真心散的像沙。如果有一天我变得更复杂，还能不能唱出歌声里的那幅画？ C
    </div>
  </div>
</div>

  <br >
<br >
<br >











<form method='post' id='testform'>
<input type='hidden' name='test' value='1' />
<textarea cols="30" id="elm" name="data[Course][description]" rows="10" style="height:300px;"></textarea>
<button type='button' class='am-btn am-btn-default'>提交</button>
</form>
<script type='text/javascript'>
var editor;
KindEditor.ready(function(K) {
	editor = K.create('#elm', {width:'100%',
		langType : 'zh-cn',filterMode : false,
		items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
		afterBlur: function () { this.sync(); }
	});
});
</script>
<img src="/media/photos/201602/0/45/detail/35a486a03c21a0d7a87c7a2a27d89977.png" id='qrcodeimg' onclick="loadImg()" />
	
<hr />

<link href="/plugins/AmazeUI/css/amazeui.datetimepicker-se.min.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.datetimepicker-se.min.js" type="text/javascript"></script>
<div class="am-input-group">
	<input	type="text" id="start_date" class="am-form-field" name="start_date" value="">
	<span class="am-input-group-label" style="cursor:pointer;">
		<i class="am-icon-remove"></i>
	</span>
</div>
<script type='text/javascript'>
$("#start_date").datetimepicker();
</script>


<hr />

<div id='makecode' onclick="makeCode()"></div>

<hr />
	
<h3>活动门票二维码</h3>
<div id='activity_qrcode'></div>

<button type='button' onclick='check_activity_qrcode(this)'>验票</button>

<script type='text/javascript'>
function loadImg(){
	var url = '/authnums/qrcode';
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url, true);
	xhr.responseType = "blob";
	xhr.onload = function() {
		if (this.status == 200) {
			var blob = this.response;
			document.getElementById('qrcodeimg').src = window.URL.createObjectURL(blob);
		}
	}
	xhr.send();
}
makeCode();
function makeCode(){
    	$qr = $('#makecode');
    	var QRCode = $.AMUI.qrcode;
    	$qr.html(new QRCode({text: "http://allinone.products.seevia.cn"}));
}

activity_qrcode();
function activity_qrcode(){
	var activity_user=encodeURI("10");
	$qr = $('#activity_qrcode');
    	var QRCode = $.AMUI.qrcode;
    	$qr.html(new QRCode({text: activity_user}));
}

function check_activity_qrcode(btn){
	if(typeof(wx)!='undefined'){
		wx.scanQRCode({
			needResult:1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
			scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
			success: function (res) {
				var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
				alert(result);
			}
		});
	}
}

var user_course_read=function(course_id,course_class_id){
	var cookie_key='course_read_time'+course_id+course_class_id;
	var course_read_time=getCookie(cookie_key);
	course_read_time=course_read_time==null?0:parseInt(course_read_time);
	course_read_time++;
	setCookie(cookie_key,course_read_time);
	setTimeout(function(){
		user_course_read(course_id,course_class_id);
	},1000);
};
var ajax_user_course_read=function(course_id,course_class_id){
	var cookie_key='course_read_time'+course_id+course_class_id;
	var course_read_time=getCookie(cookie_key);
	course_read_time=course_read_time==null?0:parseInt(course_read_time);
	$.ajax({
		url:web_base+"/courses/ajax_course_read_time",
		type:'POST',
		data:{'course_id':course_id,'course_class_id':course_class_id},
		dataType:'json',
		success:function(data){
			if(data.code=='1'){
				setTimeout(function(){
					ajax_user_course_read(course_id,course_class_id);
				},30000);
			}
		}
	});
}

user_course_read(108,167);
ajax_user_course_read(108,167);
</script>