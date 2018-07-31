<div class='category_course'>
<?php
	if(isset($sm['category_tree'])&&!empty($sm['category_tree'])){
?>
		<ul class='am-list course_category_list'>
<?php
		foreach($sm['category_tree'] as $v){
			if(!isset($sm['category_course_tree'][$v['CourseCategory']['id']]))continue;
?>
			<li><?php echo $html->link($v['CourseCategory']['name'],'/course_categories/view/'.$v['CourseCategory']['id']); ?><hr /></li>
			<li>
				<div class='am-g'>
					<ul class='am-avg-lg-4 am-avg-md-3 am-avg-sm-2'>
					<?php
						foreach($sm['category_course_tree'][$v['CourseCategory']['id']] as $kk=>$vv){if($kk>4)break;
					?>
						<li>
							<div>
								<div><a href="<?php echo $html->url('/courses/view/'.$vv['Course']['id']); ?>"><?php echo $html->image(trim($vv['Course']['img'])!=''?$vv['Course']['img']:$configs['shop_default_img'],array('title'=>$vv['Course']['name'])); ?></a></div>
								<div><?php echo $html->link($vv['Course']['name'],'/courses/view/'.$vv['Course']['id']); ?></div>
							</div>
							<div class='am-text-left'><?php echo isset($sm['course_read_list'][$vv['Course']['id']])?$sm['course_read_list'][$vv['Course']['id']]:0; ?>人学习</div>
						</li>
					<?php
						}
					?>
					</ul>
				</div>
			</li>
<?php
		}
?>
		</ul>
<?php
	}else if(isset($sm['course_list'])){
?>
		<h2><?php echo $category_detail['CourseCategory']['name']; ?></h2>
<?php
		if(sizeof($sm['course_list'])>0){
?>
		<ul class='category_course_list am-avg-lg-4 am-avg-md-3 am-avg-sm-2'>
			<?php
				foreach($sm['course_list'] as $kk=>$vv){
			?>
				<li>
					<div>
						<div><a href="<?php echo $html->url('/courses/view/'.$vv['Course']['id']); ?>"><?php echo $html->image(trim($vv['Course']['img'])!=''?$vv['Course']['img']:$configs['shop_default_img'],array('title'=>$vv['Course']['name'])); ?></a></div>
						<div><?php echo $html->link($vv['Course']['name'],'/courses/view/'.$vv['Course']['id']); ?></div>
					</div>
					<div class='am-text-left'><?php echo isset($sm['course_read_list'][$vv['Course']['id']])?$sm['course_read_list'][$vv['Course']['id']]:0; ?>人学习</div>
				</li>
			<?php
				}
			?>
		</ul>
<?php
		 echo $this->element('module_page',array('paging'=>isset($sm['paging'])?$sm['paging']:array()));
		}
	}
?>
</div>
<style type='text/css'>
div.category_course{max-width:1200px;width:95%;margin:0 auto;}
div.category_course ul.am-list.course_category_list>li:nth-child(odd){border:none;}
div.category_course ul.am-list.course_category_list>li:nth-child(odd) a,div.category_course ul.am-list.course_category_list>li:nth-child(odd) a:hover{color: #333;font-size:1.5em;padding-bottom: 0.5rem;}
div.category_course ul.am-list.course_category_list>li:nth-child(odd) hr{margin:1px auto;padding-bottom: 0.5rem;}
div.category_course ul.am-list.course_category_list>li:nth-child(even){margin-bottom:2.5rem;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li>div{max-width:220px;margin:0 auto;border: none;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li>div:first-child{/*box-shadow: 0px 2px 5px #ccc;*/text-align:center;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li>div:last-child{color:#999;font-size:12px;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div img{max-width:220px;max-height:140px;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div>div:first-child{min-height:150px;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div>div:first-child a{line-height:150px;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div>div:last-child{padding:0.5rem 0px 0px 0px;margin:0 auto;width:98%;display:inline-block;white-space: nowrap;  overflow: hidden;text-overflow:ellipsis;text-align:left;}
div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div>div:last-child a,div.category_course ul.am-list.course_category_list>li:nth-child(even) div li div>div:last-child a:hover{color: #333;font-size:14px;}

div.category_course h2{margin:1rem auto;}
.category_course_list li>div{max-width:220px;margin:0 auto;border: none;}
.category_course_list li>div:first-child{/*box-shadow: 0px 2px 5px #ccc;*/text-align:center;}
.category_course_list li>div:last-child{color:#999;font-size:12px;}
.category_course_list li>div img{max-width:220px;max-height:140px;}
.category_course_list li>div>div:first-child{min-height:150px;}
.category_course_list li div>div:first-child a{line-height:150px;}
.category_course_list li div>div:last-child{padding:0.5rem 0px 0px 0px;margin:0 auto;width:98%;display:inline-block;white-space: nowrap;  overflow: hidden;text-overflow:ellipsis;text-align:left;}
.category_course_list li div>div:last-child a,.category_course_list li div>div:last-child a:hover{color: #333;font-size:14px;text-align:left;}
</style>