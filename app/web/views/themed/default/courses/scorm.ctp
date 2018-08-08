<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo isset($couse_ware_detail['CourseClassWare']['name'])?$server_host.$couse_ware_detail['CourseClassWare']['name']:'Scorm Player'; ?></title>
	<script src="<?php echo $webroot.'plugins/jquery.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<link href="<?php echo $webroot.'plugins/Scorm/Ext/qunit/qunit.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
	<script src="<?php echo $webroot.'plugins/Scorm/sscompat.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/sscorlib.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/ssfx.Core.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/API_BASE.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/API.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/API_1484_11.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/Controls.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/LocalStorage.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/Player.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/Ext/qunit/qunit.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/Scorm/QUnitLibrary.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo $html->url('/js/selectlang/'.LOCALE);?>"></script>
</head>
<body onload="InitScromPlayer('<?php echo isset($couse_ware_detail['CourseClassWare']['ware'])?$server_host.$couse_ware_detail['CourseClassWare']['ware']:''; ?>')">
	<div id='ScromPlayer'>
		<table width="100%" height="100%" border="1" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<div id='placeholder_treeContainer'></div>
				</td>
				<td>
					<div id='placeholder_contentIFrame'></div>
				</td>
			</tr>
		</table>
	</div>
	<div id="placeholder_Debugger"></div>
<style type="text/css">
*{margin: 0px;}
html, body{height:100%;}
#ScromPlayer{width:100%;height:100%;margin:0 auto;}
#ScromPlayer table{width:100%;height:100%;margin:0 auto;border:none;}
#ScromPlayer table td{border:none;}
#ScromPlayer table td div{height:100%;}
#ScromPlayer table td iframe{width:100%;height:100%;}
#navigationContainer{display:none!important;}
</style>
<script type='text/javascript'>
var RequestFailedCount=0;
function InitScromPlayer(scrom_link){
	var ScromAPI=null;
	PlayerConfiguration.Debug = false;
	PlayerConfiguration.StorageSupport = false;
	
	PlayerConfiguration.TreeMinusIcon = web_base+"/plugins/Scorm/Img/minus.gif";
	PlayerConfiguration.TreePlusIcon = web_base+"/plugins/Scorm/Img/plus.gif";
	PlayerConfiguration.TreeLeafIcon = web_base+"/plugins/Scorm/Img/leaf.gif";
	PlayerConfiguration.TreeActiveIcon = web_base+"/plugins/Scorm/Img/select.gif";
	
	PlayerConfiguration.BtnPreviousLabel = "Previous";
	PlayerConfiguration.BtnContinueLabel = "Continue";
	PlayerConfiguration.BtnExitLabel = "Exit";
	PlayerConfiguration.BtnExitAllLabel = "Exit All";
	PlayerConfiguration.BtnAbandonLabel = "Abandon";
	PlayerConfiguration.BtnAbandonAllLabel = "Abandon All";
	PlayerConfiguration.BtnSuspendAllLabel = "Suspend All";
	
	var win = window;
	if(win.API)
  		ScromAPI=win.API;
  	if(ScromAPI){
  		ScromAPI.LMSInitialize=function(param){
			ScromAPI.data={
				'cmi.core._children':'student_id,student_name,lesson_status,lesson_location,lesson_mode,score,credit,entry,exit,session_time,total_time',
				'cmi.core.score._children':'raw',
				'cmi.core.ware_id':"<?php echo isset($couse_ware_detail['CourseClassWare']['id'])?$couse_ware_detail['CourseClassWare']['id']:'0'; ?>",
				'cmi.core.student_id':"<?php echo isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0; ?>",
				'cmi.core.student_name':"<?php echo isset($_SESSION['User'])?$_SESSION['User']['User']['first_name']:''; ?>",
				'cmi.core.lesson_status':'not attempted',
				'cmi.core.total_time':'0',
				'cmi.core.score.raw':'',
				'cmi.core.lesson_location':'',
				'cmi.suspend_data':'',
				'cmi.core.session_time':'0000:00:00.00',
				'cmi.core.credit':'credit', /* "credit" or "no-credit" */
				'cmi.core.entry':'ab-initio', /* "resume" or "ab-initio" */
				'cmi.core.lesson_mode':'normal', /* "browse",  "normal" or "review" */
				'cmi.core.exit':'' /* "time-out", "suspend" or "logout" */
			};
			return ScromAPI.$0.LMSInitialize(param);
		}
		
		ScromAPI.LMSCommit=function(param){
			ajax_course_scrom(ScromAPI.data);
			return ScromAPI.$0.LMSCommit(param);
		}
		
		ScromAPI.LMSSetValue=function(element,value){
			ScromAPI.data[element]=value;
			return ScromAPI.$0.LMSSetValue(element,value);
		}
  	}
	Run.ManifestByURL(scrom_link, true);
}

function ajax_course_scrom(requestData){
	if(RequestFailedCount>10){
		console.log('RequestFailedCount:'+RequestFailedCount);
		return;
	}
	var xhr = createXHR();
	// 定义xhr对象的请求响应事件
	xhr.onreadystatechange = function() {
	    switch(xhr.readyState) {
	        case 0 :
	            //alert("请求未初始化");
	            break; 
	        case 1 :
	            //alert("请求启动，尚未发送");
	            break;
	        case 2 :
	            //alert("请求发送，尚未得到响应");
	            break;
	        case 3 : 
	            //alert("请求开始响应，收到部分数据");
	            break;
	        case 4 :
	            if((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
				eval("var result="+xhr.responseText);
				if(result.code!='1'){
					RequestFailedCount++;
				}
	            }else {
	                	console.log("Request was unsuccessful : " + xhr.status + " " + xhr.statusText);
	                	RequestFailedCount++;
	            }
	            break;
	    }
	};
	// post请求
	xhr.open("POST", web_base+"/courses/ajax_course_scrom",true);
	// 不支持FormData的浏览器的处理 
	if(typeof FormData == "undefined") {
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	}
	xhr.send(postDataFormat(requestData));
}

/*
 * 统一XHR接口
 */
function createXHR() {
    // IE7+,Firefox, Opera, Chrome ,Safari
    if(typeof XMLHttpRequest != "undefined") {
        	return new XMLHttpRequest();
    }
    // IE6-
    else if(typeof ActiveXObject != "undefined"){
        if(typeof arguments.callee.activeXString != "string") {
            var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXMLHttp"],
            i, len;
            for(i = 0, len = versions.length; i < len; i++) {
                try{
                    new ActiveXObject(versions[i]);
                    arguments.callee.activeXString = versions[i];
                    break;
                }catch(ex) {
                    console.log("请升级浏览器版本");
                }
            }
        }
        return arguments.callee.activeXString;        
    }else {
        throw new Error("XHR对象不可用");
    }
}

// 格式化post 传递的数据
function postDataFormat(obj){
    if(typeof obj != "object" ) {
        console.log("输入的参数必须是对象");
        return;
    }
    // 支持有FormData的浏览器（Firefox 4+ , Safari 5+, Chrome和Android 3+版的Webkit）
    if(typeof FormData == "function") {
        var data = new FormData();
        for(var attr in obj) {
            data.append(attr,obj[attr]);
        }
        return data;
    }else {
        // 不支持FormData的浏览器的处理 
        var arr = new Array();
        var i = 0;
        for(var attr in obj) {
            arr[i] = encodeURIComponent(attr) + "=" + encodeURIComponent(obj[attr]);
            i++;
        }
        return arr.join("&");
    }
}
</script>
</body>
</html>