
<?php //pr($this->layout);?>
<style>
/*小屏*/
@media only screen and (max-width: 640px)
{	
	body #user_experience_detail{width:100%;left:0;}
	body #user_experience_detail>form{padding:10px 10px;}
	.user_resume .jiaoyu_jinyan>img{width:220px;}
	.usercenter_fu .user_resume>div>.am-nav>li>a{font-size:14px;height:40px;line-height:40px;width:80px;}
	.usercenter_fu .top_tab{font-size:12px;padding-bottom:15px;}
	.usercenter_fu .font_14{padding-bottom:20px;}
	#user_resume_detail ul.am-list li a{font-size:14px;}
}
	.am-tabs-bd .am-tab-panel
{
padding:12px 0 12px 10px;
}
	.am-nav-tabs>li>a
{
margin-right:0;
}
		#course_chapter_list .admin-user-img
{
display:none;
}

.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
color:#149941;
}
.xiantiao
{
border-bottom:1px solid #149842;
}
.am-tabs-bd
{
border:none;
}
.usercenter_fu .user_resume
{
margin:20px 0 50px 10px;
border:1px solid #ccc;
border-radius:3px;
box-shadow: 0 0 15px #ccc;
padding: 10px 20px 30px 20px;
}
.am-nav-tabs
{
    border-bottom: 1px solid #ddd;
}
.user_resume>div>.am-nav>li>a
{
    padding: .0 0;
    width: 100px;
    height: 46px;
    line-height: 46px;
    text-align: center;
    font-size: 16px;
}
.am-panel-bd
{
padding:0 0;
}
/*按钮样式*/
.work_jy
{
border:1px dashed #129841; 
padding:5px 80px;

}
.am-btn-success
{
background-color:#c3e4cf;
color:#149a41;
}
.am-btn-success:hover
{
background-color:#c3e4cf;
color:#149a41;
}
.am-btn-success:focus
{
background-color:#c3e4cf;
color:#149941;;
}
.am-btn.am-radius
{
border-radius:4px;
}
/*头部选项卡样式*/
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	border:none;
}
#user_experience
{
padding:20px 0;
}
#user_education
{
padding:20px 0;
}
/*添加工作经验*/
#user_experience_detail
{
padding-top:0px;
}
#user_experience_detail>form
{
    padding: 10px 0;
    margin-bottom:50px;
}
.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active
{
	background-color:#fff;
}
/*添加教育经历*/
#user_education_detail
{
padding-top:20px;
}
#user_education_detail>form
{
    padding: 20px 0;
    margin-bottom:50px;
}

.am-form-detail select
{
width:100%;
}
#user_education_list
{
color:#149941;
}
.tab_header>li>a
{
color:#434343;
}
.user_resume .tab_header>li>a:hover{color:#149941;}
.top_tab
{
color:#149941;font-size:18px;padding-bottom:25px;
}
.top_tab>div:first-child
{
padding:0 .5em 0 0;
}
#user_resume_tab>ul>li.am-active{border-bottom:1px solid #149842;}

.delete{color:#808080;}
.font_14{padding-bottom:50px;padding-left:15px;}
.font_14>div:first-child{padding-bottom:10px;}
.xiugai{color:#808080;}
.jiaoyu_jinyan,.work_jinyan>img:hover
{
cursor:pointer;
}
.jiaoyu_jinyan>img
{
width:300px;
max-height:50px;
}

.delete_2{color:red;}
.xiugai_2{color:red;}
.user_resume>div>.am-nav>li>a:hover{background:#fff;border-color:#fff;}
a:hover{color:#149941;}

</style>
<div class="am-g user_resume">
	<div data-am-tabs="{noSwipe: 1}" id="user_resume_tab" class="am-u-sm-12 am-fr am-tabs">
		  <ul class="am-tabs-nav am-nav am-nav-tabs tab_header">
		    <li class="am-active"><a href="#user_experience" >工作经验</a></li>
		    <li ><a  href="#user_education">教育经历</a></li>
		    <li><a  href="#user_works">我的作品</a></li>
		  </ul>
		
		<div class="am-tabs-bd" id="user_resume_detail">
			<!--工作经验开始-->
			<div id="user_experience" class="am-tab-panel am-active">
				
				<div class="am-panel-collapse am-collapse am-in">
			      	<div class="am-panel-bd">
			      		<div class='am-text-right' style="padding-bottom:20px;">
							<button type="button" class="work_jinyan am-btn am-btn-warning am-radius am-btn-xs addbutton" onclick='user_experience_detail(0);' >
								<span class="am-icon-plus">&nbsp;添加</span>
							</button>
			    		</div>
			    		
			    			<?php
			    				$job_type=isset($informationresource_infos['job_type'])?$informationresource_infos['job_type']:array();
			    			?>
			      		<ul class='am-list' id='user_experience_list'>
			    				<?php foreach($UserExperience_list as $v){ ?>
			      			<li>
			      				<div class='am-g'>
				    					<div class='am-u-lg-10  am-u-md-9 am-u-sm-12 top_tab'>
				    						<div class='am-fl'><?php echo $v['UserExperience']['company_name']; ?></div>
				    						<div class='am-fl'><?php echo isset($job_type[$v['UserExperience']['position']])?$job_type[$v['UserExperience']['position']]:'-'; ?></div>
				    						<div class='no_border am-fl'><?php echo date("Y年m月",strtotime($v['UserExperience']['start_time'])); ?>-<?php echo $v['UserExperience']['end_time']!=''?date("Y年m月",strtotime($v['UserExperience']['end_time'])):'至今'; ?></div>
				    						<div class='am-cf'></div>
				    					</div>
				    					<div class='am-u-lg-2 am-u-md-3 am-u-sm-12 am-text-right am-text-right'>
				    						<a class="delete"   href='javascript:void(0);' onclick="user_experience_detail(<?php echo $v['UserExperience']['id']; ?>)"><?php echo $ld['modify']; ?></a><a class="delete_2" href='javascript:void(0);' onclick="user_experience_remove(<?php echo $v['UserExperience']['id']; ?>)"><?php echo $ld['delete']; ?></a>
				    					</div>
				    					<div class='am-cf'></div>
			    					</div>
			    					<div class='am-g font_14' >
			    						<div>工作内容:</div>
			    						<div style="line-height:1.3;"><?php echo strlen(str_replace(chr(13),'<br >',str_replace(chr(32),'&nbsp;',$v['UserExperience']['description'])))>150?mb_substr(str_replace(chr(13),'<br >',str_replace(chr(32),'&nbsp;',$v['UserExperience']['description'])),0,150,'utf-8').'...':str_replace(chr(13),'<br >',str_replace(chr(32),'&nbsp;',$v['UserExperience']['description'])); ?></div>
			    					</div>
			    				</li>
			    				<?php } ?>
			      		</ul>
			      	
			      	</div>
			       </div>
			</div>
			<!--教育经历开始-->
			<div id="user_education" class="am-tab-panel">
				<div class="am-panel-collapse am-collapse am-in">
			      	<div class="am-panel-bd am-form-detail">
			      		<div class='am-text-right' style="padding-bottom:20px;">
			      			<button class="jiaoyu_jinyan am-btn am-btn-warning am-radius am-btn-xs addbutton" onclick='user_education_detail(0);'>
								<span class="am-icon-plus">&nbsp;添加</span>
							</button>
			    		</div>
			    			<?php
			    				$education_type=isset($informationresource_infos['education_type'])?$informationresource_infos['education_type']:array();
			    			?>
			      		<ul class='am-list' id='user_education_list'>
			      			<?php foreach($UserEducation_list as $v){ ?>
			      			<li>
			      				<div class='am-g'>
				    					<div class='am-u-lg-10 am-u-md-9 am-u-sm-12' style="line-height:1.3;">
				    						<div class='am-fl' style="width:200px;white-space:nowrap; word-break:keep-all; overflow:hidden; text-overflow:ellipsis;"><?php echo $v['UserEducation']['school_name']; ?></div>
				    						<div class='am-fl' style="width:200px;white-space:nowrap; word-break:keep-all; overflow:hidden; text-overflow:ellipsis;"><?php echo $v['UserEducation']['major_type']; ?></div>
				    						<div class='am-fl'><?php echo isset($education_type[$v['UserEducation']['education_id']])?$education_type[$v['UserEducation']['education_id']]:'-'; ?></div>
				    						<div class='am-fl no_border'><?php echo   $v['UserEducation']['start_time']; ?>年-<?php echo $v['UserEducation']['end_time']; ?>年</div>
				    						<div class='am-cf'></div>
				    					</div>
				    					<div class='am-u-lg-2 am-u-md-3 am-u-sm-12 am-text-right'>
				    						<a  class="xiugai"  href='javascript:void(0);' onclick="user_education_detail(<?php echo $v['UserEducation']['id']; ?>)"><?php echo $ld['modify']; ?></a>
				    						<a  class="xiugai_2"  href='javascript:void(0);' onclick="user_education_remove(<?php echo $v['UserEducation']['id']; ?>)">删除</a>
				    					</div>
				    					<div class='am-cf'></div>
			    					</div>
			    				</li>
			    				<?php } ?>
			      		</ul>
			      	</div>
			       </div>
			</div>
			<!--教育经历结束-->
			<!--我的作品开始-->
			<div id="user_works" class="am-tab-panel">
				
			</div>
			<!--我的作品结束-->
		</div>
	</div>
	<div class='am-cf'></div>
</div>
<div class='am-g am-modal am-modal-no-btn' id='user_experience_detail'></div>
<style type='text/css'>
#user_experience_detail label.am-checkbox{display:block;}
#user_experience_detail
{
	    padding-top: 0px;
    width: 60%;
    left: 20%;
}
#user_education_detail
{
	    padding-top: 20px;
    width: 60%;
    left: 20%;
}
#user_resume_detail ul.am-list li{border:1px solid #ccc;border-radius:5px;padding:10px 10px 10px 10px;;margin-bottom:20px;}
#user_resume_detail ul.am-list li div.am-fl{border-right:1px solid #dedede;padding:0px 0.5rem;}
#user_resume_detail ul.am-list li div.no_border{border-right:none;color:#ccc;}
#user_resume_detail ul.am-list li a{margin:0px 5px;}


</style>
<script type='text/javascript'>
function user_experience_detail(data_id){
	$.ajax({
		url: web_base+"/user_resumes/view/"+data_id,
		type:"GET",
		dataType:"html", 
		data: {'page_type':'experience'},
		success: function(data){
			$("#user_experience_detail").html(data);
			$('#user_experience_detail').modal('open');
		//	$("#user_experience_detail select").selected({maxHeight:200,btnWidth:'100%'});
			$("#user_experience_detail input[type='checkbox']").uCheck();
		}
	});
}

function user_education_detail(data_id){
	$.ajax({
		url: web_base+"/user_resumes/view/"+data_id,
		type:"GET",
		dataType:"html", 
		data: {'page_type':'education'},
		success: function(data){
			$("#user_experience_detail").html(data);
			$("#user_experience_detail").modal('open');
			//$("#user_education_detail select").selected({maxHeight:200,btnWidth:'100%'});
			$("#user_education_detail input[type='checkbox']").uCheck();
			//$('.jiaoyu_jinyan').find('img').attr('src','/theme/default/img/jianli_3.png');
		}
	});
}


function user_resume_save(btn,data_id){

	var post_form=$(btn).parents("form");
	var post_data_arr=$(post_form).serializeArray();
	var error_count=0;
	$.each(post_data_arr,function(index,item){
		var filed_name=item.name;
		var is_required=$(post_form).find("[name='"+filed_name+"']").attr('required');
		if(is_required=='required'){
			if(item.value.trim()==""){
				error_count++;
				$(post_form).find("[name='"+filed_name+"']").parents("div.am-form-group").addClass('am-form-error');
			}else{
				$(post_form).find("[name='"+filed_name+"']").parents("div.am-form-group").removeClass('am-form-error');
			}
		}
	});
	if(error_count>0){
		return false;
	}
	var post_data=$(btn).parents("form").serialize();
	$.ajax({
		url: web_base+"/user_resumes/view/",
		type:"POST",
		dataType:"JSON", 
		data: post_data,
		success: function(data){
			console.log(data);
			window.location.href='/user_resumes/index/?user_work='+data_id;
			//location.reload();
		}
	});
}

function user_experience_remove(data_id){
	if(confirm(confirm_delete)){
		$.ajax({
			url: web_base+"/user_resumes/remove/"+data_id,
			type:"GET",
			dataType:"JSON", 
			data: {'page_type':'experience'},
			success: function(data){
				if(data.code=='1'){
					window.location.href="/user_resumes/index/?user_work=3";
				}else{
					alert(data.message);
				}
			}
		});
	}
}


function user_education_remove(data_id){
	if(confirm(confirm_delete)){
		$.ajax({
			url: web_base+"/user_resumes/remove/"+data_id,
			type:"GET",
			dataType:"JSON", 
			data: {'page_type':'education'},
			success: function(data){
				if(data.code=='1'){
					window.location.href="/user_resumes/index/?user_work=2";
				}else{
					alert(data.message);
				}
			}
		});
	}
}

user_works();
function user_works(){
	$.ajax( {
	        url: "/user_works/index", 
	        type: "GET",
	        success: function(data){
	        	console.log(data);
	            $("#user_works").html(data);
				$.AMUI.figure.init();
	        }
	});
}
    function GetQueryString(name) {  
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
        var r = location.search.substr(1).match(reg);  
        if (r != null) return unescape(decodeURI(r[2])); return null;  


    }  

if(GetQueryString('user_work')=='1'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:last-child').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_works').addClass('am-active');
}
if(GetQueryString('user_work')=='2'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:nth-child(2)').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_education').addClass('am-active');
}
if(GetQueryString('user_work')=='3'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:first-child').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_experience').addClass('am-active');
}
</script>