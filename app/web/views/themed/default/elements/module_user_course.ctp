<div class='user_course_list'>
	<h4>我的课程</h4>
	<hr class='am-margin-top-xs am-margin-bottom-xs' />
	<?php if(isset($sm['course_list'])&&sizeof($sm['course_list'])>0){ ?>
	<ul class='am-list am-margin-top-lg'>
		<?php foreach($sm['course_list']  as $v){ ?>
		<li>
			<div class='am-g'>
					<div class='am-u-lg-2 am-u-md-2 am-u-sm-12' style="padding:0;">
						<div style="margin-bottom: 10px;" class="nian am-u-lg-12 am-u-md-12 am-u-sm-2"><?php echo date("Y",strtotime($v['Course']['created'])); ?></div>
						<div class="yue am-u-lg-12 am-u-md-12 am-u-sm-6"  style="margin-bottom: 10px;padding-right: 0;"><?php echo date("m月d日",strtotime($v['Course']['created'])); ?></div>
					</div>
					<div class="am-u-lg-10 am-u-md-10 am-u-sm-12" style="padding-left: 0;border-bottom: 0px;margin-bottom: 10px;padding-right: 0;">
						<div style="padding-left: 0;width: none;height: 140px;padding:2px;border:1px solid #ccc;text-align: center;line-height: 130px;margin-bottom: 10px;" class='am-u-md-3 am-u-sm-8'><a target="_blank" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>"><?php echo $html->image($v['Course']['img']!=''?$v['Course']['img']:"/theme/default/images/default.png",array('title'=>$v['Course']['name'])); ?></a></div>
						<div class='am-u-lg-4 am-u-sm-12 am-u-md-5' style="margin-bottom: 10px;">
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12' style="padding-left: 0;"><a class="tab_name" target="_blank" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>"><?php echo $v['Course']['name']; ?></a></div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-7' style="padding: 0;">课时数：<?php echo isset($sm['course_class_total'][$v['Course']['code']])?$sm['course_class_total'][$v['Course']['code']]:0; ?></div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12' style="padding: 0;margin-top: 10px;">课程时长：<?php echo intval($v['Course']['hour']); ?>（分钟）</div>
							<div class='am-cf'></div>
						</div>
						<div class='am-u-lg-5 am-text-right am-u-sm-12 am-u-md-4' style="padding:0;">
							<a style="margin-left: 5px;color: #fff;padding:7px 10px;margin-bottom: 4px;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>" title="开始学习" target="_blank"><span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span></a>
						</div>
					</div>
					<div class='am-cf'></div>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php }else{ ?>
	<p style="color:#ccc;" class="am-margin-xs am-padding-top-lg am-padding-bottom-lg">暂无学习记录</p>
	<?php } ?>
</div>
<style type='text/css'>
.user_course_list ul li img{max-width:100%;max-height:100%;}
</style>