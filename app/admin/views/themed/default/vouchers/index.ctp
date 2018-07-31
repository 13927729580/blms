<style type="text/css">
.am-checkbox {margin-top:0px; margin-bottom:0px;display:inline-block;vertical-align:top;}
.am-panel-title div{font-weight:bold;}
.am-form-horizontal{padding-top: 0.5em;}
.am-div-pages{background-color:#f9f9f9;height:auto;padding-top:3px;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>
<div>
	<?php echo $form->create('vouchers',array('action'=>'/','name'=>"SearchForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['keyword'];?></label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						<input type="text" name="keywords" class="am-form-field am-radius" placeholder="<?php echo $ld['name'];?>" value="<?php echo @$keywords;?>" />
					</div>
				</li>
				<!-- 有效开始时间 -->
				<li style="margin:0 0 10px 0" >
					<label class="am-u-lg-3  am-u-md-3  am-u-sm-3 am-form-label-text " style="padding-right:0;"><?php echo $ld['variable_start_time']?></label>
					<div class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="padding-right:0;width:36%;">
						<div class="am-input-group">
						<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date_from" value="<?php echo @$start_date_from; ?>" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
		</div>
						</div>
						<em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;width:4%;">-</em>
						<div class=" am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;width:32%;">
							<div class="am-input-group">
						<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date_to" value="<?php echo @$start_date_to; ?>"/>
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
		</div>
					</div>
				</li>
	     			<!-- 有效结束时间 -->
	     			<li style="margin:0 0 10px 0" >
	                		<label class="am-u-lg-3  am-u-md-3  am-u-sm-3 am-form-label-text" style="padding-right:0;"><?php echo $ld['variable_end_time']?></label>
					<div class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="padding-right:0;width:36%;">
						<div class="am-input-group">
						<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date_from" value="<?php echo @$end_date_from; ?>" />
						 <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
					</div>
					<em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;width:4%;">-</em>
						<div class=" am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;width:32%;">
							<div class="am-input-group">
						<input style="min-height:35px;" type="text" class="am-form-field am-input-sm" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date_to" value="<?php echo @$end_date_to; ?>"/>
						 <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
					</div>
	                 </li>
			   <!-- 价格区间 -->
			<li style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['price_range']?></label>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
						<input type="text"  name="min_price"  value="<?php echo @$min_price?>"/>
					</div>
					<div class="  am-fl am-text-center" style="margin-top:7px;">-</div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
						<input type="text"  name="max_price" id="max_price" value="<?php echo @$max_price?>"/>
					</div>
			</li>
	            <!-- 状态 -->
			<li style="margin:0 0 10px 0" >  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['status'];?></label>
				<div class="am-u-lg-6 am-u-md-7 am-u-sm-7  am-u-end">
					<select name="search_status"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
						<option value=""><?php echo $ld['all_data']?> </option>
						<option value="1" <?php echo @$search_status=='1'?'selected':''; ?>><?php echo $ld['valid']?> </option>
						<option value="0" <?php echo @$search_status=='0'?'selected':''; ?>><?php echo $ld['invalid']?> </option>
					</select>
				</div>
				<div class="am-u-lg-2 am-u-md-12 am-u-sm-2">
					<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" />
				</div>
			</li>
		</ul>
	<?php echo $form->end()?>
	<?php if($svshow->operator_privilege("voucher_add")){ ?>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/vouchers/view/0'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<?php } ?>
	<?php echo $form->create('vouchers',array('action'=>'index','name'=>"VoucherForm","type"=>"post",'onsubmit'=>"return false;"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
					    	<div class="am-u-lg-2 am-u-md-2  am-u-sm-3"><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />&nbsp; <b><?php echo $ld['name'];?></b></label></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3" style="padding-right: 0;"><?php echo $ld['exchange_coupon']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['total']?></div>
						<?php if(isset($entity_card_status_resource)&&sizeof($entity_card_status_resource)>0){foreach($entity_card_status_resource as $v){ ?>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-sm-only"><?php echo $v; ?></div>
						<?php }} ?>
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<div>
				<div class="listtable_div_top am-panel-body">
				<?php if(isset($voucher_list)&&sizeof($voucher_list)>0){foreach($voucher_list as $v){ ?>
						<div class="am-panel-bd">
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3">
								<label class="am-checkbox am-success"><input type="checkbox"  name="checkboxes[]" value="<?php echo $v['Voucher']['id']; ?>" data-am-ucheck ><?php echo $v['Voucher']['name']; ?></label><?php if(trim($v['Voucher']['remark'])!=""){echo "&nbsp;".$html->image('/admin/skins/default/img/note.png',array('alt'=>htmlspecialchars($v['Voucher']['remark']),'title'=>htmlspecialchars($v['Voucher']['remark'])));} ?>
							</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $v['Voucher']['batch_sn']; ?></div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php if($v['Voucher']['status']=='1'){ ?>
                						<span style="color:#5eb95e" class="am-icon-check"></span><?php }else{ ?>
                						<span style="color:#dd514c" class="am-icon-close"></span><?php } ?>
							</div>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo isset($voucher_card_datas[$v['Voucher']['batch_sn']])?$voucher_card_datas[$v['Voucher']['batch_sn']]:0; ?></div>
							<?php if(isset($entity_card_status_resource)&&sizeof($entity_card_status_resource)>0){foreach($entity_card_status_resource as $kk=>$vv){ ?>
							<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-sm-only"><?php echo isset($voucher_card_status_data[$v['Voucher']['batch_sn']][$kk])?$voucher_card_status_data[$v['Voucher']['batch_sn']][$kk]:0; ?></div>
							<?php }} ?>
							<div class="am-u-lg-2 am-u-md-4 am-u-sm-4  am-btn-group-xs am-action">
								<?php if($svshow->operator_privilege("voucher_edit")){ ?>
								<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" style="margin-right:5px;" href="<?php echo $html->url('/vouchers/view/'.$v['Voucher']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a><?php } ?>
								<?php if($svshow->operator_privilege("voucher_remove")){ ?>
                    					<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-text-danger am-seevia-btn-edit" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'vouchers/remove/<?php echo $v['Voucher']['id'] ?>');"><span class="am-icon-trash-o"></span><?php echo $ld['remove']; ?></a><?php } ?>
							</div>
							<div style="clear:both;"></div>
						</div>
				<?php }}else{ ?>
						<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php if($svshow->operator_privilege("voucher_remove")){ ?>
			<?php if(isset($voucher_list) && sizeof($voucher_list)>0){?>
				<div id="btnouterlist" class="am-div-pages">
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"  style="margin-left:13px;">
						<label class="am-checkbox am-success" style="vertical-align:middle;">
							<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck />
							<?php echo $ld['select_all']?>
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
						<select data-am-selected id="batch_operator_action">
							<option value="0"><?php echo $ld['please_select'] ?></option>
							<option value="batch_delete"><?php echo $ld['batch_delete'] ?></option>
							<option value="batch_remark"><?php echo $ld['modify'].$ld['remarks_notes']; ?></option>
						</select>
					</div>
					<div  class="am-u-lg-2 am-u-md-2 am-u-sm-4">
						<input type="button" class="am-btn am-radius am-btn-danger am-btn-sm"  onclick="batch_operator()" value="<?php echo $ld['submit']?>" />
					</div >
					<div class="am-show-sm-only am-cf">&nbsp;</div>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
						<?php echo $this->element('pagers')?>
					</div>
                    		<div class="am-cf"></div>
				</div>
			<?php }?>
		<?php }?>
	<?php echo $form->end();?>
</div>

<!-- 供应商信息弹窗start -->
<div class="am-popup" id="voucher_remark">
        <div class="am-popup-inner">
            <div class="am-popup-hd" style=" z-index: 11;">
                <h4 class="am-popup-title"><?php echo $ld['modify'].$ld['remarks_notes'];?></h4>
                <span data-am-modal-close class="am-close">&times;</span>
            </div>
            <div class="am-popup-bd" >
    		    <div class="am-form am-form-horizontal">
    				<div class="am-form-group" style="margin-bottom:1.5rem;">
    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-right"><?php echo $ld['remarks_notes'];?></label>
    					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9"><textarea maxlength='500' name="voucher_remark"></textarea></div>
    				</div>
    				<div class="am-form-group">
    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;</label>
    					<div class="am-u-lg-3 am-u-md-6 am-u-sm-9"><input type="hidden" name="voucher_ids" value="">
                            		<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="ajax_voucher_remark()" value="<?php echo $ld['d_submit']?>"></div>
    				</div>
    		    </div>
            </div>
        </div>
</div>
<!-- 供应商信息弹窗end -->

<script type="text/javascript">
function batch_operator(){
	var batch_operator_action=$("#batch_operator_action").val();
	if(batch_operator_action=="0"){return false;}
	var checkbox_input=document.getElementsByName('checkboxes[]');
	var checkbox_ids=[];
	for( var i=0;i<=parseInt(checkbox_input.length)-1;i++ ){
		if(checkbox_input[i].checked){
			checkbox_ids.push(checkbox_input[i].value);
		}
	}
	if(checkbox_ids.length>0){
		if(batch_operator_action=="batch_delete"){
			if(confirm(confirm_delete)){
				document.VoucherForm.action=admin_webroot+"vouchers/batch_remove";
				document.VoucherForm.onsubmit= "";
				document.VoucherForm.submit();
			}
		}else if(batch_operator_action=="batch_remark"){
			$("#voucher_remark input[name='voucher_ids']").val(checkbox_ids.join(","));
			$("#voucher_remark").modal().on('close.modal.amui', function(){
				$("#voucher_remark input[name='voucher_ids']").val('');
			});
		}
	}else{
		alert(j_please_select);
	}
}

function ajax_voucher_remark(){
	var voucher_ids=$("#voucher_remark input[name='voucher_ids']").val();
	var voucher_remark=$("#voucher_remark textarea[name='voucher_remark']").val();
	if(voucher_ids!=""&&voucher_remark!=""){
		$.ajax({
	 		url:admin_webroot+"vouchers/batch_voucher_remark",
	 		data:{'voucher_ids':voucher_ids,'voucher_remark':voucher_remark},
	 		type:'POST',
	 		dataType:'json',
	 		success:function(data){
	 			alert(data.message);
				if(data.flag=='1'){
					window.location.reload();
				}
	 		}
	 	});
	}
}
</script>