

	<?php //pr($data) ?>

<style>

	.subscriptions_title{
		color:#0e90d2;
		padding-left: 10px;
	}
	.subscriptions_add{
		background-color:#1E3867;
		color:#fff;
	}
	.subscriptions_add:hover{
		color:#fff;
	}
	.subscriptions_list_title{
		border-bottom: 1px solid #ddd;
		padding:10px 15px;
		
	}
.subscriptions_list_title div{
			font-weight: 600;
		}
	.subscriptions_list_body{
		border-bottom:1px solid #ddd;
		padding:8px 15px;
	}

</style>
<div class="subscriptions-list">
	<!-- <div class="am-cf" style="border-bottom:1px solid #ddd;padding:30px 0px 10px 0px;">
		<div class="am-u-sm-6"><h3 class="subscriptions_title" style="text-align:left;font-size:20px;border-bottom:2px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;"><?php echo $ld['my_subscriptions'] ?></h3></div>	
		<div class="am-u-sm-6"></div>
	</div> -->
	<div>
		<a href="<?php echo $html->url('/user_product_subscriptions/view/0') ; ?>" class="am-btn am-fr am-btn-xs  am-btn-secondary"><span class="am-icon-plus"></span><?php echo $ld['add'] ?></a>
		<div class="am-cf"></div>
	</div>
	<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;" >
		<span style="float:left;"><?php echo $ld['my_subscriptions'] ?></span>
		
		<div class="am-cf"></div>
	</div>
	
	<div class="am-cf subscriptions_list_title">
		<div class="am-u-sm-3"><?php echo $ld['subscription_name'] ?></div>
		<div class="am-u-sm-4"><?php echo $ld['sending_time'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['status'] ?></div>
		<div class="am-u-sm-3"><?php echo $ld['operation'] ?></div>
	</div>
	<span class="none_subscriptions" style="text-align: center;width: 100%;display: inline-block; margin-top: 50px;">暂无订阅</span>
<?php if (isset($data)&&sizeof($data)>0) {foreach ($data as $k => $v) { ?>

	<div class="am-cf subscriptions_list_body">
		<div class="am-u-sm-3"><?php echo $v['UserProductSubscription']['name'] ?>&nbsp;</div>
		<div class="am-u-sm-4"><?php echo isset($informationresource_infos['product_subscription'][$v['UserProductSubscription']['send_time']])?$informationresource_infos['product_subscription'][$v['UserProductSubscription']['send_time']]:'-' ?>&nbsp;</div>
		<div class="am-u-sm-2">
			 <?php if ($v['UserProductSubscription']['status'] == 0) { ?>
      			<span style="cursor:pointer;" onclick="change_state(this,'/user_product_subscriptions/update_status',<?php echo $v['UserProductSubscription']['id'];?>)" class="am-icon-close am-no"></span>
      		<?php } ?>
     		<?php if($v['UserProductSubscription']['status'] == 1) { ?>
      			<span style="cursor:pointer;" onclick="change_state(this,'/user_product_subscriptions/update_status',<?php echo $v['UserProductSubscription']['id'];?>)" class="am-icon-check am-yes"></span>
      		<?php } ?>&nbsp;
		</div>
		<div class="am-u-sm-3">
			<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/user_product_subscriptions/view/'.$v['UserProductSubscription']['id']); ?>" ><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit'] ?></a>
			<a href="javascript:void(0);" onclick="subscriptions_remove('<?php echo $v['UserProductSubscription']['id'] ?>')" class="am-btn am-btn-default am-btn-xs am-text-danger"><span class="am-icon-trash-o"></span><?php echo $ld['delete']; ?></a>
		</div>
	</div>
<?php }} ?>
</div>


<script>
	function subscriptions_remove (id) {
		if (confirm(confirm_delete)) {
		window.location.href = web_base+"/user_product_subscriptions/remove/"+id;
	}
}
function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    // var postData = "status="+val+"&id="+id;
    $.ajax({
        url:web_base+func+"/"+id+"/"+val,
        type:"POST",
        dataType:"json",
        success:function(data){
        	console.log(data);
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
var subscriptions_list_body = document.querySelector('.subscriptions_list_body');
var subscriptions_list_title = document.querySelector('.subscriptions_list_title');
var none_subscriptions = document.querySelector('.none_subscriptions');
if(subscriptions_list_body == null){
	none_subscriptions.style.display="inline-block";
	subscriptions_list_title.style.display="none";
}else{
	none_subscriptions.style.display="none";
	subscriptions_list_body.style.display="";
}
</script>