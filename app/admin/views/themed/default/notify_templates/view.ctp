<style type="text/css">
.am-panel-bd label.am-u-lg-2{line-height: 37px;}
.am-panel-default .am-form-group.am-g{margin-top: 5px;}
.am-form-group>div>div{margin-top:0;}
.scrollspy-nav {
    top: 0;
    z-index: 500;
    background: #5eb95e;
    width: 100%;
    padding: 0 10px;
  }

  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
    color: #eee;
    padding: 10px 20px;
    display: inline-block;
  }

  .scrollspy-nav a.am-active {
    color: #fff;
    font-weight: bold;
  }
  
  .crumbs{
  	padding-left:0;
  	margin-bottom:22px;
  }
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<!-- 导航条 -->
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
	<ul>
	 	<li><a href="#notify_information"><?php echo $ld['basic_information'] ?></a></li>
	<?php if (isset($this->data['NotifyTemplate']['id'])&&$this->data['NotifyTemplate']['id'] != 0) { ?>
	    	<li><a href="#notify_type"><?php echo $ld['template_channel_list'] ?></a></li>
	<?php } ?>
	</ul>
</div>

<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-panel-group" style="width:100%;padding-left:0;padding-right:0;">
<form class="am-form" method="post" onsubmit="return notify_form()" action="<?php echo $html->url('/notify_templates/view/'.isset($this->data['NotifyTemplate']['id'])?$this->data['NotifyTemplate']['id']:0 ) ?>">
	<!-- 右上角按钮 -->
	<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		<button style="margin-right:20px;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius add_notify"><?php echo $ld['submit'] ?>
		</button>
		<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['reset'] ?>
		</button>
	</div>
	<div class="am-panel am-panel-default" id="notify_information">
		<div class="am-panel-hd am_hd_background" style="border-bottom:1px solid #ddd;font-weight:600">
			<?php echo $ld['basic_information'] ?>
		</div>
		<div class="am-panel-bd am-cf">
			<input type="hidden" name="data[NotifyTemplate][id]" value="<?php echo isset($this->data['NotifyTemplate']['id'])?$this->data['NotifyTemplate']['id']:'0' ?>">
			
			<div class="am-form-group am-g">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['system'] ?></label>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[NotifyTemplate][system_code]">
							<option value=""><?php echo $ld['please_select']; ?></option>
							<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($this->data['NotifyTemplate']['system_code'])&&$this->data['NotifyTemplate']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
				</div>
			</div>
			<div class="am-form-group am-g">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['module'] ?></label>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<input type='text' name="data[NotifyTemplate][module_code]" value="<?php echo isset($this->data['NotifyTemplate']['module_code'])?$this->data['NotifyTemplate']['module_code']:''; ?>" />
				</div>
			</div>
			<div class="am-form-group am-g">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['template_code'] ?></label>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					<?php if (isset($this->data['NotifyTemplate']['id'])&& $this->data['NotifyTemplate']['id'] != 0) { ?>
					<span style="line-height:37px;"><?php echo $this->data['NotifyTemplate']['code'] ?></span>
					<?php }else{ ?>
					<input type="text" name="data[NotifyTemplate][code]" value="" onblur="code_blur(this.value)">
					<?php } ?>
				</div>
			</div>
			<div class="am-form-group am-g">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['template_description'] ?></label>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
		   		<textarea rows="3" style="resize: none;" cols="20" name="data[NotifyTemplate][description]" id="notify_templates_description"><?php echo $this->data['NotifyTemplate']['description'] ?></textarea>
		   	</div>
			</div>
			<div class="am-form-group am-g">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['template_state'] ?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="line-height:37px;">
					<label class="am-radio am-success" style="display:inline;margin-right:0.8rem;">
					<input type="radio" data-am-ucheck name="data[NotifyTemplate][status]" <?php if (isset($this->data['NotifyTemplate']['status'])&&$this->data['NotifyTemplate']['status'] == 1) {
						echo "checked" ;}elseif (!isset($this->data['NotifyTemplate']['status'])) {
						echo "checked";
						} ?> value="1"><?php echo $ld['yes'] ?>
					</label>
					<label class="am-radio am-success" style="display:inline;">
					<input type="radio" data-am-ucheck name="data[NotifyTemplate][status]" <?php if (isset($this->data['NotifyTemplate']['status'])&&$this->data['NotifyTemplate']['status'] == 0) {
						echo "checked" ;} ?> value="0"><?php echo $ld['no'] ?>
					</label>
				</div>
			</div>
			
		</div>
	</div>
</form>

<?php if (isset($this->data['NotifyTemplate']['id'])&&$this->data['NotifyTemplate']['id'] != 0) { ?>
	<div class="am-panel am-panel-default am-margin-top-lg" id="notify_type">
		<div class="am-panel-hd am_hd_background" style="border-bottom:1px solid #ddd;font-weight:600">
			<?php echo $ld['template_channel_list'] ?>
		</div>
		<div class="am-panel-bd am-cf">
			<div class="am-cf">
				<a style="color:#fff" href="javascript:void(0)" onclick="notify_template_add()" class="am-btn am-btn-warning am-seevia-btn-add am-btn-xs am-radius am-fr"><span class="am-icon-plus"></span><?php echo $ld['add_template'] ?></a>
			</div>
			<div class="am-panel-hd am-cf" style="border-bottom:1px solid #ddd">
				<div class="am-u-sm-2 am-u-md-2 am-u-lg-2" style="font-weight:600"><?php echo $ld['channel_type'] ?></div>
				<div class="am-u-lg-3 am-u-sm-3 am-u-sm-3" style="font-weight:600"><?php echo $ld['template_title'] ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:600"><?php echo $ld['template_state'] ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="font-weight:600"><?php echo $ld['operate'] ?></div>
			</div>
		<?php if (isset($notify_template_type_list)&&sizeof($notify_template_type_list)>0) {foreach ($notify_template_type_list as $k => $v) {?>
			<div class="am-g">
				<div class="am-panel-bd am-cf" style="padding-bottom:0;">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
	<?php echo $Resource_info['notity_type'][$v['NotifyTemplateType']['type']] ?>&nbsp;
    </div>
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-break">
     <?php echo $v['NotifyTemplateTypeI18n']['title'] ?>&nbsp;
    </div>
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-break">
      <?php if ($v['NotifyTemplateType']['status'] == 0) { ?>
      <span style="cursor:pointer;" onclick="change_state(this,'notify_templates/ajax_notify_template_type_status',<?php echo $v['NotifyTemplateType']['id'];?>)" class="am-icon-close am-no"></span>
      <?php } ?>
      <?php if($v['NotifyTemplateType']['status'] == 1) { ?>
      <span style="cursor:pointer;" onclick="change_state(this,'notify_templates/ajax_notify_template_type_status',<?php echo $v['NotifyTemplateType']['id'];?>)" class="am-icon-check am-yes"></span>
      <?php } ?>
    </div>
	<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
		<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="javascript:void(0)" onclick="notify_edit('<?php echo $v['NotifyTemplateType']['id'] ?>')" ><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit'] ?></a>
		<a href="javascript:void(0);" onclick="notify_view_remove('<?php echo $v['NotifyTemplateType']['id'] ?>')" class="am-btn am-btn-default am-btn-xs am-text-danger"><span class="am-icon-trash-o"></span><?php echo $ld['delete']; ?></a>
	</div>
</div>
			<div class="am-cf"></div>
		</div>
		<?php }} ?>
		</div>
	</div>
<?php } ?>
</div>


<!-- 添加模板弹窗 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="notify_template_modal">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="border-bottom:1px solid #ddd"><span class="notify_title"><?php echo $ld['add_template'] ?></span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd" style="max-height:450px;overflow-y:scroll">
    	<form class="am-form" id="notify_template_form">
    		<input type="hidden" name="data[NotifyTemplateType][id]" value="0">
		<input type="hidden" name="data[NotifyTemplateType][notify_template_code]" value="<?php echo $this->data['NotifyTemplate']['code'] ?>">
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:32px;padding-left:1rem;padding-right:0;"><?php echo $ld['channel_type'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
					<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
					<select name="data[NotifyTemplateType][type]" id="" data-am-selected>
						<option value=""><?php echo $ld['please_select'] ?></option>
<?php if (isset($Resource_info['notity_type'])&&sizeof($Resource_info['notity_type'])>0 ) {foreach ($Resource_info['notity_type'] as $k => $v) { ?>
						<option value="<?php echo $k ?>"><?php echo $v ?></option>
<?php }} ?>
					</select>
				</div>
				</div>
				<?php if(isset($open_type)&&!empty($open_type)){ ?>
				<div class="am-u-lg-5 am-u-md-4 am-u-sm-3 am-text-left" style="line-height:32px;display:none;" id="wechat_template">
					<?php echo $html->link($ld['wechat'].$ld['templates'],'/notify_templates/wechat_template/',array('class'=>'am-btn am-btn-success am-btn-xs am-radius','target'=>'_blank')); ?>
				</div>
				<?php } ?>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['template_title'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
					<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
					<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left" style="line-height:37px;margin-bottom:5px;">
						<input type="text" name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'];?>][title]" value="<?php echo isset($notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']])?$notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']]['title']:''; ?>">
					</div>
					<?php if(sizeof($backend_locales)>1){?>
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:normal;line-height:37px;margin-bottom:5px;"><?php echo $ld[$v['Language']['locale']]?></label>
					<?php } ?>
					<?php }} ?>
				</div>
		</div>
		<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
		<input type="hidden" name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'];?>][locale]" value="<?php echo $v['Language']['locale'];?>">
		<input type="hidden" name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'];?>][notify_template_type_id]" value="0">
		<input type="hidden" name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'];?>][id]" value="0">
		<?php }} ?>
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['parameter_html'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
				<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left" style="margin-bottom:5px;">
					<textarea id="notify_template_type_param01<?php echo $v['Language']['locale'] ?>" name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'] ?>][param01]"><?php echo isset($notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']])?$notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']]['param01']:''; ?></textarea>
				</div>
				<?php if(sizeof($backend_locales)>1){?>
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:normal;line-height:37px;margin-bottom:5px;"><?php echo $ld[$v['Language']['locale']]?></label>
				<?php } ?>
				<script type="text/javascript">
				var editor;
				KindEditor.ready(function(K) {
					editor=K;
					K.create("#notify_template_type_param01<?php echo $v['Language']['locale'];?>", {
						width:'98%',
						items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
						'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
						'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
						'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
						'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
						'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
						langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false,
						afterBlur:function () { this.sync(); }
						}
					);
				});
				</script>
				<?php }} ?>
				</div>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['parameter_text'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
				<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left" style="margin-bottom:5px;">
					<textarea name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'] ?>][param02]"><?php echo isset($notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']])?$notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']]['param02']:''; ?></textarea>
				</div>
				<?php if(sizeof($backend_locales)>1){?>
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:normal;line-height:37px;margin-bottom:5px;"><?php echo $ld[$v['Language']['locale']]?></label>
				<?php } ?>
				<?php }} ?>
				</div>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['parameter3'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
				<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left" style="margin-bottom:5px;">
					<textarea name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'] ?>][param03]"><?php echo isset($notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']])?$notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']]['param03']:''; ?></textarea>
				</div>
				<?php if(sizeof($backend_locales)>1){?>
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:normal;line-height:37px;margin-bottom:5px;"><?php echo $ld[$v['Language']['locale']]?></label>
				<?php } ?>
				<?php }} ?>
				</div>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['parameter4'] ?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
				<?php if (isset($backend_locales)&&sizeof($backend_locales)>0) {foreach ($backend_locales as $k => $v) { ?>
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left" style="margin-bottom:5px;">
					<textarea name="data[NotifyTemplateTypeI18n][<?php echo $v['Language']['locale'] ?>][param04]"><?php echo isset($notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']])?$notify_template_type_list['NotifyTemplateTypeI18n'][$v['Language']['locale']]['param04']:''; ?></textarea>
				</div>
				<?php if(sizeof($backend_locales)>1){?>
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:normal;line-height:37px;margin-bottom:5px;"><?php echo $ld[$v['Language']['locale']]?></label>
				<?php } ?>
				<?php }} ?>
				</div>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="line-height:37px;padding-left:1rem;padding-right:0;"><?php echo $ld['template_description'] ?></label>
			<div class="am-u-lg-10 am-u-md-9 am-u-sm-8">
				<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
					<textarea rows="3" style="resize: none;" cols="20"  name="data[NotifyTemplateType][description]"></textarea>
				</div>
				
			</div>
		</div>
		<div class="am-form-group am-g am-margin-top-xs">
			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left" style="padding-left:1rem;padding-right:0;"><?php echo $ld['template_state'] ?></label>
			<div class="am-u-lg-10 am-u-md-9 am-u-sm-8 am-text-left">
				<div class="am-u-lg-10">
					<label class="am-radio-inline am-radio am-success" style="margin-right:1rem;margin-top:0;">
					<input type="radio" data-am-ucheck name="data[NotifyTemplateType][status]" value="1" checked><?php echo $ld['yes'] ?>
				</label>
				<label class="am-radio-inline am-radio am-success">
					<input type="radio" data-am-ucheck name="data[NotifyTemplateType][status]" value="0"><?php echo $ld['no'] ?>
				</label>
				</div>
				
			</div>
		</div>
		<button type="button" onclick="notify_template_save()" class="am-btn am-btn-success am-btn-sm am-radius am-margin-top-sm" style="margin-bottom:1rem;"><?php echo $ld['submit'] ?></button>
		</form>
    </div>
  </div>
</div>
<script type="text/javascript">
var notify_locales = <?php echo json_encode($backend_locales); ?>;
function notify_form () {
	if ($("input[name='data[NotifyTemplate][code]']").val() == '') {
		alert(fill_in_the_template_code);
		return false;
	}
}
$(function(){
	$("select[name='data[NotifyTemplateType][type]']").change(function(){
		var tempale_type=$(this).val();
		if(tempale_type=="wechat"){
			$("#wechat_template").show();
		}else{
			$("#wechat_template").hide();
		}
	});
});


function notify_template_add () {
	$("#notify_template_modal select[name='data[NotifyTemplateType][type]'] option:eq(0)" ).attr('selected',true);
	$("#notify_template_modal select[name='data[NotifyTemplateType][type]']").trigger('changed.selected.amui');
	$("#notify_template_modal textarea[name='data[NotifyTemplateType][description]']").text('');
	$("#notify_template_modal .am-radio-inline input[value='1']").attr('checked',true);
	$.each(notify_locales,function (index,content) {
		var locale = content.Language.locale;
		$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][id]']").val('0');
		$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][notify_template_type_id]']").val('0');
		$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][locale]']").val(locale);
		$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][title]']").val('');
		editor.html("#notify_template_type_param01"+locale,"");
		$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param01]']").val('');
		$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param02]']").val('');
		$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param03]']").val('');
		$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param04]']").val('');
	});
	$("input[name='data[NotifyTemplateType][id]']").val('0');
	$(".notify_title").text(js_add_template)
	$('#notify_template_modal').modal({width:800,height:500,closeViaDimmer:false});
}

//编辑模板
function notify_edit (ele) {
	$("#wechat_template").hide();
	$.ajax({
			url:admin_webroot+"notify_templates/ajax_notify_template_type/"+ele,
			type:"GET",
			dataType:"json",
			success:function (data) {
				if (data.code == 1) {
				$("input[name='data[NotifyTemplateType][id]']").val(ele);
				$(".notify_title").text(js_edit_template);
			    	$("#notify_template_modal select[name='data[NotifyTemplateType][type]'] option[value='"+data.data.NotifyTemplateType.type+"']" ).attr('selected',true);
			    	$("#notify_template_modal select[name='data[NotifyTemplateType][type]']").trigger('changed.selected.amui');
				var tempale_type=data.data.NotifyTemplateType.type;
				if(tempale_type=="wechat"){
					$("#wechat_template").show();
				}
			    $("#notify_template_modal textarea[name='data[NotifyTemplateType][description]']").text(data.data.NotifyTemplateType.description);
			    if (data.data.NotifyTemplateType.status == 1){
			    $("#notify_template_modal .am-radio-inline input[value='1']").attr('checked',true)	
			    }
			    if (data.data.NotifyTemplateType.status == 0){
			    $("#notify_template_modal .am-radio-inline input[value='0']").attr('checked',true)	
			    }

			   	$.each(data.backend_locales,function (index,content) {
			   	var locale = content.Language.locale;
			   	$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][id]']").val(data.data.NotifyTemplateTypeI18n[locale].id);
			   	$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][notify_template_type_id]']").val(data.data.NotifyTemplateTypeI18n[locale].notify_template_type_id);	
			   	$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][locale]']").val(data.data.NotifyTemplateTypeI18n[locale].locale);		
			   	$("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+locale+"][title]']").val(data.data.NotifyTemplateTypeI18n[locale].title);
			   	editor.html("#notify_template_type_param01"+locale,data.data.NotifyTemplateTypeI18n[locale].param01);
			   	$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param01]']").val(data.data.NotifyTemplateTypeI18n[locale].param01);
			   	$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param02]']").val(data.data.NotifyTemplateTypeI18n[locale].param02);
			   	$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param03]']").val(data.data.NotifyTemplateTypeI18n[locale].param03);
			   	$("#notify_template_modal textarea[name='data[NotifyTemplateTypeI18n]["+locale+"][param04]']").val(data.data.NotifyTemplateTypeI18n[locale].param04);
			   	});
			   	
				};
			}
		})
	
	$('#notify_template_modal').modal({width:800,height:500,closeViaDimmer:false});
}

	function code_blur (ele) {
		$.ajax({
			url:admin_webroot+"notify_templates/ajax_check_template_code",
			type:"POST",
			dataType:"json",
			data:{'template_id':0,'template_code':ele},
			success:function (data) {
				if (data.code != 1) {
					alert(data.message)
					$(".add_notify").addClass('am-disabled')
				}else{
					$(".add_notify").removeClass('am-disabled')
				}
			}
		})
	}


	function notify_template_save () {
		if ($("#notify_template_modal select[name='data[NotifyTemplateType][type]']").val() == '' ) {
			alert(js_please_select_channel_type);
			return false;
		};
		if ($("#notify_template_modal input[name='data[NotifyTemplateTypeI18n]["+backend_locale+"][title]']").val() == '') {
			alert(js_please_fill_in_the_title);
			return false;
		};
		var post_data = $("#notify_template_form").serializeArray();
		$.ajax({
			url:admin_webroot+"notify_templates/ajax_notify_template_type/",
			type:"POST",
			dataType:"json",
			data:post_data,
			success:function (data) {
				if (data.code == 1) {
					alert(data.message);
					window.location.href = window.location.href 
				}else{
					alert(data.message);
				}
			}
		})
	}

	//删除
	function notify_view_remove (ele) {
		if (confirm(js_confirm_deletion)) {
			$.ajax({
    			url:admin_webroot+"notify_templates/ajax_notify_template_type_remove/"+ele,
    			type:"POST",
    			dataType:"json",
    			success:function (data) {
    		if (data.flag == 1) {

    			window.location.href = window.location.href
    		}else{
    			alert(data.message);
    		}
    	}

    })

		}
	}


function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "val="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        type:"POST",
        data: postData,
        dataType:"json",
        success:function(data){
            if(data.flag == 1){
                if(val==0){
                    $(obj).removeClass("am-icon-check am-yes");
                    $(obj).addClass("am-icon-close am-no");
                }
                if(val==1){
                    $(obj).removeClass("am-icon-close am-no");
                    $(obj).addClass("am-icon-check am-yes");
                }
            }

        }
    });
}

</script>