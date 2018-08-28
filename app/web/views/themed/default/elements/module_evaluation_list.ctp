<div class='category_course'>
<?php
	if(isset($evaluation_category_data)&&!empty($evaluation_category_data)){
?>
		<ul class='am-list course_category_list'>
<?php
		foreach($evaluation_category_data as $v){
?>
			<li><?php echo $html->link($v['EvaluationCategory']['name'],''); ?><hr /></li>
			<li>
				<div class='am-g'>
					<ul class='am-avg-lg-4 am-avg-md-3 am-avg-sm-2'>
					<?php
						foreach($evaluation_category_data as $kk=>$vv){?>
						<?php
						    foreach($sm['evaluation_list'] as $v){
						        if($vv['EvaluationCategory']['code']==$v['Evaluation']['evaluation_category_code']){?>
                                <li>
                                    <div>
                                        <div><a href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>"><?php echo $html->image(trim($v['Evaluation']['img'])!=''?$v['Evaluation']['img']:$configs['shop_default_img'],array('title'=>$v['Evaluation']['name'])); ?></a></div>
                                        <div><?php echo $html->link($v['Evaluation']['name'],'/evaluations/view/'.$v['Evaluation']['id']); ?></div>
                                    </div>
                                    <div class='am-text-left'><?php echo isset($sm['evaluation_user_total'][$v['Evaluation']['id']])?$sm['evaluation_user_total'][$v['Evaluation']['id']]:0; ?>人学习</div>
                                </li>
						 <?php }
						    }
					    }?>
					</ul>
				</div>
			</li>
<?php
		}
    }
?>
		</ul>
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