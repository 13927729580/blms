<?php //pr($evaluation_class_list)?>
<style>
.evaluation_log_zw>.huise:first-child{color:#ccc;}
@media only screen and (max-width: 641px){
	.riqi{padding-bottom:10px;}
	.riqi .font_16{font-size:12px;}
	.riqi .nian{font-size:10px;}
	.evaluation_log .evaluation_log_zw>div:first-child{padding-right:0;}
	.evaluation_log .jiantou{margin-top:16px;}
	.riqi .yue{font-size:12px;}
	.course_ul .yixue{font-size:12px;}
	.course_ul .keshi{font-size:12px;}
	.course_ul .xuexu_but{padding-top:15px;}
	.course_ul .kc_xx{padding:5px 0;line-height: inherit;height: inherit;width:60px;font-size:12px;}
	.minw{
		min-width: 95%;
		margin-left:-47.5%;
	}
}
#course_chapter_list .admin-user-img{display:none;}
.nian{font-size: 14px;color: #ccc;}
.riqi{color:#888888;}
.usercenter_fu .evaluation_log{margin:20px 0 50px 10px;border:1px solid #ccc;border-radius:3px;box-shadow: 0 0 15px #ccc;padding: 10px 20px 30px 20px;}
.evaluation_log>div:first-child{border-bottom: 1px solid #ccc;}
.xinxi{padding-bottom:30px;}
.xinxi>div:first-child{float:left;padding-right:18px;}	
h3{font-size: 25px;color: #424242;padding: 5px 0;font-weight: 500;}
.evaluation_log_tab{font-size:16px;color:#424242;}
.evaluation_log_zw{padding-top:10px;}
.evaluation_log_zw>div:first-child{color:#49a864;float:left;padding-right:60px;font-size:14px;}
.evaluation_log_zw>div:last-child{color:#424242;}
.jiantou{margin-top:40px;}
.kongbai{padding:20px 0;text-align:center;color: #999;height:120px;}
.evaluation_log{margin: 20px 0 50px 10px;border: 1px solid #ccc;border-radius: 3px;box-shadow: 0 0 15px #ccc;padding: 10px 20px 30px 20px;}
.evaluation_log .am-g h3{height: 50px;line-height: 38px;}

a.am-btn-success:visited{color:#149941;}
.am-btn-success{background-color:#fff;color:#149941;}
.am-btn-success:hover{background-color:#fff;color:#149941;}
.course_ul .xinxi{/*border-bottom:1px solid #ccc;*/padding-bottom:30px;padding-right:20px;}
.xinxi>div:first-child{float:left;padding-right:18px;}
.riqi{color:#888888}
.tab_name{color:#434343;}
.usercenter_fu .course_log{padding: 10px 0 30px 20px;margin: 20px 0 50px 10px;border: 1px solid #ccc;box-shadow: 0 0 15px #ccc;border-radius: 3px;}
h3{font-size: 25px;color: #424242;padding: 5px 0;font-weight: 500;}
.kc_xx{padding: 0 0;line-height: 40px;display: inline-block;width: 100px;height: 40px;text-align: center;font-size: 16px;color: #12873a;border: 1px solid #12873a;border-radius: 5px;}
.course_log>div:first-child{border-bottom: 1px solid #ccc;}
.course_ul>li{border:none;padding-top:30px;}
.neirong{padding-bottom:20px;font-size:16px;color:#424242;}
.yixue{color:#149940;font-size:14px}
.xuexu_but{padding-top:5%;}
.keshi{font-size:14px;}
.course_log{margin: 20px 0 0px 10px;border: 1px solid #ccc;border-radius: 3px;box-shadow: 0 0 15px #ccc;padding: 10px 20px 10px 20px;}
.course_log .am-g h3{height: 50px;line-height: 38px;}
.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active {background-color: #fff;}
div.course_log ul.am-list li{border-top:none;}
div.course_log ul.am-list li img{max-width:100%;max-height:100%;}
.kongbai{padding:20px 0;text-align:center;font-size: 14px;color: #999;height:120px;}

.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active {
	background-color: #fff;
}
/*.am-tabs-bd_1{border-style: none;}
.am-nav-tabs_1>li.am-active>a, .am-nav-tabs_1>li.am-active>a:focus, .am-nav-tabs_1>li.am-active>a:hover {border-style: none;background-color: #3bb4f2;color: #fff;}
.am-nav-tabs_1 {border-style: none;}
.am-nav-tabs_1>li>a:hover {background-color: #3bb4f2;color: #fff;}*/
/*.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover{box-shadow: 0 0 15px #ccc;}*/
/*.am-tabs-bd{box-shadow: 0 0 15px #ccc;}*/
.course_log{box-shadow: 0 0 0 0;border-style: none;}

.am-selected{
    width:100%;
  }

#com_chose li a{color:#aaa;}

/*.am-nav-tabs>li{margin-bottom: 0px;}
.am-nav-tabs>li.am-active{margin-bottom: 1px;}*/
/*.am-active{margin-bottom: -1px!important;}*/
a{color:#ccc;}
#csv a{color:#0e90d2;}

.am-nav-tabs>li>a,.am-tabs-nav,.am-tabs-bd,.am-tabs-bd .am-tab-panel,.am-tabs-bd,.am-tabs-bd .am-tab-panel.am-active{border:none;}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover{border:none;}
</style>
<input type="hidden" id="eval_id" value="">
<input type="hidden" id="mem_flag" value="0">
<input type="hidden" id="user_share_check" value="0">

<div class="am-tabs" data-am-tabs="{noSwipe: 1}" id="doc-tab-demo-1">
	<ul class="am-tabs-nav am-nav am-nav-tabs am-nav-tabs_1">
		<li class="am-active"><a href="javascript: void(0)">参加的评测</a></li>
	</ul>
	<div class="am-tabs-bd  am-tabs-bd_1">
		<div class="am-tab-panel am-active">
			<div class='am-g course_log' style="margin-top: 0;">
				<ul class='am-list course_ul'>
					<?php if(isset($evaluation_study)&&sizeof($evaluation_study)>0){foreach($evaluation_study as $v){ ?>
					<li>
						<div class="am-g">
						<div class="am-u-lg-12 am-u-sm-12 am-u-md-12" style="padding-left: 0;border-bottom:1px solid #ccc;">
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-12 riqi" style="padding:0;">
								<div  style="margin-bottom: 10px;" class="nian"><?php echo date("Y",strtotime($v['UserEvaluationLog']['submit_time'])); ?></div>
								<div class="font_16" style="margin-bottom: 10px;"><?php echo date("m月d日",strtotime($v['UserEvaluationLog']['submit_time'])); ?>
								</div>
							</div>
							<div class="am-u-lg-11 am-u-md-10 am-u-sm-12 xinxi" style="padding-left: 0;">
								<div class="am-u-md-3 am-u-sm-3" style="width: 25%;padding-left: 0;height: 110px;padding:2px;border:1px solid #ccc;text-align: center;line-height: 100px;">
								<a target="_blank" href="<?php echo $html->url('/evaluations/view/'.$v['UserEvaluationLog']['evaluation_id']); ?>"><?php echo $html->image($v['Evaluation']['img']!=''?$v['Evaluation']['img']:"/theme/default/images/default.png",array('title'=>$user_list['User']['name'],'style'=>'margin-left:7px;')); ?></a>
								</div>
								<div class="am-u-lg-7 am-u-sm-7 am-u-md-7">
									<a target="_blank" href="<?php echo $html->url('/evaluations/view/'.$v['UserEvaluationLog']['evaluation_id']); ?>"><div class="am-g evaluation_log_tab"><?php echo $v['Evaluation']['name']; ?></div></a>
									<div class="am-g evaluation_log_zw">
										<div style="padding:0;" class="am-u-sm-6 am-u-lg-6 am-u-md-6 <?php echo $v['UserEvaluationLog']['score']>$v['Evaluation']['pass_score']?'':'huise'?>">
										<?php echo $v['UserEvaluationLog']['score']>$v['Evaluation']['pass_score']?'通过':'未通过'?>
										</div>
										<div class="am-u-sm-6 am-u-lg-6 am-u-md-6" style="padding: 0;">
											得分:<?php echo $v['UserEvaluationLog']['score']; ?>
										</div>
									</div>
								</div>
								<div style="padding:0;margin-top: 0px;" class="jiantou am-u-lg-2 am-u-sm-1 am-u-md-1" >
									<a style="margin-left: 5px;margin-top: 5px;color: #fff;padding:7px 10px;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/user_evaluation_logs/view/'.$v['UserEvaluationLog']['id']); ?>" title="查看评测" target="_blank">
										<span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span>
									</a>
							        <?php if($v['Evaluation']['evaluation_type']=='0'){ ?>
								        <a style="margin-left: 5px;margin-top: 5px;padding:7px 10px;" class="mt am-btn am-btn-danger am-seevia-btn-add am-btn-sm am-radius" href="javascript:;" onclick="list_delete_submit(web_base+'/evaluations/remove_study/<?php echo $v['UserEvaluationLog']['id'] ?>');" title="删除评测">
								            <span class="am-icon-trash-o" style="width: 14px;height: 14px;"></span>
								        </a>
								 <?php } ?>
								</div>
								<div class="am-cf"></div>
							</div>
						</div>
					</li>
					<?php }}else{ ?>
						<div class="am-text-center" style="padding-top: 10px;">暂无评测</div>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<style type='text/css'>
div.evaluation_log ul.am-list li{border-top:none;padding-top:30px;}
div.evaluation_log ul.am-list li img{max-width:100%;max-height:100%;}
div.evaluation_log ul.am-list li .jiantou_img{width:20px;height:30px;}
</style>
<script>
var checkorg = new Array();
<?php if(isset($check_org)&&count($check_org)>0){foreach ($check_org as $k3 => $v3) { ?>
	checkorg["<?php echo $k3 ?>"] = '<?php echo $v3 ?>';
<?php }} ?>
function list_delete_submit(sUrl){
	var aa = function(){
		$.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    //alert(result.message);
                    window.location.reload();
                }
                if(result.flag==2){
                    seevia_alert(result.message);
                }
            }
        });
	}
	seevia_alert_func(aa,"确定删除？");
}

function ajax_user_share(obj){
	var postData = $(obj).parent().parent().serialize();
	console.log($(obj).parent().parent());
	//$(obj).attr("disabled", true);
	var eval_id = $("#eval_id").val();
	$('input[name="data[user_mobile][]"]').each(function(){
		if($(this).val() != ''){
			$('#user_share_check').val(1);
		}
	});
	if($('#user_share_check').val() == 1){
		$(obj).attr('disabled',true);
		//alert(user_mobile);
		$.ajax({
			type: "POST",
			url: web_base+"/evaluations/ajax_user_share/"+eval_id,
	        dataType: 'json',
	        data:postData,
	        success: function (data) {
	            if(data.message == ''){
	            	// alert('邀请成功！');
	            	// window.location.reload();
	            	$("#class_share").modal('close');
	            	seevia_alert_func(jump_reload,'邀请成功！');
	            }else{
	            	seevia_alert(data.message);
	            	$(obj).attr('disabled',false);
	            }
	        }
	    });
	}else{
		seevia_alert('请输入至少一个手机号码！');
	}
	
}

function get_depart(obj){

	if($(obj).val() != ''){
		$("#cour_depart").html('<option value="">请选择部门</option>');
	$("#mem_info").html('');
	$.ajax({
		type: "POST",
		url: web_base+"/evaluations/get_depart",
        dataType: 'json',
        data:{'org_id':$(obj).val()},
        success: function (data) {
            
            for(var i =0;i<data.length;i++){
				var depart_info = '<option value="'+data[i].OrganizationDepartment.id+'">'+data[i].OrganizationDepartment.name+'</option>';
				$("#cour_depart").append(depart_info);	
			}
        }
    });
	}
}

function get_job(obj){
	$("#cour_job").html('<option value="">请选择职位</option>');
	$("#mem_info").html('');
	if($(obj).val() != ''){
		$.ajax({
			type: "POST",
			url: web_base+"/evaluations/get_job",
	        dataType: 'json',
	        data:{'depart_id':$(obj).val()},
	        success: function (data) {
	            
	            for(var i =0;i<data.length;i++){
					var job_info = '<option value="'+data[i].OrganizationJob.id+'">'+data[i].OrganizationJob.name+'</option>';
					$("#cour_job").append(job_info);	
				}
	        }
	    });
	}
}

function get_job_mem(obj){
	$("#mem_info").html('');
}

function share(obj){
	var checkboxes = new Array();
	var eval_id = $("#eval_id").val();
	var org_id = $("#cour_com").val();
	var depart_id = $("#cour_depart").val();
	var job_id = $("#cour_job").val();
	var bratch_operat_check = document.getElementsByClassName("mem-check");
	var jump_url = function(){
		window.location.reload();
	}
	$("#mem_flag").val(0);
	if(job_id == ''){
		$(".mem-check").each(function(){
			if($(this).is(":checked") == false){
				$("#mem_flag").val(1);
			}
		});
	}else{
		$("#mem_flag").val(1);
	}
	if($("#mem_flag").val() == 1){
		for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				checkboxes.push(bratch_operat_check[i].value);
			}
		}
		if(checkboxes.length == 0){
			//alert('您还没有选择成员！');
			$("#class_share .org-warning").html('*您还没有选择成员！');
			return;
		}
		$(obj).attr("disabled", true); 
		//console.log(checkboxes);
		$.ajax({
			type: "POST",
			url: web_base+"/evaluations/share",
	        dataType: 'json',
	        data:{'mem_id':checkboxes,'eval_id':eval_id},
	        success: function (data) {
	        	if(data.code == 1){
	        		//alert('邀请成功！');
	        		//window.location.reload();
	        		seevia_alert_func(jump_url,'邀请成功！');
	        	}
	        }
	    });
	}else if($("#mem_flag").val() == 0){
		if($("#cour_com").val() == ''&&$("#cour_depart").val() == ''&&$("#cour_job").val() == ''){
			seevia_alert('邀请的对象不能为空！');
		}else if($("#cour_depart").val() == ''){
			$(obj).attr("disabled", true); 
			$.ajax({
				type: "POST",
				url: web_base+"/evaluations/share",
		        dataType: 'json',
		        data:{'org_id':org_id,'eval_id':eval_id},
		        success: function (data) {
		        	if(data.code == 1){
		        		// alert('邀请成功！');
		        		// window.location.reload();
		        		seevia_alert_func(jump_url,'邀请成功！');
		        	}else{
		        		seevia_alert(data.message);
		        		$(obj).attr("disabled", false); 
		        	}
		        }
		    });
		}else{
			for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				checkboxes.push(bratch_operat_check[i].value);
				}
			}
			$(obj).attr("disabled", true); 
			$.ajax({
				type: "POST",
				url: web_base+"/evaluations/share",
		        dataType: 'json',
		        data:{'depart_id':depart_id,'eval_id':eval_id,'depart_mem_id':checkboxes},
		        success: function (data) {
		        	if(data.code == 1){
		        		// alert('邀请成功！');
		        		// window.location.reload();
		        		seevia_alert_func(jump_url,'邀请成功！');
		        	}else{
		        		seevia_alert(data.message);
		        		$(obj).attr("disabled", false); 
		        	}
		        }
		    });
		}
	}
	
}

function chose_eval(eval_id){
	$("#eval_id").val(eval_id);
	$("input[name='eval_id']").val(eval_id);
}

function change(obj){
	$(obj).css('color','#19a7f0');
	$(obj).parent().siblings().find('a').css('color',"#aaa");
	if($(obj).attr('id') == 'per'){
		$("#class_share #first_panel").removeClass("am-active");
		$("#class_share #third_panel").removeClass("am-active");
		$("#class_share #forth_panel").removeClass("am-active");
		$("#class_share #second_panel").addClass("am-active");
	}else if($(obj).attr('id') == 'org'){
		$("#class_share #third_panel").removeClass("am-active");
		$("#class_share #second_panel").removeClass("am-active");
		$("#class_share #forth_panel").removeClass("am-active");
		$("#class_share #first_panel").addClass("am-active");
	}else if($(obj).attr('id') == 'batch'){
		$("#class_share #first_panel").removeClass("am-active");
		$("#class_share #forth_panel").removeClass("am-active");
		$("#class_share #second_panel").removeClass("am-active");
		$("#class_share #third_panel").addClass("am-active");
	}else if($(obj).attr('id') == 'thirdparty'){
		$("#class_share #first_panel").removeClass("am-active");
		$("#class_share #third_panel").removeClass("am-active");
		$("#class_share #second_panel").removeClass("am-active");
		$("#class_share #forth_panel").addClass("am-active");
	}
}

function checkFile() {
	var obj = document.getElementById('batch_file');
	var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
	if(suffix != 'csv'&&suffix != 'CSV'){
 		seevia_alert("文件格式错位！需要是CSV格式！");
 		obj.value="";
 		return false;
	}else{
		ajaxFileUpload();
	}
}

function ajaxFileUpload(){
	var eval_id = $("#eval_id").val();
	//var img_id = $('input[name="file"]').attr('id');
	var input_file = $('#batch_file')[0].files[0];
	var formData = new FormData();
	formData.append("file", input_file);
	formData.append("eval_id", eval_id);
	var xhr = null;
        if (window.XMLHttpRequest){// code for all new browsers
            xhr=new XMLHttpRequest();
        }else if (window.ActiveXObject){// code for IE5 and IE6
            xhr=new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            seevia_alert("Your browser does not support XMLHTTP.");return false;
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
	                //eval("var result="+xhr.responseText);
	                //console.log(xhr.responseText);
	                $("#third_panel").html(xhr.responseText);
	                $("#third_panel input[type='checkbox']").uCheck();
            }
        };
        xhr.onerror=function(evt){
            //console.log(j_object_transform_failed);
        };
        xhr.open("POST", web_base+'/evaluations/batch_share');
        xhr.send(formData);
}

function batch_invite(){
	var eval_id = $("#eval_id").val();
	var postData = $("#b_invite").serialize();
	sUrl = web_base+"/evaluations/batch_share/"+eval_id;
	$.ajax({
		type: "POST",
		url: web_base+"/evaluations/batch_share/"+eval_id,
        dataType: 'json',
        data:postData,
        success: function (data) {
        	if(data.code == 1){
        		//alert('邀请成功！');
        		//window.location.reload();
        	}
        }
    });
}

	$(function(){
		if(document.getElementById('msg')){
			var msg =document.getElementById('msg').value;
            if(msg !=""){
                seevia_alert(msg);
                var button=document.getElementById('btnouterlist');
                button.style.display="none";
            }
		}
	});

	function ajax_batch_share(obj){
		var eval_id = $("#eval_id").val();
		var postData = $("form[name='theDateForm']").serialize();
		var jump = function(){
			window.location.href=web_base+'/user_evaluation_logs/index';
		}
		$(obj).attr('disabled',true);
		$.ajax({
            type: "POST",
            url: web_base+'ajax_batch_share/'+eval_id,
            dataType: 'json',
            data:postData,
            success: function (data) {
                if(data.message == ''){
                	$("#class_share").moda('close');
            		seevia_alert_func(jump,'邀请成功！');
            	
	            }else{
	            	seevia_alert(data.message);
	            	$(obj).attr('disabled',false);
	            }
            }
        });
	}

	function batch_chose(obj){
		if($(obj).is(':checked')){
			$(".update_chose").uCheck('check'); 
		}else{
			$(".update_chose").uCheck('uncheck');
		}
	}

	function cancel_upload(){
		$.ajax({
	        type: "POST",
	        url: web_base+'/user_evaluation_logs/index',
	        dataType: 'html',
	        data:{},
	        success: function (data) {
	            var HtmlDiv=document.createElement('div');
	            HtmlDiv.innerHTML=data;
	            var content=$(HtmlDiv).find('#third_panel').html();
	            //alert(content);
	            $("#third_panel").html(content);
	        }
	    });
	}

	function search_org(obj){
		var reg = /^1[3|4|5|7|8][0-9]{9}$/;
		var phone = $("#search_phone").val();
		var content = '<option value="">请选择</option>';
		if(phone == ''){
			seevia_alert('手机号不能为空！');
			return false;
		}else if(reg.test(phone) == false){
			seevia_alert('手机号码格式有误！');
			return false;
		}
		$.ajax({
            type: "POST",
            url: web_base+'/evaluations/ajax_search_org',
            dataType: 'json',
            data:{'mobile':phone},
            success: function (data){
                for (var i = 0; i < data.length; i++){
                	content += '<option value="'+data[i].Organization.id+'">'+data[i].Organization.name+'</option>';
                };
                $("#org_select").html(content);
                $("#org_select").parent().show();
                $(obj).siblings('button').show();
            }
        });
	}

	function org_manager_invite(obj){
		var eval_id = $("#eval_id").val();
		var org_id = $("#org_select").val();
		$(obj).attr('disabled',true);
		$.ajax({
            type: "POST",
            url: web_base+'/evaluations/org_manager_invite',
            dataType: 'json',
            data:{'org_id':org_id,'eval_id':eval_id},
            success: function (data){
                if(data.code == 1){
                	// alert('邀请成功！');
                	// window.location.reload();
                	seevia_alert_func(jump_reload,'邀请成功！');
                }else{
                	seevia_alert(data.message);
                	$(obj).attr('disabled',false);
                }
            }
        });
	}

	function org_name_invite(obj){
		var eval_id = $("#eval_id").val();
		var org_name = $("#search_name").val();
		$(obj).attr('disabled',true);
		$.ajax({
            type: "POST",
            url: web_base+'/evaluations/org_name_invite',
            dataType: 'json',
            data:{'org_name':org_name,'eval_id':eval_id},
            success: function (data){
                if(data.code == 1){
                	// alert('邀请成功！');
                	// window.location.reload();
                	seevia_alert_func(jump_reload,'邀请成功！');
                }else{
                	seevia_alert(data.message);
                	$(obj).attr('disabled',false);
                }
            }
        });
	}

	function search_org_mem(){
		var postData = new Object();
		if($("#cour_job").val()!=''){
			postData['job_id'] = $("#cour_job").val();
		}else if($("#cour_depart").val()!=''){
			postData['depart_id'] = $("#cour_depart").val();
		}else if($("#cour_com").val()!=''){
			postData['org_id'] = $("#cour_com").val();
		}

		$.ajax({
			type: "POST",
			url: web_base+"/evaluations/get_mem",
			dataType: 'json',
			data:postData,
			success: function (data) {
				$("#mem_info").html('');
				for(var i =0;i<data.length;i++){
					var mem_info = '<label class="am-checkbox"><input type="checkbox" data-am-ucheck class="mem-check" value="'+data[i].OrganizationMember.id+'">'+data[i].OrganizationMember.name+(typeof(data[i].OrganizationMember.mobile)!='undefined'&&data[i].OrganizationMember.mobile.trim()!=''?(' - '+data[i].OrganizationMember.mobile):'')+(typeof(data[i].OrganizationMember.depart)!='undefined'&&data[i].OrganizationMember.depart.trim()!=''?(' - '+data[i].OrganizationMember.depart):'')+'</label>';
					$("#mem_info").append(mem_info);	
				}
				$("input[type='checkbox'], input[type='radio']").uCheck('check');
			}
	    });
	}
	
	function adduce_evaluation(){
        $.ajax({
            type: "POST",
            url:web_base+'/evaluations/adduce/',
            dataType: 'json',
            data: {},
            success: function (data) {
                if(data.code=='1'){
                    //console.log(data.message);
                    for(var i = 0;i<data.message.length;i++){
                    	console.log(data.message[i]);
                    	$("#adduce_evaluation_select option").remove();
                    	$('#adduce_evaluation_select').append('<option value="">请选择评测</option>');
                    	$('#adduce_evaluation_select').append('<option value="'+data.message[i]['Evaluation']['id']+'">'+data.message[i]['Evaluation']['name']+'</option>');
                    }
                }else{
                    //alert('获取失败');
                }
            }
        })
    }

    get_invite_mem();
    function get_invite_mem(){
    	$.ajax({
            type: "POST",
            url:web_base+'/courses/get_invite_mem/',
            dataType: 'json',
            data: {},
            success: function (data) {
                var content = '';
                for(var i=0;i<data.length;i++){
                	content += '<option value="'+data[i].Organization.id+'">'+data[i].Organization.name+'</option>';
                }
                $("#cour_com").append(content);
            }
        })
    }

    function add_evaluation(){
    	var evaluation_id = $('#adduce_evaluation_select').val();
		if(evaluation_id==''){
			seevia_alert('请选择课程！');
			return false;
		}
    	$.ajax({
            type: "POST",
            url:web_base+'/evaluations/adduce/',
            dataType: 'json',
            data: {'evaluation_id':evaluation_id},
            success: function (data) {
                if(data.code=="1"){
                	//window.location.reload();
                	window.location.href=web_base+'/evaluations/edit/'+data.message;
                }else{
                	seevia_alert('引用课程失败！');
                }
            }
        })
    }

    function add_class(btn){
    	$(btn).addClass('am-active');
    }

    function del_class(btn){
    	$(btn).removeClass('am-active');
    }
    // give_border();
    // function give_border(){
    // 	$(".am-nav-tabs>li:not(.am-active)").css('margin-bottom','0px');
    // }

	$(document).ready(function(){
		if($(window).width()<600){
			$('.course_log').css('padding','10px 0');
		}else{
			$('.course_log').css('padding','10px 20px');
		}
	})
	$(window).resize(function(){
	    if($(window).width()<600){
			$('.course_log').css('padding','10px 0');
		}else{
			$('.course_log').css('padding','10px 20px');
		}
	});
</script>