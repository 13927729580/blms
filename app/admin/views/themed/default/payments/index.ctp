<style>
  
     .min_w{min-width:88px;}
</style>

<p class="am-u-md-12 am-btn-group-xs" style="margin-top:10px;">
    <?php if($svshow->operator_privilege("payments_edit")){?>
    
	    <!--echo $html->link($ld['add'],'/payments/view/0',array("class"=>"am-btn am-btn-warning am-radius am-btn-sm am-fr"),false,false);-->
	       <a class="am-btn  am-btn-sm am-btn-warning am-radius am-fr" href="<?php echo $html->url('/payments/view/0')   ?>">
	        		<span class="am-icon-plus"></span>
	           		<?php  echo  $ld['add'] ?>
	           </a>
	    	<?php }?>
</p>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12 am-u-sm-12 am-cf">
    <div class="am-g">
        
        <div id="tablelist_bold" class="am-cf" style="padding-bottom:0.5rem;border-bottom:2px solid #ddd;">
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 min_w" style="padding-right:0;"><?php echo $ld['payment_name']?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 " style=""><?php echo $ld['code']?></div>
            <div class="am-u-lg-4 am-u-md-3 am-u-sm-3 min_w" style="display:none;"><?php echo $ld['payment_description']?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 " style="padding-right:0;"><?php echo $ld['fee']?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 " style="padding-right:0;"><?php echo $ld['sort']?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-right:0;"><?php echo $ld['valid']?></div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-right:0;"><?php echo $ld['operate']?></div>
        </div>
    
    
        <?php if(isset($payment_tree) && sizeof($payment_tree)>0){foreach($payment_tree as $k=>$payment){?>
            <div class=" am-panel-body am-cf" id="payment_<?php echo $payment['Payment']['id']; ?>" style="padding-bottom:0.5rem;border-bottom:1px solid #ddd;">
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 min_w" style="padding-right:0;"><span data-am-collapse="{target: '.payment_<?php echo $payment['Payment']['id']?>'}" class="<?php echo (isset($payment['SubMenu']) && !empty($payment['SubMenu']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $payment['PaymentI18n']['name']?></div>
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 occupy" style=""><?php echo $payment['Payment']['code']?></div>
                <div class="am-u-lg-4 am-u-md-3 am-u-sm-3 ellipsis min_w" style="margin-bottom:0;display:none;"><?php echo $payment['PaymentI18n']['description']?></div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-right:0;"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_fee/', <?php echo $payment['Payment']['id']?>)"><?php echo $payment['Payment']['fee']?></span></div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_orderby/', <?php echo $payment['Payment']['id']?>)"><?php echo $payment['Payment']['orderby']?></span></div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php if($payment['Payment']['status']) echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$payment["Payment"]["id"].')></div>';else echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$payment["Payment"]["id"].')></div>';?></div>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-right:0;"><?php
	                    if($svshow->operator_privilege("payments_edit")){?>
				<a class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-edit  am-action" href="<?php echo $html->url('/payments/view/'.$payment['Payment']['id']); ?>" style="">
					<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
				</a>
	                    <?php
		                    if($payment['Payment']['status']=='0'){
		                        	echo $html->link($ld['use'],'/payments/install/'.$payment['Payment']['id'],array("class"=>"am-seevia-btn mt am-btn am-btn-success  am-btn-xs am-radius","style"=>"margin-right:0.5rem;"),false,false);
		                    }else{
		                        	echo $html->link($ld['disable'],'/payments/uninstall/'.$payment['Payment']['id'],array("class"=>"am-seevia-btn mt am-btn am-btn-warning am-btn-xs am-radius","style"=>"margin-right:0.5rem;"),false,false);
		                    }
	        		}
	        		 if($svshow->operator_privilege("payments_remove")){
	        		 		echo $html->link($ld['remove'],'javascript:void(0);',array("class"=>"am-seevia-btn mt am-btn am-btn-danger am-btn-xs am-radius",'onclick'=>"list_delete_submit(admin_webroot+'payments/remove/".$payment['Payment']['id']."');"),false,false);
	        		 }
	        	?></div>
            </div>
          
           
            <?php if(isset($payment['SubMenu']) && !empty($payment['SubMenu'])>0){foreach($payment['SubMenu'] as $kk=>$vv){?>
                <div class="am-panel-collapse am-collapse am-panel-child payment_<?php echo $payment['Payment']['id']?> am-cf" title="<?php echo $payment['Payment']['id']?>" style="padding-top:0rem;padding-bottom:0.5rem;border-bottom:1px solid #ddd;">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="padding-right:0;">&nbsp;&nbsp;&nbsp;<?php echo $vv['PaymentI18n']['name']?></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 occupy" style=""><?php echo $vv['Payment']['code']; ?></div>
                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="display:none;"><div class="ellipsis"><?php echo $vv['PaymentI18n']['description']?></div></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-right:0;"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_fee/', <?php echo $vv['Payment']['id']?>)"><?php echo $vv['Payment']['fee']?></span></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><span onclick="javascript:listTable.edit(this, 'payments/update_payment_orderby/', <?php echo $vv['Payment']['id']?>)"><?php echo $vv['Payment']['orderby']?></span></div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php if($vv['Payment']['status']) echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$vv["Payment"]["id"].')></div>';else echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"payments/toggle_on_status",'.$vv["Payment"]["id"].')></div>';?></div>
                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="padding-right:0;"><?php if($svshow->operator_privilege("payments_edit")){?>
                             <a class="mt am-btn am-btn-default am-btn-xs am-seevia-btn-edit  am-action" href="<?php echo $html->url('/payments/view/'.$vv['Payment']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                      </a>
                       <?php
		                        if($vv['Payment']['status']=='0'){
		                           	echo $html->link($ld['use'],'/payments/install/'.$vv['Payment']['id'],array("class"=>" am-seevia-btn mt am-btn am-btn-success  am-btn-xs am-radius ","style"=>"margin-right:0.5rem;"),false,false);
		                        }else{
		                            	echo $html->link($ld['disable'],'/payments/uninstall/'.$vv['Payment']['id'],array("class"=>"am-seevia-btn mt am-btn am-btn-warning am-btn-xs am-radius"),false,false);
		                        }
            			}
                        if($svshow->operator_privilege("payments_remove")){
	        		 	echo $html->link($ld['remove'],'javascript:void(0);',array("class"=>"am-seevia-btn mt am-btn am-btn-danger am-btn-xs am-radius",'onclick'=>"list_delete_submit(admin_webroot+'payments/remove/".$vv['Payment']['id']."');","style"=>""),false,false);
	        	   }
                        ?></div>
                </div>
                  

          <?php }} ?>
        <?php }}?> 
       </div>
</div>
<script type="text/javascript">
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function(){
        var parentbody_id="payment_"+$(this).prop("title");
		var parentbody=$("#"+parentbody_id);
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody_id="payment_"+$(this).prop("title");
		var parentbody=$("#"+parentbody_id);
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
    for(var i = 0;i<$("div.am-u-lg-4").length;i++){
        if($("div.am-u-lg-4").eq(i).text() == ''){
        $("div.am-u-lg-4").eq(i).append('已删除');
    }
    }
    for(var i = 0;i<$(".occupy").length;i++){
        if($(".occupy").eq(i).html() == ''){
        $(".occupy").eq(i).html('&nbsp;');
    }
    }
    
    
})
</script>