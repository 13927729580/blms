<style>
	#org_list .am-list>li {
	    padding:7px;
	}
	#org_list .am-list>li>a {
	    padding:0px;
	}
</style>
<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-panel-group am-hide-sm-only" id="org_list" style="min-height: 590px;line-height:1;padding-left:10px;padding-right:10px;">
	<div style="padding:10px 0;">
		<div style="text-align:center;">
			<a href="<?php echo $html->url('/organizations/view/'.$organization_info['Organization']['id']) ?>"><img src="<?php echo isset($organization_info['Organization']['logo'])&&$organization_info['Organization']['logo']!=''?$organization_info['Organization']['logo']:$configs['shop_default_img']; ?>" alt="" style="width:60px;height:60px;" class="am-circle"></a>
		</div>
		<div class="am-cf"></div>
    </div>
    <div id="collapse-nav-pri" class="am-list am-collapse am-in" style="padding-top:14px;box-shadow:none;">
	    <a data-am-collapse="{target: '#collapse-na'}" class="am-cf" style="margin-bottom:7px;color:#000;"><span class="am-icon-folder-open" style="display:inline-block;width:18px;margin-bottom:7px;"></span>&nbsp;<?php echo '企业设置' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
	    <ul id="collapse-na" class="am-list am-collapse admin-sidebar-sub am-in" style="margin-bottom:7px;">
	    	<li style="padding-left:0;padding-right:0;">
				<a style="color: #000;margin-top:7px;" href="<?php echo $html->url('/organizations/view/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-file-text-o" style="display:inline-block;width:18px;"></i> 组织基本信息</a>
			</li>
			<?php if(isset($organization_actions)&&in_array('member',$organization_actions)){ ?>
			<li id="dep" style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/organizations/view/'.$organization_info['Organization']['id'].'?department=1');  ?>"><i class="am-icon-calculator" style="display:inline-block;width:18px;"></i> 部门及成员</a>
			</li>
			<?php } ?>
			<?php if(isset($organization_actions)&&in_array('third_party_platform',$organization_actions)){ ?>
			<li id="" style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/organizations/application/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-external-link" style="display:inline-block;width:18px;"></i> 第三方平台管理</a>
			</li>
			<?php } ?>
			<?php if(isset($organization_actions)&&in_array('manager',$organization_actions)){ ?>
			<li id="" style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/organizations/organization_role/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-male" style="display:inline-block;width:18px;"></i> 组织角色</a>
			</li>
			<?php } ?>
	    </ul>
	    <a data-am-collapse="{target: '#collapse-na-5'}" class="am-cf" style="margin-bottom:7px;color:#000;"><span class="am-icon-paper-plane" style="display:inline-block;width:18px;margin-bottom:7px;"></span>&nbsp;<?php echo '应用' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
		<ul id="collapse-na-5" class="am-list am-collapse admin-sidebar-sub am-in" style="">
			<li style="padding-left:0;padding-right:0;">
			  	<a style="color: #000;margin-top:7px;" href="<?php echo $html->url('/organizations/org_profile/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-dashboard" style="display:inline-block;width:18px;"></i> 概要</a>
			</li>
			<?php if(isset($organization_actions)&&in_array('course',$organization_actions)){ ?>
			<li style="padding-left:0;padding-right:0;">
			  	<a style="color: #000;" href="<?php echo $html->url('/courses/course_management?organizations_id='.$organization_info['Organization']['id']);  ?>"><i class="am-icon-youtube-play" style="display:inline-block;width:18px;"></i> 课程管理</a>
			</li>
			<?php } ?>
			<?php if(isset($organization_actions)&&in_array('evaluation',$organization_actions)){ ?>
			<li style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/evaluations/evaluation_management?organizations_id='.$organization_info['Organization']['id']); ?>"><i class="am-icon-trophy" style="display:inline-block;width:18px;"></i> 评测管理</a>
			</li>
			<?php } ?>
			<?php if(isset($organization_actions)&&in_array('activity',$organization_actions)){ ?>
			<li style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/activities/org_index?organization_id='.$organization_info['Organization']['id']); ?>"><i class="am-icon-cogs" style="display:inline-block;width:18px;"></i> 活动管理</a>
			</li>
			<?php } ?>
			<li style="padding-left:0;padding-right:0;">
				<a style="color: #000;" href="<?php echo $html->url('/activities/activity_user/'.$organization_info['Organization']['id']); ?>"><i class="am-icon-bar-chart" style="display:inline-block;width:18px;"></i> 我的客户</a>
			</li>
		</ul>
	</div>
</div>