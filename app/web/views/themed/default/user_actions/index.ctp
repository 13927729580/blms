<?php //pr($user_action_lists);
?>
<style>
@media only screen and (max-width: 640px)
{
.users_actions .ul_dongtai .riqi{padding-right:0px;}
}
	#course_chapter_list .admin-user-img
{
display:none;
}
.div_dongtai
{
border-bottom:1px solid #ccc;
font-size:25px;
color:#424242;
}
.ul_dongtai
{
padding-left:20px;
}
.ul_dongtai>li
{
padding:20px 0;
}

.ul_dongtai>li a
{
color:#424242;
}
.fenlei
{
padding-bottom:10px;
}
.dongtai_tab
{
overflow:hidden;white-space: nowrap;text-overflow: ellipsis;
}
.ul_dongtai .riqi
{
padding-right:20px;font-size:14px;color:#888;
}
.usercenter_fu .users_actions
{
margin:20px 0 50px 10px;
border:1px solid #ccc;
border-radius:3px;
box-shadow: 0 0 15px #ccc;
padding: 10px 20px 30px 20px;
}
.ul_dongtai li
{
border-bottom:1px solid #ccc;
}
h3
{
    font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
}
</style>
<div class="am-g users_actions">
	<div class="am-cf div_dongtai">
		<h3>我的动态</h3>
	</div>
	<ul class="am-list ul_dongtai">
		<?php if(isset($user_action_lists)&&sizeof($user_action_lists)>0){foreach($user_action_lists as $v){ ?>
		<li>
			<div class="am-g">
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9 dongtai_tab"><span class="fenlei"><?php  if($v['UserAction']['type']=='evaluation'){echo '参与了评测：';}else{echo '学习了课程:' ;}?></span><a target="_blank" href="<?php 
						$link_url=$v['UserAction']['type']=='evaluation'?'/evaluations/view/':'/courses/view/';echo $html->url($link_url.$v['UserAction']['type_id']); ?>"><?php echo $v['UserAction']['content']; ?></a></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-right riqi"><?php echo date("m月d日",strtotime($v['UserAction']['created'])); ?></div>
				<div class="am-cf"></div>
			</div>
		</li>
		<?php }} ?>
	</ul>
	<?php if(isset($user_action_lists)&&sizeof($user_action_lists)>0){ echo $this->element('pager'); } ?>
</div>