<?php if(isset($sm['category']['SubCategory'])&&sizeof($sm['category']['SubCategory'])>0){?>
<style type='text/css'>
#article_category{width:100%;max-width:1200px;}
.pages{margin-top:0;}
.am-container, .am-g-fixed{max-width:1200px;width:95%;}
ul .dongtai_fu{margin: 0px 30px 0px 0;border-top: 2px solid #0e90d2;}
.dongtai_fu a{display:inline-block;margin:12px 0;font-weight:600;color:#aaa;}
.dongtai_fu a:hover{color:#aaa;}
#article_category .biaoti_list{font-size:14px;margin-right:30px;}
#article_category>ul>li{min-height:150px;padding-bottom:20px;}
#article_category .am-nav>li>a{padding:0 0;display:inline-block;}
#article_category .am-nav>li+li{margin-top:0px;padding-bottom:0px;}
#article_category ul li div a,#article_category ul li div[class*=am-u-] a:hover{border:none;background:none;color:#666;display:inline-block;text-align:left;}
#article_category ul li div.suojin a:last-child,#article_category ul li div[class*=am-u-].suojin a:last-child:hover{font-size:12px;border:none;background:none;color:#999;text-align:left;}
#article_category ul li div.div_2 a:nth-child(2){width:94%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;}
#article_category ul li div.div_2 a:nth-child(3),#article_category ul li div.div_2 a:nth-child(3):hover{width:100%;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 3;overflow: hidden;}
.tupian_list{margin-bottom:10px;}
.suojin{padding-left:10px;}
.biaoti_list img{display:inline;max-width: 100%;max-height: 100%;}
.biaoti_list span{color:#666;font-size:12px;position: relative;top: -6px;}
@media only screen and (max-width: 1024px){
	body .biaoti_list img{width:initial}
}
@media only screen and (max-width: 640px){
	body .suojin{text-align:center;}
	body .biaoti_list img{width:initial}
	body ul .dongtai_fu{margin-right:0;}
	body #article_category .biaoti_list{margin-right:0;}
	body .suojin{padding-left:0;padding-top:10px;}
	.tupian_list>div:first-child{text-align:left;}
	.dongtai_fu{text-align:center;}
	.biaoti_list>.tupian_list:first-child>div{text-align:center;}
	.biaoti_list>.tupian_list:first-child>.suojin{text-align:left;}
	.biaoti_list span{top: -7px;}
}
@media(min-device-width:375px)and(max-device-width:667px)and(-webkit-min-device-pixel-ratio:2){
	.biaoti_list span{top: 0px;}
}
</style>
<div id="article_category" class="am-margin-top-lg">
    <ul class="am-nav am-avg-sm-1 am-avg-md-2 am-avg-lg-3">
	<?php foreach($sm['category']['SubCategory']as $k=>$v){?>
	  <li>
		<div class="dongtai_fu">
			<a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" ><?php echo $v['CategoryArticleI18n']['name']?></a>
		</div>
		<?php $sub_category_articles=isset($sm['sub_category_articles'][$v['CategoryArticle']['id']])?$sm['sub_category_articles'][$v['CategoryArticle']['id']]:array();
		  	if(sizeof($sub_category_articles)>0){$kk=0;
		?>
	    		<ul class="am-nav biaoti_list am-thumbnails">
	    		<?php foreach($sub_category_articles as $kkk=>$vvv){ ?>
	    			<li>
    					<?php if($kk==0&&(isset($vvv['ArticleI18n']['img01'])&&trim($vvv['ArticleI18n']['img01'])!='')){ ?>
						<div class="am-u-lg-5 am-u-sm-12" style="min-height: 104px;line-height: 104px;"><a href="<?php echo $html->url('/articles/'.$vvv['Article']['id']); ?>"><?php echo $html->image($vvv['ArticleI18n']['img01'],array('alt'=>$vvv['ArticleI18n']['title'])); ?></a></div>
						<div class="am-u-lg-7 am-u-sm-12 div_2 <?php echo isset($vvv['ArticleI18n']['img01'])&&!empty($vvv['ArticleI18n']['img01'])?'suojin':''?>">
							<span class="dian <?php echo isset($vvv['ArticleI18n']['img01'])&&!empty($vvv['ArticleI18n']['img01'])?'am-hide':'am-show'?>">● &nbsp;</span>
							<a href="<?php echo $html->url('/articles/'.$vvv['Article']['id']); ?>" title="<?php echo $vvv['ArticleI18n']['title']?>"><?php echo $vvv['ArticleI18n']['title']?></a>
							<a href="<?php echo $svshow->seo_link_url(array('type'=>'A','name'=>$vvv['ArticleI18n']['title'],'id'=>$vvv['Article']['id']));?>"><?php echo $vvv['ArticleI18n']['meta_description']; ?></a>
						</div>
						<div class='am-cf'></div>
    					<?php }else{?>
    						<div class="div_2"><span class="dian">● &nbsp;</span><a href="<?php echo $html->url('/articles/'.$vvv['Article']['id']); ?>" title="<?php echo $vvv['ArticleI18n']['title']?>"><?php echo $vvv['ArticleI18n']['title']?></a></div>
    					<?php }?>
	    				</li>
	    			<?php $kk++;if($kk>5)break;}?>
	    		</ul>
	    	<?php } ?>
	  </li>
	<?php } ?>
	</ul>
</div>
<?php }else{ $cid=isset($this->params['id'])?$this->params['id']:0;?>
<?php if(trim($sm['category']['CategoryArticleI18n']['detail'])!=''){ ?>
<div style="margin-left:30px;max-width:70%;margin-bottom:18px; width:70%;" ><?php echo $sm['category']['CategoryArticleI18n']['detail'];?></div>
<?php } ?>
<div id="article_category" class="am-u-md-3 am-hide-sm-only am-fr">
<?php if($code_infos[$sk]['type']=="module_article_category"){ $article_categories_tree = $sm['article_categories_tree'];?>
  <?php if($sm['category']['CategoryArticle']['tree_show_type']==0){ 
  	if(isset($sm['direct_subids']['0'])){
  	  foreach($sm['direct_subids']['0'] as $k=>$v){   ?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>
  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==1){
  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']])){
  	  foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']] as $k=>$v){?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>
  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==2){
  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['id']])){foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['id']] as $k=>$v){
  	?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>		
  <?php }}}?>	  
  	  
  <?php foreach($article_categories_tree as $k=>$v){?>
  <div class="am-titlebar am-titlebar-default" style="display:none;">
    <h2 class="am-titlebar-title <?php if($cid==$v['CategoryArticle']['id']){echo 'am-active';}?>">
	  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" ><?php echo $v['CategoryArticleI18n']['name']?></a>
    </h2>
  </div>
  <?php if(!empty($v['SubCategory'])){?>
  <ul class="am-nav" style="display:none;">
	<?php foreach($v['SubCategory'] as $kk=>$vv){?>
	<li class="<?php if($cid==$vv['CategoryArticle']['id']){echo 'am-active';}?>">
	  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vv['CategoryArticleI18n']['name'],'id'=>$vv['CategoryArticle']['id']));?>" ><?php echo $vv['CategoryArticleI18n']['name']?></a>
	</li>
	<?php if(!empty($vv['SubCategory'])){?>
	  <?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv['CategoryArticle']['id']){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vvv['CategoryArticleI18n']['name'],'id'=>$vvv['CategoryArticle']['id']));?>" ><?php echo $vvv['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php }?>
	<?php }?>
	<!-- 3 end -->
	<?php }?>
  </ul>
  <?php }?>
  <!-- 2 end -->
  <?php }?>
<?php }?>
</div> 
<div id="a_category" class="am-user-menu am-offcanvas">
  <div class="am-offcanvas-bar category_list" style="background:#fff;">
    <?php if($code_infos[$sk]['type']=="module_article_category"){ $article_categories_tree = $sm['article_categories_tree'];?>
	  <?php if($sm['category']['CategoryArticle']['tree_show_type']==0){ 
	  	if(isset($sm['direct_subids']['0'])){
	  	  foreach($sm['direct_subids']['0'] as $k=>$v){ ?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>
	  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==1){
	  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']])){
	  	  foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']] as $k=>$v){?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>
	  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==2){
	  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['id']])){foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['id']] as $k=>$v){
	  	?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>		  
	  <?php }}}?>	  
	  	  
	  <?php foreach($article_categories_tree as $k=>$v){?>
	  <div class="am-titlebar am-titlebar-default" style="display:none;">
	    <h2 class="am-titlebar-title <?php if($cid==$v['CategoryArticle']['id']){echo 'am-active';}?>">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" ><?php echo $v['CategoryArticleI18n']['name']?></a>
	    </h2>
	  </div>
	  <!-- 2 -->
	  <?php if(!empty($v['SubCategory'])){?>
	  <ul class="am-nav" style="display:none;">
		<?php foreach($v['SubCategory'] as $kk=>$vv){?>
		<li class="<?php if($cid==$vv['CategoryArticle']['id']){echo 'am-active';}?>">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vv['CategoryArticleI18n']['name'],'id'=>$vv['CategoryArticle']['id']));?>" ><?php echo $vv['CategoryArticleI18n']['name']?></a>
		</li>
		<!-- 3 -->
		<?php if(!empty($vv['SubCategory'])){?>
		  <?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv['CategoryArticle']['id']){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vvv['CategoryArticleI18n']['name'],'id'=>$vvv['CategoryArticle']['id']));?>" ><?php echo $vvv['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php }?>
		<?php }?>
		<!-- 3 end -->
		<?php }?>
	  </ul>
	  <?php }?>
	  <!-- 2 end -->
	  <?php }?>
	<?php }?>
  </div>
</div>
<?php } ?>
<?php if(isset($CategoryArticleInfo['CategoryArticle'])){ ?>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $CategoryArticleInfo['CategoryArticleI18n']['name']; ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if(trim($CategoryArticleInfo['CategoryArticle']['img01'])!=''){ ?>
var wechat_imgUrl="<?php echo $server_host.$CategoryArticleInfo['CategoryArticle']['img01']; ?>";
<?php } ?>
var wechat_descContent="<?php echo addslashes($CategoryArticleInfo['CategoryArticleI18n']['meta_description']); ?>";
</script>
<?php } ?>