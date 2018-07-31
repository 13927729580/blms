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
</style>
<div>
    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
	<input type='hidden' name="item_type" id='item_type' value="<?php echo isset($item_type)?$item_type:''; ?>" />
	<input type='hidden' name="item_type_id" id='item_type_id' value="<?php echo isset($item_type_id)?$item_type_id:''; ?>" />
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1" id="order_do" style="margin:1px 0 0 0">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['product_categories']; ?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <div class="checkbox" id = 'y1' >
                    <div class="am-dropdown" data-am-dropdown id="check_box">
                        <button  style="width:100%;" class="am-selected-btn am-btn am-dropdown-toggle am-btn-default   am-btn-sm" data-am-dropdown-toggle><span class="am-selected-status am-fl"><?php echo $ld['all_data']; ?></span><i class="am-selected-icon am-icon-caret-down"></i></button>
                        <ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1 b1" style="height:300px;overflow-x:hidden;overflow-y:scroll; width:100%;">
                            <li class="bb0" style="padding-left:10px;">
                                <label class="am-checkbox am-success">
                                    <input type="checkbox" name="box" value="-1"  data-am-ucheck <?php if(in_array("-1",$category_arr)) echo 'checked';?>/> <?php echo $ld['unknown_classification']?>
                                </label>
                            </li>
                            <?php foreach($category_name_list as $cak=>$cav){ ?>
                                <li class="bb0" style="margin-left:10px;">
                                    <label class="am-checkbox am-success">
                                        <input type="checkbox" class="checkbox" name="box" value="<?php echo $cak;?>"  data-am-ucheck <?php if(in_array($cak,$category_arr)) echo 'checked';?>/>
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
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php  echo $ld['product_brand']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <?php echo $this->element('brand_tree'); ?>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['delivery_status']?></label>
            <?php //pr($delivery_status) ?>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="delivery_status" id='delivery_status' multiple data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
                    <!-- <option value="-1">所有</option> -->
                    <?php if(isset($Resource_info['order_product_status'])&&sizeof($Resource_info['order_product_status'])>0) {foreach($Resource_info['order_product_status'] as $kk=>$vv){?>
                        <option value="<?php echo $kk; ?>" <?php if(isset($delivery_status)&&$delivery_status!=''){foreach ($delivery_status as $kkk => $vvv) {if($kk==$vvv){ ?>selected<?php }}} ?>><?php echo $vv; ?></option>
                    <?php }} ?>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0" >
            <label class="am-u-lg-3  am-u-md-3 am-u-sm-4 am-form-label-text" ><?php echo '审核状态';?></label>
            <div class="am-u-lg-8 am-u-md-7 am-u-sm-7  am-u-end">
                <select name="check_status"  id="check_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
                    <option value="-1" selected><?php echo $ld['all_data']?> </option>
                   
                    <option value="1" <?php if(isset($check_status)&&$check_status == 1){?>selected<?php }?>>
                        已审核
                    </option>
                    <option value="0" <?php if(isset($check_status)&&$check_status == 0){?>selected<?php }?>>
                        未审核
                    </option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">订单号</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="order_code" id="order_code" value="<?php echo isset($order_code)?$order_code:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">客户名称</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="consignee" id="consignee" value="<?php echo isset($consignee)?$consignee:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">销售顾问</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="order_manager" id="order_manager" value="<?php echo isset($order_manager)?$order_manager:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">修改师</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="picker" id="picker" value="<?php echo isset($picker)?$picker:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">质检师</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="QC" id="QC" value="<?php echo isset($QC)?$QC:'';?>"/>
            </div>
        </li>
                <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['product']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="product_keyword" id="product_keyword" placeholder="<?php echo $ld['code']; ?>/<?php echo $ld['name']?>" value="<?php echo isset($product_keyword)?$product_keyword:'';?>"/>
            </div>
        </li>
        <li>
        	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['create_time']?></label>
        	<div class="am-u-lg-4  am-u-md-3 am-u-sm-3" style="padding-right:0;width:35%;">
            	<div class="am-input-group">
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id='order_start_date' name="start_date" value="<?php echo isset($start_date)?$start_date:'';?>" />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                		<i class="am-icon-remove"></i>
              		</span>
          		</div>
			</div>
			<em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;width:4%;">-</em>
			<div class=" am-u-lg-4  am-u-md-3  am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;width:33%;">
            	<div class="am-input-group">
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id='order_end_date' name="end_date" value="<?php echo isset($end_date)?$end_date:'';?>" />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                		<i class="am-icon-remove"></i>
              		</span>
          		</div>
			</div>
		</li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
        </li>
    </ul>
    <div class="am-g">
        <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
        <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
        <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div>
    <div class="listtable_div_btm">
        <div class="am-g am-hide-sm-only">
            <div class="am-u-lg-1 am-u-md-1" style="text-align:left;width:2%;"><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /></label></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo $ld['sku'];?>/<?php echo $ld['name'] ?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;">商品条码</div>
            
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo $ld['brand'];?>/<?php echo $ld['classification'] ?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;">客户名称</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-1" style="text-align:left;">订单号<br>创建时间</div>
            <!-- <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">创建时间</div> -->
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;">销售顾问<br>修改师<br>质检师</div>
           <!--  <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">修改师</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">质检师</div> -->
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($product_list) && sizeof($product_list)>0){foreach($product_list as $k=>$v){?>
            <!-- 电脑端开始 -->
            <?php //pr($v) ?>
            <div class="am-g am-hide-sm-only">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:2%;">
                            <!-- <label class="am-checkbox am-success"><iput type="hidden" id="order_id_<?php echo $v['OrderProduct']['id']?>" value="<?php echo $v['Order']['id']?>"/><input type="checkbox" name="checkboxes[]" value="<?php echo $v['OrderProduct']['id']?>"  data-am-ucheck />&nbsp;<?php echo $v['OrderProduct']['id'];?></label> -->
                            <label class="am-checkbox am-success"><iput type="hidden" id="order_id_<?php echo $v['OrderProduct']['id']?>" value="<?php echo $v['Order']['id']?>"/><input type="checkbox" name="checkboxes[]" value="<?php echo $v['OrderProduct']['id']?>"  data-am-ucheck /></label>
                            <span style="display:none;"><?php echo $v['OrderProduct']['id'];?></label></span>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo isset($v['OrderProduct']['product_code'])?$v['OrderProduct']['product_code']:"-";?><br ><?php echo isset($v['OrderProduct']['product_name'])?$v['OrderProduct']['product_name']:"&nbsp;";?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="word-break:break-all;"><?php echo $v['OrderProduct']['product_number'] ?>&nbsp;</div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:"-";?><br ><?php echo isset($product_category_tree[$v['Product']['category_id']])?$product_category_tree[$v['Product']['category_id']]:"&nbsp;";?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;"><?php echo $v['Order']['consignee'] ?>&nbsp;</div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-1" style="text-align:left;"><?php echo $v['Order']['order_code'];?><br><?php echo $v['Order']['created'];?></div>
                        <!-- <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Order']['created'];?></div> -->
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;"><?php echo !empty($v['Order']['manager_name'])?$v['Order']['manager_name']:"-";?><br><?php echo !empty($v['OrderProduct']['picker_name'])?$v['OrderProduct']['picker_name']:"-";?><br><?php echo !empty($v['OrderProduct']['qc_name'])?$v['OrderProduct']['qc_name']:"-";?></div>
                      <!--   <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo !empty($v['OrderProduct']['picker_name'])?$v['OrderProduct']['picker_name']:"-";?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo !empty($v['OrderProduct']['qc_name'])?$v['OrderProduct']['qc_name']:"-";?></div> -->
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;"><?php echo $Resource_info['order_product_status'][$v['OrderProduct']['delivery_status']];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/order_products/view/'.$v['OrderProduct']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 结束 -->
            <!-- 移动端开始 -->
            <div class="am-g am-show-sm-only">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-cf">
                        <div class="am-u-sm-1" style="width:5%;"><div class="am-u-sm-1" style="text-align:left;"><label class="am-checkbox am-success"><iput type="hidden" id="order_id_<?php echo $v['OrderProduct']['id']?>" value="<?php echo $v['Order']['id']?>"/><input type="checkbox" name="checkboxes[]" value="<?php echo $v['OrderProduct']['id']?>"  data-am-ucheck /></label></div></div>
                    <div class="am-u-sm-11 am-cf">
                        <div class="am-cf" style="margin-bottom:1rem;">                      
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-9" style="word-wrap:break-word;text-align:left;"> <?php echo isset($v['OrderProduct']['product_code'])?$v['OrderProduct']['product_code']:"-";?><br ><?php echo isset($v['OrderProduct']['product_name'])?$v['OrderProduct']['product_name']:"&nbsp;";?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;"><?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:"-";?><br ><?php echo isset($product_category_tree[$v['Product']['category_id']])?$product_category_tree[$v['Product']['category_id']]:"&nbsp;";?></div>
                    <!--     <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"></div> -->
                    </div>                        
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-12" style="text-align:left;margin-bottom:1rem;"><?php echo $v['Order']['order_code'];?><br><?php echo $v['Order']['created'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-6" style="text-align:left;">销售顾问：<?php echo !empty($v['Order']['manager_name'])?$v['Order']['manager_name']:"-";?><br>修改师：<?php echo !empty($v['OrderProduct']['picker_name'])?$v['OrderProduct']['picker_name']:"-";?><br>质检师：<?php echo !empty($v['OrderProduct']['qc_name'])?$v['OrderProduct']['qc_name']:"-";?></div>
                      <!--   <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"></div> -->
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="text-align:left;"><?php echo $Resource_info['order_product_status'][$v['OrderProduct']['delivery_status']];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="text-align:left;">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/order_products/view/'.$v['OrderProduct']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                        </div>
                    </div>                   
                    </div>
                </div>
            </div>
            <!-- 结束 -->
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($product_list) && sizeof($product_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group">
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl">
                    <select id="barch_opration_select" data-am-selected>
                        <option value="-1"><?php echo $ld['all_data']?></option>
	                    <?php if(isset($Resource_info['order_product_status'])&&sizeof($Resource_info['order_product_status'])>0) {foreach($Resource_info['order_product_status'] as $kk=>$vv){?>
	                        <option value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
	                    <?php }} ?>
                    </select>
                </div>
                <div class="am-fl">
                    <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_product()" />&nbsp;
                </div>
            </div>
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="select_evaluation" style="width:640px;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            选择评测
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <input style="width:200px;float:left;margin-right:5px;" type="text" name="evaluation_keyword" id="evaluation_keyword" /> <input  type="button" class="am-btn am-btn-success am-radius am-btn-sm " value="<?php echo $ld['search']?>" onclick="searchevaluation();" />
                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        <select id="evaluation_select" data-am-selected>
                            <option value="-1">请选择</option>
                        </select>
                    </div>
                </div>
                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:set_question();"></div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
	ct_checkbox();
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
        if(document.getElementById('brand_id')!=null){
            var brand_id=document.getElementById('brand_id').value;
        }else{
            var brand_id=0;
        }
        var order_manager=document.getElementById('order_manager').value;
        var delivery_status=document.getElementById('delivery_status');
        var order_code=document.getElementById('order_code').value;
        var check_status=document.getElementById('check_status').value;
        var consignee=document.getElementById('consignee').value;
        var picker=document.getElementById('picker').value;
        var QC=document.getElementById('QC').value;
        var ta = ck_checkbox();
        var str = '';
        str +="&"+"category_id=" +ta.substring(ta,ta.length-1);
        var select_value = [];
        for(i=0;i<delivery_status.length;i++){
            if(delivery_status.options[i].selected){
                select_value.push(delivery_status[i].value);
            }
        }
        if(document.getElementById('product_keyword')){
        	str+='&product_keyword='+document.getElementById('product_keyword').value;
        }
        if(document.getElementById('order_start_date')){
        	str+='&start_date='+document.getElementById('order_start_date').value;
        }
        if(document.getElementById('order_end_date')){
        	str+='&end_date='+document.getElementById('order_end_date').value;
        }
        if(document.getElementById('item_type')){
        	str+='&item_type='+document.getElementById('item_type').value;
        }
        if(document.getElementById('item_type_id')){
        	str+='&item_type_id='+document.getElementById('item_type_id').value;
        }
        var url = "order_manager="+order_manager+"&delivery_status="+select_value+"&brand_id="+brand_id+"&check_status="+check_status+"&order_code="+order_code+"&consignee="+consignee+"&picker="+picker+"&QC="+QC+str;
        window.location.href = encodeURI(admin_webroot+"order_products?"+url);
    }

    $("#check_box  input[type='checkbox']").click(function(){
        ct_checkbox();
    });

    var all=$('#y1 .a1');
    bll=$('#y1 .b1'),
        cll=$('#y1 .btn'),
        allclick = function(){
            if(bll.prop("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
            }else{
                bll.addClass('c1');all.addClass('up');}
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
    cll.on('click', removeclick);
	all.on('click', allclick);

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
    
    //批量操作
    function batch_product(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var barch_opration_select = document.getElementById("barch_opration_select");
        var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
        var checkboxes=new Array();
        var orderids=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
                var product_id=bratch_operat_check[i].value;
                var order_id = document.getElementById("order_id_"+product_id);
                orderids.push(order_id.value);
            }
        }
        if(barch_opration_select.value != "-1" && checkboxes.length != 0 && checkboxes.length == orderids.length){
        	if(confirm(confirm_exports+"修改状态？")){
	            $.ajax({
	                url:admin_webroot+"orders/barch_ajax_order_product_status_modify/",
	                type:"POST",
	                data:{ids:checkboxes,orderids:orderids,status:barch_opration_select.value},
	                dataType:"json",
	                success:function(data){
	                    try{
	                        alert(data.message);
	                    }catch (e){
	                        alert(j_object_transform_failed);
	                    }
	                    window.location.href = window.location.href;
	                }
	            });
            }
        }
    }
</script>