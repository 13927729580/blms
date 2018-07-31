<style type='text/css'>
#u_list ul.am-list li a{margin:0;padding:0.6rem 0;}
#u_list ul.admin-sidebar-sub li:first-child a{margin-top:0.6rem;}
#u_list li.admin-user-img a{padding:0;}
#u_list ul.am-list li a span{display:inline-block;width:18px;}
</style>
<div class="am-u-lg-2 am-u-md-4 am-u-sm-12 am-user-menu am-hide-sm-only am-padding-right-0" id="u_list">
    <ul class="am-list admin-sidebar-list">
	<li class="admin-user-img">
		<div>
			<a href="<?php echo $html->url('/users/edit_headimg'); ?>"><?php echo $html->image( isset($user_list['User']['img01'])&&$user_list['User']['img01']!=""?$user_list['User']['img01']:"/theme/default/img/no_head.png",array('title'=>$user_list['User']['name'],'class'=>'am-circle'));  ?></a>
		</div>
	</li>
	<?php if($svshow->check_module('LMS','Learning')){?>
		<li><a href="<?php echo $html->url('/courses/course_log'); ?>"><span class="am-icon-graduation-cap"></span>&nbsp;我的课程</a></li>
	      <?php }?>
	<?php if($svshow->check_module('LMS','Evaluating')){?>
					<li><a href="<?php echo $html->url('/user_evaluation_logs/index'); ?>"><span class="am-icon-file"></span>&nbsp;我的评测</a></li>
	      <?php }?>
	<?php if($svshow->check_module('LMS','Activity')){?>
								<li><a href="<?php echo $html->url('/activities/user_index'); ?>"><span class="am-icon-clipboard"></span>&nbsp;我的活动</a></li>
      <?php }?>
      <?php if($svshow->check_module('LMS','Mentor')){?>
		<li><a href="<?php echo $html->url('/users/master'); ?>"><span class="am-icon-pencil-square-o"></span>&nbsp;师徒关系</a></li>
      <?php }?>
      <li>
        <a data-am-collapse="{target: '#collapse-nav-1'}"><span class="am-icon-mail-forward"></span>&nbsp;<?php echo $ld['my_account'] ?><span class="am-icon-angle-right am-fr am-margin-right-sm"></span></a>
        <ul id="collapse-nav-1" class="am-list am-collapse admin-sidebar-sub">
          <li><a href="<?php echo $html->url('/users/edit'); ?>"><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['account_profile'] ?></a></li>
          <li><a href="<?php echo $html->url('/users/edit_pwd'); ?>"><span class="am-icon-lock"></span>&nbsp;<?php echo $ld['change_password'] ?></a></li>
          <li><a href="<?php echo $html->url('/users/bind'); ?>"><span class="am-icon-plug"></span>&nbsp;<?php echo '第三方账号绑定' ?></a></li>
        	<?php if($svshow->check_module('B2C')){?>
		<li><a href="<?php echo $html->url('/points/'); ?>"><span class="am-icon-gift"></span>&nbsp;<?php echo $ld['account_reward_points'];?></a></li>
		<li><a href="<?php echo $html->url('/orders'); ?>"><span class="am-icon-shopping-cart"></span>&nbsp;<?php echo $ld['account_orders'] ?></a></li>
		<li><a href="<?php echo $html->url('/coupons/user_index'); ?>"><span class="am-icon-credit-card"></span>&nbsp;<?php echo $ld['user_002'];?></a></li>
            <li><a href="<?php echo $html->url('/addresses'); ?>"><span class="am-icon-th"></span>&nbsp;<?php echo $ld['account_address_book'] ?></a></li>
    		<li><a href="<?php echo $html->url('/users/deposit'); ?>"><span class="am-icon-money"></span>&nbsp;<?php echo $ld['user_deposit'] ?></a></li>
	<?php if($svshow->check_module('O2O','Quotation')){?>
		<li><a href="<?php echo $html->url('/users/enquiries'); ?>"><span class="am-icon-table"></span>&nbsp;<?php echo $ld['enquiry'] ?></a></li>
		<li><a href="<?php echo $html->url('/quotes/'); ?>"><span class="am-icon-list-ul"></span>&nbsp;报价列表</a></li>
		<?php }} ?>
          <!-- end -->
        </ul>
      </li>
      <?php if($svshow->check_module('O2O','SNS')){?>
      <li>
        <a data-am-collapse="{target: '#collapse-nav-2'}"><span class="am-icon-tasks"></span>&nbsp;<?php echo '我的动态'; ?><span class="am-icon-angle-right am-fr am-margin-right-sm"></span></a>
        <ul id="collapse-nav-2" class="am-list am-collapse admin-sidebar-sub">
          <li><a href="<?php echo $html->url('/favorites'); ?>"><span class="am-icon-heart"></span>&nbsp;<?php echo $ld['account_my_wishlist'] ?></a></li>
                    <li><a href="<?php echo $html->url('/user_socials/fanslist/?id='.$_SESSION['User']['User']['id'].'&type=1'); ?>"><span class="am-icon-crosshairs"></span>&nbsp;<?php echo $ld['my'].$ld['focus'] ?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/fanslist/?id='.$_SESSION['User']['User']['id'].'&type=2'); ?>"><span class="am-icon-user-plus"></span>&nbsp;<?php echo $ld['my'].$ld['fans'] ?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/index/'.$_SESSION['User']['User']['id']); ?>"><span class="am-icon-book"></span>&nbsp;<?php echo $ld['my'].$ld['diary'] ?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/privacy_settings'); ?>"><span class="am-icon-bug"></span>&nbsp;<?php echo $ld['privacy_settings'] ?></a></li>
		<li><a href="<?php echo $html->url('/user_shares/'); ?>"><span class="am-icon-share-alt"></span>&nbsp;<?php echo '我的分享';?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/message_index'); ?>"><span class="am-icon-bell-o"></span>&nbsp;我的消息</a></li>
          <!-- end -->
        </ul>
      </li>
      <?php } ?>
      <?php if($svshow->check_module('Project')){?>
            <li><a href="<?php echo $html->url('/user_resumes/index'); ?>"><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo '我的履历' ?></a></li>
          <?php } ?>
      
      <li><a href="<?php echo $html->url('/users/logout'); ?>"><span class="am-icon-sign-out"></span>&nbsp;<?php echo $ld['logout'] ?></a></li>
    </ul>
</div>