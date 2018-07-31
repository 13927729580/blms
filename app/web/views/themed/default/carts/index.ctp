<style type="text/css">
    .cl{clear:both;}
    .errort{margin:2.5rem auto;padding:5rem 0;text-align:center;}
    .cart_table div[class*="am-u-"] > *[class*="am-u-"]{padding-left:0px;padding-right:0px;}
    .cart_table .am-g{margin:8px 0;}
    .cart_table .cart_pro_img img{width:100%;height:100%;max-width:100px;}
    .cart_price a:last-child{padding-left:10px;}
    .cart_price input[type='text']{text-align:center;border: 1px solid #CCC;width:30%;}

    @media only screen
    and (max-width : 640px){
        .cart_table .am-g{margin:10px 0;}
        .cart_table div[class*="am-u-"] > *[class*="am-u-"]{padding-left:5px;padding-right:5px;}
        .cart_table .cart_pro_img,.cart_table .goodsname{padding-left:0px;padding-right:0px;}
        .cart_price a{display:block;}
        .cart_price a{text-align:center;margin:0 auto;}
    }
    .goodsname a{font-size:15px;color:#333;}
    .goodsname p{font-size:15px;}
    .shop_price,#cart_price,#cart_price2{color:#dd514c;font-weight:bold;}
    .shop_price del{color:#ccc;font-weight:normal;}
    .name{font-size:15px;}
    @media only screen and (min-width:1024px){
        .cart_price{padding-left:14px;}
    }
    .info{font-size:1.4rem;padding:0 1.5rem;text-align:justify;}
    .info em{color:#cf0000;}
    #cart_prompt{font-size: 14px;color:#999;}
    .am-carts-buy{padding-bottom:10px;}
    .am-carts-lease{margin-top:10px;}
</style>
<?php //pr($svcart['products']) ?>
<?php  
    $product_is_lease = array();
   if (isset($svcart['products'])) {foreach ($svcart['products'] as $ks => $vs) {
   $product_is_lease[] = $vs['Product']['is_lease'];
   }}
?>
<div class="shoppingcart am-container" style="padding-left:5px;">
    <?php if(!empty($svcart['products'])){ ?>

<!-- 购物车商品开始 -->
        <div class="am-g cart_table am-carts-buy">
            <?php if (in_array('0',$product_is_lease)) {?>
            <div class="am-g am-hide-sm-only">
                <div class="am-u-lg-1 am-u-md-1 am-text-center"><input type="checkbox" name="all_check" id="am-buy-check"></div>
                <div class="am-u-lg-5 am-u-md-5 am-text-left"><?php echo $ld['buy_goods'] ?></div>
                <div class="am-u-lg-2 am-u-md-5">&nbsp;</div>
                <div class="am-u-lg-2 am-u-md-2 am-text-center"><?php echo $ld['quantity']?></div>
                <div class="am-u-lg-2 am-u-md-2 am-text-center"><?php echo $ld['number_num1']?></div>
            </div>
            <?php } ?>
            <?php
            $sum_point = 0;$sum_discount=0; $i=0;
            foreach($svcart['products'] as $k=>$v){
                $i++;$sum_point+=$v['Product']['point']*$v['quantity'];$total_attr_price=0;
                if(!empty($v['Product']['market_price'])&&$v['Product']['market_price']>$v['Product']['shop_price']){
                    $sum_discount+=(($v['Product']['market_price']-$v['Product']['shop_price'])*$v['quantity']);
                }
                echo $form->create('carts',array('action'=>'update_num','name'=>'update_num'.$k,'type'=>'POST','class'=>'am-form' ));
                ?>
                <div class="am-g <?php if ($v['Product']['is_lease'] == 1) {
                    echo "am-hide am_remove";
                } ?>" style="border:1px solid #f5f5f5;padding:10px 0px;">

                <div class="am-u-sm-12">
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding:0"><input type="checkbox" name="subBox" value="<?php echo $k;?>"/></div>
                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-8 am-text-left productname" style="padding:0">
                        <!-- 商品 -->

                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 cart_pro_img am-text-left" style="padding:0">
                            <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>!empty($v['Product']['img_thumb'])?$v['Product']['img_thumb']:'/theme/default/images/default.png','name'=>$v['ProductI18n']['name']));?>
                        </div>

                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 goodsname">
                            <?php if(!empty($v['Product']['file_url'])){?>
                                <a href='<?php echo $server_host.($html->url("/products/view/".$k));?>' ><?php echo $v['ProductI18n']['name'];?></a>
                            <?php }else{ ?>
                                <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name']));}?>
                            <p class="name" style="margin-top:0px;margin-bottom:0px;white-space:nowrap;font-size:15px;"><?php echo $ld['sku'];?>:&nbsp;<span><?php echo $v['Product']['code'] ?></span></p>
                            <?php if(isset($v['attributes'])){ ?>
                                <div class="name" style="white-space:nowrap; "><?php echo $ld['attribute'] ?>:<?php $arr1=explode("<br />",$v['attributes']);
                                    foreach($arr1 as $attr_vv){
                                        if($attr_vv==""){continue;}
                                        $attr_str=explode(":",$attr_vv);
                                        echo "<span style='margin-left:5px;'>".(empty($attr_str[1])?'':$attr_str[1])."</span>";
                                    }
                                    ?></div>
                            <?php }?>
                            <!-- sm价格 -->
                            <div class="am-show-sm-only  shop_price" style="white-space:nowrap; ">
                                <?php 
                                if($v['Product']['is_lease'] !== 1){
                                if(isset($v['is_promotion'])&&$v['is_promotion']==1){
                                    echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
                                    if($v['Product']['market_price']!=$v['Product']['promotion_price']){
                                        ?>
                                        <del ><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                        <?php
                                    }
                                }else{
                                    echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
                                    if($v['Product']['market_price']!=$v['Product']['shop_price']){
                                        ?>
                                        <del style="color:gray;"><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                        <?php
                                    }
                                }}?>
                                <?php if ($v['Product']['is_lease'] == 1) {
                                     echo $svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
                                } ?>
                            </div>

                        </div>

                        <!-- 商品end -->

                     
                    </div>
                    <div class="am-u-md-2 am-u-lg-2 am-hide-sm-only">&nbsp;</div>
                    <!-- 数量 -->
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-text-center editnum">
                        <div class="am-form-group cart_price" style="min-width:120px;overflow:hidden;margin-bottom:0">
                            <a class="am-u-lg-4 am-u-md-2 am-u-sm-4 am-text-center addnum" href="javascript:void(0);" onclick="cart_product_num(this,'minus');packaged_minus(this)"><i class="am-icon-minus"></i>&nbsp;</a>
                            <input id="update_num<?php echo $k?>" class="am-fl numinput" style="margin-right:0;padding-left:2px;padding-right:2px;" type="text" name="product_num[<?php echo $k?>]" value="<?php echo $v['quantity']?>" onchange="checkChanges($(this),'<?php echo $i?>');packaged_proudct(this)" onkeydown="if (event.keyCode==13){ javascript:checkChanges($(this),'<?php echo $i?>');return false;}" />
                            <a class="am-u-lg-4 am-u-md-2 am-u-sm-4 am-text-center reducenum"  href="javascript:void(0);" onclick="cart_product_num(this,'plus');packaged_plus(this)"><i class="am-icon-plus"></i>&nbsp;</a>
                        </div>
                        <div class="am-cf"></div>
                        <a id="product_num_<?php echo $i;?>_update" class="am-hide" href="javascript:void(0);" onclick="document.forms['update_num<?php echo $k?>'].submit();"><?php echo $ld['modify']?></a>
                        <div class="am-g am-text-center"><a style="margin-top:-10px;color:#fff" class="am-btn am-btn-default am-btn-xs am-btn-danger" href="javascript:void(0);" onclick="if(confirm('<?php echo $ld['remove_product'].'?'; ?>')){window.location.href='<?php echo $server_host.($html->url("/carts/act_remove/product/".$k));?>';}"><span class="am-icon-trash-o"></span><?php echo $ld['delete'] ?></a></div>
                    </div>
                    


                    <!-- md+价格 -->
                    <div class="am-u-lg-2 am-u-md-2 am-hide-sm-only am-text-center shop_price" style="white-space:nowrap">
                        <!-- 租赁 -->
                        <?php if($v['Product']['is_lease']==1){
                            echo $svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
                        }else if(isset($v['is_promotion'])&&$v['is_promotion']==1){
                            echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
                            if($v['Product']['market_price']!=$v['Product']['promotion_price']){
                                ?>
                                <del ><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                <?php
                            }
                        }else{
                            echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
                            if($v['Product']['market_price']!=$v['Product']['shop_price']){
                                ?>
                                <del style="color:gray;"><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                <?php
                            }
                        }?>
                    </div>
                </div>

                <!-- 定制商品 -->
                <?php if(isset($v['CartProductValue'])&&!empty($v['CartProductValue'])){ ?>
                    <div class="am-g am-margin-0">
                        <div class="am-u-lg-9 am-u-md-9 am-u-md-9" style="padding-left:2.1rem">
                            <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 cart_product_value">
                            <p style="border-bottom:1px solid #ccc;margin-bottom:0.5rem;line-height:25px;">定制属性:</p>
                            <ul class="am-avg-sm-1 am-avg-md-1 am-avg-lg-2" id="customized_carts">
                                <?php foreach($v['CartProductValue'] as $cpk=>$cpv){ ?>
                            <li style="padding:0 10px;height:25px;line-height:25px;">
                                    <div style="clear:both;">
                            <span class="am-fl">
                            <span class="name"><?php echo $all_attr_list[$cpv['attribute_id']]; ?>:</span>&nbsp;<?php echo $cpv['attribute_value'] ?>
                            </span>
                            <span class="am-fr">
                            <?php if(isset($cpv['attr_price'])&&!empty($cpv['attr_price'])&&intval($cpv['attr_price'])>0){echo $svshow->price_format($cpv['attr_price'],$configs['price_format']);}$total_attr_price+=$cpv['attr_price']; ?>
                            </span>
                                </div>
                                </li>
                                <?php  } ?>
                            </ul>
                                <div class='cart_product_note' style="clear:both;">
                                    <div><span><?php echo $ld['remark'] ?>:</span>&nbsp;&nbsp;<?php echo $v['note'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php } ?>
                <?php if($total_attr_price){?>
                <div class="am-g am-margin-0">
                    <!--     <div class="am-u-lg-4 am-u-md-4 am-u-sm-1">&nbsp;</div> -->
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-left:2.1rem">定制金额:</div>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="color:#f60;font-weight:600;text-align:right">
                            <?php echo $svshow->price_format($total_attr_price,$configs['price_format']); ?>
                        </div>
                </div>
                <?php } ?>
                <!-- 定制商品 -->
                <!-- 套装商品 -->
                        <?php if (isset($v['PackageProduct'])&&sizeof($v['PackageProduct'])>0) {
                        			$PackageProduct_total=isset($v['PackageProduct_total'])?$v['PackageProduct_total']:0;
                  			$PackageProduct_proportion=$v['Product']['shop_price']/$PackageProduct_total;
                  			$PackageProduct_sutotal=0;
                    			foreach ($v['PackageProduct'] as $kk => $vv) {
                    					if($kk<sizeof($v['PackageProduct'])-1){
	                    					$PackageProduct_price=$PackageProduct_proportion*$vv['Product']['shop_price'];
	                    					$PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
	                    					$PackageProduct_sutotal+=$PackageProduct_price;
                    					}else{
                    						$PackageProduct_price=$v['Product']['shop_price']-$PackageProduct_sutotal;
                    					}
                        ?>
                    <div class="am-u-sm-12 am-g-collapse am-margin-top-xs">
                        <div class="am-u-sm-1">&nbsp;</div>
                        <div class="am-u-sm-5">
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-right">
                            <img style="width:100%;height:100%;max-width:90px;" src="<?php echo !empty($vv['Product']['img_thumb'])?$vv['Product']['img_thumb']:'/theme/default/images/default.png' ?>" alt="">
                        </div>
                        <div class="am-u-sm-8" style="padding-left:20px;">
                            <a class="am-text-truncate" href="<?php echo $html->url('/products/'.$vv['Product']['id']) ?>" style="font-size:15px;color:#666;display:block;"><?php echo $vv['PackageProduct']['package_product_name'] ?></a>
                            <span class="am-text-truncate" style="display:block;font-size:15px;">货号:&nbsp;<?php echo $vv['Product']['id'] ?></span>
                        </div>
                        </div>
                        <div class="am-u-sm-2">&nbsp;</div>
                         <div class="am-u-sm-2 am-text-center carts_packaged">
                            <span><?php echo $vv['PackageProduct']['package_product_qty'] ?></span>
                        </div>
                        <div class="am-u-sm-2" style="font-weight:600;text-align:center"> <?php echo $svshow->price_format($PackageProduct_price,$configs['price_format']); ?></div>
                       
                        </div>
                        <?php }} ?>
                <!-- 套装商品end -->
                </div>


                <?php
                echo $form->end();
            }
            ?>
        </div>
<!-- 购物车商品结束 -->
<!-- 租赁单独显示 -->
        <div class="am-g cart_table am-carts-lease">
            <?php if (in_array('1',$product_is_lease)) {?>
            <div class="am-g am-hide-sm-only">
                <div class="am-u-lg-1 am-u-md-1 am-text-center"><input type="checkbox" name="all_check" id="am-lease-check"></div>
                <div class="am-u-lg-5 am-u-md-5 am-text-left"  style="font-weight:600;color:#dd514c;"><?php echo $ld['lease_goods'] ?></div>
                <div class="am-u-lg-2 am-u-md-2 am-text-center"><?php echo $ld['lease_days'] ?></div>
                <div class="am-u-lg-2 am-u-md-2 am-text-center"><?php echo $ld['quantity']?></div>
                <div class="am-u-lg-2 am-u-md-2 am-text-center"><?php echo $ld['lease_price'] ?></div>
            </div>
            <?php } ?>
            <?php
            $sum_point = 0;$sum_discount=0; $i=0;
            foreach($svcart['products'] as $k=>$v){
                $i++;$sum_point+=$v['Product']['point']*$v['quantity'];$total_attr_price=0;
                if(!empty($v['Product']['market_price'])&&$v['Product']['market_price']>$v['Product']['shop_price']){
                    $sum_discount+=(($v['Product']['market_price']-$v['Product']['shop_price'])*$v['quantity']);
                }
                echo $form->create('carts',array('action'=>'update_num','name'=>'update_num'.$k,'type'=>'POST','class'=>'am-form' ));
                ?>
                <div class="am-g <?php if ($v['Product']['is_lease'] == 0) {
                   echo "am-hide am_remove";
                } ?>" style="border:1px solid #f5f5f5;padding:10px 0px;">
                
                <div class="am-u-sm-12">

                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding:0"><input type="checkbox" name="subBox" value="<?php echo $k;?>"/></div>
                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-8 am-text-left productname" style="padding:0">
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 cart_pro_img am-text-left" style="padding:0">
                            <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>!empty($v['Product']['img_thumb'])?$v['Product']['img_thumb']:'/theme/default/images/default.png','name'=>$v['ProductI18n']['name']));?>
                        </div>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 goodsname">
                            <?php if(!empty($v['Product']['file_url'])){?>
                                <a href='<?php echo $server_host.($html->url("/products/view/".$k));?>' ><?php echo $v['ProductI18n']['name'];?></a>
                            <?php }else{ ?>
                                <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name']));}?>
                            <p class="name" style="margin-top:0px;margin-bottom:0px;white-space:nowrap; "><?php echo $ld['sku'];?>:<span><?php echo $v['Product']['code'] ?></span></p>

                            <?php if(isset($v['attributes'])){ ?>
                                <div class="name" style="white-space:nowrap; "><?php echo $ld['attribute'] ?>:<?php $arr1=explode("<br />",$v['attributes']);//pr($arr1);
                                    foreach($arr1 as $attr_vv){
                                        if($attr_vv==""){continue;}
                                        $attr_str=explode(":",$attr_vv);
                                        echo "<span style='margin-left:5px;'>".(empty($attr_str[1])?'':$attr_str[1])."</span>";
                                    }
                                    ?></div>
                            <?php }?>
                            <!-- sm价格 -->
                            <div class="am-show-sm-only  shop_price" style="white-space:nowrap; ">
                                <?php 
                                if($v['Product']['is_lease'] !== 1){
                                if(isset($v['is_promotion'])&&$v['is_promotion']==1){
                                    echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
                                    if($v['Product']['market_price']!=$v['Product']['promotion_price']){
                                        ?>
                                        <del ><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                        <?php
                                    }
                                }else{
                                    echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
                                    if($v['Product']['market_price']!=$v['Product']['shop_price']){
                                        ?>
                                        <del style="color:gray;"><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                        <?php
                                    }
                                }}?>
                                <?php if ($v['Product']['is_lease'] == 1) {
                                     echo $svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
                                } ?>
                            </div>

                        </div>
                    </div>
                    <!-- 数量 -->
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">
                        <?php if ($v['Product']['is_lease']==1) {
                            echo $v['Product']['lease_day'];
                         } ?>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-text-center editnum">
                        <div class="am-form-group cart_price" style="min-width:120px;overflow:hidden;margin-bottom:0">
                            <a class="am-u-lg-4 am-u-md-2 am-u-sm-4 am-text-center addnum" href="javascript:void(0);" onclick="cart_product_num(this,'minus');packaged_minus(this)"><i class="am-icon-minus"></i>&nbsp;</a>
                            <input id="update_num<?php echo $k?>" class="am-fl numinput" style="margin-right:0;padding-left:2px;padding-right:2px;" type="text" name="product_num[<?php echo $k?>]" value="<?php echo $v['quantity']?>" onchange="checkChanges($(this),'<?php echo $i?>');packaged_proudct(this)" onkeydown="if (event.keyCode==13){ javascript:checkChanges($(this),'<?php echo $i?>');return false;}" />
                            <a class="am-u-lg-4 am-u-md-2 am-u-sm-4 am-text-center reducenum"  href="javascript:void(0);" onclick="cart_product_num(this,'plus');packaged_plus(this)"><i class="am-icon-plus"></i>&nbsp;</a>
                        </div>
                        <div class="am-cf"></div>
                        <a id="product_num_<?php echo $i;?>_update" class="am-hide" href="javascript:void(0);" onclick="document.forms['update_num<?php echo $k?>'].submit();"><?php echo $ld['modify']?></a>
                        <div class="am-g am-text-center"><a style="margin-top:-10px;color:#fff" class="am-btn am-btn-default am-btn-xs am-btn-danger" href="javascript:void(0);" onclick="if(confirm('<?php echo $ld['remove_product'].'?'; ?>')){window.location.href='<?php echo $server_host.($html->url("/carts/act_remove/product/".$k));?>';}"><span class="am-icon-trash-o"></span><?php echo $ld['delete'] ?></a></div>
                    </div>
                    <!-- md+价格 -->
                    <div class="am-u-lg-2 am-u-md-2 am-hide-sm-only am-text-center shop_price" style="white-space:nowrap">
                        <!-- 租赁 -->
                        <?php if($v['Product']['is_lease']==1){
                            echo $svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
                        }else if(isset($v['is_promotion'])&&$v['is_promotion']==1){
                            echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
                            if($v['Product']['market_price']!=$v['Product']['promotion_price']){
                                ?>
                                <del ><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                <?php
                            }
                        }else{
                            echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
                            if($v['Product']['market_price']!=$v['Product']['shop_price']){
                                ?>
                                <del style="color:gray;"><?php echo $svshow->price_format($v['Product']['market_price'],$configs['price_format']); ?></del>
                                <?php
                            }
                        }?>
                    </div>
                </div>
                <!-- 定制商品 -->
                <?php if(isset($v['CartProductValue'])&&!empty($v['CartProductValue'])){ ?>
                    <div class="am-g">
                        <div class="am-u-lg-9 am-u-md-9 am-u-md-9" style="padding-left:2.1rem">
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-12 cart_product_value">
                            <p style="border-bottom:1px solid #ccc;margin-bottom:0.5rem;">定制属性:</p>
                            <ul class="am-avg-sm-1 am-avg-md-1 am-avg-lg-2" id="lease_customized_carts">
                                <?php foreach($v['CartProductValue'] as $cpk=>$cpv){ ?>
                            <li style="padding:0 10px;height:40px;">
                                    <div style="clear:both;">
                            <span class="am-fl">
                            <span class="name"><?php echo $all_attr_list[$cpv['attribute_id']]; ?>:</span>&nbsp;<?php echo $cpv['attribute_value'] ?>
                            </span>
                            <span class="am-fr">
                            <?php if(isset($cpv['attr_price'])&&!empty($cpv['attr_price'])&&intval($cpv['attr_price'])>0){echo $svshow->price_format($cpv['attr_price'],$configs['price_format']);}$total_attr_price+=$cpv['attr_price']; ?>
                            </span>
                                </div>
                                </li>
                                <?php  } ?>
                            </ul>
                                <div class='cart_product_note' style="clear:both;">
                                    <div><span><?php echo $ld['remark'] ?>:</span>&nbsp;&nbsp;<?php echo $v['note'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if($total_attr_price){?>
                    <div class="am-g">
                    <!--     <div class="am-u-lg-4 am-u-md-4 am-u-sm-1">&nbsp;</div> -->
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-6" style="padding-left:2.1rem">定制金额:</div>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="color:#f60;font-weight:600">
                            <?php echo $svshow->price_format($total_attr_price,$configs['price_format']); ?>
                        </div>
                    </div>
                <?php }?>
                <!-- 定制商品 -->
            
              <!-- 套装商品 -->
                        <?php if (isset($v['PackageProduct'])&&sizeof($v['PackageProduct'])>0) {
						$PackageProduct_total=isset($v['PackageProduct_total'])?$v['PackageProduct_total']:0;
						$PackageProduct_proportion=$v['Product']['shop_price']/$PackageProduct_total;
						$PackageProduct_sutotal=0;
                    			foreach ($v['PackageProduct'] as $kk => $vv) {
                    					if($kk<sizeof($v['PackageProduct'])-1){
	                    					$PackageProduct_price=$PackageProduct_proportion*$vv['Product']['shop_price'];
	                    					$PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
	                    					$PackageProduct_sutotal+=$PackageProduct_price;
                    					}else{
                    						$PackageProduct_price=$v['Product']['shop_price']-$PackageProduct_sutotal;
                    					} ?>
                       <div class="am-u-sm-12 am-g-collapse am-margin-top-xs">
                        <div class="am-u-sm-1">&nbsp;</div>
                        <div class="am-u-sm-5">
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-right">
                            <img style="width:100%;height:100%;max-width:80px;" src="<?php echo !empty($vv['Product']['img_thumb'])?$vv['Product']['img_thumb']:'/theme/default/images/default.png' ?>" alt="">
                        </div>
                        <div class="am-u-sm-8" style="padding-left:20px;">
                            <a href="<?php echo $html->url('/products/'.$vv['Product']['id']) ?>" style="font-size:15px;color:#666;display:block"><?php echo $vv['PackageProduct']['package_product_name'] ?></a>
                            <span style="display:block;font-size:15px;">货号:<?php echo $vv['Product']['id'] ?></span>
                        </div>
                        </div>
                        <div class="am-u-sm-2">&nbsp;</div>
                         <div class="am-u-sm-2 am-text-center carts_packaged">
                            <span><?php echo $vv['PackageProduct']['package_product_qty'] ?></span>
                        </div>
                        <div class="am-u-sm-2" style="font-weight:600;text-align:center"> <?php echo $svshow->price_format($PackageProduct_price,$configs['price_format']); ?></div>
                        </div>
                        <?php }} ?>
                <!-- 套装商品end -->


            </div>


                <?php
                echo $form->end();
            }
            ?>
        </div>
<!-- 租赁结束 -->
        <div class="am-g">
            <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-padding-0 am-text-center">
            <input type="checkbox" id="checkAll" class="am-checkbox-inline"/>
            </div>
             <a class="am-btn am-btn-xs am-btn-danger" style="font-size:1rem" href="javascript:void(0);" onclick="javascript:formsubmit();"><span class="am-icon-trash-o"></span><?php echo $ld['batch_delete']?></a>
                    <span id="sb" style="display:inline-block">
                    <?php echo $ld['selected_goods'] ?>
                    </span>
            </div>
            <?php echo $form->create('carts',array('action'=>'','class'=>'am-form am-form-horizontal','name'=>'cart_info','id'=>'cart_info','type'=>'POST'));?>
            <?php if (in_array('0',$product_is_lease)) {?>
            <?php if((isset($_SESSION['svcart']['cart_info']['discount_price'])&&$_SESSION['svcart']['cart_info']['discount_price']>0)||(isset($_SESSION['svcart']['cart_info']['sum_subtotal'])&&$_SESSION['svcart']['cart_info']['sum_subtotal']>0)){?>
                <div class="am-form-group am-margin-bottom-sm" id="buy_item">
                    <div class="am-padding-top-0 am-u-lg-10 am-u-md-9 am-u-sm-7 am-form-label am-text-right"><?php echo $ld['total_purchases'] ?>:</div>
                    <div class="am-padding-top-0 am-u-lg-2 am-u-md-3 am-u-sm-5 am-form-label am-text-right" id="cart_price"><?php echo $svshow->price_format($_SESSION['svcart']['cart_info']['sum_subtotal'],$configs['price_format']);?></div>
                </div>
            <?php }} ?>
            <?php if (in_array('1',$product_is_lease)) {?>
            <?php if((isset($_SESSION['svcart']['cart_info']['discount_price'])&&$_SESSION['svcart']['cart_info']['discount_price']>0)||(isset($_SESSION['svcart']['cart_info']['lease_subtotal'])&&$_SESSION['svcart']['cart_info']['lease_subtotal']>0)){?>
                <div class="am-form-group am-margin-bottom-sm" id="lease_item">
                    <div class="am-padding-top-0 am-u-lg-10 am-u-md-9 am-u-sm-7 am-form-label am-text-right"><?php echo $ld['lease_item_subtotals'] ?>:</div>
                    <div class="am-padding-top-0 am-u-lg-2 am-u-md-3 am-u-sm-5 am-form-label am-text-right" id="cart_price2"><?php echo $svshow->price_format($_SESSION['svcart']['cart_info']['lease_subtotal'],$configs['price_format']);?></div>
                </div>
            <?php }} ?>
            <div class="am-form-group am-margin-bottom-sm">
                    <div class="am-padding-top-0 am-u-lg-8 am-u-md-7 am-u-sm-4 am-form-label">&nbsp;</div>
                    <div class="am-padding-top-0 am-u-lg-4 am-u-md-5 am-u-sm-8 am-form-label am-text-right" id="cart_prompt"></div>
            </div>
            <div class="am-form-group">
                <div class="am-u-lg-3 am-u-md-12 am-u-sm-12 am-text-right am-fr">
                    <a href="javascript:to_checkout();" class="am-u-sm-12 am-btn am-btn-warning am-btn-block-two am-btn-balance"><span  style="display:inline-block; width:110px;color:white;text-align: center;"><?php echo $ld['settlement']?></span></a>
                </div>
            </div>
            <?php echo $form->end();?>
            <hr/>
        </div>
    <?php }else{ ?>
        <div class="errort"><?php echo $ld['empty_product']?></div>
    <?php } ?>
    <div class="am-cf"></div>
    <?php /*猜你喜欢*/ if (!empty($this->data['relation_products'])) { ?>
        <div class="am-g am-container">
            <div class="am-panel am-panel-default" style="margin-top:10px;">
                <div class="am-panel-hd my-head">您也许也喜欢<?php //echo $ld['huess_you_like']?></div>
                <div  class="am-panel-bd">
                    <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
                        <?php foreach($this->data['relation_products'] as $k=>$v){?>
                            <li>
                                <div class="am-gallery-item">
                                    <!--<i class="am-icon-mobile am-icon-sm"></i>-->
                                    <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
                                        <span class="like_icon am-gallery-like" style="">
                  <?php echo $html->image('/theme/default/img/like_icon.png',array('id'=>$v['Product']['id'],'style'=>'width:15px;height:15px;'));  ?>
                                            <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
                  <?php echo $v['Product']['like_stat'];?>
                </span>
              </span>
                                    <?php } ?>
                                    <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
                                    <h3 class="am-gallery-title">
                                        <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
                                    </h3>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['svcart']['promotion'])&&!empty($_SESSION['svcart']['promotion'])){?>
        <div class="carts_h1 ai"><?php echo $ld['activities_offer_you']?></div>
        <div class="marketing">
            <?php foreach($_SESSION['svcart']['promotion'] as $k=>$v){?>
                <div class="marketitems">
                    <font >* <?php echo $v['title']?></font> <a href="<?php echo $html->url('/promotions/'.$v['id']); ?>"><?php echo $ld['activities_offer_detail']?></a>
                </div>
                <?php if($v['type']==2 && !empty($v['products'])){?>
                    <div>
                        <table cellpadding="0" cellspacing="0">
                            <tr class="thead">
                                <th><?php echo $ld['product_name']?></th>
                                <th><?php echo $ld['single-product_integration']?></th>
                                <th><?php echo $ld['market_price']?></th>
                                <th><?php echo $ld['activities_price']?></th>
                                <th><?php echo $ld['quantity']?></th>
                                <th><?php echo $ld['operation']?></th>
                            </tr>
                            <?php foreach($v['products'] as $i=>$p){?>
                            <tr onmouseout="this.className='table_row table_cell'" onmouseover="this.className='table_over table_row table_cell'" class="table_row table_cell">
                                <!--商品名称-->
                                <td class="carts_sel_list_name"><input class="promotions_checkbox" type="checkbox" <?php if(!empty($_SESSION['svcart']['Product_by_Promotion'][$v['Promotion']['id']][$i])){?>checked="checked"<?php }?> value="" onclick="promotion_product_change(this,'<?php echo $v['Promotion']['id']?>','<?php echo $i?>')" />&nbsp;<?php echo $html->link($p['ProductI18n']['name'],$svshow->sku_product_link($i,$p['ProductI18n']['name'],$p['Product']['code'],$configs['use_sku']),array("target"=>"_blank"),false,false);?></td>
                                <!--积分-->
                                <td style="color: rgb(153, 153, 153);" class="carts_sel_list_jifen">-</td>
                                <!--市场价-->
                                <td class="carts_sel_list_shijia"><span class="scx"><?php echo $svshow->price_format($p['Product']['market_price'],$configs['price_format']);?></span></td>
                                <!--本店价-->
                                <td class="carts_sel_list_benjia"><?php echo $svshow->price_format($p['Product']['now_fee'],$configs['price_format']);?></td>
                                <!--数量-->
                                <td class="carts_sel_list_num"><input type="text" disabled="disabled" style="width: 34px;" value="1" ></td>
                                <td><a href="javascript:favorite(<?php echo $i;?>)" style="color:#D8D839;"><?php echo $ld['favorites']?></a> </td>
                            </tr>
                        </table>
                        <?php }?>
                    </div>
                <?php }?>
            <?php }?></div>
    <?php }?>
    <?php if(isset($this->data['advertisement_list']['checkout_bottom']) && $this->data['advertisement_list']['checkout_bottom'] != ""){?>
        <div class='fullscreen shopping_trolley'><?php echo $this->element("advertisement_list",array("ad_show_code"=>'checkout_bottom')) ?></div>
    <?php }?>
    <?php if(isset($topics)&&!empty($topics)){?>
        <div class="privilege">
            <h3 class="cl"><?php echo $ld['special_districts']?></h3>
            <?php foreach($topics as $k=>$v) {?>
                <div><?php echo $html->link($v['TopicI18n']['title'],'/topics/'.$v['Topic']['id']);echo "<br/><hr/>";?></div>
            <?php }?>
        </div>
    <?php }?>
    <div class="info"><em>*&nbsp;</em><?php echo $ld['shopping_cart_information']?><br />
        <em>*&nbsp;</em><?php echo $ld['satisfied_with']?><?php echo $svshow->link($ld['leave_suggestions']."&gt;&gt;","/contacts");?></div>
</div>
<script>
    //购物车选中显示商品数及背景色
    $(function(){
        $(".am_remove.am-hide").parent().remove();
        $(".am-carts-buy #am-buy-check").prop('checked',true);   
        $(".am-carts-lease #am-lease-check").prop('checked',true);   
        var csb=$("input[name='subBox']:checked").length;
        $("#sb").html("<?php echo $ld['selected_goods'] ?> "+"<b>"+csb+"</b>");
        // $("input[type='checkbox']").change(function(){
        //     if($("input[type='checkbox']").is(':checked')){
        //         $(".checkx").removeClass("am-disabled");
        //     }else{
        //         $(".checkx").addClass("am-disabled");
        //     }
        //     if($("#checkAll").is(':checked')) {
        //         var dsb=$("input[type='checkbox']:checked").length-1;
        //         $("#sb").html("已选商品 "+"<b>"+dsb+"</b>");

        //     }else{
        //         var xsb=$("input[type='checkbox']:checked").length;
        //         $("#sb").html("已选商品 "+"<b>"+xsb+"</b>");
        //     }
        // })
        // if($("input[name='subBox']").is(':checked')) {
        //     if($("#checkAll").is(':checked')){
        //         $(".cart_backg").toggleClass("cart_back");
        //     }
        //     $("input[name='subBox']:checked").click(function() {
        //         $(this).parent().parent().toggleClass("cart_back")
        //     })
        // }
        // $("#checkAll").click(function() {
        //     if ($("#checkAll").is(':checked')) {

        //         $("input[name='subBox']:checked").parent().parent().addClass("cart_back")

        //     }else{
        //         $("input[name='subBox']").not("input:checked").parent().parent().removeClass("cart_back")
        //     }
        // })
    })
</script>
<script type="text/javascript">
    $(function(){
        $(".cart_table input[type=checkbox]").change(function(){
            if($(".cart_table input[type=checkbox]").is(":checked")){
                $(".am-btn-balance").removeClass("am-disabled");
            }else{
                $(".am-btn-balance").addClass("am-disabled");
            }
        })
        var buy_length = $(".am-carts-buy input[name='subBox']:checked").length;
        var lease_length = $(".am-carts-lease input[name='subBox']:checked").length;
        if (buy_length > 0 && lease_length > 0) {
        $("#cart_prompt").html("<span style='color:red'>*</span>购买商品和租赁商品无法同时结算"); 
        }else{
        $("#cart_prompt").html("");     
        }
    })


//已选商品数量显示
$("input[name='subBox']").on('change',function () {
var box_num = $("input[name='subBox']:checked").length;
$("#sb b").html(box_num);
});
$("input[name='all_check']").on('change',function () {
var box_num = $("input[name='subBox']:checked").length;
$("#sb b").html(box_num);    
})
$("#checkAll").on('change',function () {
var box_num = $("input[name='subBox']:checked").length;
$("#sb b").html(box_num);
if (box_num == 0) {
$("input[name='all_check']").prop('checked',false);  
}else{
$("input[name='all_check']").prop('checked',true);  
}
});


//普通商品全选
    
    $(".am-carts-buy input[name='subBox']").on('click',function () {
    var buy_sub = $(".am-carts-buy input[name='subBox']");
    var buy_sub_check = $(".am-carts-buy input[name='subBox']:checked");
    if (buy_sub.length  ==  buy_sub_check.length ) {
     $(".am-carts-buy #am-buy-check").prop('checked',true);   
    }else{
     $(".am-carts-buy #am-buy-check").prop('checked',false);     
    }
    })
    $(".am-carts-buy #am-buy-check").click(function () {
        if ($(".am-carts-buy #am-buy-check").prop('checked') == true) {
          $(".am-carts-buy input[name='subBox']").each(function (index) {
           $(".am-carts-buy input[name='subBox']")[index].checked = true;
        })
        if ($(".am-carts-lease #am-lease-check").prop('checked') == true ) {
        $("#checkAll").prop('checked',true);    
        }
        }else{
          $(".am-carts-buy input[name='subBox']").each(function (index) {
           $(".am-carts-buy input[name='subBox']")[index].checked = false;
        })
        $("#checkAll").prop('checked',false);
        } 
    })
//租赁商品全选

    $(".am-carts-lease input[name='subBox']").on('click',function () {
    var lease_sub = $(".am-carts-lease input[name='subBox']");
    var lease_sub_check = $(".am-carts-lease input[name='subBox']:checked");
    if (lease_sub.length  ==  lease_sub_check.length ) {
     $(".am-carts-lease #am-lease-check").prop('checked',true);   
    }else{
     $(".am-carts-lease #am-lease-check").prop('checked',false);     
    }
    })
    $(".am-carts-lease #am-lease-check").click(function () {
        if ($(".am-carts-lease #am-lease-check").prop('checked') == true) {
          $(".am-carts-lease input[name='subBox']").each(function (index) {
           $(".am-carts-lease input[name='subBox']")[index].checked = true;
        })
        if ($(".am-carts-buy #am-buy-check").prop('checked') == true ) {
        $("#checkAll").prop('checked',true);    
        }
        }else{
          $(".am-carts-lease input[name='subBox']").each(function (index) {
           $(".am-carts-lease input[name='subBox']")[index].checked = false;
        })
        $("#checkAll").prop('checked',false);
        } 
    })


    function to_checkout(){
        <?php if(isset($_SESSION['User']['User']['id'])){ ?>
        var sel_pro_count=0;//选中商品数
        $("input[name='subBox']").each(function (index){
            if ($(this).prop("checked")){
                sel_pro_count++;
            }
        });
        if($("#checkAll").is(":checked") == true){
            <?php $_SESSION['checkout']=$_SESSION['svcart'];?>
            document.forms['cart_info'].action = "<?php echo $html->url('/carts/checkout/');?>";
            document.forms['cart_info'].submit();
        }else if(sel_pro_count>0){
            document.forms['cart_info'].action = "<?php echo $html->url('/carts/checkout/');?>";
            document.forms['cart_info'].submit();
        }
        <?php }else{ ?>
        ajax_login_show();
        <?php }?>
    }
</script>
<?php if(!empty($error_arr)){$str = '';?>
    <script>
        <?php foreach($error_arr as $v){
            $str .= $v['name'].'\n'.$v['message'].'\n'.$v['message2'].'\n';
        }?>
        alert('<?php echo $str?>');
    </script>
<?php }?>
<script>
    function promotion_product_change(obj,promotion_id,product_id){
        var sUrl = <?php echo "'".$webroot."carts/promotion_product_change/'";?>;
        if(obj.checked){
            type="add";
        }else{
            type="del";
        }
        var postData = {promotion_id:promotion_id,product_id:product_id,type:type};
        $.post(
            sUrl, //url
            postData,//data
            promotion_product_change_Success,
            "json"//type
        );
    }
    var promotion_product_change_Success=function(result){
        if(result.total){
            document.getElementById('cart_price').innerHTML=result.le_total;
            document.getElementById('cart_price2').innerHTML=result.total;
            document.getElementById('sum_discount').innerHTML=result.sum_discount;
        }
    }
</script>
<script type="text/javascript">
    var documentHeight = 0;
    var topPadding =115;
    function ajax_cart(){
        $.ajax({ url: web_base+"/carts/index/?ajax=1",dataType:"json", context: $("#shoppingcart a"), success: function(data){
            //alert(data.sum_quantity);
            $("#shoppingcart a").html("购物车("+data.sum_quantity+")");
        }});
    }
    $(document).ready(function() {
        //评论跳转（判断登录）
        $(".p_comment").click(function(){
            //alert($(this).attr("id"));
            var id=$(this).attr("id");
            //id=id.replace('comment','');
            <?php if(!isset($_SESSION['User']['User']['id'])){?>
            //未登录
            $(".denglu").click();
            <?php }else{ ?>
            id=id.replace('comment','');
            // window.location.href="<?php echo $html->url('/products/'); ?>"+id;
            window.location.href=web_base+"/products/"+id;
            <?php }?>
        });
        //ajax_cart添加购物车
        $(".ajax_cart").click(function(){
            //alert($(this).attr("id"));
            <?php if(!isset($_SESSION['User']['User']['id'])){?>
            //未登录
            $(".denglu").click();
            <?php }else{ ?>
            var id=$(this).attr("id");//产品id
            var type="product";//类型：产品
            var quantity=1;//数量：1
            var flag=false;
            $.ajax({ url:web_base+ "/carts/buy_now/?ajax=1",
                type:"POST",
                dataType:"json",
                data: { 'type': type, 'id':id,'quantity':quantity },
                success: function(data){
                    flag=true;
                    if(flag){
                        alert("购物车添加成功！");
                        window.location.href=location.href;
                    }
                }
            });
            <?php }?>
        });

//    var speed = 10000;
//    $(".productslist .current").masonry({
//      singleMode: true,
//        columnWidth:200,
//        itemSelector: '.item',
//        animate: false,
//        animationOptions: {
//            duration: speed,
//            queue: false}
//  
//    });
        $(".item_box").mouseover(function(){
            var id=$(this).attr("id");
            sid=id.replace("product_","suspension");
            $("#"+sid).css("display","block");
            $(this).css("cursor","pointer");
        });
        $(".item_box").mouseout(function(){
            var id=$(this).attr("id");
            sid=id.replace("product_","suspension");
            $("#"+sid).css("display","none");
        });
    });
    var selectedIds = [];
    $(document).ready(function() {
        $('input[name="subBox"]').prop("checked","checked");
        updateMasterCheckbox();
        $("input[name='subBox']").each(function (index) {
            var checked = jQuery.inArray($(this).val(), selectedIds);
            if (checked == -1) {
                selectedIds.push($(this).val());
            }
        });
        //普通商品全选
        $("#am-buy-check").click(function  () {
        selectedIds = [];
        $("input[name='subBox']").each(function (index) {
           if($(this).prop('checked')){
            var checked = $.inArray($(this).val(), selectedIds);
            if (checked == -1) {
                selectedIds.push($(this).val());
            };
            } 
        })   
        var postData = {selectedIds: selectedIds.join(",")};   
        console.log(postData);
        changeCart(postData);     
        })
        //租赁商品全选
        $("#am-lease-check").click(function  () {
        selectedIds = [];
        $("input[name='subBox']").each(function (index) {
           if($(this).prop('checked')){
            var checked = $.inArray($(this).val(), selectedIds);
            if (checked == -1) {
                selectedIds.push($(this).val());
            };
            } 
        })   
        var postData = {selectedIds: selectedIds.join(",")};   
        console.log(postData);
        changeCart(postData);     
        })
        //购物车全选，反选
        $("#checkAll").click(function() {
            selectedIds = [];
            $("input[name='subBox']").prop('checked', $(this).prop("checked"));
            $("input[name='subBox']").each(function (index){
                if ($(this).prop("checked")) {
                    var checked = jQuery.inArray($(this).val(), selectedIds);
                    if (checked == -1) {
                        selectedIds.push($(this).val());
                    }
                }
            });
            var postData = {
                selectedIds: selectedIds.join(",")
            };
            changeCart(postData);
        });

        var $subBox = $("input[name='subBox']");
        $subBox.on('change', function(){
            var $check = $(this);
            if ($check.is(":checked") == true) {
                var checked = jQuery.inArray($check.val(), selectedIds);
                if (checked == -1) {
                    selectedIds.push($check.val());
                }
            }
            else {
                var checked = jQuery.inArray($check.val(), selectedIds);
                if (checked > -1) {
                    selectedIds = $.grep(selectedIds, function (item, index) {
                        return item != $check.val();
                    });
                }
            }
            var postData = {
                selectedIds: selectedIds.join(",")
            };
            $(this).prop('checked', $(this).is(':checked'))
            changeCart(postData);
            updateMasterCheckbox();
        });
        var width1=document.documentElement.clientWidth;
        //alert(screen.availWidth);
        if(screen.availWidth<997){
            //  $("#your_order").css("right",""+((width1-980)/2+2)+"px");
        }
        if(screen.availWidth==1280){
            //$("#your_order").css("left","875px");
            //alert(screen.availWidth);
            // $("#your_order").css("right",""+((width1-980)/2+2)+"px");
        }
        if(screen.availWidth==1440){
            //alert(screen.availWidth+1);
            //$("#your_order").css("left","955px");
            //  $("#your_order").css("right",""+((width1-980)/2+2)+"px");
        }
        if(screen.availWidth==1366){
            //$("#your_order").css("left","915px");
            //$("#your_order").css("right",""+((width1-980)/2+2)+"px");
        }
        if(screen.availWidth==1920){
            //$("#your_order").css("left","1315px");
            //$("#your_order").css("right",""+((width1-980)/2+2)+"px");
        }
        $(window).resize(function() {
            //alert(document.documentElement.clientWidth);
            var width=document.documentElement.clientWidth;
            if(width<997){
                $("#your_order").css("left","777px").css("right","0px");
            }
            else{
                $("#your_order").css("right",""+((width-980)/2+2)+"px").css("left","");
            }
        });
        //绿色透明悬浮层相关js   
        $(".picture").mouseover(function(){
            var id=$(this).attr("id");
            id=id.replace("picture","suspension");
            $("#"+id).css("display","block");
        });
        $(".picture").mouseout(function(){
            var id=$(this).attr("id");
            id=id.replace("picture","suspension");
            $("#"+id).css("display","none");
        });
        $(".suspension").mouseover(function(){
            var id=$(this).attr("id");
            id=id.replace("suspension","name");
            $(this).css("display","block");
            $(this).css("cursor","pointer");

        });
        $(".suspension").mouseout(function(){
            var id=$(this).attr("id");
            id=id.replace("suspension","name");
            $(this).css("display","none");
            $(this).css("cursor","pointer");
        });
    });

    function checkbox(){
        var str=document.getElementsByName("subBox");
        var leng=str.length;
        var chestr="";
        for(i=0;i<leng;i++){
            if(str[i].checked == true)
            {
                chestr+=str[i].value+",";
            };
        };
        return chestr;
    };

    function formsubmit(){
        var ta = checkbox();
        var str = '';
        str +=ta.substring(ta,ta.length-1);
        if(str!=""&& confirm('<?php echo $ld['remove_product'].'?'?>')){
            window.location.href = encodeURI(web_base+"/carts/act_remove/product/"+str);
        }
    }

    function changeCart(postData) {
        $.ajax({
            type: "POST",
            url: web_base+"/carts/changeCart",
            data: postData,
            dataType:"json",
            success: function (data) {
                $("#sum_discount").html("￥"+data.sum_discount_ajax+"元");
                $("#cart_price").html("￥"+data.sum_subtotal_ajax+"元");
                $("#cart_price2").html("￥"+data.les_subtotal_ajax+"元");
                $("#total_price").html("￥"+data.sum_market_subtotal+"元");
                var buy_length = $(".am-carts-buy input[name='subBox']:checked").length;
                var lease_length = $(".am-carts-lease input[name='subBox']:checked").length;
                if (buy_length > 0 && lease_length > 0) {
                $("#cart_prompt").html("<span style='color:red'>*</span>购买商品和租赁商品无法同时结算"); 
                }else{
                $("#cart_prompt").html("");     
                }
                if (data.les_subtotal_ajax == 0) {
                $("#lease_item").hide();
                }else{
                $("#lease_item").show();    
                };
                if(data.sum_subtotal_ajax == 0){
                $("#buy_item").hide();    
                }else{
                $("#buy_item").show();      
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Operation failure! Status=" + xhr.status + " Message=" + thrownError);
            },
            traditional: true
        });
    }

    function updateMasterCheckbox() {
        var numChkBoxes = $("input[name='subBox']").length;
        var numChkBoxesChecked=0;
        $("input[name='subBox']").each(function (index){
            if ($(this).prop("checked")) {
                numChkBoxesChecked++;
            }
        });
        $('#checkAll').prop('checked', numChkBoxes == numChkBoxesChecked && numChkBoxes > 0);
    }
    
    (function ($) {
        $.fn.extend({
            Scroll: function (opt, callback) {
                if (!opt) var opt = {};
                var _btnUp = $("#" + opt.up); //Shawphy:向上按钮
                var _btnDown = $("#" + opt.down); //Shawphy:向下按钮
                var _this = this.eq(0).find("ul:first");
                var lineH = _this.find("li:first").height(); //获取行高    
                var line = opt.line ? parseInt(opt.line, 10) : parseInt(this.height() / lineH, 10); //每次滚动的行数，默认为一屏，即父容器高度
                var speed = opt.speed ? parseInt(opt.speed, 10) : 600; //卷动速度，数值越大，速度越慢（毫秒） 
                var m = line;  //用于计算的变量
                var count = _this.find("li").length; //总共的<li>元素的个数
                var upHeight = line * lineH;
                function scrollDown() {
                    count = _this.find("li").length;
                    if (!_this.is(":animated")) {  //判断元素是否正处于动画，如果不处于动画状态，则追加动画。
                        if (m < count) {  //判断 m 是否小于总的个数
                            m += line;
                            _this.animate({ marginTop: "-=" + upHeight + "px" }, speed);
                        }
                    }
                }
                function scrollUp() {
                    count = _this.find("li").length;
                    if (!_this.is(":animated")) {
                        if (m > line) { //判断m 是否大于一屏个数
                            m -= line;
                            _this.animate({ marginTop: "+=" + upHeight + "px" }, speed);
                        }
                    }
                }
                _btnUp.live("click", scrollUp);
                _btnDown.live("click", scrollDown);
            }
        });
    })(jQuery);

$(function () {
var carts_customized_border_length = $("#customized_carts li").length;
for(var i = 0;i < carts_customized_border_length;i++){
    if (i%2 == 0) {
    $("#customized_carts li").eq(i).css("border-right","1px solid #ccc");
    };
}
})

$(function () {
var lease_customized_border_length = $("#lease_customized_carts li").length;
for(var i = 0;i < lease_customized_border_length;i++){
    if (i%2 == 0) {
    $("#lease_customized_carts li").eq(i).css("border-right","1px solid #ccc");
    };
}
})
//套装商品修改购物车数量
function packaged_proudct (obj) {
    var carts_packaged_num = $(obj).val();
    $(obj).parent().parent().parent().parent().find(".carts_packaged span").html(carts_packaged_num)
    // console.log(carts_packaged_num);
    // $(".carts_packaged span").html(carts_packaged_num);
}

function packaged_plus(obj) {
    var input_num = $(obj).parent().find(".am-fl.numinput").val();
    $(obj).parent().parent().parent().parent().find(".carts_packaged span").html(input_num);
}

function packaged_minus(obj) {
    var input_num = $(obj).parent().find(".am-fl.numinput").val();
    $(obj).parent().parent().parent().parent().find(".carts_packaged span").html(input_num);
}

(function () {
    var numinput = $(".am-fl.numinput").length;
    for(var i =0; i<numinput; i++){
    $(".am-fl.numinput").eq(i).parent().parent().parent().parent().find(".carts_packaged span").html($(".am-fl.numinput").eq(i).val());
    }
})();
</script>

<style type="text/css">
    .addnum{height:35px;width:35px;border:1px solid #ccc;padding:5px 10px;color:#000}
    .reducenum{height:35px;width:35px;border:1px solid #ccc;padding:5px 10px;color:#000;position:relative}
    .numinput{height:35px;width:35px;border-left:none;border-right:none;margin:0}
    @media only screen and (max-width:641px){
        .editnum{padding:0;}
        .cart_price{min-width:80px!important;}
        .reducenum,.addnum{height:25px;width:25px;}
        .numinput{width:25px!important;height:25px!important;}
        .addnum i,.reducenum i{font-size:12px;position:relative;bottom:8px;right:3px;}
    }
</style>