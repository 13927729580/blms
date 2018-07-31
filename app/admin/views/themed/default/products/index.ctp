<?php echo $javascript->link('/skins/default/js/product');?>
<style type="text/css">
.am-radio, .am-checkbox{display: inline-block;margin-top:0px;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
.am-panel-title div{font-weight:bold;}
#SearchForm .am-form-label{padding-top:8px;}
#SearchForm li{margin-bottom:10px;}
#SearchForm .am-selected-list li{margin-bottom: 0px;}
.am-form-horizontal .am-checkbox{padding-top:0px;}
#changeAttr div{float:left;width:150px;}
#changeAttr div .am-checkbox{margin-left:5px;margin-top:-5px;}
#check_box{width:100%;}
#product_type_id{font-size: 1.4rem;}
#attr_cate_id{font-size: 1.4rem;}
#product_attr_id{font-size: 1.4rem;}
.product_attr_info div{color:gray;font-size:1.2rem;padding:0px;}
.product_attr_info div.product_attr_title{padding-left:2px;}
.product_attr_info div.product_attr_title:after{content:":";}
.recom_yes{color:#5eb95e;border:1px solid #5eb95e;padding:2px 5px;background: #fff;outline: 0;border-radius: 3px;}
.recom_no{color:#ccc;border:1px dotted #ccc;padding:2px 5px;background: #fff;outline: 0;border-radius: 3px;margin-top:-0.4em;}
.product_manager_change{cursor: pointer;}
.last_update_info{color:#ccc;}
.btnouterlist label.export_type_location_price{margin-top:6px;margin-bottom:6px;display: inline-block;}
.am-btn{border-radius:2px};
.am-u-lg-3.am-u-md-3.am-u-sm-4.am-form-label-text.am-form-label-text{text-align: right;}
</style>
<div>
    <?php echo $form->create('Product',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <div>
        <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
        <input type="hidden" name="attr_value" id="attr_value" value="<?php if(isset($attr_value)){echo $attr_value;}?>"/>
    </div>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li> 
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo $ld['product_categories']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <div class="checkbox" id = 'y1' >
                    <div class="am-dropdown" data-am-dropdown id="check_box">
                        <button  style="width:100%;" class="am-selected-btn am-btn am-dropdown-toggle am-btn-default   am-btn-sm" data-am-dropdown-toggle><span class="am-selected-status am-fl"><?php echo $ld['all_data']; ?></span><i class="am-selected-icon am-icon-caret-down"></i></button>
                        <ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1 b1" style="height:300px;overflow-x:hidden;overflow-y:scroll; width:100%;">
                            <li class="bb0" style="padding-left:10px;">
                                <label class="am-checkbox am-success">
                                    <input type="checkbox" name="box" value="-1" onclick="reAll(this)" data-am-ucheck <?php if(in_array("-1",$category_arr)) echo 'checked';?>/> <?php echo $ld['unknown_classification']?>
                                </label>
                            </li>
                            <?php foreach($category_name_list as $cak=>$cav){ ?>
                                <li class="bb0" style="margin-left:10px;">
                                    <label class="am-checkbox am-success">
                                        <input type="checkbox" class="checkbox" name="box" onclick="reAll(this)" value="<?php echo $cak;?>"  data-am-ucheck <?php if(in_array($cak,$category_arr)) echo 'checked';?>/>
                                        <?php echo $cav;?>
                                    </label>
                                </li>
                            <?php }?>
                            <li class="bb1">
                                <div class="am-form-group">
                                    <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
                                        <label class="am-checkbox am-success">
                                            <input type="checkbox" id="select" class="bb2" data-am-ucheck />
                                            <?php echo $ld['select_all']?>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php  echo $ld['product_brand']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="brand_id" id="brand_id" data-am-selected="{maxHeight:400,noSelectedText:'<?php echo $ld['all'] ?>'}">
				<option value="0"><?php echo $ld['all_data']?></option>
				<option value="-1" <?php if(isset($brand_id) && $brand_id ==-1)echo 'selected';?>><?php echo $ld['unknown_brand']?></option>
				<?php if(isset($brand_tree) && sizeof($brand_tree)>0){$brand_id = isset($brand_id)?$brand_id:'';?><?php foreach($brand_tree as $k=>$v){?>
				<option value="<?php echo $v['Brand']['id']?>" <?php if($brand_id == $v['Brand']['id']){?>selected<?php }?>><?php echo $v['BrandI18n']['name']?></option>
				<?php }}?>
			</select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php  echo $ld['prod_category_commodity']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            	<select name="category_type" id='category_type' data-am-selected="{maxHeight:300}">
            		<option value="0"><?php echo $ld['all_data']; ?></option>
            		<?php if(isset($category_type_tree)&&sizeof($category_type_tree)>0){foreach($category_type_tree as $v){ ?>
            		<option value="<?php echo $v['CategoryType']['id']; ?>" <?php echo isset($category_type_id)&&$category_type_id==$v['CategoryType']['id']?"selected":''; ?>><?php echo $v['CategoryTypeI18n']['name']; ?></option>
            			<?php if(isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $vv){ ?>
            			<option value="<?php echo $vv['CategoryType']['id']; ?>" <?php echo isset($category_type_id)&&$category_type_id==$vv['CategoryType']['id']?"selected":''; ?>><?php echo $vv['CategoryTypeI18n']['name']; ?></option>
            				<?php if(isset($vv['SubCategory'])&&sizeof($vv['SubCategory'])>0){foreach($vv['SubCategory'] as $vvv){ ?>
	            			<option value="<?php echo $vvv['CategoryType']['id']; ?>" <?php echo isset($category_type_id)&&$category_type_id==$vvv['CategoryType']['id']?"selected":''; ?>><?php echo $vvv['CategoryTypeI18n']['name']; ?></option>
	            			<?php }} ?>
            			<?php }} ?>
            		<?php }} ?>
            	</select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo $ld['forsale_status']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="forsale" id='forsale' data-am-selected="{maxHeight:300}">
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <option value="1" <?php if($forsale==1){?>selected<?php }?> ><?php echo $ld['for_sale']?></option>
                    <option value="0" <?php if($forsale==0){?>selected<?php }?> ><?php echo $ld['out_of_stock']?></option>
                </select>
            </div>
        </li>
        <li>
        	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo $ld['recommend']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="is_recommond" id='is_recommond' data-am-selected="{maxHeight:300}">
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if($is_recommond == 0){?>selected<?php }?>><?php echo $ld['no']?></option>
                    <option value="1" <?php if($is_recommond == 1){?>selected<?php }?>><?php echo $ld['yes']?></option>
                </select>
            </div>
        </li>
        <li class="amHide" style="display:none">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left">
                <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['product'].' '.$ld['type']:$ld['product'].$ld['type'];?>
            </label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="option_type_id" id='option_type_id' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($pro_option_type_name as $kk=>$vv){ ?>
                        <option value="<?php echo $kk ?>" <?php if($option_type_id ==$kk){?>selected<?php }?>><?php echo $vv ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="amHide" style="display:none">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-text-left"><?php echo $ld['operator']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="operator_id" id='operator_id' data-am-selected="{maxHeight:300}">
                    <option value="-1" selected ><?php echo $ld['all_data']?></option>
                    <?php foreach($Operator_list as $Opk=>$Opv){?>
                        <option value="<?php echo $Opv;?>" <?php if(isset($operator_id)&& $operator_id==$Opk){?>selected<?php }?> ><?php echo $Opv;?></option>
                    <?php }?>
                </select>
            </div>
        </li>     			
        <li class="amHide" style="display:none">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text am-text-left"  ><?php echo $ld['operation_time']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
            </div>

        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text am-text-left"><?php echo $ld['added_time'];?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="width:37%;padding-right:5px;">
                <div class="am-input-group">
                <input type="text"  name="start_date"  id="time_s" class="am-form-field " readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo $start_date;?>" />
                <span class="am-input-group-label" onclick="cll()" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class="  am-text-center  am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-right:0;width:33%;padding-left:5px;">
                <div class="am-input-group">
                    <input type="text"  id="time_e" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date" value="<?php echo $end_date;?>" style="" />
                 <span class="am-input-group-label" onclick="clr()" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
                </div>
                
            </div>
           
        </li>
        <li class="amHide" style="display:none">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['product_manager']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="product_manager" id='product_manager' data-am-selected="{maxHeight:300}">
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php echo isset($product_manager)&&$product_manager=='0'?'selected':''; ?>><?php echo $ld['unspecified']?></option>
                    <?php foreach($Operator_list as $Opk=>$Opv){?>
                        <option value="<?php echo $Opk;?>" <?php echo isset($product_manager)&&$product_manager==$Opk?'selected':''; ?>><?php echo $Opv;?></option>
                    <?php }?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['price_range']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="min_price" id="min_price" value="<?php echo @$min_price?>"/>
            </div>
            <div class="  am-fl am-text-center" style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="max_price" id="max_price" value="<?php echo @$max_price?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['quantity']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="min_quantity" id="min_quantity" value="<?php echo @$min_quantity; ?>"/>
            </div>
            <div class="  am-fl am-text-center" style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="text"  name="max_quantity" id="max_quantity" value="<?php echo @$max_quantity?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-5 am-u-md-4 am-u-sm-4 am-padding-right-xs">
                <input type="text" name="product_keywords" id="product_keywords" value="<?php echo $product_keywords?>" onkeypress="sv_search_action_onkeypress(this,event)" />
            </div>
            <label class='am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label am-text-center am-padding-left-0 am-margin-left-0 am-padding-right-0'><?php if(isset($configs['cross_reference'])&&$configs['cross_reference']=='1'){ ?><label class='am-success am-checkbox'><input type='checkbox' name='product_cross_reference' id='product_cross_reference' value='1' data-am-ucheck <?php echo isset($product_cross_reference)&&$product_cross_reference=='1'?'checked':''; ?> /><?php echo $ld['cross_reference']; ?></label><?php }else{echo "&nbsp;";} ?>
            </label>
	</li>
	<li >
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                <input type="button" style="height:33px" class="am-btn am-btn-xs am-btn-default  am-form-label am-left"  value="<?php echo $ld['advanced_search'];?>" id="gaoji" />
            </div>
        </li>
    </ul>
    <?php if(isset($product_type_tree)&&!empty($product_type_tree)){echo $this->element('product_type_tree');} ?>
    <?php echo $form->end()?>
</div>
<div>
<div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    <!-- <?php if($svshow->operator_privilege("products_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['lease_parameter'].$ld['set_up'],"/product_lease_prices/index",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view"));} ?> -->
    <!-- <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['product'].$ld['set_up'],"/products/config",array('target'=>'_blank',"class"=>" mt am-btn am-btn-default am-seevia-btn-view"));} ?> -->
    <?php if($svshow->operator_privilege("products_trash")){echo $html->link($ld['recycle_bin']."(".$trash_count.")","/trash/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));}?>
    <?php if($svshow->operator_privilege("category_types_view")){echo $html->link($ld['category_management'],"/category_types/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));} ?>
    <!-- <?php if($svshow->operator_privilege("product_style_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['style_manager'],"/product_styles/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));} ?> -->
    <?php if($svshow->operator_privilege("products_upload")){echo $html->link($ld['bulk_upload_products'],"/products/uploadproduct",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));}?>
    <?php if($svshow->operator_privilege("products_add")){ ?>
    	<a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/products/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
    <?php }?>
</div>
<?php
	$public_attr_options=array();
	if(isset($configs['show_public_attr'])&&$configs['show_public_attr']==1&&isset($public_attr_info)&&sizeof($public_attr_info)>0){
		foreach($public_attr_info as $v){
			if(isset($v['AttributeOption'])&&!empty($v['AttributeOption'])){
				$AttributeOptions=array();
				foreach($v['AttributeOption'] as $vv){
					$AttributeOptions[]=$vv['option_value'].'||'.$vv['option_name'];
				}
				$public_attr_options[$v['Attribute']['id']]=implode(chr(9),$AttributeOptions);
			}
		}
	}
?>
<div class="listtable_div_btm">
		<div class="am-g">
		<div class="am-u-lg-1 am-u-md-1 am-hide-sm-down" ><label class="am-checkbox am-success" ><input id="selAll1" onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />&nbsp; <?php echo $ld['thumbnail'];?> </label></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sku'];?>/<?php echo $ld['name'] ?></div>
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['brand'];?>/<?php echo $ld['classification'] ?></div>
		<div class="am-u-lg-1 am-hide-md-down"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['product'].' '.$ld['type']:$ld['product'].$ld['type'];?></div>
		<div  class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['quantity']; ?></div>
		<?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])&&!empty($ec_product_sku)){ ?>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo  $ld['virtual_inventory'];?></div>
		<?php }?>
		<div class="am-u-lg-1 am-u-md-1  am-hide-sm-only"><?php echo $ld['price']?></div>
		<?php if (isset($configs["show_purchase_price"]) && $configs["show_purchase_price"]==1){ ?>
		<div class="am-u-lg-1 am-u-md-1   am-hide-sm-only"><?php echo $ld['purchase_price']?></div>
		<?php }?>
		<div class="am-u-lg-1 am-u-md-1  am-hide-sm-only"><?php echo $ld['for_sale']?><br><?php echo $ld['is_send_subscription']?></div>
		<div class="am-u-lg-1 am-u-md-1  am-hide-sm-only"><?php echo $ld['product_manager']; ?><br>/<?php echo $ld['operator']?></div>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-5"><?php echo $ld['operate']; ?></div>
		</div>
        <?php //pr($product_list); ?>
    <?php if(isset($product_list) && sizeof($product_list)>0){foreach($product_list as $k=>$v){?>
        <div class="am-g">
           	<div class="listtable_div_top" >
                <div style="margin:10px auto;" class="am-g">
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-sm-only" >
           <label class="am-checkbox am-success" style=""><input type="checkbox" name="checkboxes[]" value="<?php echo $v['Product']['id']?>"  onclick="reAll(this)" data-am-ucheck />&nbsp;<?php echo $html->image(empty($v['Product']['img_thumb'])?$configs['shop_default_img']:$v['Product']['img_thumb'],array('width'=>'50px','height'=>'50px')); ?></label></div>  
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                <?php if ($v['Product']['recommand_flag'] == 1) {?>
                <button type="button" class="recom_yes" onclick="commodity_recommend ('<?php echo $v['Product']['id'] ?>',this)"><?php echo $ld['recommend'] ?></button>
                <?php }elseif($v['Product']['recommand_flag'] == 0){ ?>
                <button type="button" class="recom_no" onclick="commodity_recommend ('<?php echo $v['Product']['id'] ?>',this)"><?php echo $ld['recommend'] ?></button>
                <?php } ?>
                <?php if($svshow->operator_privilege('products_edit')){ ?>
                <span onclick="javascript:listTable.edit(this, 'products/update_product_code/', <?php echo $v['Product']['id']?>)"><?php }else{echo "<span>";} echo $v['Product']['code']; ?>&nbsp;</span><br >
                <?php if($svshow->operator_privilege('products_edit')){ ?><span class='products_name_length' onclick="javascript:listTable.edit(this, 'products/update_product_name/', <?php echo $v['Product']['id']?>)"><?php }else{echo "<span>";} echo $v['ProductI18n']['name'];?></span>&nbsp;
			<?php if($v['Product']['option_type_id']==0&&isset($configs['show_public_attr'])&&$configs['show_public_attr']==1&&isset($public_attr_info)&&sizeof($public_attr_info)>0){ foreach($public_attr_info as $pa){?>
			<div class="product_attr_info">
				<div class="am-u-lg-3 am-u-md-6 am-u-sm-6 am-text-left product_attr_title"><?php echo $pa['AttributeI18n']['name'];?></div>
				<div class="am-u-lg-3 am-u-md-6 am-u-sm-6 am-text-left"><?php
					$_attr_info_name=isset($attr_info)&&(isset($attr_info[$v['Product']['id']][$pa['Attribute']['id']]))&&trim($attr_info[$v['Product']['id']][$pa['Attribute']['id']]!="")?$attr_info[$v['Product']['id']][$pa['Attribute']['id']]:'-';
					$attr_input_type=$pa['Attribute']['attr_input_type'];
					if($svshow->operator_privilege('products_edit')){
						if(strlen($_attr_info_name)>100){
							$ddn_id="DDN_".$v['Product']['id'].'_'.$pa['Attribute']['id'];
                                		$_ID_STR="document.getElementById('".$ddn_id."')";
							echo $html->image('/admin/skins/default/img/note.png',array('style'=>'cursor:pointer;','title'=>$_attr_info_name,"onclick"=>"setInput('".$ddn_id."')"));
				?>
							<span id="<?php echo $ddn_id; ?>" onclick="javascript:PublicAttributeEdit(<?php echo $_ID_STR ?>, 'products/update_product_attr/', '<?php echo $v['Product']['id'].';'.$pa['Attribute']['id']?>','<?php echo $attr_input_type; ?>','<?php echo isset($public_attr_options[$pa['Attribute']['id']])?$public_attr_options[$pa['Attribute']['id']]:''; ?>')" style="display:none;"><?php echo $_attr_info_name; ?></span>
				<?php
						}else{
				?>
							<span onclick="javascript:PublicAttributeEdit(this, 'products/update_product_attr/', '<?php echo $v['Product']['id'].';'.$pa['Attribute']['id']?>','<?php echo $attr_input_type; ?>','<?php echo isset($public_attr_options[$pa['Attribute']['id']])?$public_attr_options[$pa['Attribute']['id']]:''; ?>')"><?php echo $_attr_info_name; ?></span>
				<?php
						}
					}else{
						if(strlen($_attr_info_name)>100){
							echo $html->image('/admin/skins/default/img/note.png',array('style'=>'cursor:pointer;','title'=>$_attr_info_name));
						}else{
							echo $_attr_info_name;
						}
					}
				?></div>
			</div>
			<?php }}?>
		   </div>
           <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:"-";?><br ><?php echo isset($product_category_tree[$v['Product']['category_id']])?$product_category_tree[$v['Product']['category_id']]:"&nbsp;";?></div>
           <div class="am-u-lg-1 am-u-md-1 am-hide-md-down"><?php echo $pro_option_type_name[$v['Product']['option_type_id']]; ?>&nbsp;</div>
           <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php if($svshow->operator_privilege('products_edit')){?><span onclick="javascript:listTable.edit(this, 'products/update_product_quantity/', <?php echo $v['Product']['id']?>)"><?php } echo $v['Product']['quantity']?></span>&nbsp;</div>
            <?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])&&!empty($ec_product_sku)){ ?>
		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2" >
			<?php if(isset($ec_product_sku[$v['Product']['code']])){
				$ec_quantity=0;
					foreach($ec_product_sku[$v['Product']['code']] as $eck=>$ecv){
					if($ecv['warhouse_name']=='虚拟仓'){
					$ec_quantity= $ec_quantity+$ecv['product_quantity'];
					}
				}
			echo $ec_quantity;
		}?>&nbsp;
		</div>
            <?php } ?>
           <div class="am-u-lg-1 am-u-md-1 am-hide-sm-only am-text-left"><?php
           	   	if($svshow->operator_privilege('products_edit')){
           	   			if(isset($configs['product_location_price'])&&$configs['product_location_price']=='1'){
           	   				if(isset($systemresource_info['product_location'])&&!empty($systemresource_info['product_location'])){
           	   					foreach($systemresource_info['product_location'] as $kk=>$vv){
           	   						echo $vv.':';
           	   						$_pro_city_price=isset($pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['product_price'])?$pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['product_price']:"0";					$_pro_price_id=isset($pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['id'])?$pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['id']:"0";
           	   	?><span onclick="javascript:listTable.edit(this, 'products/update_product_city_price/<?php echo $_pro_price_id; ?>/<?php echo $kk; ?>', <?php echo $v['Product']['id']?>)"><?php echo $_pro_city_price; ?></span><br />
           	   	<?php
           	   					}
           	   				}else{
           	   					echo "-";
           	   				}
           	   			}else{
           	   	?><span onclick="javascript:listTable.edit(this, 'products/update_product_price/', <?php echo $v['Product']['id']?>)"><?php echo $v['Product']['shop_price']; ?></span>
           	   	<?php
           	   			}
	           	}else{
	           		if(isset($configs['product_location_price'])&&$configs['product_location_price']=='1'){
	           			
	           		}else{
	           			echo $v['Product']['shop_price'];
	           		}
	           	}
	           	?>&nbsp;</div>
	           	<?php if (isset($configs["show_purchase_price"]) && $configs["show_purchase_price"]==1){ ?>
	           	<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php if($svshow->operator_privilege('products_edit')){?><span onclick="javascript:listTable.edit(this, 'products/update_product_purchase_price/', <?php echo $v['Product']['id']?>)"><?php } echo $v['Product']['purchase_price']?></span>&nbsp;</div>
			<?php }?>
            <?php //pr($product_list); ?>
           		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-hide-sm-only" style="text-align: left;"><?php if ($v['Product']['forsale'] == 1){?>
	                        <?php if($svshow->operator_privilege('products_edit')){?>
	                            <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_forsale',<?php echo $v['Product']['id'];?>)"></span>
	                        <?php }elseif($opertor_type=="D"){?>
	                            <span class="am-icon-check am-yes"></span>
	                        <?php }else{?>
	                            <span class="am-icon-check am-yes"></span>
	                        <?php }?>
	                    <?php }elseif($v['Product']['forsale'] == 0){?>
	                        <?php if($svshow->operator_privilege('products_edit')){?>
	                            <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_forsale',<?php echo $v['Product']['id'];?>)"></span>
	                        <?php }elseif($opertor_type=="D"){?>
	                            <span class="am-icon-close am-no"></span>
	                        <?php }else{?>
	                            <span class="am-icon-close am-no"></span>
	                        <?php }?>
	                    <?php }?> 
                        <br>
                        <?php if ($v['Product']['is_subscription_send'] == 1){?>
                            <?php if($svshow->operator_privilege('products_edit')){?>
                                <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_subscription',<?php echo $v['Product']['id'];?>)"></span>
                            <?php }elseif($opertor_type=="D"){?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }else{?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }?>
                        <?php }elseif($v['Product']['is_subscription_send'] == 0){?>
                            <?php if($svshow->operator_privilege('products_edit')){?>
                                <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'products/toggle_on_subscription',<?php echo $v['Product']['id'];?>)"></span>
                            <?php }elseif($opertor_type=="D"){?>
                                <span class="am-icon-close am-no"></span>
                            <?php }else{?>
                                <span class="am-icon-close am-no"></span>
                            <?php }?>
                        <?php }?> 

			</div>
			<div class="am-u-lg-1 am-u-md-1  am-hide-sm-only"><span class='product_manager_change' data="<?php echo $v['Product']['id'].';'.$v['Product']['product_manager']; ?>"><?php echo isset($Operator_list[$v['Product']['product_manager']])?$Operator_list[$v['Product']['product_manager']]:'-'; ?></span><br ><span class='last_update_info'><?php echo date("Y-m-d",strtotime($v['Product']['last_update_time'])); ?><br ><?php echo $v['Product']['operator_name']; ?></span>&nbsp;</div>
           <div class="am-u-lg-2 am-u-md-3 am-u-sm-5">

 <?php      $preview_url=$svshow->seo_link_path(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$ld['preview']));?>
                    <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                    </a> 
                <?php if($svshow->operator_privilege("products_edit")){ ?>
                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/products/view/'.$v['Product']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php } ?> 
                    <?php if($svshow->operator_privilege("products_copy")){ ?>
                    <a class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-edit" href="<?php echo $html->url('/products/copy_product/'.$v['Product']['id']); ?>"><span class="am-icon-copy"></span> <?php echo $ld['copy'];?>
                     </a>
                    <?php } ?>
                      <?php if($svshow->operator_privilege("products_recycle_bin")){ ?>
                      <a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'products/recycle_bin/<?php echo $v['Product']['id'] ?>','<?php echo $ld['confirm_products_to_recycle_bin'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['move_to_recycle_bin']; ?></a>
                     <?php } ?>
		  </div>
		</div></div></div>
        <?php }}else{?>
			<div>
				<div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
			</div>
		<?php }?>
</div>
<?php if(isset($product_list) && sizeof($product_list)){?>
    <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
        <?php if($svshow->operator_privilege("products_batch")){ ?>
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input id="selAll" onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl">
                    <select id="barch_opration_select" data-am-selected onchange="barch_opration_select_onchange(this)">
                        <option value="0"><?php echo $ld['batch_operate']?></option>
                        <?php if($svshow->operator_privilege("products_recycle_bin")){?>
                            <option value="recycle_bin"><?php echo $ld['batch_move_to_recycle_bin']?></option>
                        <?php }?>
                        <?php if($svshow->operator_privilege("product_categories_move")){?>
                            <option value="transfer_category"><?php echo $ld['transferred_to_classification']?></option>
                        <?php }?>
                        <option value="batch_onsale"><?php echo $ld['batch_onsale']?></option>
                        <option value="batch_notsale"><?php echo $ld['batch_notsale']?></option>
                        <option value="batch_set_subscription"><?php echo $ld['batch_set_subscription']?></option>
                        <option value="export_csv"><?php echo $ld['batch_export_product']?></option>
                        <option value="export_csv_by_category"><?php echo $ld['batch_export_product_by_category']?></option>
                        <?php if(constant("Product")=="AllInOne"){ ?>
                            <option value="product_cat"><?php echo $ld['batch_add_product_line']?></option>
                        <?php } ?>
                        	<option value="send_product_mail"><?php echo $ld["log_send_email"];?></option>
                    </select>&nbsp;
                </div>
                <!-- 订阅 -->
                <div class="am-fl" id="subscription_show" style="">
                    <select id="barch_subscription" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="barch_opration_select_onchange" onchange="order_opration_select_onchange(this)">
                        <option value=""><?php echo $ld['please_select']?></option>
                        <option value="1"><?php echo $ld['yes']?></option>
                        <option value="0"><?php echo $ld['no']?></option>
                    </select>&nbsp;
                </div>
                <!--导出 -->
                <div class="am-fl" style="display:none;">
                    <select id="export_csv" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="barch_opration_select_onchange" onchange="order_opration_select_onchange(this)">
                        <option value=""><?php echo $ld['please_select']?></option>
                        <option value="all_export_csv"><?php echo $ld['all_export']?></option>
                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
                        <option value="search_result"><?php echo $ld['search_export']?></option>
                    </select>&nbsp;
                </div>
                <!-- 导出 -->
                <div class="am-fl" style="display:none;">
                    <select id="export_type" data-am-selected name="all_order_opration_select_onchange">
                        <option value="all_product"><?php echo $ld['please_select']?></option>
                        <option value="for_sale"><?php echo $ld['for_sale_export']; ?></option>
                        <option value="out_of_stock"><?php echo $ld['out_of_stock_export']; ?></option>
                    </select>&nbsp;
                </div>
                <div class="am-fl" style="display:none;">
                    <select id="export_type_re" data-am-selected name="all_order_opration_select_onchange">
                        <option value="all_product"><?php echo $ld['please_select']?></option>
                        <option value="recommend"><?php echo $ld['recommend']; ?></option>
                        <option value="not_recommended"><?php echo $ld['no_recommend']; ?></option>
                    </select>&nbsp;
                </div>
                <?php if(isset($configs['product_location_price'])&&$configs['product_location_price']=='1'){ ?>
                <div class="am-fl" style="display:none;"><label class="am-checkbox am-success export_type_location_price"><input class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['location_price']?></label>&nbsp;</div>
                <div class="am-fl" style="display:none;">
                	 <select id="export_type_location_price" class='export_type_location_price' data-am-selected name="export_type_location_price">
                		<option value="all"><?php echo $ld['all']?></option>
                		<?php if(isset($systemresource_info['product_location'])&&!empty($systemresource_info['product_location'])){foreach($systemresource_info['product_location'] as $k=>$v){ ?>
                			<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
				<?php }} ?>
                	 </select>
                </div>
                <?php } ?>
                <div class="am-fl" style="display:none;">
                    <select id="transfer_category" data-am-selected name="barch_opration_select_onchange[]">
                        <option value="0"><?php echo $ld['select_categories']?></option>
                        <?php if(isset($category_tree) && sizeof($category_tree)>0){
                            foreach($category_tree as $first_k=>$first_v){?>
                                <option value="<?php echo $first_v['CategoryProduct']['id'];?>"><?php echo $first_v['CategoryProductI18n']['name'];?></option>
                                <?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){
                                    foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
                                        <option value="<?php echo $second_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
                                        <?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){
                                            foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
                                                <option value="<?php echo $third_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
                                            <?php }	}	}	}	}	}?>
                    </select>&nbsp;
                </div>
                <?php if(constant("Product")=="AllInOne"){ ?>
                <div class="am-fl" style="display:none;">
                    <select id="product_cat" data-am-selected name="product_cat">
                        <option value="0"><?php echo $ld['please_select']; ?></option>
                        <?php if(isset($fenxiao_productcat_list) && !empty($fenxiao_productcat_list)){ foreach ($fenxiao_productcat_list as $key=>$v){?>
                            <option value="<?php echo $key?>"><?php echo $v;?></option>
                        <?php }}?>
                    </select>&nbsp;
                </div>
                <?php } ?>
                <div class="am-fl">
                    <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_operations()" />&nbsp;
                </div>
            </div>
        <?php }?>
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php }?>
</div>
<div class="am-hide">
	<select id='product_manager_select'>
		<option value='0'><?php echo $ld['please_select'] ?></option>
		<?php foreach($Operator_list as $Opk=>$Opv){?>
             <option value="<?php echo $Opk;?>"><?php echo $Opv;?></option>
            <?php }?>
	</select>
</div>
<script type="text/javascript">
ct_checkbox();
if(typeof(getAttr)!='undefined'){
	getAttr();
}

$(function(){
	$("#check_box  input[type='checkbox']").click(function(){
		ct_checkbox();
	});
	
	$(".product_manager_change").dblclick(function(){
			var old_product_manager=$(this).html();
			var span_data=$(this).attr('data');
			var span_data_arr=span_data.split(';');
			var product_id=span_data_arr[0];
			var product_manager=span_data_arr[1];
			var option_html=$("#product_manager_select").html();
			var change_select_html="<select onchange='product_manager_change(this,"+product_id+","+product_manager+",\""+old_product_manager+"\")'>"+option_html+"</select>";
			$(this).html(change_select_html);
			if(product_manager!='0'){
				$(this).find("select option[value='"+product_manager+"']").attr('selected',true);
			}
	});
	
	$("label.export_type_location_price input[type='checkbox']").click(function(){
			if($(this).prop('checked')){
				$("select.export_type_location_price").parent().show();
			}else{
				$("select.export_type_location_price").parent().hide();
				$("select.export_type_location_price").parent().hide();
				$("select.export_type_location_price option[value='all']").attr('selected',true);
				$("select.export_type_location_price").trigger('changed.selected.amui');
			}
	});
});


function product_manager_change(s_obj,product_id,product_manager,old_product_manager){
	var product_manager_id=$(s_obj).val();
	if(s_obj!=product_manager){
		$.ajax({
		        url:admin_webroot+"products/ajax_update_product_manager/",
		        type:"POST",
		        data:{'product_id':product_id,'product_manager':product_manager_id},
		        dataType:"json",
		        success:function(data){
		            	if(data.code=='1'){
		            		$(s_obj).parent().attr('data',product_id+";"+product_manager_id);
		            		$(s_obj).parent().html(data.message);
		            	}else{
		            		alert(data.message);
		            	}
		        }
		    });
	}
}

function ct_checkbox(){
	var ck_txt_arr=new Array();
	$(".bb0 input[type='checkbox']:checked").each(function(){
		var cl_value=$(this).val();
		if(cl_value!="-1"){
			var ck_html=$(this).parent().html().replace(/<[^>]+>/g,"").trim();
			ck_html=ck_html.replace("--","").replace("--","");
			ck_txt_arr.push(ck_html);
		}
	});
	if(ck_txt_arr.length>0){
		$("#check_box button span").html(ck_txt_arr.join(";"));
	}else{
		$("#check_box button span").html("<?php echo $ld['all_data']; ?>");
	}
}

function ck_checkbox(){
    var dropdown = $('#check_box'),
        data = dropdown.data('amui.dropdown');
    if(data.active){
        dropdown.dropdown('close');
    }
    var str=document.getElementsByName("box");
    var leng=str.length;
    var chestr="";
    for(var i=0;i<leng;i++){
        if(str[i].checked == true)
        {
            chestr+=str[i].value+",";
        };
    };
    return chestr;
}

function formsubmit(){
    var export_act_flag=document.getElementById('export_act_flag').value;
    if(document.getElementById('brand_id')!=null){
        var brand_id=document.getElementById('brand_id').value;
    }else{
        var brand_id=0;
    }
    if(document.getElementById('product_type_id')!=null){
        var product_type_id=document.getElementById('product_type_id').value;
    }else{
        var product_type_id=0;
    }
    var category_type=document.getElementById('category_type').value;
    var operator_id=document.getElementById('operator_id').value;
    var forsale=document.getElementById('forsale').value;
    var start_date_time = document.getElementsByName('start_date_time')[0].value;
    var end_date_time = document.getElementsByName('end_date_time')[0].value;
    var product_keywords=document.getElementById('product_keywords').value;
    var is_recommond=document.getElementById('is_recommond').value;
    var start_date = document.getElementsByName('start_date')[0].value;
    var end_date = document.getElementsByName('end_date')[0].value;
    var min_price=document.getElementById('min_price').value;
    var max_price=document.getElementById('max_price').value;
    var min_quantity=document.getElementById('min_quantity').value;
    var max_quantity=document.getElementById('max_quantity').value;
    var option_type_id=document.getElementById('option_type_id').value;
    var product_manager=document.getElementById('product_manager').value;
    if(document.getElementById('attr_cate_id')){
        var attr_cate_id=document.getElementById('attr_cate_id').value;
    }else{
        var attr_cate_id=0;
    }
    //关联搜索
    var product_cross_reference=0;
    if(document.getElementById('product_cross_reference')){
    		if(document.getElementById('product_cross_reference').checked){
    			product_cross_reference=1;
    		}
    }
    var ta = ck_checkbox();
    var str = '';
    str +="&"+"category_id=" +ta.substring(ta,ta.length-1);
    var attr=attr_checkbox();
    str +="&"+"attr_value=" +attr.substring(attr,attr.length-1);
    var url = "operator_id="+operator_id+"&export_act_flag="+export_act_flag+"&brand_id="+brand_id+"&product_type_id="+product_type_id+"&forsale="+forsale+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&product_keywords="+product_keywords+"&is_recommond="+is_recommond+"&start_date="+start_date+"&end_date="+end_date+"&min_price="+min_price+"&max_price="+max_price+str+"&option_type_id="+option_type_id+"&attr_cate_id="+attr_cate_id+"&category_type="+category_type+"&product_manager="+product_manager+"&min_quantity="+min_quantity+"&max_quantity="+max_quantity+"&product_cross_reference="+product_cross_reference;
    window.location.href = encodeURI(admin_webroot+"products?"+url);
}

function attr_checkbox(){
    var str=document.getElementsByName("attr_box");
    var chestr="";
    if(str.length>0){
        var leng=str.length;
        for(i=0;i<leng;i++){
            if(str[i].checked == true){
                chestr+=str[i].value+",";
            };
        };
    }else{
	if(document.getElementById('product_dropdown_id')!=null){
		chestr=document.getElementById('product_dropdown_id').value+";";
	}
    }
    return chestr;
}
var all=$('#y1 .a1');
bll=$('#y1 .b1'),
    cll=$('#y1 .btn'),
    allclick = function(){
        if(bll.prop("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
        }
        else{bll.addClass('c1');all.addClass('up');}
    },
    removeclick = function(){
        all.removeClass('up');
        bll.removeClass('c1');
    };
var checkbox =$('#y1 .b1 .checkbox');
select = $('#y1 .b1 #select');
$("#select").click(function(){
    $(".bb0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
});



checkboxControl = function(){
    //	$.Array.indexOf(checkbox.get('checked'), false) < 0 ? select.attr('checked', true) : select.attr('checked', false);
},
    selectControl = function(){
        //select.attr('checked') ? checkbox.attr('checked', true) : checkbox.attr('checked', false);
    };

checkbox.on('click', checkboxControl);
select.on('click', selectControl);
cll.on('click', removeclick);
all.on('click', allclick);

function select_attr(){
    var str=document.getElementsByName("attr_box");
    var attr_id=document.getElementsByName("product_attr_id");
    var sel_attr=document.getElementById("attr_value").value;
    sel_atrr=sel_attr.split(",");
    if(str){
        for(i=0;i<str.length;i++){
            for(j=0;j<sel_atrr.length;j++){
                if(str[i].value==sel_atrr[j]){
                    str[i].checked = true;
                }
            }
        }
    }
}

//点击属性图标编辑事件
function setInput(id){
    var obj=document.getElementById(id);
    if(obj.style.display=='block'){
        obj.style.display='none';
    }else{
        obj.style.display='block';
    }
}


function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "val="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        Type:"POST",
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
function commodity_recommend  (id,obj) {
    var class_name = $(obj).attr('class');
    var val = (class_name.match(/yes/i)) ? 0 : 1;
    var postData = {'val':val,'id':id};
    $.ajax({
        url:admin_webroot+"products/update_recommand_flag",
        type:"POST",
        dataType:"json",
        data:postData,
        success:function (data) {
            if (data.flag == 1) {
                if (val == 0) {
                    $(obj).removeClass("recom_yes")
                          .addClass("recom_no");   
                }else
                if (val == 1){
                    $(obj).removeClass("recom_no")
                          .addClass("recom_yes");   
                }
            };
        }
    })
}

//商品列表公共属性编辑
function PublicAttributeEdit(obj,func,id,attr_input_type,attr_options){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && (tag.toLowerCase() == "input"||tag.toLowerCase() == "select")){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	if(typeof(attr_input_type)== "undefined")attr_input_type=0;
  	if(attr_input_type==1){
  		if(typeof(attr_options) == "undefined")attr_options='';
  		var attr_option_info=attr_options.split("\t");
  		var SELECT = document.createElement("SELECT");
  		SELECT.options.add(new Option(j_please_select,''));
  		for(var i=0;i<attr_option_info.length;i++){
  			var attr_option_txt=attr_option_info[i];
  			var attr_option_arr=attr_option_txt.split("||");
  			SELECT.options.add(new Option(attr_option_arr[1],attr_option_arr[0],true,attr_option_arr[0]==val));
  		}
  		obj.innerHTML = "";
		obj.appendChild(SELECT);
		SELECT.focus();
		
		SELECT.onchange=function(){
			var sel_index=SELECT.selectedIndex;
			var val = SELECT.options[sel_index].value;
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+func,
				data:{'id':id,'val':Utils.trim(val)},
				async: false,
				success: function(data) {
					try{
						var result= JSON.parse(data);
						if(result.flag == 1){
							var result_content = (result.flag == 1) ? result.content : org;
							if(Browser.isIE){
								obj.innerText=Utils.trim(result_content);
							}else{
								obj.innerHTML=Utils.trim(result_content);
							}
						}
						if(result.flag == 2){
							alert(result.content);
							obj.innerHTML = org;
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
				}
			}); 
		};
  	}else{
	  	/* 创建一个输入框 */
		var txt = document.createElement("INPUT");
		txt.type = "text" ;
		txt.value = (val == 'N/A')|| (val == '-')? '' : val;
		txt.className = "input_text" ;
		txt.style.width = (obj.offsetWidth + 12) + "px" ;
		txt.style.minWidth = "20px" ;
	  	
	  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
		obj.innerHTML = "";
		obj.appendChild(txt);
		txt.focus();
		
		/* 编辑区输入事件处理函数 */
		txt.onkeypress = function(e){
			var evt = Utils.fixEvent(e);
			var obj = Utils.srcElement(e);
			if(evt.keyCode == 13){
				obj.blur();
				return false;
			}
			if(evt.keyCode == 27){
				obj.parentNode.innerHTML = org;
			}
		 }
		
		/* 编辑区失去焦点的处理函数 */
		txt.onblur = function(e){
			if(Utils.trim(txt.value).length > 0 || true){
				$.ajax({
					cache: true,
					type: "POST",
					url:admin_webroot+func,
					data:{'id':id,'val':Utils.trim(txt.value)},
					async: false,
					success: function(data) {
						try{
							var result= JSON.parse(data);
							if(result.flag == 1){
								var result_content = (result.flag == 1) ? result.content : org;
								if(Browser.isIE){
									obj.innerText=Utils.trim(result_content);
								}else{
									obj.innerHTML=Utils.trim(result_content);
								}
							}
							if(result.flag == 2){
								alert(result.content);
								obj.innerHTML = org;
							}
						}catch(e){
							alert(j_object_transform_failed);
							obj.innerHTML = org;
						}
					}
				});
			}else{
		  		alert(j_empty_content);
		    		obj.innerHTML = org;
		    	}
		}
	}
}
reAll = function(obj){

 var selAll = document.getElementById("selAll");
 var selAll1 = document.getElementById("selAll1");
 var select = document.getElementById("select");
 if(selAll.checked == true){
    selAll.checked = obj.checked;
 }
 if(selAll1.checked == true){
    selAll1.checked = obj.checked;
 }
 if(select.checked == true){
    select.checked = obj.checked;
 }
}

cll = function(){
    var time_s = document.getElementById("time_s");
    
    time_s.value = '';
    
}
clr = function(){
    var time_e = document.getElementById("time_e");
    time_e.value = '';
}

$(document).ready(function(){
	$("ul.product_type_tree").toggle();
	$('#gaoji').click(function(){
		$('.amHide').toggle();
		$('ul.product_type_tree').toggle();
	});
});	

$("#barch_opration_select").on('change',function(){

    if($(this).val() == 'batch_set_subscription'){
           //alert($(this).val());
        $("#subscription_show").removeAttr("style");
    }else{
         $("#subscription_show").attr("style","display:none;"); 
    }
})
</script>