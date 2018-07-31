<style type='text/css'>
	.first_foot
{
position: relative;
    buttom:0;
}
.am-container, .am-g-fixed{max-width: inherit;}
a{color:#000;}
a:hover{color:#149842;}
.zuixin{background:#fafafa;}
.zuixin>.am-tabs
{
margin:0 auto;max-width:1200px;width:95%;
}

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
		padding:0 0 20px 0;
	}
	.course_category
	{
		margin-bottom:10px;
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
.zuixin_div
{
	padding:15px 0 15px 15px;
}
.zuixin_div>a:first-child
{
	margin-right:10px;
}
.am-g-fixed .xuexi_list
{
max-width:1200px;margin:0 auto;width:95%;
}
.fenlei>li
{display:inline-block;margin:0 10px;font-size:14px;}
#course_form ul>li.link-checked a,a.link-checked{color:green;}
#course_form ul>li:first-child span:after{content:':';}

.course_list li>div img{max-width: 200px;max-height:200px;}
.div_img{overflow:hidden;height:120px;}
.course_tab_list{padding-right:20px;}

.xuexi_shouye_img{
	transition: all 0.6s;  
}
.xuexi_shouye_img:hover{
	transform: scale(1.1); 
}
.div_img{
	height: 200px;
	line-height: 100%;
}
</style>
<?php
	//pr($course_category_data);
	//pr($course_type_data);
	//pr($sm);
?>
<div class="xuexi_list am-g">
	<form action="<?php echo $html->url('/course_categories/index'); ?>" id="course_form" class="am-g" style="max-width: 1200px;margin:0 auto;width: 95%;">
		<input type='hidden' name="course_orderby" id="course_orderby" value="<?php echo isset($course_orderby)?$course_orderby:''; ?>" />
	
		<div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
		<div class="course_category am-g">
			<input type='hidden' value="<?php echo isset($course_category_code)?$course_category_code:''; ?>" name="course_category_code" />
			<div class="am-fl course_tab_list"><span>分类</span></div>
			<div class="am-u-sm-10 am-u-md-10 am-u-lg-11">
				<ul class="fenlei">
				<li class="<?php echo isset($course_category_code)&&$course_category_code==''?'link-checked':''; ?>"><a href="javascript:void(0);" data=""><?php echo $ld['all']; ?></a></li>
				<?php if(isset($course_category_data)&&sizeof($course_category_data)>0){foreach($course_category_data as $v){ ?>
				<li class="<?php echo isset($course_category_code)&&$course_category_code==$v['CourseCategory']['code']?'link-checked':''; ?>"><a href="javascript:void(0);" data="<?php echo $v['CourseCategory']['code']; ?>"><?php echo $v['CourseCategory']['name']; ?></a></li>
				<?php }} ?>
			</ul>
			</div>
			
		</div>
		<div class="course_type am-g">
			<input type='hidden' value="<?php echo isset($course_type_code)?$course_type_code:''; ?>" name="course_type_code" />
			<div class="am-fl course_tab_list"><span>类型</span></div>
			<div class="am-u-sm-10 am-u-md-10 am-u-lg-11">
			<ul class="fenlei">
				<li class="<?php echo isset($course_type_code)&&$course_type_code==''?'link-checked':''; ?>"><a href="javascript:void(0);" data=""><?php echo $ld['all']; ?></a></li>
				<?php if(isset($course_type_data)&&sizeof($course_type_data)>0){foreach($course_type_data as $v){ ?>
				<li class="<?php echo isset($course_type_code)&&$course_type_code==$v['CourseType']['code']?'link-checked':''; ?>"><a href="javascript:void(0);" data="<?php echo $v['CourseType']['code']; ?>"><?php echo $v['CourseType']['name']; ?></a></li>
				<?php }} ?>
			</ul>
		</div>
		</div>
		</div>
		<div class="am-cf"></div>
	</form>
</div>
<div class="zuixin">
<div class="am-tabs" data-am-tabs>
		<div class="zuixin_div"><?php
				echo $html->link('最新','javascript:void(0);',array('data'=>'','class'=>!isset($course_orderby)?'link-checked':''));
				echo $html->link('最热','javascript:void(0);',array('data'=>'clicked','class'=>isset($course_orderby)&&$course_orderby=='clicked'?'link-checked':'')); 
			?>
		</div>
		<div class="am-tabs-bd">
			 <div class="am-tab-panel am-fade am-in am-active" id="tab1">
     			<div class="course_list am-g">
     				<div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
     					<ul class="am-avg-lg-4 am-avg-md-3 am-avg-sm-2">
					<?php if(isset($sm['course_list'])&&sizeof($sm['course_list'])>0){foreach($sm['course_list'] as $v){ ?>
					<li class="kecheng_list">
						<div class="kcfl_div">
							<a href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>">
								<div class="am-text-center div_img" style="line-height: 200px;"><img class="xuexi_shouye_img" src="<?php echo $v['Course']['img']!=''?$v['Course']['img']:'/theme/default/images/default.png'; ?>" >
								</div>
								<div class="am-text-center kcfl_biaoti"><?php echo $v['Course']['name']; ?>
								</div>
							</a>
							<div class="kcfl_rs"><?php echo isset($sm['course_user'][$v['Course']['id']])?$sm['course_user'][$v['Course']['id']]:0; ?>人学习</div>
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
	$("#course_form ul li a").click(function(){
		var link_value=$(this).attr('data');
		$(this).parents('div.am-g:eq(0)').find("input[type='hidden']").val(link_value);
		$("#course_form").submit();
	});
	
	$(".zuixin_div a").click(function(){
		var link_value=$(this).attr('data');
		$("#course_orderby").val(link_value);
		$("#course_form").submit();
	});
});
</script>