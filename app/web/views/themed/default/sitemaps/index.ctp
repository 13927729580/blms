<style type="text/css">
	.am-color a{color:#000}
	.am-color div a{color:#000}
	.del-font{font-size:15px;font-weight:10px;float:left}
	.del-smfont{font-size:14px;font-weight:0px}
	.change-space{padding:4px}
</style>
<div class="am-g am-g-fixed">
  <div class="am-panel-group" id="accordion">
	<div class="am-panel am-panel-default">
	  <div class="am-panel-hd change-space">
	    <h4 class="am-panel-title change-space" data-am-collapse="{parent: '#accordion', target: '#do-not-say-1'}">
	      <?php echo $ld['categories']?>
	    </h4>
	  </div>
	  <div id="do-not-say-1" class="am-panel-collapse am-collapse am-in">
	    <div class="am-panel-bd">
	      <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
	  		am-avg-md-3 am-avg-lg-5 am-gallery-default change-right" data-am-gallery="{ pureview: true }">
	       	<?php $i=0; foreach($product_categories_tree as $k=>$v){?>
			  <li class="am-color">
			    <dl class="del-font"><b><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$v['CategoryProductI18n']['name'],'id'=>$v['CategoryProduct']['id']));?></b></dt>
				<?php if(!empty($v['SubCategory'])){?>
				<?php foreach($v['SubCategory'] as $kk=>$vv){?>
				  <dd class="del-smfont"><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$vv['CategoryProductI18n']['name'],'id'=>$vv['CategoryProduct']['id']));?></dd>
				<?php if(!empty($vv['SubCategory'])){?>
				<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
				  <span style="width:100%;padding-left:20px;" class="del-smfont"><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$vvv['CategoryProductI18n']['name'],'id'=>$vvv['CategoryProduct']['id']));?></span><br>
				<?php }?>
				<?php  }?>
				<?php }?>
				<?php  }?>
			    </dl>
				</font>
			  </li>
			<?php $i++;}?>
		  </ul>
	    </div>
	  </div>
	</div>
  </div>
  <div class="am-panel-group" id="accordion">
	<div class="am-panel am-panel-default">
	  <div class="am-panel-hd change-space">
	    <h4 class="am-panel-title change-space" data-am-collapse="{parent: '#accordion', target: '#do-not-say-2'}">
	      <?php echo $ld['article_categories']?>
	    </h4>
	  </div>
	  <div id="do-not-say-2" class="am-panel-collapse am-collapse am-in">
	    <div class="am-panel-bd">
	      <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
	  		am-avg-md-3 am-avg-lg-5 am-gallery-default" data-am-gallery="{ pureview: true }">
			<?php $j=0;foreach($article_lists as $k=>$v){if($k==0 || empty($article_categories_assoc[$k]))continue;?>
			<li class="am-color">
			  <dl>
				<dt><?php if(isset($article_categories_assoc[$k]['CategoryProductI18n']['name'])){echo $article_categories_assoc[$k]['CategoryProductI18n']['name'];}?></dt>
				<?php $i=1;foreach($v as $kk=>$vv){ if($i>10){break;} $i++;?>
				<dd class="del-smfont"><?php echo $svshow->seo_link(array('type'=>'A','name'=>$vv['ArticleI18n']['title'],'sub_name'=>$vv['ArticleI18n']['title'],'id'=>$vv['Article']['id']));?></dd>
				<?php }?>
			  </dl>
			</li>
			<?php $j++;}?>
		  </ul>
	    </div>
	  </div>
	</div>
  </div>
</div>
