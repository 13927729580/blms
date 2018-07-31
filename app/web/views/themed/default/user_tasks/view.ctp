<style type="text/css">
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
    margin:0 0 50px 10px;
}

	h3
{
    font-size: 25px;
    color: #424242;
    letter-spacing: -2px;
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
#start ul li
{
padding: 25px 20px;
}
#start ul li>div:first-child
{
max-height:80px;font-size:16px;padding-bottom:25px;
}
</style>
<div class="header_title">
	<h3><?php echo $UserTask_lnfo['UserTask']['name']; ?></h3>
	<div>
		<div class="title_shuom">任务简要说明</div>
		<div class="list">
			<?php echo $UserTask_lnfo['UserTask']['description']; ?>
		</div>
	</div>
	<div class="am-g am-text-center jyz">获得经验值&nbsp;&nbsp;<span><?php echo $UserTask_lnfo['UserTask']['task_experience_value']; ?></span></div>
	<div class="am-g am-text-center"><a class="kaishi_a" <?php echo $task_complete?'disabled':''; ?> href="<?php echo $html->url($UserTask_lnfo['UserTask']['task_url']); ?>">开始</a></div>
</div>
<script type='text/javascript'>
$(function(){
	$('a.kaishi_a').click(function(){
		var disabled_status=$(this).attr('disabled');
		if(disabled_status=='disabled'){
			return false;
		}
	});
});
</script>