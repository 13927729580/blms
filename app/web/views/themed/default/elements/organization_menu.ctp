<style>
  #org_menu2 .am-list>li {
      padding:7px;
  }
  #org_menu2 .am-list>li>a {
      padding:0px;
      line-height: 1;
  }
</style>
<div id="org_menu2" class="am-offcanvas">
  <div class="am-offcanvas-bar" style="background:#fff;">
    <div class="am-offcanvas-content" style="color:#000;">
       <div style="text-align:center;padding-top:1rem;margin-bottom:10px;"><a href="<?php echo $html->url('/organizations/view/'.$organization_info['Organization']['id']) ?>"><img src="<?php echo isset($organization_info['Organization']['logo'])?$organization_info['Organization']['logo']:$configs['shop_default_img']; ?>" alt="" style="width:60px;height:60px;" class="am-circle"></a></div>
       <a data-am-collapse="{target: '#collapse-nav-pri'}" class="am-cf" style="margin-bottom:7px;color:#000;line-height:1;display:inline-block;width:100%;"><span class="am-icon-bars" style="display:inline-block;width:18px;"></span>&nbsp;<span style="display:inline-block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width:66%;"><?php echo $organization_info['Organization']['name'] ?></span><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
    <div id="collapse-nav-pri" class="am-list am-collapse admin-sidebar-sub am-in" style="border-top:1px solid #dedede;padding-top:10px;box-shadow:none;">
      <a data-am-collapse="{target: '#collapse-na'}" class="am-cf" style="margin-bottom:7px;color:#000;"><span class="am-icon-folder-open" style="display:inline-block;width:18px;margin-bottom:7px;"></span>&nbsp;<?php echo '企业设置' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
      <ul id="collapse-na" class="am-list am-collapse admin-sidebar-sub am-in" style="margin-bottom:7px;">
	<li style="padding-left:0;padding-right:0;">
		<a style="color: #000;margin-top:7px;" href="<?php echo $html->url('/organizations/view/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-pencil-square-o" style="display:inline-block;width:18px;"></i> 组织基本信息</a>
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
	<li style="background-color: #f1f1f1;padding-left:0;padding-right:0;">
		<a style="color: #000;" href="<?php echo $html->url('/organizations/organization_role/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-user-secret" style="display:inline-block;width:18px;"></i> 组织角色</a>
	</li>
	<?php } ?>
      </ul>
      <a data-am-collapse="{target: '#collapse-na-5'}" class="am-cf" style="margin-bottom:7px;color:#000;"><span class="am-icon-paper-plane" style="display:inline-block;width:18px;margin-bottom:7px;"></span>&nbsp;<?php echo '应用' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
    <ul id="collapse-na-5" class="am-list am-collapse admin-sidebar-sub am-in" style="">
      <li style="padding-left:0;padding-right:0;">
          <a style="color: #000;margin-top:7px;" href="<?php echo $html->url('/organizations/org_profile/'.$organization_info['Organization']['id']);  ?>"><i class="am-icon-folder-o" style="display:inline-block;width:18px;"></i> 组织概要</a>
      </li>
       <?php if(isset($organization_actions)&&in_array('course',$organization_actions)){ ?>
      <li style="padding-left:0;padding-right:0;">
          <a style="color: #000;" href="<?php echo $html->url('/courses/course_management?organizations_id='.$organization_info['Organization']['id']);  ?>"><i class="am-icon-cog" style="display:inline-block;width:18px;"></i> 课程管理</a>
      </li>
       <?php } ?>
       <?php if(isset($organization_actions)&&in_array('evaluation',$organization_actions)){ ?>
      <li style="padding-left:0;padding-right:0;">
        <a style="color: #000;" href="<?php echo $html->url('/evaluations/evaluation_management?organizations_id='.$organization_info['Organization']['id']); ?>"><i class="am-icon-circle-o-notch" style="display:inline-block;width:18px;"></i> 评测管理</a>
      </li>
       <?php } ?>
      <?php if(isset($organization_actions)&&in_array('activity',$organization_actions)){ ?>
      <li style="padding-left:0;padding-right:0;">
        <a style="color: #000;" href="<?php echo $html->url('/activities/org_index?organization_id='.$organization_info['Organization']['id']); ?>"><i class="am-icon-cogs" style="display:inline-block;width:18px;"></i> 活动管理</a>
      </li>
      <?php } ?>
      <?php if(isset($organization_actions)&&in_array('customer',$organization_actions)){ ?>
      <li style="padding-left:0;padding-right:0;">
        <a style="color: #000;" href="<?php echo $html->url('/activities/activity_user/'.$organization_info['Organization']['id']); ?>"><i class="am-icon-mail-forward" style="display:inline-block;width:18px;"></i> 我的客户</a>
      </li>
      <?php } ?>
    </ul>
  </div>
    </div>
  </div>
</div>