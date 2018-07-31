<style>
.xuexi_shouye
{
	margin-bottom:70px;
}
.xuexi_shouye .xuexi_img
{
overflow:hidden;
height:200px;
line-height: 200px;
padding:20px;
}
.xuexi_shouye_img
{
	max-width: 200px;
}

.xuexi_shouye_li
{
	padding:0 10px 20px 10px;
}
.xuexi_biaoti
{
	color:#424242;
	font-weight: 600;
	padding:10px 0;
	font-size:14px;
}
.xuexi_biaoti:hover
{
	color:#2d8344;
}
.xuexi_shouye_rs
{
	text-align: right;
	padding-bottom:10px;
	color:#aaaaaa;
	font-size:12px;
	padding-right:20px;
}
/*设置边框阴影*/
.xuexi_shouye_div
{
	border:none;
	box-shadow: 0px 2px 5px #ccc;
}
h3
{
margin-bottom:20px;font-size:16px;
}
.course_list{width:95%;margin:1rem auto;}
</style>
<div class="course_list">
	<h3>推荐课程</h3>
	<ul class="am-avg-lg-4 am-avg-md-3 am-avg-sm-2 xuexi_shouye">
		<?php if(isset($sm)&&sizeof($sm)>0){foreach($sm as $v){ ?>
		<li class="xuexi_shouye_li">
			<div class="xuexi_shouye_div">
				<a href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>">
					<div class="am-text-center xuexi_img">
						<img class="xuexi_shouye_img" src="<?php echo $v['Course']['img']!=''?$v['Course']['img']:'/theme/default/images/default.png'; ?>" >
					</div>
					<div class="am-text-center xuexi_biaoti">
						<?php echo $v['Course']['name']; ?>
					</div>
				</a>
				<div class="xuexi_shouye_rs"><?php echo isset($v['course_user'])?$v['course_user']:0; ?>人学习</div>
			</div>
		</li>
		<?php }} ?>
	</ul>
</div>