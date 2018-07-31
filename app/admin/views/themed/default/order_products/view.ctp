<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
    select.order_product_status{width:95%;padding:5px;margin-bottom:5px;}
    td select.order_product_status{width:65%;}
</style>
<?php
	//pr($order_product_value_info);
	//pr($attribute_list);
?>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<input type="hidden" id="order_id" value="<?php echo $order_product_info['Order']['id']?>" />
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
            <li><a data-am-collapse="{parent: '#accordion'}" href="#other">附加信息</a></li>
            <li><a data-am-collapse="{parent: '#accordion'}" href="#media">媒体信息</a></li>
            <li><a data-am-collapse="{parent: '#accordion'}" href="#log">操作日志</a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content">
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
            <button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 编辑按钮区域 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="font-weight:400;">订单号</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <?php echo $order_product_info['Order']['order_code'];?>
                            <input type="hidden" name="order_code" value="<?php echo $order_product_info['Order']['order_code'];?>">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="font-weight:400;">货号</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo $order_product_info['OrderProduct']['product_code'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="font-weight:400;padding-right:0;"><?php echo $ld['product_brand'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo isset($brand_names[$order_product_info['Product']['brand_id']])?$brand_names[$order_product_info['Product']['brand_id']]:"-";?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="font-weight:400;padding-right:0;"><?php echo $ld['product_categories'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php echo isset($product_category_tree[$order_product_info['Product']['category_id']])?$product_category_tree[$order_product_info['Product']['category_id']]:"&nbsp;";?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="font-weight:400;"><?php echo $ld['status'];?></label>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <select class='order_product_status' style="min-width:74px;" onchange="order_product_status_modify(this,<?php echo $order_product_info['OrderProduct']['id']; ?>)">
                                <?php if(isset($Resource_info['order_product_status'])&&sizeof($Resource_info['order_product_status'])>0) {foreach($Resource_info['order_product_status'] as $kk=>$vv){?>
                                    <option value="<?php echo $kk; ?>" <?php if($order_product_info['OrderProduct']['delivery_status'] ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#other'}">附加信息</h4>
            </div>
            <div id="other" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" id="ajax_order_product_modify">
                    <form method='POST' class='am-form am-form-horizontal'>
                        <input type='hidden' name='order_id' value='0'>
                        <input type='hidden' name='order_product_id' value='0'>
                       
                            <div></div>
                            <tbody>
                                <table class="am-table">
                            <div class="am-form-group">
                                <div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="border-bottom:0;line-height:33px;"><?php echo $ld['gaiyishi']; ?></div>
                                <div class='am-text-left am-u-lg-2 am-u-md-2 am-u-sm-7 am-u-end' style="">
                                    <select name='order_product_picker' data-am-selected="{maxHeight:200}">
                                        <option value='0'><?php echo $ld['please_select']; ?></option>
                                        <?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
                                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="am-cf"></div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="border-bottom:0;line-height:33px;"><?php echo '质检'; ?></div>
                                <div class='am-text-left am-u-lg-2 am-u-md-2 am-u-sm-7 am-u-end'>
                                    <select name='order_product_qc' data-am-selected="{maxHeight:200}">
                                        <option value='0'><?php echo $ld['please_select']; ?></option>
                                        <?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
                                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="am-cf"></div>
                            </div>
                            <tr>
                                <th style="border-bottom:0;"></th> 
                                <td class='am-text-left'>
                                    <button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_order_product_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#order_product_attribute'}">订单商品属性</h4>
            </div>
            <div id="order_product_attribute" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    
                    <div class="am-cf am-hide-sm-only" style="border-bottom:2px solid #ddd;padding-bottom:0.6rem;">
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:left;font-weight:700;padding-left: 10px;">属性名称</div>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:left;font-weight:700;padding-left: 10px;">属性值</div>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:left;font-weight:700;padding-left: 10px;">价格</div>
                    </div>
                    </thead>
                    <div class="am-cf">
                        <?php if(isset($order_product_value_info)) {foreach($order_product_value_info as $k=>$v){?>
                            <div>
                                <div style="padding:10px;" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $attribute_list[$v['OrderProductValue']['attribute_id']]['AttributeI18n']['name'] ?></div>
                                <div style="padding:10px;" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php if($v['OrderProductValue']['attribute_value']=='NaN'){echo '';}else{echo $v['OrderProductValue']['attribute_value'];}; ?></div>
                                <div style="padding:10px;" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $v['OrderProductValue']['attr_price']; ?></div>
                                <div class="am-cf"></div>
                            </div>  
                        <?php }}else{?>
                            <div colspan="5" align="center" class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin-top:0.6rem;"><?php echo $ld['no_data_found']?></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#media'}">媒体信息</h4>
            </div>
            <div id="media" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <p style="text-align:right;">
                        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/order_product_medias/add/'.$order_product_info['OrderProduct']['id']); ?>">
                            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
                        </a>
                    </p>
                    <thead>
                    <div class="am-cf am-hide-sm-only" style="border-bottom:2px solid #ddd;padding-bottom:0.6rem;">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['operator']?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['type'];?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;">媒体</div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['description']?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['operation_time']?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['operate']?></div>
                    </div>
                    </thead>
                    <div class="am-cf">
                        <?php if(isset($media_list) && sizeof($media_list)>0){foreach($media_list as $k=>$v){?>
                            <!-- 电脑端 -->

                            <div class="am-cf am-hide-sm-only" style="border-bottom:1px solid #ddd;padding-top:0.6rem;padding-bottom:0.6rem;">

                                
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OrderProductMedia']['operator_name']; ?></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 media_type"><?php echo $v['OrderProductMedia']['type']; ?></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="word-break:break-all;" >
                                    <img src="<?php echo $v['OrderProductMedia']['media']; ?>" style="width:60px;height:60px;" class="media_image">

                                </div>                    
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="word-break:break-all;"><?php echo $v['OrderProductMedia']['description'];?></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OrderProductMedia']['created']; ?></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/order_product_medias/view/'.$v['OrderProductMedia']['id']); ?>">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'order_product_medias/remove/<?php echo $v['OrderProductMedia']['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                </div>
                            </div>
                            <!-- 移动端 -->
                             <div class="am-cf am-show-sm-only" style="border-bottom:1px solid #ddd;padding-top:0.6rem;padding-bottom:0.6rem;">
                                <div class="am-cf" style>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="word-break:break-all;" >
                                    <img src="<?php echo $v['OrderProductMedia']['media']; ?>" style="width:60px;height:60px;" class="media_image">

                                </div>  
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-9" style="word-break:break-all;line-height:1.2;padding-right:0;"><?php echo $v['OrderProductMedia']['description'];?></div>
                                </div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-9"><?php echo $v['OrderProductMedia']['created']; ?><br><?php echo $v['OrderProductMedia']['operator_name']; ?></div>
                                <!-- <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 media_type"><?php echo $v['OrderProductMedia']['type']; ?></div> -->
                                                          
                                
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-3">
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/order_product_medias/view/'.$v['OrderProductMedia']['id']); ?>">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <br>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'order_product_medias/remove/<?php echo $v['OrderProductMedia']['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                </div>
                            </div>
                        <?php }}else{?>
                            <div colspan="5" align="center" class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin-top:0.6rem;"><?php echo $ld['no_data_found']?></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#log'}">操作日志</h4>
            </div>
            <div id="log" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-cf" style="border-bottom:2px solid #ddd;padding-bottom:0.6rem;">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;font-weight:700;"><?php echo $ld['operator']?></div>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:left;font-weight:700;"><?php echo $ld['description']?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="text-align:left;font-weight:700;"><?php echo $ld['operation_time']?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="text-align:left;font-weight:700;"><?php echo $ld['status'];?></div>
                    </div>
                    <div class="am-cf">
                        <?php if(isset($action_list) && sizeof($action_list)>0){foreach($action_list as $k=>$v){?>
                            <div class="am-cf" style="border-bottom:1px solid #ddd;padding-top:0.6rem;padding-bottom:0.6rem;">
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;"><?php echo $v['OrderProductAction']['operator_name']; ?></div>
                                <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="text-align:left;"><?php echo $v['OrderProductAction']['action_note'];?></div>
                                <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="text-align:left;"><?php echo $v['OrderProductAction']['created']; ?></div>
                                <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="text-align:left;"><?php echo $Resource_info['order_product_status'][$v['OrderProductAction']['status']]; ?></div>
                            </div>
                        <?php }}else{?>
                            <div colspan="4" align="center" class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin-top:0.6rem;"><?php echo $ld['no_data_found']?></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .am-g.admin-content{margin:0 auto;}
    .am-form-label{text-align:right;}
    .am-form .am-form-group:last-child{margin-bottom:0;}
    #rank_operator select{width:50%;}
    #rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
    #rank_operator input[type="button"]{margin-right:1.2rem;}
</style>
<script>
    //订单商品状态修改
    function order_product_status_modify(select,order_product_id){
        var order_id = document.getElementById("order_id").value;
        var order_product_status=$(select).val();
        $.ajax({
            url: admin_webroot+"orders/ajax_order_product_status_modify",
            type:"POST",
            data:{'order_id':order_id,'order_product_id':order_product_id,'order_product_status':order_product_status},
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    order_reflash(true,data.total,data.need_pay,data.insure_fee);
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function ajax_order_product_modify(order_product_id){
        var order_id = document.getElementById("order_id").value;
        $.ajax({
            url: admin_webroot+"orders/ajax_order_product_modify",
            type:"get",
            data:{'order_id':order_id,'order_product_id':order_product_id},
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    $("#ajax_order_product_modify input[name='order_id']").val(order_id);
                    $("#ajax_order_product_modify input[name='order_product_id']").val(order_product_id);
                    var modify_table=$("#ajax_order_product_modify form div:eq(0)");
                    var resource_info=data.data.resource_info;
                    var order_product_additional=data.data.order_product_additional;
                    var additional_info={};
                    if(typeof(order_product_additional.value)!='undefined'){
                        additional_info=JSON.parse(order_product_additional.value);
                    }
                    $.each(resource_info,function(index,item){
                        var additional_value=typeof(additional_info[index])!='undefined'?additional_info[index]:'';
                        var tr_html="<div class='am-form-group' style='border-bottom:1px solid #ddd;padding-bottom:1rem;'>";                       
                        tr_html+="<div class='am-u-lg-2 am-u-md-2 am-u-sm-3' style='border-bottom:0;line-height:35px;'>"+item+"</div>";
                        tr_html+="<div class='am-u-lg-10 am-u-md-10 am-u-sm-9'><input type='text' style='width:100px;' name='data["+index+"]' value='"+additional_value+"' /></div>";
                        tr_html+="<div class='am-cf'>";
                        tr_html+="</div>";                 
                        tr_html+="</div>";
                        modify_table.append(tr_html);
                    });
                    var order_product_picker=data.data.order_product.picker;
                    var order_product_QC=data.data.order_product.QC;
                    $("#ajax_order_product_modify select[name='order_product_picker'] option[value='"+order_product_picker+"']").prop("selected",true);
                    $("#ajax_order_product_modify select[name='order_product_qc'] option[value='"+order_product_QC+"']").prop("selected",true);
                    $("#ajax_order_product_modify select[name='order_product_picker']").trigger('changed.selected.amui');
                    $("#ajax_order_product_modify select[name='order_product_qc']").trigger('changed.selected.amui');
                }else{
                    alert(data.message);
                }
            }
        });
    }

    ajax_order_product_modify(<?php echo $order_product_info['OrderProduct']['id'];?>)

    function ajax_order_product_modify_submit(btn){
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"orders/ajax_order_product_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    order_reflash(true,data.total,data.need_pay,data.insure_fee);
                }else{
                    alert(data.message);
                }
            }
        });
    }
    // 媒体信息
    $(document).ready(function(){
        var media_type = $(".media_type");
        
        for(var i=0;i<media_type.length;i++){
             if(media_type.eq(i).html() == 'image'){
                 ;
             }
          
        }
        
    });

    
</script>