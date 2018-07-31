<div class='user_evaluation_list'>
	<h4>我的评测</h4>
	<hr class='am-margin-top-xs am-margin-bottom-xs' />
	<?php if(isset($sm['evaluation_list'])&&sizeof($sm['evaluation_list'])>0){ ?>
	<ul class='am-list am-margin-top-lg'>
		<?php foreach ($sm['evaluation_list'] as $k => $v) { ?>
		<li>
			<div class="am-g">
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-padding-right-0">
					<div  style="margin-bottom: 10px;"><?php echo date("Y",strtotime($v['UserEvaluationLog']['submit_time'])); ?></div>
					<div><?php echo date("m月d日",strtotime($v['UserEvaluationLog']['submit_time'])); ?></div>
				</div>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-padding-0">
					<div class="am-u-md-3 am-u-sm-3" style="padding-left: 0;height: 110px;padding:2px;border:1px solid #ccc;text-align: center;line-height: 100px;">
						<a target="_blank" href="<?php echo $html->url('/evaluations/view/'.$v['UserEvaluationLog']['evaluation_id']); ?>"><?php echo $html->image($v['Evaluation']['img']!=''?$v['Evaluation']['img']:"/theme/default/images/default.png",array('title'=>$user_list['User']['name'],'style'=>'margin-left:7px;')); ?></a>
					</div>
					<div class="am-u-lg-7 am-u-sm-7 am-u-md-7">
						<a target="_blank" href="<?php echo $html->url('/evaluations/view/'.$v['UserEvaluationLog']['evaluation_id']); ?>"><div class="am-g evaluation_log_tab"><?php echo $v['Evaluation']['name']; ?></div></a>
						<div class="am-g evaluation_log_zw">
							<div style="padding:0;" class="am-u-sm-6 am-u-lg-6 am-u-md-6 <?php echo $v['UserEvaluationLog']['score']>$v['Evaluation']['pass_score']?'':'huise'?>">
							<?php echo $v['UserEvaluationLog']['score']>$v['Evaluation']['pass_score']?'通过':'未通过'?>
							</div>
							<div class="am-u-sm-6 am-u-lg-6 am-u-md-6" style="padding: 0;">
								得分:<?php echo $v['UserEvaluationLog']['score']; ?>
							</div>
						</div>
					</div>
					<div class="am-u-lg-2 am-u-sm-1 am-u-md-1 am-padding-0 am-margin-0 am-text-right" >
						<a style="margin-left: 5px;margin-top: 5px;color: #fff;padding:7px 10px;" class="am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/user_evaluation_logs/view/'.$v['UserEvaluationLog']['id']); ?>" title="查看评测" target="_blank">
							<span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span>
						</a>
					</div>
					<div class="am-cf"></div>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php }else{ ?>
	<p style="color:#ccc;" class="am-margin-xs am-padding-top-lg  am-padding-bottom-lg">暂无评测记录</p>
	<?php } ?>
</div>