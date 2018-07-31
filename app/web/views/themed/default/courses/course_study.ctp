<style>
.am-radio-inline{padding-top: 0!important;}
<?php if($organizations_id!=''){ ?>
.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
<?php } ?>
</style>
<div class="am-g am-g-fixed">
	<?php if($organizations_id!=''){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>
	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-panel am-panel-default <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" id="course_study" style="font-size: 14px;margin-left: 0;">
	    <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	      <span style="float:left;"><?php echo isset($course_info['Course']['name'])?$course_info['Course']['name']:''; ?></span>
	      <div class="am-cf"></div>
	    </div>
	    <div class="am-panel-hd" style="font-size: 15px;">
	        <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Learning_situation'}">学习情况</h4>
	    </div>
	    <div id="Learning_situation" class="am-panel-collapse am-collapse am-in">
	        <div class="listtable_div_btm" style="margin-top: 10px;">
	            <div class="am-panel-hd">
	                <div class="am-panel-title">
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-8">学习者姓名</div>
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-hide-sm-only">学习时间</div>
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">学习进度</div>
	                    <div style="clear:both;"></div>
	                </div>
	            </div>
	        </div>
	        <?php if(isset($course_class_log)&&sizeof($course_class_log)>0){foreach ($course_class_log as $k => $v) { ?>
	        <div class="listtable_div_btm" style="border-top: 1px solid #ccc;padding:1.25rem;">
	            <div class="am-panel-hd" style="padding:0;">
	                <div class="am-panel-title">
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-8"><?php echo isset($course_user[$v['UserCourseClass']['user_id']])?$course_user[$v['UserCourseClass']['user_id']]:''; ?></div>
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-hide-sm-only"><?php echo $v['UserCourseClass']['modified']; ?></div>
	                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo isset($courselog_data[$v['UserCourseClass']['user_id']])?$courselog_data[$v['UserCourseClass']['user_id']]:0; ?>/<?php echo $courseclass_total; ?></div>
	                    <div style="clear:both;"></div>
	                    <?php //pr($v['Course']['id']); ?>
	                </div>
	            </div>
	        </div>
	        <?php }}else{?>
	            <div style="border-top: 1px solid #ccc;text-align: center;padding:75px;">暂无学习记录</div>
	        <?php }?>
	    </div>
	</div>
</div>