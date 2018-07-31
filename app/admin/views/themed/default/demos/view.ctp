<?php
/*****************************************************************************
 * SV-Cart 编辑专题
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<div class="am-panel-group admin-content am-detail-view" id="accordion">
	<?php echo $form->create('',array('action'=>''));?>
	<!-- 导航 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}">
		<ul>
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#content"><?php echo $ld['content']?></a></li>
		</ul>
	</div>
	<div class="am-text-right am-margin-xs" data-am-sticky="{top:'100px',animation:'slide-top'}">
		<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick='ajax_demo_save(this)'><?php echo $ld['d_submit'];?></button>
		<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['d_reset']?></button>
	</div>
	<div id="basic_information"  class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
	      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
				<div class="am-form-group">
	    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['title']?></label>
	    				<div class="am-u-lg-4 am-u-md-6 am-u-sm-8">
	    					<input type='text' />
	    				</div>
	    				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-danger am-padding-top-sm'>*</div>
	    			</div>
	    			<div class="am-form-group">
	    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['classification']?></label>
	    				<div class="am-u-lg-4 am-u-md-6 am-u-sm-8">
	    					<select data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?> '}">
							<option value='0'><?php echo $ld['please_select']; ?></option>
							<?php for($i=0;$i<10;$i++){ ?>
							<option value="<?php echo $i; ?>"><?php echo '选项'.$i; ?></option>
							<?php } ?>
						</select>
	    				</div>
	    				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-danger am-padding-top-sm'>*</div>
	    			</div>
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">图片</label>
					<div class="am-u-lg-4 am-u-md-6 am-u-sm-8">
						<div class="am-input-group am-input-group-sm">
							<input id="DemoImg" type="text" value="" />
							<span class="am-input-group-btn">
								<button class="am-btn am-btn-xs am-btn-success am-radius" type="button" onclick="select_img('DemoImg')"><?php echo $ld['choose_picture']?></button>
							</span>
						</div>
						<div class="img_select">
							<?php echo $html->image($configs['shop_default_img'],array('id'=>'show_DemoImg'))?>
						</div>
					</div>
				</div>
	    			<div class="am-form-group">
	    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['meta_description']?></label>
	    				<div class="am-u-lg-4 am-u-md-6 am-u-sm-8">
	    					<textarea></textarea>
	    				</div>
	    			</div>
	    			<div class="am-form-group">
	    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['valid']?></label>
	    				<div class="am-u-lg-4 am-u-md-6 am-u-sm-8">
	    					<label class='am-radio am-success'><input type='radio' name='demoStatus' value='1' data-am-ucheck checked>&nbsp;<?php echo $ld['yes']; ?></label>
	    					<label class='am-radio am-success'><input type='radio' name='demoStatus' value='0' data-am-ucheck >&nbsp;<?php echo $ld['no']; ?></label>
	    				</div>
	    			</div>
			</div>
		</div>
	</div>
	<div id="content" class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title"><?php echo $ld['description']?></h4>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
			<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
				<div class="am-form-group">
					<div class="am-u-lg-8 am-u-md-10 am-u-sm-12">
						<?php echo $this->element('editor',array('editorId'=>'elmChi')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $form->end();?>
</div>

<div class="am-modal am-modal-confirm" tabindex="-1" id="demoConfirm">
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo $ld['j_sure_to_save'] ?></div>
		<div class="am-modal-bd">&nbsp;</div>
		<div class="am-modal-footer">
			<span class="am-modal-btn" data-am-modal-confirm>确定</span>
			<span class="am-modal-btn" data-am-modal-cancel>取消</span>
		</div>
	</div>
</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="demoAlert">
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo $ld['j_modified_successfully']; ?></div>
		<div class="am-modal-bd">&nbsp;</div>
		<div class="am-modal-footer">
			<span class="am-modal-btn">确定</span>
		</div>
	</div>
</div>



<style type='text/css'>
div.am-detail-view{width:100%;}
div.am-detail-view .am-form-label{text-align:left;}
div.am-detail-view .am-radio{display:inline-block;padding-top:0px;}
div.am-detail-view .am-radio+.am-radio{margin-left:2.5rem;}
div.am-detail-view textarea{resize:none;}
</style>
<script type='text/javascript'>
function ajax_demo_save(btn){
	var ConfirmFunction=function(){
		$.ajax({
			type: "POST",
			url:location.href,
			data:{},
			dataType:"html",
			async: false,
			beforeSend: function(){
				$(btn).button('loading');
				$.AMUI.progress.set(1);
			},
			success: function(result) {
				$.AMUI.progress.inc();
				$('#demoAlert').modal({
					closeViaDimmer:false,
				});
			},
			complete:function(){
				$.AMUI.progress.done(true);
				$(btn).button('reset');
			}
		});
	};
	$('#demoConfirm').modal({
		closeViaDimmer:false,
		onConfirm: ConfirmFunction,
		onCancel: function() {
			$(this).modal('close');
		}
	});
}
</script>