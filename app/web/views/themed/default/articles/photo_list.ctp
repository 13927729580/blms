<style>
[class*="am-u-"] + [class*="am-u-"]:last-child {float: right;}
/*中屏*/
@media only screen and (max-width:1024px)
{
.pgoto_list .touxiang{width:100%;}
.pgoto_fu .pgoto_list{padding:20px 10px;}
.touxiang{width:100%;padding-bottom:10px;}
.pgoto_fu .name{text-align:center;}
.pgoto_fu .name{padding-left:0;}
.name_2{padding:10px 0;}
.pgoto_fu .jiejian{padding-left:8px;}
.pgoto_fu .jiejian_1{padding-top:8px;}
}
		 /*手机版*/
@media only screen and (max-width: 640px)
{
body .lunbo{padding:10px 0;}
body .jiejian{height:inherit;}
.pgoto_fu .pgoto_list{padding:20px 10px;}
.touxiang{width:100%;padding-bottom:10px;}
.pgoto_fu .name{text-align:center;}
.pgoto_fu .name{padding-left:0;}
.name_2{padding:10px 0;}
.pgoto_fu .jiejian{padding-left:8px;}
.pgoto_fu .jiejian_1{padding-top:8px;padding-right:15px;}
}
.jiesao_sp
{
border-bottom:3px solid #0e90d2;
    padding: 2px 5px;
    font-size:18px;
}
.jiejian
{
background:#f5f5f5;
height:125px;
font-size:14px;
}
.name
{
    line-height: 25px;
    padding-left:15px;
}
.name>a{margin-top:5px;}
.xiangqing
{
    text-align: center;
    background: #08afff;
    line-height: 25px;
    color: #fff;
    border-radius: 5px 5px 0 0;
    margin-top:10px;
}
.lunbo
{
max-width:1200px;
margin:0 auto;
width:95%;
padding:43px 0;
}
.am-slider-default{margin:0 0;}
.jiejian_1{color:#666;padding:15px 0 10px 15px;}
.xiangqing_1
{
	width: 100%;
	text-align: center;
    font-size: 14px;
    padding:0;
}
.jiejian .jiejian_2,.right_list{max-width:130px;float:right;}
.jiejian_2{height: 60%;margin-top:3%;border-left:1px solid #ddd;}
</style>
<div class="photo_fu">
<div class="am-g am-fbl">
<?php echo $this->element('ur_here')?>
<!--轮播-->
<div class="lunbo">
	<div class="am-slider am-slider-default" data-am-flexslider="{playAfterPaused: 8000}">
				<?php if (isset($flashes)) {foreach ($flashes as $k => $v) {?>
	  			<ul class="am-slides">
	  				<?php if (isset($v['FlashImage'])) {foreach ($v['FlashImage'] as $kk => $vv) {?>
					<li>
						<img src="<?php echo $vv['image'];?>" /> 
					</li>
					<?php }}?>
				</ul>
				<?php }}?>
	</div>
</div>
<!--轮播结束-->
<div class="jiessao">
	<div class="am-text-center"><span  class="jiesao_sp"><?php echo $CategoryArticleInfo['CategoryArticleI18n']['name'] ?></span></div>
</div>
<div class="pgoto_fu am-g">
<?php if (isset($articles)) {foreach ($articles as $k => $v) {?>

<div class="am-u-sm-12 pgoto_list">
		<div class="am-fl touxiang">
			<a href="<?php echo $html->url('/articles/'.$v['Article']['id']); ?>" <?php echo isset($v['Article']['seo_attribute'])?$v['Article']['seo_attribute']:''; ?>><img src="<?php echo $v['ArticleI18n']['img01'] ?>" alt="<?php echo $v['ArticleI18n']['title']; ?>" class="am-img-responsive" style="height:160px;width:153px;margin:0 auto;"></a>
		</div>
		<div class="am-u-lg-10 am-u-sm-12">
			<div class=" am-u-sm-12 am-u-md-12 am-u-lg-10 name">
				<a href="<?php echo $html->url('/articles/'.$v['Article']['id']); ?>" <?php echo isset($v['Article']['seo_attribute'])?$v['Article']['seo_attribute']:''; ?> class="am-block am-title-sty"><?php echo $v['ArticleI18n']['title'] ?></a>
			</div>
			<div class="am-hide-lg-only am-text-center name_2"><?php echo $v['ArticleI18n']['subtitle'];?></div>
			<div class="am-u-lg-2 am-hide-md-only am-hide-sm-only right_list">
				<div class="xiangqing"><a style="color:#fff;"href="/articles/<?php echo $v['Article']['id'] ?>">详情</a></div>
			</div>
			<div class="am-u-sm-12 am-u-md-12 am-u-lg-12 jiejian">
				<div class="jiejian_1 am-u-sm-12 am-u-md-12 am-u-lg-10">
					<a href="<?php echo $html->url('/articles/'.$v['Article']['id']); ?>" <?php echo isset($v['Article']['seo_attribute'])?$v['Article']['seo_attribute']:''; ?> style="color:#333;"><?php echo isset($v['ArticleI18n']['meta_description'])?$v['ArticleI18n']['meta_description']:'&nbsp;';?></a>
				</div>
				<div class="jiejian_2 am-hide-sm-only am-hide-md-only  am-u-lg-2 am-vertical-align">
					<div class="xiangqing_1 am-text-center am-vertical-align-middle"><?php echo $v['ArticleI18n']['subtitle'];?></div>
				</div>
			</div>
		</div>
		<div class="am-cf"></div>
	</div>
		<?php }} ?>
	<?php if(isset($articles) && sizeof($articles)>1){?>
	<?php echo $this->element('pager')?>
	<?php }?>
</div>
</div>
</div>
<script>

</script>
<style>
.am-text-trun{
	overflow : hidden;
	text-overflow: ellipsis;
	max-height: 70px;
}
.article-list-content{
	font-size: 14px;
	margin:8px 10px 20px 30px;
	line-height:24px;
}
.am-author{
	color: #999;
	margin: 2px 0px 16px;
	font-size: 12px;
}
    .pgoto_list{
    	    padding: 30px 0 0px 30px;
    }
	.am-title-sty{
		/*color:#8b4513!important;*/
		font-size: 16px;
		color:#333;
		margin-top:5px;
	}

</style>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $CategoryArticleInfo['CategoryArticleI18n']['name']; ?>";
var wechat_lineLink="<?php echo $server_host.'/articles/category/'.$CategoryArticleInfo['CategoryArticle']['id']; ?>";
<?php if(trim($CategoryArticleInfo['CategoryArticle']['img01'])!=''){ ?>
var wechat_imgUrl="<?php echo $server_host.$CategoryArticleInfo['CategoryArticle']['img01']; ?>";
<?php } ?>
var wechat_descContent="<?php echo $CategoryArticleInfo['CategoryArticleI18n']['meta_description']; ?>";
</script>