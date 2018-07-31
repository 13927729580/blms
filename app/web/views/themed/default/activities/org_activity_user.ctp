<style>
	#course_chapter_list .admin-user-img{
		display:none;
	}
	ol.am-breadcrumb.am-hide-md-down.am-color{
	    max-width:1200px;
	    margin:0 auto;
	    padding:0;
	    margin-bottom:1rem;
    }
    .am-selected{
		width:100%;
	}
	ol.am-breadcrumb.am-hide-md-down.am-color{
		margin-top:10px;
	}
</style>
<div style="max-width:1200px;margin:0px auto;min-height:500px;">
	<?php if(isset($_GET['viewonly'])&&$_GET['viewonly'] == 1){ ?>
	<div class="am-hide-sm-only">
	<?php //pr($user_info); ?>
	<?php if(isset($user_info)){foreach ($user_info as $k => $v) { ?>
		<div style="padding:10px;display: inline-block;text-align: center;max-width:70px;">
			<img src="<?php if($v['User']['img01']==''){echo '/theme/default/images/default.png';}else{echo $v['User']['img01'];} ?>" title="<?php echo $v['User']['name']; ?>" style="border-radius: 50%;height: 50px;width: 50px;">
			<br>
			<span style="margin-top: 10px;display: inline-block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width:100%;"><?php echo $v['User']['name']; ?></span>
		</div>
	<?php }} ?>
	</div>
	<div class="am-u-lg-12 am-show-sm-only" style="padding:0;">
		<div style="border-bottom:1px solid #ddd;font-weight:600;" class="am-hide-sm-only">
			<div class="am-u-lg-4 am-u-sm-3">成员头像</div>
			<div class="am-u-lg-4 am-u-sm-3">成员名称</div>
			<div class="am-u-lg-3 am-u-sm-6">报名时间</div>
			<div class="am-cf"></div>
		</div>
		<?php //pr($user_info) ?>
		<?php if(isset($user_info)){foreach ($user_info as $k => $v) { ?>
			<div style="padding-top:0.2rem;padding-bottom:0.2rem;border-bottom:1px solid #ddd;line-height:40px;">
				<img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:$configs['shop_default_img']; ?>" alt="" style="width:40px;height:40px;border-radius:50%;float:left;margin-left:10px;">
				<div class="am-u-lg-4 am-u-sm-5"><?php echo $v['User']['name']; ?></div>
				<div class="am-u-lg-3 am-u-sm-3 am-text-right" style="float:right;"><?php
				$time = time() - strtotime($activity_check[$v['User']['id']]['ActivityUser']['created']);
				if($time/(60*60*24) == 0){
					echo '今天';
				}else if($time/(60*60*24) == 1){
					echo '昨天';
				}else if($time/(60*60*24) == 2){
					echo '前天';
				}else{
					echo date('n',strtotime($activity_check[$v['User']['id']]['ActivityUser']['created'])).'月'.date('j',strtotime($activity_check[$v['User']['id']]['ActivityUser']['created'])).'日';
				}
				 ?></div>
				<div class="am-cf"></div>
			</div>
		<?php } ?>
		<?php echo $this->element('pager')?>
		<?php } ?>
	</div>
	<?php }else{ ?>
	<div class="am-u-lg-12" style="padding:0;">
		<div style="border-bottom:1px solid #ccc;font-weight:600;" class="am-hide-sm-only">
			
			<?php if(isset($act_info['Activity']['price'])&&$act_info['Activity']['price']>0){ ?>
				<div class="am-u-lg-2 am-u-sm-2">成员头像</div>
				<div class="am-u-lg-2 am-u-sm-2">成员名称</div>
				<div class="am-u-lg-3 am-u-sm-3">报名时间</div>
				<div class="am-u-lg-3 am-u-sm-3">支付状态</div>
				<div class="am-u-lg-2 am-u-sm-2">操作</div>
			<?php }else{ ?>
				<div class="am-u-lg-3 am-u-sm-2">成员头像</div>
				<div class="am-u-lg-3 am-u-sm-3">成员名称</div>
				<div class="am-u-lg-3 am-u-sm-5">报名时间</div>
				<div class="am-u-lg-2 am-u-sm-2">操作</div>
			<?php } ?>
			<div class="am-cf"></div>
		</div>
		<div style="border-bottom:1px solid #ccc;" class="am-show-sm-only"></div>
		<?php //pr($user_info) ?>
		<?php if(isset($user_info)){foreach ($user_info as $k => $v) { ?>
			<div style="padding-top:0.2rem;padding-bottom:0.2rem;border-bottom:1px solid #ccc;line-height:40px;">
				<?php if(isset($act_info['Activity']['price'])&&$act_info['Activity']['price']>0){ ?>
					<div class="am-u-lg-2 am-u-sm-2"><img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:$configs['shop_default_img']; ?>" alt="" style="width:40px;height:40px;border-radius:50%;"></div>
					<div class="am-u-lg-2 am-u-sm-2"><?php echo $v['User']['name']; ?></div>
					<div class="am-u-lg-3 am-u-sm-3"><?php echo $activity_check[$v['User']['id']]['ActivityUser']['created']; ?></div>
					<div class="am-u-lg-3 am-u-sm-3 am-hide-sm-only"><?php if($activity_check[$v['User']['id']]['ActivityUser']['payment_status']==0){echo '未支付';}else if($activity_check[$v['User']['id']]['ActivityUser']['payment_status']==1){echo '已支付';}else{echo '已退款';} ?></div>
					<div class="am-u-lg-2 am-u-sm-2">
						<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/activities/activity_user_edit/'.$activity_id.'?user_id='.$v['User']['id']) ?>" >编辑</a>
					</div>
				<?php }else{ ?>
					<div class="am-u-lg-3 am-u-sm-2"><img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:$configs['shop_default_img']; ?>" alt="" style="width:40px;height:40px;border-radius:50%;"></div>
					<div class="am-u-lg-3 am-u-sm-3"><?php echo $v['User']['name']; ?></div>
					<div class="am-u-lg-3 am-u-sm-5"><?php echo $activity_check[$v['User']['id']]['ActivityUser']['created']; ?></div>
					<div class="am-u-lg-2 am-u-sm-2">
						<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/activities/activity_user_edit/'.$activity_id.'?user_id='.$v['User']['id']) ?>" >编辑</a>
					</div>
				<?php } ?>
				<div class="am-cf"></div>
			</div>
			
		<?php } ?>
		<?php echo $this->element('pager')?>
		<?php }else{ ?>
			<div style="text-align:center;margin-top:10px;">暂无参与活动的成员</div>
		<?php } ?>
		
	</div>
	<?php } ?>
	<div class="am-cf"></div>
</div>