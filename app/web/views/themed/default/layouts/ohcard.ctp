<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>OH卡体验</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<link rel="icon" type="image/png" href="<?php echo $html->url('/theme/image/logo.png'); ?>">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $html->url('/theme/image/logo.png'); ?>">
	<meta name="apple-mobile-web-app-title" content="" />
	<link href="<?php echo $webroot.'plugins/AmazeUI/css/amazeui.min.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
	<link href="<?php echo $webroot.'plugins/AmazeUI/css/app.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
	<link href="<?php echo $webroot.'plugins/AmazeUI/css/admin.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
	<script src="<?php echo $webroot.'plugins/AmazeUI/js/jquery.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.lazyload.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'plugins/tmpl/jquery.tmpl.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>

	<link href="<?php echo $webroot.'theme/css/default.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
	<script src="<?php echo $webroot.'js/selectlang?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
	<script src="<?php echo $webroot.'theme/js/default.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
</head>
<body>
	<!--[if lte IE 9]>
	<p class="browsehappy">为了获得更好的体验,建议您升级浏览器!</p>
	<![endif]-->
	<!-- content -->
	<div class="am-g am-g-fixed" id="page_content"><?php echo $content_for_layout;?></div>
	<button class='am-hide' type="button" id='CopyUserClick'>模拟用户按钮</button>
	<?php echo $this->element('pop_modal');echo $this->element('wechat_action'); ?>
	<!-- content -->
	<script src="<?php echo $webroot.'plugins/AmazeUI/js/utils.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
</body>
</html>