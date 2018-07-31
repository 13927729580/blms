<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php
	if(isset($title_for_layout)){
   		echo $title_for_layout;
   	}else{
    	echo $configs['shop_title'];
   	}?></title>
  <meta name="description" content="<?php if(isset($meta_description)){echo $meta_description;} ?>" />
  <meta name="keywords" content="<?php if(isset($meta_keywords)){echo $meta_keywords;} ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <?php if(isset($configs['shop_icon'])){?>
  <link rel="icon" type="image/png" href="<?php echo isset($configs['shop_icon'])?$configs['shop_icon']:'';?>">
  <?php }?>
  <link rel="apple-touch-icon-precomposed" href="/theme/default/img/seevia.png">
  <meta name="apple-mobile-web-app-title" content="<?php
	if(isset($title_for_layout)){
   		echo $title_for_layout;
   	}else{
    	echo $configs['shop_title'];
   	}?>" />
<?php
	echo isset($configs['head_content'])?$configs['head_content']:'';
?>
<script type="text/javascript" src="<?php echo $html->url('/js/selectlang/'.LOCALE);?>"></script>
<link href="<?php echo $webroot.'plugins/AmazeUI/css/amazeui.min.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<link href="<?php echo $webroot.'plugins/AmazeUI/css/app.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<link href="<?php echo $webroot.'plugins/AmazeUI/css/admin.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<script src="<?php echo $webroot.'plugins/AmazeUI/js/jquery.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.lazyload.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<?php
	//加载js
	if(Configure::read('debug')==0&&$configs['is_cache']){

	}else{
		echo $htmlSeevia->css(array('seevia.amazeui','embed.default'));
	}
	echo $htmlSeevia->js(array("seevia.amazeui"));
?>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">为了获得更好的体验,建议您升级浏览器!</p>
<![endif]-->
<div id="wrapper">
<?php
    if(isset($open_config)){
        echo isset($open_config['HEADER-AREA-INFORMATION'])?$open_config['HEADER-AREA-INFORMATION']['value']:'';
    }
    echo $this->element('header');
?>
	<div class="am-cf am-g am-g-fixed">
		<?php echo $this->element("ur_here");?>
		<?php echo $this->element('users_menu',array('user_list'=>isset($user['User'])?$user:array()));echo $this->element('users_offcanvas',array('user_list'=>isset($user['User'])?$user:array()));	?>
		<div class="am-u-lg-9 am-u-md-8 am-u-sm-12"><?php echo $content_for_layout;?></div>
	</div>
	<!-- content end -->
<?php
    echo $this->element('footer');
    echo $this->element('wechat_action');
    echo $this->element('popup_login_register');
    echo $this->element('alert_message');
?>
</div>
<script src="<?php echo $webroot.'plugins/AmazeUI/js/utils.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
</body>
</html>