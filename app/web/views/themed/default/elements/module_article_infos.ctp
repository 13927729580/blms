<style type='text/css'>
#article_info .auto_zoom *{margin:0;padding:0;}
#article_info .auto_zoom p,#article_info .auto_zoom ul,#article_info .auto_zoom ol{margin:1em 0px;}
#article_info .auto_zoom ul,#article_info .auto_zoom ol{padding-left:40px;}
#article_info .auto_zoom ul li{list-style:disc outside none;}
#article_info .auto_zoom ol li{list-style:decimal outside none;}
</style>
<div id="article_info" class="am-u-md-9 am-fl">
  <?php if($code_infos[$sk]['type']=="module_article_infos"){?>
  <article class="blog-main" style="margin-top: 10px;">
    	<h3 class="am-article-title blog-title am-text-center"><?php echo $sm['ArticleI18n']['title'];?></h3>
   	<h4 class="am-article-meta blog-meta" style="margin-top: 5px;margin-left: 12px;"><?php echo $ld['time']?>:<time><?php echo date("Y-m-d", strtotime($sm['Article']['created']));?></time>&nbsp;&nbsp;<?php if(!empty($sm['ArticleI18n']['author'])){echo $sm['ArticleI18n']['author'];}?></h4>
    	<div class="blog-content" style="margin-top: 20px;">
	  	<div class="auto_zoom"><?php echo $sm['ArticleI18n']['content'];?></div>
	  	<div class='am-cf am-margin-bottom-lg'>&nbsp;</div>
	</div>
  </article>
  <?php }?>
  <?php if(isset($configs['articles_comment_condition'])&&$configs['articles_comment_condition']!='0'){ ?><div id="article_comment" class="am-cf"></div><?php } ?>
</div>
<input type="hidden" id="article_id" value="<?php echo $article['Article']['id'] ?>">
<script type="text/javascript">
var wechat_shareTitle="<?php echo $sm['ArticleI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['ArticleI18n']['content']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if(trim($sm['ArticleI18n']['img01'])!=""&&$svshow->imgfilehave($server_host.(str_replace($server_host,'',$sm['ArticleI18n']['img01'])))){  ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$sm['ArticleI18n']['img01'])); ?>";
<?php } ?>
var article_id = $('#article_id').val();
article_comment()
function article_comment(){
	if(!document.getElementById('article_comment'))return;
	$.ajax({ 
		url: web_base+"/articles/article_comment/"+article_id,
		dataType:"html",
		type:"POST",
		success: function(data){
			$('#article_comment').html(data);
	    }
	});
}
</script>