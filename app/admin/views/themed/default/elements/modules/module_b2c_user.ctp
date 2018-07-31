<div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#user_style'}"><?php echo $ld['user_template']; ?></h4>
                </div>
                <div id="user_style" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd" id="style">
                        <p style="text-align:right;">
                            <button type="button" class="am-btn am-btn-warning am-radius am-btn-sm add_style"  /><span class="am-icon-plus"></span> <?php echo $ld['add'];?></button>
                        </p>
                        <table class="am-table  table-main">
                            <thead>
                            <tr>
                                <th><?php echo $ld['template_name']?></th>
                                <th><?php echo $ld['user_edition_type'];?></th>
                                <th><?php echo $ld['specification']?></th>
                                <th><?php echo $ld['product_type']?></th>
                                <th><?php echo $ld['default']?></th>
                                <th><?php echo $ld['operate']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($user_style_list) && sizeof($user_style_list)>0){foreach($user_style_list as $k=>$v){ ?>
                                <tr >
                                    <td><?php echo $v['UserStyle']['user_style_name']; ?></td>
                                    <td><?php echo $v['UserStyle']['style_name']; ?></td>
                                    <td><?php echo $v['UserStyle']['attribute_code']; ?></td>
                                    <td><?php echo $v['UserStyle']['attr_name']; ?></td>
                                    <td><?php if($v['UserStyle']['default_status'])echo $html->image('/admin/skins/default/img/yes.gif');else echo $html->image('/admin/skins/default/img/no.gif');?></td>
                                    <td><a href="javascript:void(0);" id="<?php echo $v['UserStyle']['id']?>" class="am-btn am-btn-default am-radius am-btn-sm edit_user_style"><?php echo $ld["edit"]?></a><a href="javascript:void(0);" id="<?php echo $v['UserStyle']['id']?>" class="am-btn am-btn-default am-radius am-btn-sm delete_user_style" style="margin-left:5px;"><?php echo $ld["delete"]?></a></td>
                                </tr>
                            <?php }}else{?>
                                <tr><td colspan="6" align="center"><?php echo $ld['no_user_template']?></td></tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
	<div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#users_user_address'}"><?php echo $ld['users_user_address'] ?></h4>
            </div>
            <div id="users_user_address" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd" id="user_addr_list_show"></div>
                <!--- address_collapse-->
            </div>
        </div>
        		
       <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#order'}"><?php echo $ld['order'] ?></h4>
                </div>
                <div id="order" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd" id="user_order"></div>
                </div>
            </div>
            		
          <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#order_product'}"><?php echo '订单商品' ?></h4>
                </div>
                <div id="order_product" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd" id="user_order_product">
                    <div class="am-g am-hide-sm-only">
                        <div class="am-u-lg-1 am-u-md-1" style="text-align:left;width:2%;">&nbsp;</div>
                        <div class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="text-align:left;padding-left:0;padding-right:0;"><?php echo $ld['sku'];?>/<?php echo $ld['name'] ?></div>
                        <div class="am-u-lg-2 am-u-md-1 am-u-sm-1">商品条码</div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;"><?php echo $ld['brand'];?>/<?php echo $ld['classification'] ?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;padding-left:0;padding-right:0;">客户名称</div>
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
                    <div class="am-g am-hide-sm-only">
                        <div class="listtable_div_top" >
                            <div style="margin:10px auto;" class="am-g">
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:2%;">
                                    <?php //echo empty($v['Product']['img_thumb'])?"":$svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>'','sub_name'=>$v['OrderProduct']['product_name'],'img'=>$v['Product']['img_thumb'],'style'=>'width: 100px;')); ?>&nbsp;
                                </div>
                                <div class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="text-align:left;padding-left:0;padding-right:0;"><?php echo isset($v['OrderProduct']['product_code'])?$v['OrderProduct']['product_code']:"-";?><br ><?php echo isset($v['OrderProduct']['product_name'])?$v['OrderProduct']['product_name']:"&nbsp;";?></div>
                                <div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $v['OrderProduct']['product_number'] ?>&nbsp;</div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;"><?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:"-";?><br ><?php echo isset($product_category_tree[$v['Product']['category_id']])?$product_category_tree[$v['Product']['category_id']]:"&nbsp;";?></div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;padding-left:0;padding-right:0;"><?php echo $v['Order']['consignee'] ?>&nbsp;</div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-1" style="text-align:left;"><?php echo $v['Order']['order_code'];?><br><?php echo $v['Order']['created'];?></div>
                                <!-- <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Order']['created'];?></div> -->
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;"><?php echo !empty($v['Order']['manager_name'])?$v['Order']['manager_name']:"-";?><br><?php echo !empty($v['OrderProduct']['picker_name'])?$v['OrderProduct']['picker_name']:"-";?><br><?php echo !empty($v['OrderProduct']['qc_name'])?$v['OrderProduct']['qc_name']:"-";?></div>
                              <!--   <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo !empty($v['OrderProduct']['picker_name'])?$v['OrderProduct']['picker_name']:"-";?></div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo !empty($v['OrderProduct']['qc_name'])?$v['OrderProduct']['qc_name']:"-";?></div> -->
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;width:11.3%;"><?php echo $R_info['order_product_status'][$v['OrderProduct']['delivery_status']];?></div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="text-align:left;">
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/order_products/view/'.$v['OrderProduct']['id']); ?>">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['view']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                    <!-- end -->
                    </div>
                </div>
            </div>
            			
            			
                
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#user_coupons_list'}"><?php echo $ld['my_coupons'] ?></h4>
                </div>
                <div id="user_coupons_list" class="am-panel-collapse am-collapse">
                    <div class="am-panel-bd" id="user_coupons"></div>
                </div>
            </div>