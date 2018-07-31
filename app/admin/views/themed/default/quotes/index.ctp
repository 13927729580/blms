<style type='text/css'>
.am-form-label{font-weight:bold;margin-top:5px;left:20px;}
table.quote_product label.am-checkbox{display: inline;}
table.quote_product th div.am-g,table.quote_product td div.am-g{margin:0px auto;}
table.quote_product td div.am-g{border-bottom:1px solid #ddd;}
table.quote_product td div.am-g:last-child{border-bottom:none;}
table.quote_product td div.am-g div[class*="am-u-"]{padding-bottom:0.5rem;}
table.quote_product td div.am-g div[class*="am-u-"] span{font-weight:600;}
</style>
<div class="listsearch">
    <?php echo $form->create('Quote',array('action'=>'/','id'=>'QuoteForm','name'=>"QuoteForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li>
            <label class="am-u-sm-4 am-u-lg-3 am-u-md-3 am-form-label" style="margin-left:0;left:0;"><?php echo $ld['quote_date'];?></label>
            <div class="am-u-sm-2 am-u-lg-3 am-u-md-3" style="padding:0 0rem;width:32%;">
                <div class="am-input-group">
                <input style="min-height:38px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date1" value="<?php if(isset($date1)){echo $date1;} ?>" />
                  <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <em class="am-text-center am-u-sm-1 am-u-lg-1 am-u-md-1"  style="width:4%;padding-top:0.5rem;">-</em>
            <div class="am-u-sm-2 am-u-lg-3 am-u-md-3 am-u-end" style="padding:0 0rem;width:32%;">
                <div class="am-input-group">
                <input style="min-height:38px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php if(isset($date2)){echo $date2;} ?>" />
                  <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li style="margin:7px 0px 10px 0px">
            <label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label"><?php echo $ld['quoted_by'];?></label>
            <div class="am-u-lg-8 am-u-sm-6 am-u-md-8">
			<select name="quoted_by" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data'] ?>'}">
				<option value=""><?php echo $ld['all_data']?></option>
				<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
				<option value="<?php echo $v; ?>" <?php echo isset($quoted_by)&&$quoted_by==$v?'selected':''; ?>><?php echo $v; ?></option>
				<?php }} ?>
			</select>
            </div>
        </li>
        <li style="margin:7px 0px 10px 0px">
            <label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label" ><?php echo $ld['status']; ?></label>
            <div class=" am-u-lg-8 am-u-sm-6 am-u-md-8 am-u-end" >
                <select name="quote_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
                    <option value=""><?php echo $ld['all_data']?></option>
                    <?php if (isset($systemresource_info['quote_status'])&&sizeof($systemresource_info['quote_status'])>0) {foreach ($systemresource_info['quote_status'] as $k => $v) { ?>
                    <option <?php echo isset($quote_status)&&$quote_status == $k?'selected':'' ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                    <?php }} ?>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-sm-4  am-u-md-3 am-form-label" style="padding-top:12px;left:0;margin-left:0;"><?php echo $ld['name_of_member'];?></label>
            <div class="am-u-lg-8 am-u-sm-6 am-u-md-8 " style="padding-left:0;">
                <input style="height:38px;" type="text" name="customer_name" id="customer_name" value="<?php if(isset($customer_name)){echo $customer_name;} ?>"/> </div>
        </li>
	<?php if($svshow->operator_privilege("users_advanced")) { ?>
	<li  class="am-margin-top-xs">
		<label class="am-u-lg-3 am-u-sm-4  am-u-md-3 am-form-label-text"><?php echo $ld['user_manager'] ?></label>
		<div class="am-u-lg-8 am-u-sm-6 am-u-md-8">
			<select name="user_manager[]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data'] ?>'}">
				<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
				<option value="<?php echo $k; ?>" <?php echo isset($user_manager)&&in_array($k,$user_manager)?'selected':''; ?>><?php echo $v; ?></option>
				<?php }} ?>
			</select>
		</div>
	</li>
	<?php } ?>
        <li style="margin:3px 0px 10px 0px">
            <label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label"><?php echo $ld['product'].$ld['code'];?></label>
            <div class=" am-u-lg-8 am-u-sm-6  am-u-md-8 am-u-end">
                <input style="height:38px;" type="text" name="product_keywords" id="product_keywords" value="<?php if(isset($product_keywords)){echo $product_keywords;}?>"/>
            </div>
        </li>
          <li style="margin:7px 0px 10px 0px">
			<label class="am-u-lg-3 am-u-sm-4 am-u-md-3 am-form-label">&nbsp;</label>
			<div class="am-u-lg-5 am-u-sm-5 am-u-md-5 "style="margin-left:20px;">
				<input  style="margin-top:5px;"  class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search']?>"  class="search_article">
			</div>
            </li>
    </ul>
    <?php echo $form->end();?>
</div>
<p class="am-g am-text-right am-btn-group-xs" style="margin-right:10px">
	<?php if($svshow->operator_privilege("quotes_edit")){ ?>
	<a class="am-btn am-btn-warning am-radius am-btn-sm " href="<?php echo $admin_webroot; ?>quotes/view/0">
		<span class="am-icon-plus"></span>
		<?php echo $ld['add']?>
	</a>
	<?php } ?>
</p>
<div class="am-g">
    <?php echo $form->create('Quote',array('action'=>'/','name'=>'QuoteForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <table class="am-table table-main quote_product">
        <thead>
        <tr>
            <th><label class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>&nbsp;</label><?php echo $ld['name_of_member'];?></th>
            <th class="am-hide-sm-down"><?php echo $ld['inquire_date'];?></th>
            <th><?php echo $ld['status'];?></th>
            <th width='50%'>
            	<div class="am-g">
				<div class='am-u-sm-2'><?php echo $ld['sku'];?></div>
				<div class='am-u-sm-2'><?php echo $ld['brand'];?></div>
				<div class='am-u-sm-2'><?php echo $ld['app_qty'];?></div>
				<div class='am-u-sm-2'><?php echo $ld['qty_req'];?></div>
				<div class='am-u-sm-2'><?php echo $ld['offered_price'];?></div>
				<div class='am-u-sm-2'><?php echo $ld['target_price'];?></div>
				<div class="am-cf"></div>
			</div>
            </th>
            <th><?php echo $ld['quoted_by'];?></th>
            <th class="am-hide-sm-down"><?php echo $ld['quote_date'];?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($quotes_list)&&sizeof($quotes_list)>0){foreach($quotes_list as $k => $v){?>
            <tr>
                <td><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Quote']['id']?>" />&nbsp;</label><?php if(isset($v['Quote']['customer_name'])){echo $v['Quote']['customer_name'];}if(isset($v['Quote']['contact_person'])&&!empty($v['Quote']['contact_person'])){echo '-'.$v['Quote']['contact_person'];}?></td>
                <td class="am-hide-sm-down"><?php if(isset($v['Quote']['inquire_date'])){echo date('Y-m-d',strtotime($v['Quote']['inquire_date']));}?></td>
                <td><?php echo isset($systemresource_info['quote_status'][$v['Quote']['status']])?$systemresource_info['quote_status'][$v['Quote']['status']]:$v['Quote']['status']; ?></td>
                <td>
                		<?php if(isset($v['QuoteProduct'])){foreach($v['QuoteProduct'] as $vv){ ?>
                		<div class="am-g">
					<div class='am-u-sm-2'><span title="<?php echo isset($quote_product_list[$vv['QuoteProduct']['product_code']])?$quote_product_list[$vv['QuoteProduct']['product_code']]:$vv['QuoteProduct']['product_code']; ?>"><?php echo $vv['QuoteProduct']['product_code'];?></span></div>
					<div class='am-u-sm-2'><?php echo $vv['QuoteProduct']['brand_code'];?></div>
					<div class='am-u-sm-2'><?php echo $vv['QuoteProduct']['qty_offered'];?></div>
					<div class='am-u-sm-2'><?php echo $vv['QuoteProduct']['qty_requested'];?></div>
					<div class='am-u-sm-2'><?php echo $vv['QuoteProduct']['offered_price'];?></div>
					<div class='am-u-sm-2'><?php echo $vv['QuoteProduct']['target_price'];?></div>
					<div class="am-cf"></div>
				</div>
				<?php }} ?>
                </td>
                <td><?php if(isset($v['Quote']['quoted_by'])){echo $v['Quote']['quoted_by'];}?></td>
                <td class="am-hide-sm-down"><?php if(isset($v['Quote']['created'])){echo date('Y-m-d',strtotime($v['Quote']['created']));}?></td>
                <td class="am-action">
                    <?php
                    if(isset($v['Quote']['is_sendmail'])&&$v['Quote']['is_sendmail']=='0'){ if($svshow->operator_privilege("quotes_edit")){?>
                          <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/quotes/view/'.$v['Quote']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a><?php } if($svshow->operator_privilege("quotes_remove")){ ?>
				 <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'/quotes/Remove/<?php echo $v['Quote']['id']; ?>'}"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
                   <?php  }}else if($svshow->operator_privilege("quotes_edit")){ ?>
                   	 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/quotes/view/'.$v['Quote']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
                    <?php } ?>
                </td>
            </tr>
        <?php }}else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php if(isset($quotes_list) && sizeof($quotes_list)){?>
        <div id="btnouterlist" class="btnouterlist">
            <div class="am-u-lg-6 am-u-sm-12 am-u-md-12">
                <label style="margin:5px 5px 5px 0px;float:left;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/><b><?php echo $ld['select_all']?></b></label>
                <span><select id="barch_opration_select" data-am-selected  onchange="quote_opration_select_onchange(this)">
                    <option value="0"><?php echo $ld['all_data']?></option>
                    <option value="export_csv"><?php echo $ld['batch_export']?></option>
            	<?php if($svshow->operator_privilege("quotes_remove")){ ?>
                    <option value="batch_deletes"><?php echo$ld['batch_delete'] ?></option>
                    <?php } ?>
                </select></span>
                <span style="display:none;"><select id="export_csv" name="barch_opration_select_onchange" data-am-selected>
                    <option value="all_export_csv"><?php echo $ld['all_data']; ?></option>
                    <option value="choice_export"><?php echo $ld['choice_export']?></option>
                </select></span>
                <span><input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="<?php echo $ld['submit']?>" onclick="quote_operation()" /></span>
               </div>
            <div class="am-u-lg-6 am-u-sm-12 am-u-md-12">
            <?php echo $this->element('pagers')?>
            <div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
    <?php echo $form->end();?>
</div>
<script type="text/javascript">
function quote_opration_select_onchange(obj){
    var barch_opration_select_onchange = document.getElementsByName("barch_opration_select_onchange[]");
    for( var i=0;i<barch_opration_select_onchange.length;i++ ){
        barch_opration_select_onchange[i].style.display = "none";
    }
    if(obj.value=="export_csv"){
        $("#export_csv").parent().show();
    }else{
        $("#export_csv").parent().hide();
    }
}

function quote_operation()
{ 
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
    
    var barch_opration_select_type = document.getElementById("barch_opration_select").value;
    var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
	            if(bratch_operat_check[i].checked){
	                checkboxes.push(bratch_operat_check[i].value);
	            }
        }
         if(barch_opration_select_type=='batch_deletes'&&checkboxes==""){
            //alert(j_select_user);
            return;
          }//alert(barch_opration_select);
     if(barch_opration_select_type=='batch_deletes'){ 
     	 if(confirm("<?php echo $ld['confirm_delete']; ?>")){
     	 	 var sUrl = admin_webroot+"quotes/removeAll";//访问的URL地址
     	 	  $.ajax({
     	 	  type: "POST",
                url: sUrl,
                dataType: 'json',
                data:{checkboxes:checkboxes},
                success: function (result) {
                 window.location.href = window.location.href;
                }
     	 	 
     	 	 
     	 	 });
     	 }
      }
    ///////
}
</script>