<style>
@media only screen and (max-width: 640px){
.pingce_fu .evaluation_tab_1{padding-right:10px;}}
.first_foot{position:relative;buttom:0;}
a{color:#000;}
a:hover{color:#149842;}
.zuixin{max-width:1200px;margin:0 auto;}
.am-tabs-bd .am-tab-panel
{
	padding:0 0;
}
.kcfl_biaoti
{
	color:#424242;
	font-weight: 600;
	padding:10px 0;
	font-size:14px;
}
.kcfl_biaoti:hover
{
	color:#2d8344;
	font-size:14px;
}
.kcfl_rs
{
	text-align: right;
	padding-bottom:10px;
	color:#aaaaaa;
	font-size:12px;
	padding-right:20px;
}
	#course_form
	{
		padding:10px 0 25px 0;
	}
	#course_form>.course_type>ul>li>a
	{
		color:#424242;
	}
		#course_form>.course_type>ul>li>a:hover
	{
		color:#129843;
	}
	#course_form>.course_category>ul>li>a
	{
		color:#424242;
	}
	#course_form>.course_category>ul>li>a:hover
	{
		color:#129843;
	}
	.course_list
	{
		margin-bottom:40px;
	}
	.kecheng_list
	{
		padding:0 15px 15px 0;
	}
	/*阴影*/
	.kcfl_div
{
	border:none;
	box-shadow: 0px 3px 5px #ccc;
}
.am-nav-tabs
{
	border:none;
}
.am-tabs-bd
{
	border:none;
}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	border:none;
}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	color:#149842;
	background-color: #fafafa;
}
.am-tabs ul>li>a
{
	color:#000;
}
.fenlei>li
{display:inline-block;margin:0 10px;font-size:15px;}
.zuixin_div
{
	padding:0.5rem 0;
}
.zuixin_div>a:first-child
{
	margin-right:10px;
}
.pingce_fu{max-width:1200px;margin:0 auto;width:95%;margin-bottom:1rem;}
#evaluation_form{padding:0px;}
#evaluation_form ul>li.link-checked a,a.link-checked{color:green;}
#evaluation_form ul>li:first-child span:after{content:':';}
.course_list li>div img{width:100%;max-height:120px;}
.evaluation_tab_1{padding-right:20px;}
.zuixin>.am-tabs{margin:0 auto;max-width:1200px;width:95%;}
.div_img{overflow:hidden;height:120px;}
</style>
<?php
	//pr($course_category_data);
	//pr($course_type_data);
	//pr($sm);
?>
<div class="pingce_fu">
	<form action="<?php echo $html->url('/evaluation_categories/index'); ?>" id="evaluation_form" class="am-g">
		<input type='hidden' value="<?php echo isset($evaluation_orderby)?$evaluation_orderby:''; ?>" name="evaluation_orderby" id="evaluation_orderby" />
		<div class="course_category  am-g">
			<input type='hidden' value="<?php echo isset($evaluation_category_code)?$evaluation_category_code:''; ?>" name="evaluation_category_code" />
			<div class="am-fl evaluation_tab_1"><span>分类:</span></div>
			<div class="am-u-sm-10 am-u-md-11 am-u-lg-11">
				<ul class="fenlei">
					<li class="<?php echo isset($evaluation_category_code)&&$evaluation_category_code==''?'link-checked':''; ?>"><a href="javascript:void(0);" data=""><?php echo $ld['all']; ?></a></li>
					<?php if(isset($evaluation_category_data)&&sizeof($evaluation_category_data)>0){foreach($evaluation_category_data as $v){ ?>
					<li class="<?php echo isset($evaluation_category_code)&&$evaluation_category_code==$v['EvaluationCategory']['code']?'link-checked':''; ?>"><a href="javascript:void(0);" data="<?php echo $v['EvaluationCategory']['code']; ?>"><?php echo $v['EvaluationCategory']['name']; ?></a></li>
					<?php }} ?>
				</ul>
			</div>
		</div>
	</form>
</div>
<div class="zuixin">
<div class="am-tabs" data-am-tabs>
		<div class="zuixin_div"><?php
				echo $html->link('最新','javascript:void(0);',array('data'=>'','class'=>!isset($evaluation_orderby)?'link-checked':''));
				echo $html->link('最热','javascript:void(0);',array('data'=>'clicked','class'=>isset($evaluation_orderby)&&$evaluation_orderby=='clicked'?'link-checked':'')); 
			?>
		</div>
		<div class="am-tabs-bd">
			 <div class="am-tab-panel am-fade am-in am-active" id="tab1">
     			<div class="course_list am-g">
     				<div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
     					<ul class="am-avg-lg-4 am-avg-md-3 am-avg-sm-2">
					<?php if(isset($sm['evaluation_list'])&&sizeof($sm['evaluation_list'])>0){foreach($sm['evaluation_list'] as $v){ ?>
					<li class="kecheng_list">
						<div class="kcfl_div">
							<a href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>">
								<div class="am-text-center div_img" style="line-height:120px;"><img class="xuexi_shouye_img" style="width:auto;height:100%;max-width:200px;max-height:200px;" src="<?php echo $v['Evaluation']['img']!=''?$v['Evaluation']['img']:'/theme/default/images/default.png'; ?>">
								</div>
								<div class="am-text-center kcfl_biaoti"><?php echo $v['Evaluation']['name']; ?>
								</div>
							</a>
							<div class="kcfl_rs"><?php echo isset($sm['evaluation_user_total'][$v['Evaluation']['id']])?$sm['evaluation_user_total'][$v['Evaluation']['id']]:0; ?>人学习</div>
						</div>
					</li>
					<?php }} ?>
				</ul>
				<?php echo $this->element('module_page',array('paging'=>isset($sm['paging'])?$sm['paging']:0)); ?>
     				</div>
	</div>
	   		 </div>
		    
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#evaluation_form ul li a").click(function(){
		var link_value=$(this).attr('data');
		$(this).parents('div.am-g:eq(0)').find("input[type='hidden']").val(link_value);
		$("#evaluation_form").submit();
	});
	
	$(".zuixin_div a").click(function(){
		var link_value=$(this).attr('data');
		$("#evaluation_orderby").val(link_value);
		$("#evaluation_form").submit();
	});
});
</script>