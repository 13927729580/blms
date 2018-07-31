<link href="<?php echo $webroot.'plugins/umeditor/themes/default/css/umeditor.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<script src="<?php echo $webroot.'plugins/umeditor/third-party/template.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/umeditor/umeditor.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/umeditor/umeditor.config.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/umeditor/lang/'.(isset($editorLang)?$editorLang:'zh-cn').'/'.(isset($editorLang)?$editorLang:'zh-cn').'.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<textarea <?php echo isset($editorName)?"name='{$editorName}'":''; ?> <?php echo isset($editorId)?"id='{$editorId}'":''; ?>><?php echo isset($editorValue)?$editorValue:''; ?></textarea>
<script type='text/javascript'>
var UMEditorList=typeof(UMEditorList)=='undefined'?{}:UMEditorList;//页面编辑器集合
if(typeof(UM)!='undefined'){
	loadUMEditor({
		id:"<?php echo isset($editorId)?$editorId:''; ?>",
		lang:"<?php echo isset($editorLang)?$editorLang:'zh-cn'; ?>"
	});
}else if(typeof(UM)!='undefined'){
	$.getScript(webroot+"plugins/umeditor/lang/<?php echo isset($editorLang)?$editorLang:'zh-cn'; ?>/<?php echo isset($editorLang)?$editorLang:'zh-cn'; ?>.js");
	loadUMEditor({
		id:"<?php echo isset($editorId)?$editorId:''; ?>",
		lang:"<?php echo isset($editorLang)?$editorLang:'zh-cn'; ?>"
	});
}

function loadUMEditor(umeditor){
	if(umeditor.id==''||typeof(UM)=='undefined')return;
	var um = UM.getEditor(umeditor.id,{
		initialFrameWidth:'100%',
		lang:umeditor.lang
	});
	if($("#"+umeditor.id).parents("div.am-modal").length>0){
		um.setHeight(150);
	}
	UMEditorList[umeditor.id]=um;
	return um;
}
</script>