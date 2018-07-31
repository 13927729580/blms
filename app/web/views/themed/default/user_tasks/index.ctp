<?php //pr($user_list)?>
<style>
@media only screen and (max-width: 640px)
{
	img
	{
	width:200px;
	height:100px;
	}
}
#course_chapter_list .admin-user-img
{
display:none;
}

	.pages 
{
padding-top:50px;
}
	a
{
color:#434343;
}
	.usercenter_fu .user_task
{
padding:10px 20px 30px 20px;
    border: 1px solid #ccc;
    padding-right: 15px;
    box-shadow: 0 0 15px #ccc;
    margin:20px 0 50px 10px;
}

	h3
{
    font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
    border-bottom: 1px solid #ccc;
}
	.header_title
{
margin:20px 0 50px 10px;
border:1px solid #ccc;
border-radius:3px;
box-shadow: 0 0 15px #ccc;
padding: 10px 20px 30px 20px;
}
.header_title .list
{
	padding-left:20px;
	padding-top:10px;
}
.header_title .list>p
{
	padding:10px 0 ;
	font-size:14px;
	color:#424242;
}
.kaishi_a
{
    display: inline-block;
    border: 1px solid #149842;
    border-radius: 3px;
    color: #fff;
    background: #149842;
    padding: 0 0;
    width: 140px;
    line-height: 35px;
    font-size: 16px;
}
.kaishi_a:hover
{
color:#fff;

}
.yuan
{
    height: 20px;
    width:20px;
    line-height:17px;
    text-align: center;
    border: 1px solid #149842;
    border-radius: 20px;
    background: #149842;
    color: #fff;
}
.xiangqing_buzou
{
padding-top:35px;
}
.henxian
{
border-top:1px solid #ccc;
top:10px;
}
.title_shuom
{
padding-top:10px;color:#666;font-size:16px;
}
.jyz
{
padding:15px 0 25px 0;font-size:16px;color:#424242;
}
.jyz>span
{
color:#149842;padding-left:15px;
}
.user_task ul li
{
padding: 25px 20px;
}
.user_task ul li>div:first-child
{
max-height:80px;font-size:16px;padding-bottom:25px;
}
.user_task{
    margin: 20px 0 50px 10px;
    /*border: 1px solid #ccc;*/
    border-radius: 3px;
    /*box-shadow: 0 0 15px #ccc;*/
    padding: 10px 20px 30px 20px;
}
.user_task h3{
	height: 50px;
	line-height: 38px;
}
</style>
<div class="am-g user_task">
	<h3>我的任务</h3>
	<div class='am-cf'>
		<?php if(isset($ability_level_infos)&&sizeof($ability_level_infos)>0){ ?>
		<table class='am-table'>
			<tr>
				<th>技能</th>
				<th>等级</th>
				<th>经验值</th>
			</tr>
			<?php foreach($ability_level_infos as $v){ ?>
			<tr>
				<th><?php echo $v['Ability']['name']; ?></th>
				<td><?php echo $v['AbilityLevel']['name']; ?></td>
				<td><?php echo $v['AbilityLevel']['experience_value']; ?></td>
			</tr>
			<?php } ?>
		</table>
		<?php } ?>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails">
			<?php if(isset($UserTask_lists)&&sizeof($UserTask_lists)>0){foreach($UserTask_lists as $k=>$v){?>
			<li>
				<div class='am-text-left'>
					<?php echo $k+1;?>
					<?php echo $html->link($v['UserTask']['name'],'/user_tasks/view/'.$v['UserTask']['id']); ?>
				</div>
			</li>
			<?php }}else{ ?>
			<div style="text-align: center;padding:40px 0 20px 0;">暂无任务</div>
			<?php } ?>
		</ul>
		<?php echo $this->element('pager'); ?>
	</div>
</div>