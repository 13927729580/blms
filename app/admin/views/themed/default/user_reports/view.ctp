<?php
	//积分获得
	//pr($point_increase_log);
	
	//积分使用
	//pr($point_use_log);
	//pr($user_point_log);
	//分享访问
	//pr($share_affiliate_log);

?>
<div>
<div class="am-g" style="border:1px solid #ddd;border-bottom:none;border-right:none;">
	<div style="border-bottom:1px solid #ddd;font-weight:600;">
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">用户名</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">变更</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">变更时间</div>
		<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;">备注</div>
		<div class="am-cf"></div>
	</div>
	
		<?php foreach ($user_point_log as $key => $value) {?>
			<div style="border-bottom:1px solid #ddd;">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><a href="<?php echo $html->url('/users/view/'.$value['User']['id']); ?>"><?php echo $value['User']['name'] ?></a></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo $value['UserPointLog']['point_change'] ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo $value['UserPointLog']['created'] ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;"><?php echo $value['UserPointLog']['system_note'] ?></div>
				<div class="am-cf"></div>
			</div>
		<?php } ?>
	
</div>
</div>
<div style="margin-top:1rem;">
<div class="am-g" style="border:1px solid #ddd;border-bottom:none;">
	<div style="border-bottom:1px solid #ddd;font-weight:600;">
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:5px;padding-bottom:5px;">分享者</div>
		<div class="am-u-lg-8 am-u-md-6 am-u-sm-6" style="border-left:1px solid #ddd;border-right:1px solid #ddd;">
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="border-right:1px solid #ddd;padding-top:5px;padding-bottom:5px;padding-left:0;">分享链接</div>
		<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-top:5px;padding-bottom:5px;">访问时间</div>
		</div>
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:5px;padding-bottom:5px;">IP地址</div>
		<div class="am-cf"></div>
	</div>
	
		<?php foreach ($share_affiliate_log as $key1 => $value1) {?>
			<div style="border-bottom:1px solid #ddd;">
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:5px;padding-bottom:5px;"><a href="<?php echo $html->url('/users/view/'.$value1['User']['id']); ?>"><?php echo $value1['User']['name'] ?></a></div>
				<div class="am-u-lg-8 am-u-md-6 am-u-sm-6" style="border-left:1px solid #ddd;border-right:1px solid #ddd;">
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:5px;padding-bottom:5px;padding-left:0;overflow-wrap: normal;overflow-wrap: break-word;border-right:1px solid #ddd;"><?php echo $value1['ShareAffiliateLog']['link_source'] ?></div>
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-top:5px;padding-bottom:5px;padding-right:0;"><?php echo $value1['ShareAffiliateLog']['created'] ?></div>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:5px;padding-bottom:5px;"><?php echo $value1['ShareAffiliateLog']['ip_address'] ?></div>
				<div class="am-cf"></div>
			</div>
		<?php } ?>
	
</div>
</div>
