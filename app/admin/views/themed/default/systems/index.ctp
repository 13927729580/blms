<style type="text/css">
.am-panel-bd {padding: 0.5rem;}
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
.am-panel-title div{font-weight:bold;}
.ziji div a{
	margin-bottom:10px;margin-left:15px;
}
</style>
<div id="tablelist" class="">
    <?php echo $form->create('ProductCategorie',array('action'=>'/','name'=>'ArticleForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <div class="am-panel-group am-panel-tree" id="accordion">
        <!--标题栏-->
        <div class="am-panel-header listtable_div_btm">
            <div class="am-panel-hd">
                <div class="am-panel-title">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['system'] ?>/<?php echo $ld['module'] ?></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></div>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><?php echo $ld['operate']?></div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <!-- 菜单-->
        <div>
            <div class=" listtable_div_top  am-panel-body">
    		<?php if(isset($system_list)&&sizeof($system_list)>0){foreach($system_list as $v){ ?>
                <div class="am-panel-bd fuji">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                        <div><?php echo $v['System']['code']; ?></div>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">&nbsp;
                        <?php if($v['System']['status']) {?>
                            <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'systems/toggle_status',<?php echo $v['System']['id'];?>)"></span>
                        <?php }else{?>
                            <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'systems/toggle_status',<?php echo $v['System']['id'];?>)">&nbsp;</span>
                        <?php }?>
                    </div>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                        
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/profiles?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['file_allocation'];?>
							</a>	
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/languages?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['manage_languages'];?>
							</a>
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/dictionaries?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['dictionar'];?>
							</a>	
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/notify_templates/index?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['notify_template'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/information_resources?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['information_resource_manage'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/menus?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['menu_manage'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/actions?system_code='.$v['System']['code']); ?>">
							<?php echo $ld['rights_management'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/resources?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['resource_manage'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/cronjobs?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['manage_cronjob'];?>
							</a>	
						
						
							<a style="margin-top:-1px;margin-bottom:10px;margin-left:15px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/configs?system_code='.$v['System']['code']); ?>">
							 <?php echo $ld['shop_configs'];?>
							</a>

							<?php if($svshow->operator_privilege("products_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['lease_parameter'].$ld['set_up'],"/product_lease_prices/index",array('target'=>'_blank',"class"=>" mt mt_2 am-btn am-btn-default am-seevia-btn-view lease_parameter_set_up"));} ?>

							<?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['product'].$ld['set_up'],"/products/config",array('target'=>'_blank',"class"=>" mt mt_2 am-btn am-btn-default am-seevia-btn-view product_set_up"));} ?>

							<?php if($svshow->operator_privilege("category_types_view")){echo $html->link($ld['category_management'],"/category_types/",array("class"=>"mt mt_2 am-btn am-btn-default am-seevia-btn-view category_management"));} ?>

                            <?php if($svshow->operator_privilege("product_style_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['style_manager'],"/product_styles/",array("class"=>"mt mt_2 am-btn am-btn-default am-seevia-btn-view style_manager"));} ?>	

                            <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['order'].$ld['set_up'],"/orders/config",array('target'=>'_blank','class'=>' mt_2 am-btn am-btn-default am-btn-sm order_set_up')).'&nbsp;';}?>

                            <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['vip'].$ld['set_up'],"/users/config",array('target'=>'_blank',"class"=>"mt_2 am-btn am-btn-default am-seevia-btn-view vip_set_up"));} ?>
						
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <?php if(isset($v['SystemModule'])&&sizeof($v['SystemModule'])>0){foreach($v['SystemModule'] as $vv){ ?>
		   <div class="am-panel-bd ziji">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
			     <div style="margin-left:10px"><span class="am-icon-minus">&nbsp;&nbsp;</span><?php echo $vv['code']; ?></div>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">&nbsp;
                        <?php if($vv['status']) {?>
                            <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'systems/toggle_module_status',<?php echo $vv['id'];?>)"></span>
                        <?php }else{?>
                            <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'systems/toggle_module_status',<?php echo $vv['id'];?>)">&nbsp;</span>
                        <?php } ?>
                    </div>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                        
							<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/profiles?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['file_allocation'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/languages?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['manage_languages'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/dictionaries?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							<?php echo $ld['dictionar'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/notify_templates/index?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							<?php echo $ld['notify_template'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/information_resources?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['information_resource_manage'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/menus?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['menu_manage'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/actions?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							<?php echo $ld['rights_management'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/resources?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['resource_manage'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/cronjobs?system_code='.$v['System']['code'].'&module_code='.$vv['code']); ?>">
							 <?php echo $ld['manage_cronjob'];?>
							</a>	
						
							<a style="margin-top:-1px" class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-view" target='_blank' href="<?php echo $html->url('/configs'); ?>">
							 <?php echo $ld['shop_configs'];?>
							</a>

							<?php if($svshow->operator_privilege("products_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['lease_parameter'].$ld['set_up'],"/product_lease_prices/index",array('target'=>'_blank',"class"=>" mt mt_1 am-btn am-btn-default am-seevia-btn-view lease_parameter_set_up"));} ?>

							<?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['product'].$ld['set_up'],"/products/config",array('target'=>'_blank',"class"=>" mt mt_1 am-btn am-btn-default am-seevia-btn-view product_set_up"));} ?>

							<?php if($svshow->operator_privilege("category_types_view")){echo $html->link($ld['category_management'],"/category_types/",array("class"=>"mt mt_1 am-btn am-btn-default am-seevia-btn-view category_management"));} ?>

                            <?php if($svshow->operator_privilege("product_style_view")&&constant('Product') == 'AllInOne'){echo $html->link($ld['style_manager'],"/product_styles/",array("class"=>"mt mt_1 am-btn am-btn-default am-seevia-btn-view style_manager"));} ?>

                            <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['order'].$ld['set_up'],"/orders/config",array('target'=>'_blank','class'=>' mt_1 am-btn am-btn-default am-btn-sm order_set_up')).'&nbsp;';}?>

                            <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['vip'].$ld['set_up'],"/users/config",array('target'=>'_blank',"class"=>"mt_1 am-btn am-btn-default am-seevia-btn-view vip_set_up"));} ?>
						
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <?php }} ?>
                <?php }} ?>
        </div>
</div>

<script type='text/javascript'>
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
</script>

<style>
.mt_1{
	font-size:12px;
	margin-top:-1px;
}
.mt_2 {
	font-size:12px;
    margin-top: -11px;
    margin-left: 16px;
}
</style>
<script>
var lease_parameter_set_up = document.querySelectorAll('.lease_parameter_set_up');
var product_set_up = document.querySelectorAll('.product_set_up');
var category_management = document.querySelectorAll('.category_management');
var style_manager = document.querySelectorAll('.style_manager');
var order_set_up = document.querySelectorAll('.order_set_up');
var vip_set_up = document.querySelectorAll('.vip_set_up');

for(var i = 0;i<lease_parameter_set_up.length;i++){
	lease_parameter_set_up[i].style.display='none';
	product_set_up[i].style.display='none';
	category_management[i].style.display='none';
	style_manager[i].style.display='none';
}
lease_parameter_set_up[2].style.display='';
lease_parameter_set_up[11].style.display='';
product_set_up[2].style.display='';
product_set_up[11].style.display='';
category_management[2].style.display='';
category_management[11].style.display='';
style_manager[2].style.display='';
style_manager[11].style.display='';

for(var i = 0;i<order_set_up.length;i++){
	order_set_up[i].style.display='none';
}
order_set_up[8].style.display='';

for(var i = 0;i<vip_set_up.length;i++){
	vip_set_up[i].style.display='none';
}
vip_set_up[18].style.display='';

</script>