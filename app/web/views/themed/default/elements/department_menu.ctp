<div class="am-offcanvas" style="" id="dep_group_mobile">
<div class="am-offcanvas-bar" style="background:#fff;">
    <div class="am-offcanvas-content">
<h3 style="font-size:18px;margin-bottom:1rem;font-weight:400;margin-top:1rem;">部门及成员</h3>
		<div style="height:30px;width:100%;margin-top:2px;cursor:pointer;border-bottom:1px solid #ccc;">
			<div class="am-u-lg-6 am-text-left am-u-sm-6" style="font-size:14px;padding-left:5px;padding-right:0;line-height:30px;" onclick="depart_reload()">组织架构</div>
<div class="am-u-lg-6 am-text-right am-u-sm-6"><i class="am-icon-angle-right"></i></div>
			<div class="am-cf"></div>
		</div>
		<div class="am-panel-group am-panel-tree" id="tree_list_mobile">
		<?php foreach ($depart_info as $k => $v) { //pr($v); ?>
<?php if($_SESSION['User']['User']['id'] == $v['OrganizationDepartment']['department_manage'] || $_SESSION['User']['User']['id'] == $organization_info['Organization']['manage_user']){ ?>
			<div class="am-panel-body">
			<div class="am-panel-bd" style="padding-top:0.3rem;padding-bottom:0;">
			
			<div style="height:30px;font-size:14px;line-height:30px;cursor:pointer;" >
			<input type="hidden" class="depart_info" value="<?php echo $v['OrganizationDepartment']['id'] ?>">
			<span data-am-collapse="{parent: '#tree_list_mobile', target: '#action_mobile_<?php echo $v['OrganizationDepartment']['id'] ?>'}" onclick="changeicon(this)">
				<?php if(isset($jobs_info[$v['OrganizationDepartment']['id']])&&count($jobs_info[$v['OrganizationDepartment']['id']])>0){ ?>
						<i class="am-icon-plus"></i>
						<i class="am-icon-minus" style="display:none;"></i>
						<?php }else{ ?>
						<i class="am-icon-minus" style=""></i>
						<?php } ?>
			</span>
			<span onclick="ajax_get_depart(this)" class="org-depart"><?php echo $v['OrganizationDepartment']['name'] ?></span>
			</div>
			<div class="am-panel-collapse am-collapse am-panel-child" id="action_mobile_<?php echo $v['OrganizationDepartment']['id'] ?>" style="font-size:14px;padding-left:15px;">
			<?php if(isset($jobs_info[$v['OrganizationDepartment']['id']])){ ?>
				<?php foreach ($jobs_info[$v['OrganizationDepartment']['id']] as $kkk => $vvv) { ?>
					<div onclick="ajax_get_job(this)" id="get_depart_<?php echo $v['OrganizationDepartment']['id']; ?>" style="cursor:pointer;"> <?php echo $vvv['OrganizationJob']['name'] ?>
					<input type="hidden" value="<?php echo $vvv['OrganizationJob']['id'] ?>">
					</div>
				<?php } ?>
				
			<?php } ?>
			</div>
			</div>
			</div>
		<?php }} ?>
<?php if($_SESSION['User']['User']['id'] == $organization_info['Organization']['manage_user']){ ?>
		<div style="margin-left:15px;font-size:14px;line-height:30px;cursor:pointer;" onclick="get_unset_member()"><i class="am-icon-minus" style=""></i> 未分配</div>
<?php } ?>
	</div>
	</div>
	</div>
</div>