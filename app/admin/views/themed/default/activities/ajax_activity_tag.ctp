<ul class="am-avg-sm-2 am-avg-md-4 am-avg-lg-6">
	<?php if(isset($activity_tags)&&sizeof($activity_tags)>0){foreach($activity_tags as $v){ ?>
	<li><?php pr($v); ?></li>
	<?php }} ?>
</ul>