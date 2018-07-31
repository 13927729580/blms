<div class="am-g" >
	<div class="am-u-lg-12 am-u-md-11 am-u-sm-11" >
		<div >下一个任务</div>
		<?php if(isset($user_task_list)&&sizeof($user_task_list)>0){ ?>
		<ul class='am-list'>
		<?php 
			     foreach($user_task_list as $v){ ?>
			<li class="am-g">
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<a class="font_14" href="<?php echo $v['UserTask']['task_url']; ?>"><?php echo $v['UserTask']['name']; ?></a>
				</div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-right">
					<a class="jinru" href="<?php echo $html->url('/user_tasks/view/'.$v['UserTask']['id']); ?>">进入</a>
				</div>
			</li>
		<?php } ?>
		</ul>
			<?php }else{?>
			<div class="kongbai">当前无任务</div>
			<?php }?>
	</div>
</div>