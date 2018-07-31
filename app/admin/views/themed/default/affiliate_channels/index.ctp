<input type="hidden" id="source_name" value="">
<div class="am-modal am-modal-no-btn" tabindex="-1" id="channel_modal">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">&nbsp;
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd" style="">
		    <div class="am-u-lg-4" style="line-height:35px;">请输入地址</div>
		    <form class="am-form"><div class="am-u-lg-7"><input type="text" id="address"></div></form>
		    <div class="am-u-lg-12" id="affiliate_url" style="margin-top:0.5rem;">
		    	
		    </div>
		    <div class="am-u-lg-12" style="margin-top:0.5rem;">
		    	<img src="" alt="" id="aff_img" style="display:none;">
		    </div>
		    <div class="am-u-lg-12" style="margin-top:1rem;padding:0;">
		    	<div class="am-u-lg-12 am-text-center" style="">
		    		<button href="" type="button" class="am-btn am-btn-success" onclick="ajax_sub_address()">提交</button>
		    		<button href="" type="button" class="am-btn am-btn-default" style="margin-left:5px;" onclick="channel_modal_close('close')">取消</button>
		    	</div>	
		    </div>
		    <div class="am-cf"></div>
    </div>
  </div>
</div>

<div class="am-text-right">
<?php if($svshow->operator_privilege('add_customer_recommend_source')){ ?>
	<a href="<?php echo $html->url('/affiliate_channels/view/0') ?>" class="am-btn am-btn-warning am-btn-xs am-radius"><i class="am-icon-plus"></i> 添加</a>
<?php } ?>
	<div class="am-cf"></div>
</div>

<div style="border-bottom:1px solid #ccc;">
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
		<label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;">
	        渠道名称
	    </label>
    </div>
	<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">渠道配置描述</label></div>
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">渠道负责人</label></div>
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">状态</label></div>
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">操作</label></div>
	<div class="am-cf"></div>
</div>
<div>
	<?php foreach ($affiliate_channels_info as $k => $v) { ?>
		<div style="border-bottom:1px solid #ccc;">
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;font-weight:400;">
		        	<?php echo $v['AffiliateChannel']['name'] ?>
		        </label>
		    </div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;font-weight:400;"><?php echo $v['AffiliateChannel']['description'] ?></label></div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;font-weight:400;"><?php echo $v['AffiliateChannel']['channel_manager'] ?></label></div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;font-weight:400;">
				<?php if( $v['AffiliateChannel']['status'] == 1){?>
	                <span class="am-icon-check am-yes" style="cursor:pointer;" onclick=""></span>
	            <?php }else{ ?>
	                <span class="am-icon-close am-no" style="cursor:pointer;" onclick=""></span>   
	            <?php }?>&nbsp;</label>
            </div>
			<div class="am-u-lg-3 am-u-md-2 am-u-sm-2" style="padding-top:10px;padding-bottom:10px;padding-right:0;">
			<?php if($svshow->operator_privilege('edit_customer_recommend_source')){?>
				<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/affiliate_channels/view/'.$v['AffiliateChannel']['id']); ?>">
		        	<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
		        </a>
		    <?php } ?>
		    <?php if($svshow->operator_privilege('delete_customer_recommend_source')){?>
		        <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-edit" href="javascript:;" onclick="channel_delete(<?php echo $v['AffiliateChannel']['id'] ?>)">
		        	<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
		        </a> 
		    <?php } ?>
		    <?php if( $v['AffiliateChannel']['status'] == 1){?>
		        <a class="am-btn am-btn-default am-btn-xs am-text-warning am-seevia-btn-edit" href="javascript:;" onclick="channel_modal(&apos;<?php echo $v['AffiliateChannel']['id'] ?>&apos;)">
		        	<span class="am-icon-cog"></span> <?php echo '生成'; ?>
		        </a> 
		    <?php } ?>
	        </div>
	        <div class="am-cf"></div>
	    </div>
	<?php } ?>
</div>
<div style="margin-top:1rem;">
	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
		&nbsp;
	</div>
	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
		<?php echo $this->element('pagers')?>
	</div>
	<div class="am-cf"></div>
</div>
<script>
	function channel_delete(obj_id){
		if(confirm('是否确认删除？')){
			$.ajax({
				url:admin_webroot+"/affiliate_channels/delete_channel/",
				type:"POST",
				dataType:"json",
				data: {'channel_id':obj_id},
				success: function(data){
					if(data.code == 1){
						alert('删除成功！');
						window.location.reload();
					}
				}
			});
		}else{

		}
	}

	function channel_modal(data){
		$("#source_name").val(data);
		$("#channel_modal").modal();
	}

	function channel_modal_close(){
		$("#channel_modal").modal('close');
	}

	function ajax_sub_address(){
			var address = $("#address").val();
			var affiliate_from = $("#source_name").val();
			$.ajax({
				url:admin_webroot+"affiliate_channels/ajax_sub_address/",
				type:"POST",
				dataType:"json",
				data: {'address':address,'affiliate_from':affiliate_from},
				success: function(data){
					if(data!=''){
						var url = admin_webroot+'affiliate_channels/QRImage/?surl='+data;
						//alert(url);
						$('#aff_img').attr('src',url);
						$('#aff_img').show();
						$("#affiliate_url").html(data);
					}
				}
			});
	}


</script>