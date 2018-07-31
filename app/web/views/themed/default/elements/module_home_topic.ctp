<?php 
//pr($sm);
if($code_infos[$sk]['type']=="module_home_topic"){ ?>
<style>
.am-panel-default>.am-panel-hd{background:#fff;}
.detail-h3{height:160px;}
</style>
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default"  style="margin:0 0;padding-top:50px;" >
	<div class="am-panel-hd my-head"style="margin:0;padding:.6rem 40px;font-weight:600;"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd" style="margin:0;padding:0">
	  <div >
	  <?php foreach($sm as $k=>$t){?>
	  <div class="am-u-lg-3 am-u-md-3 am-u-sm-6 amaze_home_center2" style="margin-top:25px;padding:0 40px;" >
		<div class="detail-h3 am-thumbnails"style="margin-bottom:0px;">
		  <a title="<?php echo $t['TopicI18n']['title'];?>" href="<?php echo $html->url('/topics/'.$t['Topic']['id']);?>">
		  <img class="am-thumbnail" src="<?php echo $t['TopicI18n']['img01']!=''?$t['TopicI18n']['img01']:'/theme/default/images/default.png'?>" style="max-width:160px;max-height:160px;"/>
		  </a>
		</div>
		<p class="detail-p" style="margin-top:0px;">
		  <?php echo $svshow->link($t['TopicI18n']['title'],'/topics/'.$t['Topic']['id'],array('title'=>$t['TopicI18n']['title']));?>
		</p>
	  </div>
	  <?php }?>
	  </div>
	  <div class="am-topic"></div>
	</div>
  </div>
</div>
<?php }?>

<style type="text/css">
.detail-p{}
</style>
