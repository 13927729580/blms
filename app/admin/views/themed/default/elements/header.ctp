<header class="am-topbar am-topbar-fixed-top" id="header">
  <div class="am-container ">
	<div class="am-fl admin_logo">
		<?php 
			if($configs['admin_logo'] ==""){
				$configs['admin_logo']="/admin/skins/default/img/logo.png";
			}
				echo $svshow->link($html->image($configs['admin_logo'],array('style'=>'max-height:38px;')),$admin_webroot."/pages/home",array("class"=>'am-text-center'));
		
      	?> <div class="am-dropdown" data-am-dropdown>
        	<div id="admin_userinfo"><a href="javascript:void(0);" id="admin_username" class="am-dropdown-toggle username" title="<?php echo $admin["name"] ?>"><?php echo $admin["name"].""; ?></a></div>
		  <ul class="am-dropdown-content">
			<?php if (isset($_SESSION['dev']) && $_SESSION['dev'] == 1) { ?>
			<li><?php echo $html->link($ld["system"],"javascript:void(0);",array('onclick'=>'seevia_system_modified(this);'));?></li>
			<?php } ?>
			<li><?php echo $html->link($ld["alter_password"], "/pages/edit");?></li>
			<li><?php echo $html->link($ld["log_out"], "/pages/logout");?></li>
		  </ul>
		</div>
      	<button class="am-btn am-btn-sm am-btn-success am-btn-xs am-show-sm-only am-menu-openbtn am-margin-top-sm am-fl am-margin-right-lg" data-am-offcanvas="{target: '#doc-oc-demo2', effect: 'push'}"><span class="am-sr-only"></span>
<span class="am-icon-bars"></span></button>
	</div>
	<div class="am-fl am-padding-top-0 am-padding-right-0">
	<nav data-am-widget="menu" class="am-menu am-menu-dropdown2" data-am-menu-collapse>
		<a href="javascript:void(0)" class="am-menu-toggle am-hide">
			<i class="am-menu-toggle-icon am-icon-bars"></i>
		</a>
	  <?php if(isset($menus)&&sizeof($menus)>0){ ?>
	    <ul class="am-menu-nav am-avg-lg-3 am-avg-md-3 am-avg-sm-2 am-collapse">
	    <?php foreach($menus as $k=>$v){?> 
	    	<li class="<?php echo isset($DefaultMenuId)&&$DefaultMenuId==$v['Menu']['id']?'am-default-menu':''; ?>"><?php echo $html->link($v['MenuI18n']['name'],isset($v['SubMenu'][0])?$v['SubMenu'][0]['Menu']['link']:'javascript:void(0);'); ?></li>
	    <?php } ?>
	    </ul>
	  <?php } ?>
	</nav>
  </div>
</header>
<div id="doc-oc-demo2" class="am-offcanvas am-header-menu">
  <div class="am-offcanvas-bar">
    <div class="am-offcanvas-content">
    	<?php if(isset($menus)&&sizeof($menus)>0){ ?>
    		<?php foreach($menus as $k=>$v){ ?>
    			<a id="nav_link_<?php echo $v['Menu']['id']; ?>" class="first_nav" data-am-collapse href="#second-nav-<?php echo $v['Menu']['id']; ?>"><?php echo $v['MenuI18n']['name']; ?></a>
    			<?php if(isset($v['SubMenu'])&&sizeof($v['SubMenu'])>0){ ?>
    				<nav id="nav<?php echo $v['Menu']['id']; ?>">
    					<ul id="second-nav-<?php echo $v['Menu']['id']; ?>" class="am-nav am-collapse am-cate-3">
    						<?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
		    				<li><a href="<?php echo $html->url($vv['Menu']['link']); ?>" class="second_nav am-collapsed"><?php echo $vv['MenuI18n']['name']; ?></a>
		    					</li>
		    				<?php } ?>
    					</ul>
    				</nav>
    			<?php } ?>
    		<?php } ?>
    	<?php } ?>
    </div>
  </div>
</div>
<div class="head_div" style="clear:both;"></div>
<!-- 系统编辑 -->
<div class='am-modal am-modal-no-btn' id='seevia_system'>
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo $ld['system']; ?>
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd">
			<div class='am-g'></div>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#admin_username").dropdown({justify: '#admin_userinfo'});

$('#doc-oc-demo2 .am-cate-3').on("open.collapse.amui", function() {
	$("a.first_nav").addClass("am-open");
});

$('#doc-oc-demo2 .am-cate-3').on("close.collapse.amui", function() {
	$("a.first_nav").removeClass("am-open");
});

</script>