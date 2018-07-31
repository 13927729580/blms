<?php 
    //pr($order_product_data);
    //pr($order_info);
 ?>
<!-- 订单状态 -->
<style>
    .am-form-group{padding:10px;}
</style>
<div class="am-form-group">
    <label class="am-u-sm-3">订单状态</label>
    <div class="am-u-sm-9">
        <span>
            <?php if($order_info['status']==0){echo $ld['unrecognized'];}else if($order_info['status']==2){ ?>
                <?php echo $ld['order_canceled'] ?>
                <?php  }else if($order_info['payment_status']==0){?>
                <?php if(isset($order_info['payment_is_cod'])&&$order_info['payment_is_cod']==1 && $order_info['shipping_status']==1){echo $ld['order_shipped'];}else{ echo $ld['order_unpaid'] ;}?>
                <?php }elseif($order_info['status']==1 && $order_info['shipping_status']==0 && $order_info['payment_status']==2){ ?>
                <?php echo $ld['order_processing'] ?>
                <?php }elseif($order_info['status']==1 && $order_info['shipping_status']==1 && $order_info['payment_status']==2){ ?>
                <?php echo $ld['order_shipped'] ?>
                <?php }elseif($order_info['status']==1 && $order_info['shipping_status']==2 && $order_info['payment_status']==2){ ?>
                <?php echo $ld['order_complete'] ?>
                <?php }elseif($order_info['Order']['status']==4){echo $ld['product_returns'];}else if($order_info['shipping_status']==3){echo $ld['order_processing'];}else if($order_info['shipping_status']==5){echo $ld['product_returns'];}?>
                    <?php
                        if($order_info['order_currency']=='Euro'){
                            $currency_code='EUR';
                        }else if($order_info['order_currency']=='Dollar'){
                            $currency_code='USD';
                        }else if($order_info['order_currency']=='Pound'){
                            $currency_code='GBP';
                        }else if($order_info['order_currency']=='CA_Dollar'){
                            $currency_code='CAD';
                        }else if($order_info['order_currency']=='AU_Dollar'){
                            $currency_code='AUD';
                        }else if($order_info['order_currency']=='Francs'){
                            $currency_code='CHF';
                        }else if($order_info['order_currency']=='hk'){
                            $currency_code='HKD';
                        }else if($order_info['order_currency']=='CNY'){
                            $currency_code='CNY';
                        }
                    ?>
        </span>
    </div>
</div>
<!-- 订单商品名称 -->
<div class="am-form-group">
    <label class="am-u-sm-3">订单商品名称</label>
    <div class="am-u-sm-9">
        <span><?php echo $order_product_data['OrderProduct']['product_name'] ?></span>
    </div>
</div>
<!-- 订单商品修改属性 -->
<div class="am-form-group">
    <label class="am-u-sm-3">订单商品修改属性</label>
    <div class="am-u-sm-9">
        <span><?php echo $order_product_data['OrderProduct']['product_attrbute'] ?></span>
    </div>
</div>
<!-- 订单号 -->
<div class="am-form-group">
    <label class="am-u-sm-3">订单号</label>
    <div class="am-u-sm-9">
        <span><?php echo $order_info['order_code'] ?></span>
    </div>
</div>
<!-- 物流公司 -->
<div class="am-form-group">
    <label class="am-u-sm-3">物流公司</label>
    <div class="am-u-sm-9">
        <span><?php echo $order_info['logistics_company_id'] ?></span>
    </div>
</div>
<!-- 快递单号 -->
<div class="am-form-group">
    <label class="am-u-sm-3">快递单号</label>
    <div class="am-u-sm-9">
        <span><?php echo $order_info['invoice_no'] ?></span>
    </div>
</div>
<!-- 物流信息 -->
<div class="am-form-group">
    <label class="am-u-sm-3">物流信息</label>
    <div class="am-u-sm-9">
        <span></span>
    </div>
</div>