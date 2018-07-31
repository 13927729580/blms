<?php	//pr($sm);	?>
<div class="am-g am-g-fixed">
	<div class="am-panel am-panel-default am-margin-top-sm">
		<div class="am-panel-hd"><?php echo $code_infos[$sk]['name'];?></div>
		<div  class="am-panel-bd">
			<div class="listbox am-form am-padding-horizontal-sm">
				<div class="am-form-group">
					<input type="hidden" id="product_id" name="data[UserMessage][value_id]" value="<?php echo isset($sm['product_id'])?$sm['product_id']:""; ?>">
					<textarea class='am-margin-top-lg' rows="6" id="msg_content" name="data[UserMessage][msg_content]" <?php if(empty($_SESSION['User']['User']['id'])){echo " disabled='disabled'";}?> ></textarea>
				</div>
				<div class="am-form-group am-text-right am-margin-top-sm">
					<button class="am-btn am-btn-secondary message_adds" onclick="add_video_UserMessage();" type="button"><?php echo $ld['release'];?></button>
				</div>
			</div>
			<ul class="am-comments-list">
			<?php if(!empty($sm['product_message'])&&sizeof($sm['product_message'])>0){ foreach($sm['product_message'] as $k=>$v){ ?>
				<li class="am-comment">
					<a href='javascript:void(0);'><?php echo $html->image( isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:('/theme/default/img/no_head.png'),array('class'=>'am-comment-avatar','style'=>'width:48px;height:48px;'));  ?></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
							<div class="am-comment-meta">
								<a href="javascript:void(0);" class="am-comment-author"><?php echo mb_substr($v['UserMessage']['user_name'],0,1,'utf-8').'***'; ?></a> 提问于 <time><?php echo $v['UserMessage']['created'];?></time>
								<button class="am-btn am-btn-default msg_reply am-hide"  id="<?php echo 'msg_reply'.($k)?>"><?php echo $ld['answer'];?></button>
							</div>
						</header>
					</div>
					<div class="am-comment-bd">
						<p class='am-padding-left-sm'><?php echo $v['UserMessage']['msg_content'];?></p>
					</div>
				</li>
				<?php	if(isset($v['Reply'])&&sizeof($v['Reply'])>0){foreach($v['Reply'] as $kk=>$vv){//pr($vv);	?>
					<li class='am-comment am-comment-reply'>
						<a href='javascript:void(0);'><?php echo $html->image( isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:('/theme/default/img/no_head.png'),array('class'=>'am-comment-avatar am-fr','style'=>'width:48px;height:48px;'));  ?></a>
						<div class="am-comment-main am-comment-main-reply">
							<header class="am-comment-hd">
								<div class="am-comment-meta">
									<a href="javascript:void(0);" class="am-comment-author"><?php echo $ld['administrator']; ?></a> 回复于 <time><?php echo $vv['UserMessage']['created'];?></time>
								</div>
							</header>
						</div>
						<div class="am-comment-bd">
							<p class='am-padding-left-sm'><?php echo $vv['UserMessage']['msg_content'];?></p>
						</div>
					</li>
				<?php	}} ?>
				
			<?php }} ?>
			</ul>
		</div>
	</div>
</div>
<style type='text/css'>
.am-comment-reply .am-comment-main-reply{margin-left:0px;margin-right:63px;}
.am-comment-reply .am-comment-main-reply:before,.am-comment-reply .am-comment-main-reply:after{border-color: transparent;border-style: solid dotted dotted solid;border-width: 8px 0px 8px 8px;content: " ";display: block;height: 0;right:-8px;pointer-events: none;position: absolute;top: 10px;width: 0;left:auto;}
.am-comment-reply .am-comment-main-reply:before{border-left-color: #dedede;z-index: 1;}
.am-comment-reply .am-comment-main-reply:after{border-left-color: #fff;margin-right: 1px;z-index: 2;}
</style>
<script type="text/javascript">
//提问提交	
function add_video_UserMessage(){
	//判断登录
	<?php if(empty($_SESSION['User']['User']['id'])){?>
		$(".denglu").click();
	<?php }else{?>
		if($("#msg_content").val()==""){
			alert("<?php echo $ld['message_content_empty']; ?>");
			return false;
		}else{
			var con=$("#msg_content").val();
			var product_id=$("#product_id").val();
			$.ajax({ url: web_base+"/products/ajax_add_message",
		    		dataType:"json",
		    		type:"POST",
		    		context: $("#msg_content"),
		    		data: { 'product_id': product_id, 'content': con },
		    		success: function(data){
	    				alert(data.message);
	    				$("#msg_content").val("");
	    				window.location.reload();
	  			}
	  		});
		}
	<?php }?>
}
//回复内容显示
$(".msg_reply").click(function(){
	var id=$(this).attr("id");
	id=id.replace("msg_reply","msg_answer");
	var message_id=id.replace("msg_answer","msg_reply_to_user_buttom_");
	var message_btn=id.replace("msg_answer","msg_reply_to_user_");
	//alert(message_id+" "+message_btn)
	if($("#"+id).css("display")=="none")
	{
		$("#"+id).css("display","block");
		$("#"+message_id).parent().css("display","block");
		$("#"+message_btn).parent().css("display","block");
	}
	else
	{
		$("#"+id).css("display","none");
		$("#"+message_id).parent().css("display","none");
		$("#"+message_btn).parent().css("display","none");
	}
});

//回复评论功能
$(".msg_reply_to_user").click(function(){	
	//判断登录
	<?php if(empty($_SESSION['User']['User']['id'])){?>
		$(".denglu").click();
	<?php }else{?>
	var id=$(this).attr("id");
	var textid=id.replace("msg_reply_to_user_buttom","msg_reply_to_user");
	var text=$("#"+textid).val();
	var message_list=id.replace("msg_reply_to_user_buttom","message_list_");
	if(text==""){
		alert("回答不能为空，写点什么吧!");
	}
	else{
		var message=id.replace("msg_reply_to_user_buttom","message");
		message_id=$("#"+message).val();
		$.ajax({ url: web_base+"/products/ajax_add_message/",
	    		dataType:"json",
	    		type:"POST",
	    		context: $("#"+message_list),
	    		data: { 'parent_id': message_id, 'content': text },
	    		success: function(data){
//				$("#"+message_list).html("");
//	    			$("#"+message_list).html(data.message);
//	    			$("#"+textid).val("");
//	    			$("#"+id).parent().css("display","none");
//	    			$("#"+textid).parent().css("display","none");
  			}
  		});
	}
	<?php }?>
});
</script>