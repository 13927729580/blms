<?php  if($code_infos[$sk]['type']=="module_pro_image"){?>
<div class="am-u-lg-5 am-u-md-5 am-u-sm-12">
    <div class="am-g am-hide-sm-only">
        <figure data-am-widget="figure" class="am am-figure am-figure-default " data-am-figure="{ pureview: 'auto'}">
    <?php echo $html->image(isset($sm[0])&&$sm[0]['ProductGallery']['img_big']!=''?$sm[0]['ProductGallery']['img_big']:$configs['products_default_image'],array('data-rel'=>isset($sm[0])&&$sm[0]['ProductGallery']['img_big']!=''?$sm[0]['ProductGallery']['img_big']:$configs['products_default_image'],'width'=>'400','height'=>'400'));  ?>
        </figure>
    </div>
     <?php //pr($sm) ?>
    <!-- 相册开始 -->
    <div class="am-g am-g-fixed am-product-gallery">
        <figure data-am-widget="figure" class="am-figure am-figure-default " data-am-figure="{ pureview: 'auto'}">
        <div class="am-slider am-slider-default am-slider-carousel" data-am-flexslider="{itemWidth:200,itemMargin: 5, slideshow: true}">
          <ul class="am-slides">

            <?php $i=0;foreach($sm as $p){?>
            <?php //pr($p) ?>
            <?php //pr($configs) ?>
        	  <li>
        	    <div class="am-gallery-item">
        	      <?php echo $html->image($p['ProductGallery']['img_big']!=''?$p['ProductGallery']['img_big']:$configs['products_default_image']); ?>
        		</div>
        	  </li>
        	<?php } ?>
          </ul>
        </div>
        </figure>
    </div>
    <!-- 相册结束 -->
</div>

<?php }?>