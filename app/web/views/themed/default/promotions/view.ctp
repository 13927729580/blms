<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head">
	  <?php echo $ld['event_title'];?>:<?php echo $promotion['PromotionI18n']['title']?>
	</div>
	<div  class="am-panel-bd">
 	  <p class="promotiontime">
		<?php echo $ld['activity_time']?>:&nbsp;<?php echo substr($promotion['Promotion']['start_time'],0,10).' - '.substr($promotion['Promotion']['end_time'],0,10); ?>
	  </p>
	  <!--活动描述-->
	  <?php if(isset($promotion['PromotionI18n']['short_desc']) && $promotion['PromotionI18n']['short_desc'] != '') {?>
	  <div id="category_info" class="auto_zoom"><?php echo $promotion['PromotionI18n']['short_desc'];?></div>
	  <?php }?>
	<?php if (isset($this->data['products']) && sizeof($this->data['products'])>0) { ?>
	<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
	  <?php foreach($this->data['products'] as $k=>$v) { ?>
	  <?php
		$v['Product']['user_price']='';
		if(isset($this->data['product_ranks'][$v['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']])){
			if(isset($this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0 && $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price']>0){
			  $v['Product']['user_price'] = $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
			}
		}
		if(isset($_SESSION['User']['User']['rank']) && isset($this->data['user_rank_list'][$_SESSION['User']['User']['rank']])  && $this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']>0 && $this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']<100){
			if(empty($v['Product']['user_price'])|| $v['Product']['user_price']>($this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($v['Product']['shop_price']))
			  $v['Product']['user_price']=($this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($v['Product']['shop_price']);
		}
	  ?>
	  <li>
	    <div class="am-gallery-item">
		  <!--<i class="am-icon-mobile am-icon-sm"></i>-->
		 	<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
		  <span class="like_icon am-gallery-like" style="">
		  	  <?php echo $html->image('/theme/default/img/like_icon.png',array('id'=>$v['Product']['id'],'style'=>'width:15px;height:15px;'));  ?>
		    <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
			  <?php echo $v['Product']['like_stat'];?>
			</span>
		  </span>
		  	<?php } ?>
		  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['Product']['img_detail'],'name'=>$v['ProductI18n']['name'],'sub_name'=>''));?>
		  <h3 class="am-gallery-title">
			<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>''));?>
		  </h3>
		</div>
	  </li>
	  <?php }?>
	</ul>
	<?php }?>
	</div>
  </div>

</div>
<div class="am-g am-g-fixed">
  <?php if(!empty($this->data['promotion_products'])){?>
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd my-head">
	  <?php echo $ld['special_products']?>
	</div>
	<div  class="am-panel-bd">
	<?php if (isset($this->data['promotion_products']) && sizeof($this->data['promotion_products'])>0) { ?>
	<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
	  <?php foreach($this->data['promotion_products'] as $k=>$v) { ?>
		<?php
			$v['Product']['user_price']='';
			if(isset($this->data['product_ranks'][$v['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']])){
				if(isset($this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0 && $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price']>0){
				  $v['Product']['user_price'] = $this->data['product_ranks'][$v['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
				}
			}
			if(isset($_SESSION['User']['User']['rank']) && isset($this->data['user_rank_list'][$_SESSION['User']['User']['rank']])  && $this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']>0 && $this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']<100){
				if(empty($v['Product']['user_price'])|| $v['Product']['user_price']>($this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($v['Product']['shop_price']))
				  $v['Product']['user_price']=($this->data['user_rank_list'][$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($v['Product']['shop_price']);
			}
		?>
	  <li>
	    <div class="am-gallery-item">
		  <!--<i class="am-icon-mobile am-icon-sm"></i>-->
		 	<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
		  <span class="like_icon am-gallery-like" style="">
		  	  <?php echo $html->image('/theme/default/img/like_icon.png',array('id'=>$v['Product']['id'],'style'=>'width:15px;height:15px;'));  ?>
		    <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
			  <?php echo $v['Product']['like_stat'];?>
			</span>
		  </span>
		  	<?php } ?>
<?php echo $svshow->productimagethumb($v['Product']['img_detail'],$svshow->sku_product_link($v['Product']['id'],$v['ProductI18n']['name'],$v['Product']['code'],$this->data['configs']['product_link_type']),array("alt"=>$v['ProductI18n']['name'],'width'=>$this->data['configs']['thumbl_image_width'],'height'=>$this->data['configs']['thumb_image_height']),$this->data['configs']['products_default_image'],$v['ProductI18n']['name']);?>
		  <h3 class="am-gallery-title">
			<?php echo $html->link( $v['ProductI18n']['sub_name'],$svshow->sku_product_link($v['Product']['id'],$v['ProductI18n']['name'],$v['Product']['code'],$this->data['configs']['product_link_type']),array("target"=>"_blank"),false,false);?>
		  </h3>
		</div>
	  </li>
	  <?php }?>
	</ul>
	<?php }?>
	</div>
  </div>
  <?php }?>
  <!--特惠品结束-->
</div>