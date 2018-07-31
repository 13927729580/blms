<script type='text/javascript'>
function InvitingImg(img){
	var ImgWidth=img.width;
	var ImgHeight=img.height;
	if(ImgWidth>ImgHeight){
		$(img).css('height',ImgWidth+'px');
	}else if(ImgWidth<ImgHeight){
		$(img).css('width',ImgHeight+'px');
	}
}
</script>
<div class='am-g am-padding-top-xs user_project_detail'>
	<?php if(isset($InvitingSource['Operator'])){ ?>
	<div class='am-g am-margin-top-xs'>
		<div class='am-u-lg-4 am-u-md-4 am-hide-sm-only'>&nbsp;</div>
		<div class='am-u-lg-4 am-u-md-4 am-u-sm-12 am-text-center'>
				<img class='am-circle' src="<?php echo trim($InvitingSource['Operator']['avatar'])==''?'/theme/default/img/no_head.png':$InvitingSource['Operator']['avatar']; ?>" onload="InvitingImg(this)" />
				<div class='am-text-center am-margin-top-xs'>课程顾问:<?php echo $InvitingSource['Operator']['name']; ?></div>
		</div>
		<div class='am-u-lg-4 am-u-md-4 am-hide-sm-only'>&nbsp;</div>
		<div class='am-cf'></div>
	</div>
	<?php } ?>
	<div class='am-g'>
		<form method="POST" class="am-form am-form-horizontal">
			<?php if(isset($InvitingSource['Operator'])){ ?>
			<input type='hidden' name="data[UserProject][manager]" value="<?php echo $InvitingSource['Operator']['id']; ?>" />
			<?php } ?>
			<div class='am-form-group'>
				<h2>项目信息</h2>
				<hr class='am-padding-0 am-margin-top-0 am-margin-bottom-xs' />
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-6 am-padding-top-xs'>报名项目</label>
				<div class='am-u-lg-6 am-u-sm-6'><button type='button' class='am-btn am-btn-warning am-btn-xs am-radius am-padding-right-lg am-padding-left-lg' onclick="add_user_project(this)"><?php echo $ld['add']; ?></button></div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<ul class='am-list user_project_list'>
					<?php if(isset($InvitingList)&&!empty($InvitingList)){foreach($InvitingList as $vv){ ?>
					<li>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_code][]" onchange="user_project_source(this)" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<?php if(isset($informationresource_infos['user_project'])&&!empty($informationresource_infos['user_project'])){foreach($informationresource_infos['user_project'] as $k=>$v){
											if(isset($informationresource_infos[$k])&&!empty($informationresource_infos[$k])){
								?>
								<optgroup label="<?php echo $v; ?>">
									<?php 	foreach($informationresource_infos[$k] as $kkk=>$vvv){ ?>
									<option value="<?php echo $kkk; ?>" <?php echo $kkk==$vv?'selected':''; ?>><?php echo $vvv; ?></option>
									<?php		}	?>
								</optgroup>
								<?php
											}else{
								?>
								<optgroup label="<?php echo $v; ?>">
									<option value="<?php echo $k; ?>" <?php echo $k==$vv?'selected':''; ?>><?php echo $v; ?></option>
								</optgroup>
								<?php 	}	}} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_time][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间</option>
								<?php for($project_time_year=date('Y');$project_time_year<=date('Y')+1;$project_time_year++){for($project_time_month=($project_time_year==date('Y')?date('m'):1);$project_time_month<=12;$project_time_month++){$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT); ?>
								<option value="<?php echo $project_time_year.'/'.$project_time_month; ?>"><?php echo $project_time_year.'/'.$project_time_month; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_hour][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间段</option>
								<?php if(isset($informationresource_infos['user_project_time'])&&!empty($informationresource_infos['user_project_time'])){foreach($informationresource_infos['user_project_time'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-2 am-u-sm-6'>
							<select name="data[UserProject][project_site][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>校区</option>
								<?php if(isset($informationresource_infos['user_project_site'])&&!empty($informationresource_infos['user_project_site'])){foreach($informationresource_infos['user_project_site'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-1 am-u-sm-2 am-padding-top-xs'>
							<a class='am-text-danger' href='javascript:void(0);' onclick="remove_user_project(this)"><?php echo $ld['delete']; ?></a>
						</div>
						<div class='am-cf'></div>
					</li>
					<?php }}else{ ?>
					<li>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_code][]" onchange="user_project_source(this)" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<?php if(isset($informationresource_infos['user_project'])&&!empty($informationresource_infos['user_project'])){foreach($informationresource_infos['user_project'] as $k=>$v){
											if(isset($informationresource_infos[$k])&&!empty($informationresource_infos[$k])){
								?>
								<optgroup label="<?php echo $v; ?>">
									<?php 	foreach($informationresource_infos[$k] as $kk=>$vv){ ?>
									<option value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
									<?php		}	?>
								</optgroup>
								<?php
											}else{
								?>
								<optgroup label="<?php echo $v; ?>">
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								</optgroup>
								<?php 	}	}} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_time][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间</option>
								<?php for($project_time_year=date('Y');$project_time_year<=date('Y')+1;$project_time_year++){for($project_time_month=($project_time_year==date('Y')?date('m'):1);$project_time_month<=12;$project_time_month++){$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT); ?>
								<option value="<?php echo $project_time_year.'/'.$project_time_month; ?>"><?php echo $project_time_year.'/'.$project_time_month; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_hour][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间段</option>
								<?php if(isset($informationresource_infos['user_project_time'])&&!empty($informationresource_infos['user_project_time'])){foreach($informationresource_infos['user_project_time'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-2 am-u-sm-6'>
							<select name="data[UserProject][project_site][]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
								<option value=" "><?php echo $ld['please_select']; ?>校区</option>
								<?php if(isset($informationresource_infos['user_project_site'])&&!empty($informationresource_infos['user_project_site'])){foreach($informationresource_infos['user_project_site'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-1 am-u-sm-2 am-padding-top-xs'>
							<a class='am-text-danger' href='javascript:void(0);' onclick="remove_user_project(this)"><?php echo $ld['delete']; ?></a>
						</div>
						<div class='am-cf'></div>
					</li>
					<?php } ?>
					<li>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_code][]" onchange="user_project_source(this)">
								<?php if(isset($informationresource_infos['user_project'])&&!empty($informationresource_infos['user_project'])){foreach($informationresource_infos['user_project'] as $k=>$v){
											if(isset($informationresource_infos[$k])&&!empty($informationresource_infos[$k])){
								?>
								<optgroup label="<?php echo $v; ?>">
									<?php 	foreach($informationresource_infos[$k] as $kk=>$vv){ ?>
									<option value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
									<?php		}	?>
								</optgroup>
								<?php
											}else{
								?>
								<optgroup label="<?php echo $v; ?>">
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								</optgroup>
								<?php 	}	}} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_time][]">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间</option>
								<?php for($project_time_year=date('Y');$project_time_year<=date('Y')+1;$project_time_year++){for($project_time_month=($project_time_year==date('Y')?date('m'):1);$project_time_month<=12;$project_time_month++){$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT); ?>
								<option value="<?php echo $project_time_year.'/'.$project_time_month; ?>"><?php echo $project_time_year.'/'.$project_time_month; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-3 am-u-sm-6'>
							<select name="data[UserProject][project_hour][]">
								<option value=" "><?php echo $ld['please_select']; ?>上课时间段</option>
								<?php if(isset($informationresource_infos['user_project_time'])&&!empty($informationresource_infos['user_project_time'])){foreach($informationresource_infos['user_project_time'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-2 am-u-sm-6'>
							<select name="data[UserProject][project_site][]">
								<option value=" "><?php echo $ld['please_select']; ?>校区</option>
								<?php if(isset($informationresource_infos['user_project_site'])&&!empty($informationresource_infos['user_project_site'])){foreach($informationresource_infos['user_project_site'] as $k=>$v){ ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class='am-u-lg-1 am-u-sm-2 am-padding-top-xs'>
							<a class='am-text-danger' href='javascript:void(0);' onclick="remove_user_project(this)"><?php echo $ld['delete']; ?></a>
						</div>
						<div class='am-cf'></div>
					</li>
				</ul>
			</div>
			<div class='am-form-group'>
				<h2>个人信息</h2>
				<hr class='am-padding-0 am-margin-top-0 am-margin-bottom-xs' />
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>姓名</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[User][first_name]" value="" />
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>性别</label>
				<div class='am-u-lg-6 am-u-sm-11 am-padding-top-xs'>
					<label class="am-radio am-secondary">
						<input type="radio" name="data[User][sex]" value="1" checked data-am-ucheck>男
					</label>
					<label class="am-radio am-secondary">
						<input type="radio" name="data[User][sex]" value="2" data-am-ucheck>女
					</label>
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>身份证号</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[User][identity_card]" value="" />
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>学历</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<select name="data[UserEducation][education_id]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
						<option value="0"><?php echo $ld['please_select']; ?></option>
						<?php if(isset($informationresource_infos['education_type'])&&!empty($informationresource_infos['education_type'])){foreach($informationresource_infos['education_type'] as $k=>$v){ ?>
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>手机</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[User][mobile]" />
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>行业</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<select name="data[UserExperience][company_industry]" data-am-selected="{maxHeight:300,btnWidth: '100%'}">
						<option value=" "><?php echo $ld['please_select']; ?></option>
						<?php if(isset($informationresource_infos['company_type'])&&!empty($informationresource_infos['company_type'])){foreach($informationresource_infos['company_type'] as $k=>$v){ ?>
						<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>单位</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[UserExperience][company_name]" />
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>职位</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[UserExperience][position]" />
				</div>
				<div class='am-fl am-text-danger am-padding-top-xs'>*</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>QQ</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[UserConfig][qq]" value="" />
				</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>邮箱</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<input type='text' name="data[User][email]" value="" />
				</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>备注</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<textarea name="data[UserProject][remark]"></textarea>
				</div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-1 am-u-sm-12 am-padding-top-xs'>&nbsp;</label>
				<div class='am-u-lg-6 am-u-sm-11'>
					<button type='button' class='am-btn am-btn-success am-radius' onclick="user_project_submit(this)">提交</button>
				</div>
				<div class='am-cf'></div>
			</div>
		</form>
	</div>
</div>
<style type='text/css'>
.user_project_detail{max-width:1200px;margin:0 auto;}
.user_project_detail img.am-circle{max-width:100px;max-height:100px;}
.user_project_detail div.am-form-group{margin-bottom:0.5rem;}
.user_project_detail div.am-form-group>div[class*=am-u-]>div.am-selected{width:50%;}
ul.user_project_list{margin-bottom:0px;}
ul.user_project_list>li{margin-bottom:0.5rem;}
ul.user_project_list>li:last-child{display:none;}
ul.user_project_list>li div a:hover{color:#dd514c;}
ul.user_project_list>li input[type='text']{margin-top:5px;}
ul.user_project_list>li select optgroup{font-weight:100;}
ul.user_project_list div.am-selected{max-width:85%;}
ul.user_project_list>li>div[class*=am-u-]:after{content:'*';color:red;padding-top:1rem;padding-left:0.5rem;}
ul.user_project_list>li>div.am-padding-top-xs:after{content:'';}
.user_project_detail form label.am-radio{display:inline-block;padding-top:0px;}
.user_project_detail form textarea{height:150px;resize: none;}
@media only screen and (max-width: 640px){
	.user_project_detail>div.am-g{padding-left:1rem;padding-right:1rem;}
	ul.user_project_list>li div[class*=am-u-]{padding-right:0px;margin-bottom:0.5rem;}
	ul.user_project_list>li{margin-bottom:0.25rem;}
	.user_project_detail div.am-form-group{margin-bottom:0.25rem;}
	.user_project_detail div.am-form-group>div[class*=am-u-]>div.am-selected{width:100%;}
	.user_project_detail input[type='text'],.user_project_detail select{padding:0.3rem 0.5rem;}
	.user_project_detail form label{margin-bottom:0px;}
}
</style>
<script type='text/javascript'>
$(function(){
	$("input.user_project_time_input:visible").each(function(){
		var inputObj=$(this)[0];
		user_project_time_input(inputObj);
	});
});

function user_project_submit(btn){
	var PostForm=$(btn).parents('form');
	var selectedProject=[];
	var EmptyProjectTime=[];
	var EmptyProjectHour=[];
	var EmptyProjectSite=[];
	$("ul.user_project_list li:visible").each(function(){
		var project_code=$(this).find("div:first-child select").val();
		if(project_code!='0'){
			selectedProject.push(project_code);
			var project_name=$(this).find("div:first-child select option:selected").text();
			var project_time=$(this).find('div:nth-child(2) select').val().trim();
			var project_hour=$(this).find('div:nth-child(3) select').val().trim();
			var project_site=$(this).find('div:nth-child(4) select').val().trim();
			if(project_time=='')EmptyProjectTime.push(project_name);
			if(project_hour=='')EmptyProjectHour.push(project_name);
			if(project_site=='')EmptyProjectSite.push(project_name);
		}
	});
	if(selectedProject.length==0){
		seevia_alert('请选择项目');
		return false;
	}
	if(EmptyProjectTime.length>0){
		seevia_alert('请选择项目上课时间:'+EmptyProjectTime.join(','));
		return false;
	}
	if(EmptyProjectHour.length>0){
		seevia_alert('请选择项目上课时间段:'+EmptyProjectHour.join(','));
		return false;
	}
	if(EmptyProjectSite.length>0){
		seevia_alert('请选择项目上课地点:'+EmptyProjectSite.join(','));
		return false;
	}
	var first_name=$(PostForm).find("input[name='data[User][first_name]']").val();
	var identity_card=$(PostForm).find("input[name='data[User][identity_card]']").val();
	var user_mobile=$(PostForm).find("input[name='data[User][mobile]']").val();
	var education_id=$(PostForm).find("select[name='data[UserEducation][education_id]']").val();
	var company_industry=$(PostForm).find("select[name='data[UserExperience][company_industry]']").val();
	var company_name=$(PostForm).find("input[name='data[UserExperience][company_name]']").val();
	var position=$(PostForm).find("input[name='data[UserExperience][position]']").val();
	
	if(first_name.trim()==""){
		seevia_alert('请填写姓名');
		return false;
	}
	if(identity_card.trim()==""){
		seevia_alert('请填写身份证号');
		return false;
	}
	if(education_id=="0"){
		seevia_alert('请选择学历');
		return false;
	}
	if(user_mobile.trim()==""){
		seevia_alert('请填写手机号');
		return false;
	}else if(!/^1[3-9]\d{9}$/.test(user_mobile)){
		seevia_alert('手机号不正确');
		return false;
	}
	if(company_industry.trim()==""){
		seevia_alert('请选择行业');
		return false;
	}
	if(company_name.trim()==""){
		seevia_alert('请填写单位');
		return false;
	}
	if(position.trim()==""){
		seevia_alert('请填写职位');
		return false;
	}
	$("ul.user_project_list li:last-child div").remove();
	var PostData=$(PostForm).serialize();
	$.ajax({
		type:"POST",
		url:web_base+'/user_projects/index',
		data:PostData,
		dataType: "JSON",
		beforeSend:function(){$(btn).button("loading");},
		success:function(result){
			$(btn).button("reset");
			if(result.code=='1'){
				var OperatorMessage=result.message;
				OperatorMessage="您已成功报名:"
				var complete_user_project=[];
				$.each(result.complete_user_project,function(index,item){
						complete_user_project.push(item);
				});
				OperatorMessage+=complete_user_project.join(',')
				seevia_alert_func(function(){
					window.location.reload();
				},OperatorMessage);
			}else{
				seevia_alert(result.message);
			}
		},
		complete: function(XMLHttpRequest, textStatus){
			$(btn).button("reset");
		},
		error: function(){
			$(btn).button("reset");
		}
	});
}

check_user_project_visible();

function add_user_project(btn){
	$("ul.user_project_list>li.last_user_project").removeClass('last_user_project');
	var defaultLi=$("ul.user_project_list>li:last-child");
	var defaultHtml=defaultLi.html().trim();
	defaultLi.before("<li class='last_user_project'>"+defaultHtml+"</li>");
	$("ul.user_project_list li.last_user_project select").selected({maxHeight:300,btnWidth: '100%'});
//	var lastDateInput=$("ul.user_project_list li.last_user_project input.user_project_time_input")[0];
//	user_project_time_input(lastDateInput);
	check_user_project_visible();
}

function remove_user_project(btn){
	$(btn).parents('li').remove();
	check_user_project_visible();
}

function check_user_project_visible(){
	var project_list=$("ul.user_project_list li:visible");
	if(project_list.length>1){
		$("ul.user_project_list li:first-child div:nth-child(4) a").show();
	}else{
		$("ul.user_project_list li:first-child div:nth-child(4) a").hide();
	}
}

function user_project_source(select){
	var project_row=$(select).parents('li');
	var project_code=$(select).val();
	var project_time=$(project_row).find('div:eq(1) select');
	var project_site=$(project_row).find('div:eq(2) select');
	if(project_code!='0'){
		
	}else{
		
	}
}

function user_project_time(select){
	var project_time=$(select).val();
	if(project_time=='-1'){
		$(select).parent().find("input[type='text']").show();
	}else{
		$(select).parent().find("input[type='text']").hide();
	}
}

function user_project_time_input(input){
	var nowTemp = new Date();
	var nowDay = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0).valueOf();
	var nowMoth = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 1, 0, 0, 0, 0).valueOf();
	var nowYear = new Date(nowTemp.getFullYear(), 0, 1, 0, 0, 0, 0).valueOf();
	
	var $myStart2 = $(input);
	var checkin = $myStart2.datepicker({
		format:'yyyy-mm',
		//viewMode:'months',
		onRender: function(date, viewMode) {
			// 默认 days 视图，与当前日期比较
			var viewDate = nowDay;
			switch (viewMode) {
				// moths 视图，与当前月份比较
				case 1:
					viewDate = nowMoth;
					break;
				// years 视图，与当前年份比较
				case 2:
					viewDate = nowYear;
					break;
			}
			return date.valueOf() < viewDate ? 'am-disabled' : '';
		}
	}).on('changeDate.datepicker.amui', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		checkin.close();
	}).data('amui.datepicker');
}
</script>