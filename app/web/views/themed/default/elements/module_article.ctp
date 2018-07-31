<?php //pr($sm); ?>
<ul style="width: 100%;">
  <?php if(isset($sm['category'])){foreach($sm['category'] as $v){?>
    <li style="width: 33%;float: left;">
        <div class="dongtai_fu " style="border-top: 2px solid #0e90d2;width: 90%;height: 35px;line-height: 50px;">
          <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" style="color: #666;font-weight: 600;"><?php echo $v['CategoryArticleI18n']['name']?></a>
        </div>
        <?php if(isset($v['Articles'])&&sizeof($v['Articles'])>0) {?>
        <ul  id="article_list_ul">
        	<?php foreach($v['Articles'] as $kk=>$vv){//pr($vv); ?>
            <li class="am-cf article_list_li" style="margin-bottom: 50px;">
              <?php if($kk==0&&(isset($vv['ArticleI18n']['img01'])&&trim($vv['ArticleI18n']['img01'])!='')){ ?>
                <div style="width: 40%;height: 140px;float: left;">
                    <a href="<?php echo $html->url('/articles/'.$vv['Article']['id']); ?>" >
                      <img  style="border:1px solid #ccc;padding: 2px;" src="<?php  echo $vv['ArticleI18n']['img01']; ?>" alt="" width="100%" >
                    </a>
                </div>
                <div style="width: 60%;float: left;padding-left: 15px;" class="text">
                    
                    <?php echo $svshow->seo_link(array('type'=>'A', 'name'=>$vv['ArticleI18n']['title'], 'sub_name'=>$vv['ArticleI18n']['title'], 'id'=>$vv['Article']['id']));?>
                </div>
                <?php }else{?>
                  <div class="am-u-lg-12 am-u-sm-12 div_2"><span class="dian">● &nbsp;</span><a style="color: #666;" href="<?php echo $html->url('/articles/'.$vv['Article']['id']); ?>"><?php echo $vv['ArticleI18n']['title']?></a></div>
              <?php } ?>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </li>
  <?php }}?>
</ul>


<style>
.am-titlebar.am-titlebar-default{
  display: none;
}
.article_list_li .text a{
  color: #666;
}
</style>
<script type="text/javascript">

var article_list_ul = document.querySelectorAll('#article_list_ul');
for(var i = 0;i<article_list_ul.length;i++){
  console.log(article_list_ul[i].childNodes[0].childNodes);
  article_list_ul[i].childNodes[0].childNodes.style,display = 'none';
}
</script>