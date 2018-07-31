<div class="am-u-md-12">
  <h2 class="topic_info_title"><?php// echo $code_infos[$sk]['name'];?></h2> 
  <?php if($code_infos[$sk]['type']=="module_topic_info"){ ?>
	<?php if(!empty($sm['Topic'])){ ?>
	<div id="top">
	  <div class="am-u-lg-3 am-u-md-3 am-u-sm-12">
	    <?php echo $html->image($sm['TopicI18n']['img01'],array('class'=>'am-img-responsive','style'=>''));  ?>
	  </div>

	  <div class="am-u-lg-6 am-u-md-6 am-u-sm-12"><?php echo $sm['TopicI18n']['title'] ?></div>		
	  <div class="am-u-lg-3 am-u-md-3 am-u-sm-12">
	         <time>
	  	  <?php echo $ld["time"]; ?>:&nbsp;<?php echo date("Y/m/d",strtotime($sm["Topic"]["start_time"])); ?>-<?php echo date("Y/m/d",strtotime($sm["Topic"]["end_time"])); ?>
		</time>
	  </div>
	</div>
	<!-- 专题详情内容 -->
	<div class="topiccontent" style="clear:both">
	  <h2 class="topic_detail"></h2>
	  <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 auto_zoom">
		<?php echo $sm["TopicI18n"]["intro"]; ?>
	  </div>
	</div>
	<!-- 专题详情内容 -->
  <?php }}?>
  <div style="clear: both;"></div>
  <?php if(isset($configs['enable_topic_comment'])&&$configs['enable_topic_comment']=='1'){ ?><div id="topic_comment" class="am-cf"></div><?php } ?>
</div>
<input type="hidden" id="topic_id" value="<?php echo($sm['Topic']['id']); ?>">
<!-- <?php pr($sm['Topic']['id']); ?> -->
<script type="text/javascript">
var wechat_shareTitle="<?php echo $sm['TopicI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['TopicI18n']['intro']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if(trim($sm['TopicI18n']['img01'])!=""&&$svshow->imgfilehave($server_host.(str_replace($server_host,'',$sm['TopicI18n']['img01'])))){  ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$sm['TopicI18n']['img01'])); ?>";
<?php } ?>

var topic_id = $('#topic_id').val();
topic_comment();
function topic_comment(){
	if(!document.getElementById('topic_comment'))return;
	$.ajax({ 
		url: web_base+"/topics/topic_comment/"+topic_id,
		dataType:"html",
		type:"POST",
		success: function(data){
			$('#topic_comment').html(data);
	    }
	});
}
</script>

