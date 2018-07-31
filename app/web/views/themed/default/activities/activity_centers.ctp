<style>
h3{font-size: 20px;padding-left: 10px;}
.activity_child:hover{border:none;box-shadow: 0px 0px 9px #aaa;}
.activity_child_name:hover{color: skyblue;}
div.recommd_activity{max-width:1200px;margin:0 auto;margin-top:1.5rem;}
</style>
<div class="recommd_activity">
	<h3>推荐活动</h3>
	<ul class="am-avg-lg-4 am-avg-md-3 am-avg-sm-1">
		<?php if(isset($activities_list)&&sizeof($activities_list)>0){foreach($activities_list as $v){ ?>
		<li style="padding:10px;">
			<div class="activity_child" style="background-color:#F8F8F8;border-radius: 5px;">
				<div style="padding:0;text-align: center;height: 160px;line-height: 160px;">
					<img src="<?php if($v['Activity']['image']==''){echo '/theme/default/images/default.png';}else{echo $v['Activity']['image'];} ?>" onclick="activities_center_view('<?php echo $v['Activity']['id']; ?>')" style="cursor: pointer;max-width: 100%;max-height: 100%;">
				</div>
				<div style="position: relative;height: 130px;padding:20px;font-size: 16px;">
					<div class="activity_child_name" onclick="activities_center_view('<?php echo $v['Activity']['id']; ?>')" style="cursor: pointer;"><?php echo $v['Activity']['name'] ?></div>
					<div style="width:100%;color: #999;position: absolute;left:18px;bottom:40px;">
						<div class='am-u-sm-7'><?php echo date("Y-m-d",strtotime($v['Activity']['start_date'])).' '.$v['Activity']['time_quantum'] ?>  开始</div>
						<div class='am-u-sm-5 am-text-center'><?php echo $v['Activity']['channel']=='0'?'线上':($v['Activity']['channel']=='1'?'线下':($v['Activity']['channel']=='2'?'直播':'')); ?></div>
						<div class='am-cf'></div>
					</div>
					<div style="position: absolute;left: 18px;bottom:12px;">
						<?php if(isset($tag_list[$v['Activity']['id']])&&sizeof($tag_list[$v['Activity']['id']])>0){foreach($tag_list[$v['Activity']['id']] as $kk=>$vv){if($kk>2)continue; ?>
						<span style="background-color: skyblue;display: inline-block;padding:2px 6px;border-radius: 5px;font-size: 12px;margin-right: 5px;color: #fff;"><?php echo $vv; ?></span>
						<?php }} ?>
					</div>
				</div>
				<div class="am-cf"></div>
			</div>
		</li>
		<?php }} ?>
	</ul>
	<?php echo $this->element('pager'); ?>
</div>
<script>
	function activities_center_view(id){
		window.location.href='/activities/view/'+id;
	}
</script>