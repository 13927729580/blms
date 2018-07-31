<div class='am-g' id='search_result'>
<?php
	if(isset($product_list)&&!empty($product_list)){
?>
	<div class='am-panel' id="product_list">
		<h3 class="am-panel-hd"><?php echo $ld['product'] ?></h3>
		<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
<?php
		foreach($product_list as  $v){
?>
			<li>
				<div class="am-gallery-item">
				<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
					<span class="like_icon am-gallery-like" style="display:none">
						<?php echo $html->image('/theme/default/img/like_icon.png',array('id'=>$v['Product']['id'],'style'=>'width:15px;height:15px;'));  ?><span id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
					    <?php if(isset($v['Product']['like_stat'])){echo $v['Product']['like_stat'];}else{echo '0';}?>
					  </span>
					</span>
				<?php } ?>
					<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
					<h3 class="am-gallery-title">
			          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
			      	</h3>
			      </div>
			</li>
<?php
		}
?>
		</ul>
		<?php echo $this->element('module_page',array('paging'=>$product_page_list)); ?>
	</div>
<?php
	}
	if(isset($article_list)&&!empty($article_list)){
?>
	<div class='am-panel'>
		<h3 class="am-panel-hd"><?php echo $ld['article'] ?></h3>
		<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
<?php
		foreach($article_list as  $v){
?>
			<li>
				<div class="am-gallery-item">
					<?php echo $svshow->seo_link(array('type'=>'A','id'=>$v['Article']['id'],'img'=>($v['ArticleI18n']['img01']!=''?$v['ArticleI18n']['img01']:$configs['products_default_image']),'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
					<h3 class="am-gallery-title">
			          		<?php echo $svshow->seo_link(array('type'=>'A','id'=>$v['Article']['id'],'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
			      	</h3>
			      </div>
			</li>
<?php
		}
?>
		</ul>
		<?php	echo $this->element('module_page',array('paging'=>$article_page_list));	?>
	</div>
<?php
	}
	
	if(isset($course_list)&&!empty($course_list)){
?>
		<div class='am-panel'>
			<h3 class="am-panel-hd">课程</h3>
			<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
<?php
		foreach($course_list as  $v){
?>
				<li>
					<div class="am-gallery-item">
						<a href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>" title="<?php echo $v['Course']['name']; ?>" ><img src="<?php echo trim($v['Course']['img'])!=''?$v['Course']['img']:$configs['products_default_image'] ?>" /></a>
						<h3 class="am-gallery-title">
							<a href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>" title="<?php echo $v['Course']['name']; ?>" ><?php echo $v['Course']['name']; ?></a>
				      	</h3>
				      </div>
				</li>
<?php
		}
?>
			</ul>
			<?php	echo $this->element('module_page',array('paging'=>$course_page_list));	?>
		</div>
<?php
	}
	
	if(isset($evaluation_list)&&!empty($evaluation_list)){
?>
		<div class='am-panel'>
			<h3 class="am-panel-hd">评测</h3>
			<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
<?php	foreach($evaluation_list as $k=>$v){ ?>
				<li>
					<div class="am-gallery-item">
						<a href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>" title="<?php echo $v['Evaluation']['name']; ?>"><img src="<?php echo trim($v['Evaluation']['img'])!=''?$v['Evaluation']['img']:$configs['products_default_image'] ?>" /></a>
						<h3 class="am-gallery-title">
							<a href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>" title="<?php echo $v['Evaluation']['name']; ?>"><?php echo $v['Evaluation']['name']; ?></a>
				      	</h3>
				      </div>
				</li>
<?php
		}
?>
			</ul>
			<?php echo $this->element('module_page',array('paging'=>$evaluation_page_list));	?>
		</div>
<?php
	}
	
	if(isset($activity_list)){
		//pr($activity_list);
		//pr($activity_tag_list);
		//pr($activity_page_list);
	}


?>

<?php if(isset($activity_list)&&!empty($activity_list)){ ?>
	<div class='am-panel'>
			<h3 class="am-panel-hd">活动</h3>
			<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
			<?php foreach($activity_list as $k=>$v){ ?>
				<li>
					<div class="am-gallery-item">
						<a href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>" title="<?php echo $v['Activity']['name']; ?>"><img src="<?php echo trim($v['Activity']['image'])!=''?$v['Activity']['image']:$configs['products_default_image'] ?>" /></a>
						<h3 class="am-gallery-title">
							<a href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>" title="<?php echo $v['Activity']['name']; ?>"><?php echo $v['Activity']['name']; ?></a>
				      	</h3>
				      </div>
				</li>
			<?php } ?>
			</ul>
			<?php echo $this->element('module_page',array('paging'=>$activity_page_list));	?>
		</div>
<?php } ?>
</div>
<style type='text/css'>
#search_result li div.am-gallery-item>a{text-align:center;}
#search_result li img{width:auto;max-height:160px;}
#search_result h3.am-gallery-title{display:none;}
#search_result h3.am-gallery-title>a{width:100%;overflow: hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<script type='text/javascript'>
$(function () {
	$('#search_result li').hover(function(){
		$(this).find('.am-gallery-title').toggle();
	});
});
</script>