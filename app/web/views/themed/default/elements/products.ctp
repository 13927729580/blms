<?php
	$next_page="";
	if(isset($paging)&&$pagination->setPaging($paging)){
		$rightArrow = $ld['next']." ›";
		$next_page = $pagination->nextPage($rightArrow,false);
	}
?>
<div class="am-u-lg-9 am-u-md-9 am-u-sm-12 am-fr" id="product_wrapper">
	<pre id="next_page" class="am-hide"><?php  echo $next_page; ?></pre>
<?php if(!empty($products)){?>
<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }" id="product_events">
  <?php $flagnum=0;foreach($products as $k=>$v){?>
  <li class="am-margin-bottom-lg">
    <div class="am-gallery-item">
	  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
	  <h3 class="am-gallery-title" style="display:none">
      	<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
  	  </h3>
  	</div>
  </li>
  <?php $flagnum++;}?>
</ul>
<?php }else{echo "<h2 class='detail-h2' style='font-size:1.6rem;color:#909090;font-weight:normal'>".$ld['no_related_products']."</h2>";}?>
<!-- 分页 -->
<?php  if($sm['paging']['pageCount']>=1){?>
  <div class="pages am-pagination-right am-hide-sm-only">
  <?php
  if($pagination->setPaging($sm['paging'])):
    $leftArrow = "‹ ".$ld['previous'];
    $rightArrow = $ld['next']." ›";
    $prev = $pagination->prevPage($leftArrow,false);
    $prev = $prev?$prev:$leftArrow;
    $next = $pagination->nextPage($rightArrow,false);
    $next = $next?$next:$rightArrow;
    $pages = $pagination->pageNumbers("  ");
    //echo $pagination->result()."<br>";
    echo $prev." ".$pages." ".$next;
    //echo $pagination->resultsPerPage(NULL, ' ');
  endif;
  ?>
  </div>
  <div class="pull-action loading am-show-sm-only"><span class="am-icon-spinner am-icon-spin am-icon-lg"></span></div>
  <?php }?>
</div>
<style type='text/css'>
.pull-action{display:none;text-align: center;}
.pull-action.loading{display:block;height: 45px;line-height: 45px;color: #999;}
.pull-action.error{display:block;height: 45px;line-height: 45px;color: #0e90d2;}
</style>
<script type="text/javascript">
if($(window).width()<641 && $("#product_wrapper")){
var nextHref=$("#next_page a").prop("href");
var ajaxPageLock=true;
// 给浏览器窗口绑定 scroll 事件

$(window).bind("scroll",function(){
	var body_height=$(document).height();//获取页面高度
	var other_height=body_height-$("#product_events").height()-$("#product_events").offset().top;//显示在当前div下的剩余内容高度
	var AjaxLoadPro=function(){
    		// Ajax 翻页
            $.ajax( {
                url: nextHref,
                type: "get",
                success: function(data) {
                	ajaxPageLock=true;
                	var newElems=$(data).find("#product_events").html();
                	nextHref =$(data).find("#next_page a").prop("href");
                	$("#product_events").append(newElems);
                	
                	$("#product_wrapper  .pull-action").removeClass('loading');
                }
            });
    	};
	
	
    // 判断窗口的滚动条是否接近页面底部
    if( ($(document).scrollTop() + $(window).height()) > ($(document).height() - other_height - 10) && ajaxPageLock) {
    	 ajaxPageLock=false;
        // 判断下一页链接是否为空
        if( nextHref != undefined) {
        	$("#product_wrapper .pull-action").addClass('loading');
        	setTimeout(AjaxLoadPro, 1000);
        } else {
        	$("#product_wrapper .pull-action").removeClass('loading');
        	$("#product_wrapper .pull-action span").remove();
        	$("#product_wrapper .pull-action").html('木有了噢，最后一页了！');
        	$("#product_wrapper .pull-action").addClass('error');
        	
        	setTimeout(function(){
        		$("#product_wrapper .pull-action").remove();
        	}, 5000);
        	
        }
    }
});
}
$(function () {
	$('#product_events li').hover(function(){
		$(this).find('.am-gallery-title').toggle();
	});
});
</script>