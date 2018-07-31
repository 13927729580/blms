	<!--广告位-->
	<?php echo $this->element("advertisement_list",array("ad_show_code"=>'promotion_top')) ?>
	<!--广告位end-->
<!-- 促销 -->
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head"><?php echo $ld['promotion']?>
		<!--<h3><?php //echo $ld['event_rules_purchase']?></h3>-->
	</div>
	<div  class="am-panel-bd">
	<div data-am-widget="list_news" class="am-list-news am-list-news-default">
	  <!--列表标题-->
	  <div class="am-list-news-hd am-cf">
	    <h2><?php echo $ld['activities']?></h2>
	  </div>
	  <div class="am-list-news-bd">
	    <ul class="am-list">
	    <?php if(isset($promotions)){foreach($promotions as $k=>$v){ ?>
	      <li class="am-g am-list-item-dated">
	        <a style="font-weight:bold;" href="<?php echo $html->url('/promotions/view/'.$v['Promotion']['id']);?>"><?php echo $v['PromotionI18n']['title'];?></a>
		    <h3 class="am-gallery-title" style="margin:0 0 10px;font-weight:normal;">
		      <?php echo $ld['promotion_time']?>: <span><?php echo date("Y-m-d",strtotime($v['Promotion']['start_time']));?> - <?php echo date("Y-m-d",strtotime($v['Promotion']['end_time']));?></span>
			  
		    </h3>
	      </li>
	    <?php }}?>
	    </ul>
	  </div>
	</div>
	</div>
  </div>
</div>