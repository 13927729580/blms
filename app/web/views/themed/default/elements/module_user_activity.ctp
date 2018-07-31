<div class='user_activity_list'>
	<h4>我的活动</h4>
	<hr class='am-margin-top-xs am-margin-bottom-xs' />
	<?php if(isset($sm['activity_list'])&&sizeof($sm['activity_list'])>0){ ?>
	<ul class='am-list am-margin-top-lg'>
		<?php foreach ($sm['activity_list'] as $k => $v) { ?>
		<li>
			<div class='am-g'>
			<div class="" style="padding-left: 0;border-bottom: 1px solid #eee;">
				<div class="xinxi" style="padding-left: 0;padding-right:0;border-bottom: 0px;">
					<div style="padding-left: 0;margin-bottom:1rem;" class='am-u-md-3 am-u-sm-12'><a href="javascript:;" onclick="activities_center_view('<?php echo $v['Activity']['id']; ?>')" ><?php echo $html->image(isset($v['Activity']['image'])&&$v['Activity']['image']!=''?$v['Activity']['image']:"/theme/default/images/default.png",array('style'=>'max-width:150px;max-height:150px;')); ?></a></div>
					<div class="am-u-sm-12 am-u-lg-9" style="padding:0;">
					<div class='am-u-lg-6 w72 am-u-md-6' style="padding:0;">
						<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 neirong' style="padding-left: 0;"><a class="tab_name"  href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>"><?php echo $v['Activity']['name']; ?></a></div>
						<div class='am-u-lg-12 am-u-sm-12 am-u-md-7 keshi' style="padding: 0;">时间：
							<span><?php echo date("Y-m-d H",strtotime($v['Activity']['start_date'])).':00'; ?></span>
							<span>~</span>
							<span><?php echo date("Y-m-d H",strtotime($v['Activity']['end_date'])).':00'; ?></span>
						</div>
						<div class='am-u-lg-12 am-u-sm-12 am-u-md-7 keshi' style="padding: 0;margin-top:5px;">状态：<?php if(date('Y-m-d')<$v['Activity']['start_date']){
							echo '未开始';
						}else if(date('Y-m-d')>$v['Activity']['start_date']&&date('Y-m-d')<$v['Activity']['end_date']){echo '进行中';}else{echo '已结束';} ?>
						</div>
						<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 keshi' style="padding: 0;margin-top: 5px;">参加人数：<?php echo isset($sm['activity_user'][$v['Activity']['id']])?$sm['activity_user'][$v['Activity']['id']]:'0'; ?></div>
						<div class='am-cf'></div>
					</div>
					<div class='am-text-right w28 am-u-md-6 xuexu_but' style="padding:0;">
						<a style="margin-left: 5px;color: #fff;padding:7px 10px;margin-bottom: 4px;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>"><span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span></a>
					</div>
					</div>
					<div class='am-cf'></div>
				</div>
			</div>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php }else{ ?>
	<p style="color:#ccc;" class="am-margin-xs am-padding-top-lg  am-padding-bottom-lg">暂无活动记录</p>
	<?php } ?>
</div>