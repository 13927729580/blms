<style>
	ol.am-breadcrumb.am-hide-md-down.am-color{
	    max-width:1200px;
	    margin:10px auto;
	    padding:0;
	    line-height: 25px;
    }
    .fw600{
		font-weight:600;
    }
</style>
<div style="max-width:1200px;margin:10px auto;">
	<?php echo $this->element('org_menu')?>
	<?php echo $this->element('organization_menu')?>
	<button class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}" style="margin-right:0.5rem;">我的组织</button>
	<div class="am-g am-u-lg-9 am-u-sm-12">
		<h3 style="font-size:20px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;margin-bottom:20px;padding-bottom:1rem;line-height:1;" class="am-hide-sm-only">
	    	<span>我的客户</span>
	    </h3>
	    <form action="<?php echo $html->url('/activities/activity_user/'.$orga_id) ?>" class="am-form">
	    	<div style="margin-top:20px;margin-bottom:20px;">
	    		<div class="am-u-lg-4 am-u-sm-6" style="line-height:37px;">
	    			<input type="text" placeholder="名称/手机号/标签" name="search_content" value="<?php echo isset($search_content)&&$search_content!=''?$search_content:''; ?>">
	    		</div>
	    		<button style="float:left;" class="am-btn am-btn-primary" type="submit">搜索</button>
	    		<div class="am-cf"></div>
	    	</div>
	    </form>
		<div style="border-bottom:1px solid #ccc;" class="am-hide-sm-only">
			<div class="am-u-lg-2 am-u-sm-2 fw600">头像</div>
			<div class="am-u-lg-3 am-u-sm-3 fw600" style="padding-right:0;">姓名</div>
			<div class="am-u-lg-2 am-u-sm-2 fw600" style="padding-right:0;">手机号</div>
			<div class="am-u-lg-4 am-u-sm-4 fw600">标签</div>
			<div class="am-u-lg-1 am-u-sm-1">操作</div>
			<div class="am-cf"></div>
		</div>
		<?php //pr($activity_user_list); ?>
		<div style="height:1px;border-bottom:1px solid #ccc;margin-top:10px;" class="am-show-sm-only"></div>
		<?php if(is_array($activity_user_list)&&count($activity_user_list)>0){foreach ($activity_user_list as $k => $v) {//pr($v); ?>
			<div style="padding-top:0.3rem;padding-bottom:0.3rem;border-bottom:1px solid #ccc;">
				<div class="am-u-lg-2 am-u-sm-2"><img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:'/theme/default/images/default.png'; ?>" style="width:30px;height:30px;border-radius:50%;" alt=""></div>
				<div class="am-u-lg-3 am-u-sm-3" style="line-height:30px;padding-right:0;"><?php echo isset($v['ActivityUser']['name'])?$v['ActivityUser']['name']:''; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-sm-2 am-hide-sm-only" style="line-height:30px;padding-right:0;"><?php echo isset($v['ActivityUser']['mobile'])?$v['ActivityUser']['mobile']:''; ?>&nbsp;</div>
				<div class="am-u-lg-4 am-u-sm-4" style="line-height:30px;"><?php echo isset($v['User']['tag'])?$v['User']['tag']:''; ?>&nbsp;</div>
				<div class="am-u-lg-1 am-u-sm-1" style="line-height:30px;"><a href="<?php echo $html->url('/activities/activity_user_detail/'.$v['ActivityUser']['id']); ?>" class='am-text-primary'><span class="am-icon-pencil-square-o"></span></a></div>
				<div class="am-cf"></div>
			</div>
		<?php }}else{ ?>
			<div class="am-text-center" style="margin-top:200px;">暂无参与活动人员</div>
		<?php } ?>
		<?php echo $this->element('pager')?>
	</div>
	<div class="am-cf"></div>
</div>