<?php
	if(isset($SubMenu)&&!empty($SubMenu)){
?>
<div id="shortcut_menu">
	<ul class="am-menu-nav am-avg-sm-1">
		<?php foreach($SubMenu as $v){ ?>
		<li class="<?php echo isset($DefaultSubMenuId)&&$DefaultSubMenuId==$v['Menu']['id']?'am-default-menu':''; ?>"><?php echo $html->link($v['MenuI18n']['name'],$v['Menu']['link'],array('title'=>$v['MenuI18n']['name'])); ?></li>
		<?php } ?>
	</ul>
</div>
<?php
	}
?>