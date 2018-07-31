<style>
  .am-offcanvas-bar{background:#fff;}
  #wrapper #doc-oc-demo2 li>a{padding:0;}
</style>
<div id="doc-oc-demo2" class="am-user-menu am-offcanvas">
  <div class="am-offcanvas-bar">
    <ul class="am-list admin-sidebar-list" id="header_list">
      <li class="admin-user-img">
		<?php echo $html->image( isset($user_list['User']['img01'])&&$user_list['User']['img01']!=""?$user_list['User']['img01']:("/theme/default/img/no_head.png"),array('title'=>$user_list['User']['name']));  ?>
		<!-- 头像编辑链接浮动窗口 -->
		<div class="am-popover am-popover-bottom" id="am-user-avatar-offcanvas">
		<div class="am-popover-inner"><a href="<?php echo $html->url('/users/edit_headimg'); ?>"><?php echo $ld['editing_avatar'] ?></a></div>
		</div>
		<!-- 头像编辑链接浮动窗口 -->
      </li>
      <li>
        <a data-am-collapse="{target: '#collapse-nav-00'}" class="am-cf" style="padding-left:2px;margin-bottom:7px;"><span class="am-icon-bars" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '用户中心' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav-00" class="am-list am-collapse admin-sidebar-sub">
          <li><a class="am-cf" href="<?php echo $html->url('/users/index'); ?>" style="padding-left:2px;margin-top:12px;"><span class="am-icon-user" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '概况' ?><span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
          <li><a class="am-cf" href="<?php echo $html->url('/users/edit'); ?>" style=""><span class="am-icon-pencil-square-o" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['account_profile'] ?></a></li>
          <li><a href="<?php echo $html->url('/users/edit_pwd'); ?>"><span class="am-icon-lock" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['change_password'] ?></a></li>
          <li><a href="<?php echo $html->url('/addresses'); ?>"><span class="am-icon-th" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['account_address_book'] ?><span class="am-badge am-badge-secondary am-margin-right am-fr"></span></a></li>
          <li><a href="<?php echo $html->url('/user_socials/privacy_settings'); ?>"><span class="am-icon-bug" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['privacy_settings'] ?></a></li>
          <li><a href="<?php echo $html->url('/users/bind'); ?>"><span class="am-icon-history" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '第三方账号绑定' ?></a></li>
        </ul>
      </li>
      
       <li>
        <a data-am-collapse="{target: '#collapse-nav-51'}" class="am-cf" style="padding-left:2px;margin-bottom:7px;"><span class="am-icon-home" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '我的空间' ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav-51" class="am-list am-collapse admin-sidebar-sub" style="">
          <li><a class="am-cf" href="<?php echo $html->url('/user_socials/fanslist/?id='.$_SESSION['User']['User']['id'].'&type=1'); ?>" style="margin-top:12px;"><span class="am-icon-crosshairs" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['my'].$ld['focus'] ?></a></li>
          <li><a class="am-cf" href="<?php echo $html->url('/user_socials/fanslist/?id='.$_SESSION['User']['User']['id'].'&type=2'); ?>"><span class="am-icon-user-plus" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['my'].$ld['fans'] ?></a></li>
          <li><a class="am-cf" href="<?php echo $html->url('/user_socials/index/'.$_SESSION['User']['User']['id']); ?>"><span class="am-icon-book" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['my'].$ld['diary'] ?></a></li>
        </ul>
      </li>
      
      <li>
        <a data-am-collapse="{target: '#collapse-nav-11'}" class="am-cf" style="padding-left:2px;margin-bottom:7px;"><span class="am-icon-mail-forward" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['my_account'] ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav-11" class="am-list am-collapse admin-sidebar-sub">
    <li><a href="<?php echo $html->url('/points/'); ?>"><span class="am-icon-gift" style="margin-top:12px;display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['account_reward_points'];?></a></li>
    <li><a href="<?php echo $html->url('/orders'); ?>"><span class="am-icon-shopping-cart" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['account_orders'] ?></a></li>
    <li><a href="<?php echo $html->url('/coupons/user_index'); ?>"><span class="am-icon-credit-card" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['user_002'];?></a></li>
    <li><a href="<?php echo $html->url('/users/deposit'); ?>"><span class="am-icon-money" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['user_deposit'] ?></a></li>
            <?php if(in_array('Member',$SystemList)&&isset($system_modules['Member']['modules']['Quotation'])){if(isset($configs['open_enquiry'])&&$configs['open_enquiry']==1){ ?>
            <li><a href="<?php echo $html->url('/users/enquiries'); ?>"><span class="am-icon-table" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['enquiry'] ?></a></li>
           <li><a href="<?php echo $html->url('/quotes/'); ?>"><span class="am-icon-list-ul" style="display:inline-block;width:18px;"></span>&nbsp;报价列表</a></li>
           <?php }} ?>
          <!-- end -->
        </ul>
      </li>
      <li>
        <a data-am-collapse="{target: '#collapse-nav-21'}" class="am-cf" style="padding-left:2px;margin-bottom:7px;"><span class="am-icon-tasks" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['my_service'] ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav-21" class="am-list am-collapse admin-sidebar-sub">
          <li><a href="<?php echo $html->url('/favorites'); ?>" style="margin-top:12px;"><span class="am-icon-heart" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo $ld['account_my_wishlist'] ?></a></li>
          <li><a href="<?php echo $html->url('/user_shares/'); ?>"><span class="am-icon-share-alt" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '我的分享';?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/message_index'); ?>"><span class="am-icon-bell-o" style="display:inline-block;width:18px;"></span>&nbsp;我的消息</a></li>
          <!-- end -->
        </ul>
      </li>
      <li>
        <a data-am-collapse="{target: '#collapse-nav-31'}" class="am-cf" style="padding-left:2px;margin-bottom:7px;"><span class="am-icon-link" style="display:inline-block;width:18px;"></span>&nbsp;<?php echo '我的应用'; ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav-31" class="am-list am-collapse admin-sidebar-sub">
		<li><a href="<?php echo $html->url('/user_evaluation_logs/index'); ?>"><span class="am-icon-file" style="display:inline-block;width:18px;"></span>&nbsp;我的评测</a></li>
		<li><a href="<?php echo $html->url('/courses/course_log'); ?>"><span class="am-icon-graduation-cap" style="display:inline-block;width:18px;"></span>&nbsp;我的课程</a></li>
		<li><a href="<?php echo $html->url('/activities/user_index'); ?>"><span class="am-icon-clipboard" style="display:inline-block;width:18px;"></span>&nbsp;我的活动</a></li>
          <!-- end -->
        </ul>
      </li>
      <li><a href="<?php echo $html->url('/users/logout'); ?>" style="margin-top:7px;padding-left:2px;"><span class="am-icon-sign-out"></span>&nbsp;<?php echo $ld['logout'] ?></a></li>
    </ul>
  </div>
</div>