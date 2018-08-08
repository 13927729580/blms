<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title_for_layout; ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="<?php echo isset($configs['shop_icon'])?$configs['shop_icon']:'';?>">
  <meta name="apple-mobile-web-app-title" content="<?php echo $title_for_layout; ?>" />
<script type="text/javascript">
	var admin_webroot = "<?php echo $admin_webroot;?>";
	var webroot = "<?php echo $webroot;?>";
	var backend_locale = "<?php echo $backend_locale;?>";
	var ip = "<?php echo $_SERVER['REMOTE_ADDR'] ?>";
</script>
<?php
	if($backend_locale=='eng'){
		$language = 'en';
	}elseif($backend_locale=='chi'){
		$language = 'zh-CN';
	}elseif($backend_locale=='jpn'){
		$language = 'jp';
	};
?>
<script type="text/javascript" src="<?php echo $admin_webroot;?>js/selectlang/<?php echo $backend_locale;?>"></script>
<link href="<?php echo $webroot.'plugins/AmazeUI/css/amazeui.min.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<link href="<?php echo $webroot.'plugins/AmazeUI/css/amazeui.page.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<script src="<?php echo $webroot.'plugins/jquery.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/datepicker.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/utils.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/listtable.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.page.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<?php
	if(Configure::read('debug')==0&&$configs['is_cache']=='1'){
		
	}else{
		echo $html->css('/skins/default/css/seevia.amazeui');
        	echo $javascript->link('/skins/default/js/common');
        	echo $javascript->link('/skins/default/js/seevia.amazeui');
	}
?>

</head>
<body class="am-with-topbar-fixed-top am-with-fixed-navbar">
<?php echo $this->element('header');?>
<!-- content -->
<div class="am-container">
	<?php
		if(isset($SubMenu)&&!empty($SubMenu)){
	?>
	<div class='layout_menu_content'>
		<div class='am-fl am-hide-sm-only am-padding-left-0 am-padding-right-0'><?php echo $this->element('menu'); ?></div>
		<div class='am-fr am-padding-left-0 am-padding-right-0'><?php
			if(isset($this->params)){
				if($this->params['controller'] !="pages" && $this->params['action'] !="home"){
					echo $this->element('ur_here', array('cache'=>'+0 hour','navigations'=>$navigations));
				}else{
					echo $this->element('ur_here');
				}
			}
			echo $content_for_layout;?></div>
		<div class='am-cf'></div>
	</div>
	<?php
		}else{
			if(isset($this->params)){
				if($this->params['controller'] !="pages" && $this->params['action'] !="home"){
					echo $this->element('ur_here', array('cache'=>'+0 hour','navigations'=>$navigations));
				}else{
					echo $this->element('ur_here');
				}
			}
			echo $content_for_layout;
		}
	?>
</div>

<button id="like-icon-btn" class="am-btn am-radius am-btn-sm" data-am-modal="{target: '#doc-modal-1'}" style="display:none;">like</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    </div>
  </div>
</div>

<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default "
id="navbar" style="display:none;">
<ul class="am-navbar-nav am-cf am-avg-sm-4">
    <li>
      <a href="/../" target="_blank">
        <span class="am-navbar-label"><?php echo $ld['browse_store'] ?></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $html->url('/users'); ?>">
        <span class="am-navbar-label"><?php echo $ld['customers'] ?></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $html->url('/orders'); ?>">
        <span class="am-navbar-label"><?php echo $ld['orders'] ?></span>
      </a>
    </li>
    <li>
      <a href="<?php echo $html->url('/pages/logout'); ?>">
        <span class="am-navbar-label"><?php echo $ld['log_out'] ?></span>
      </a>
    </li>
    <li>
      <a class="am-icon-info-circle am-icon-md" href="#my-actions" data-am-modal>
        <span><?php //echo $ld['about'] ?></span>
      </a>
    </li>
  </ul>
</div>

<!-- content end -->
<?php echo $this->element('footer');?>
</body>
</html>