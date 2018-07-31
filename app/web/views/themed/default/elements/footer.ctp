<script src="<?php echo $webroot.'plugins/megapix-image.js'; ?>" type="text/javascript"></script>
<?php echo $this->element('org_alert');?>
<div class="bottom_navigations am-hide-sm-down">
<div class="am-topbar am-container" style="margin:0px auto 0;border-bottom:1px solid #ddd;">
  <?php if(isset($navigations['B'])){?>
  <ul class="am-nav am-nav-pills am-topbar-nav" style="margin-left:2%">
    <?php $navigations_t_count=count($navigations['B']);
      foreach($navigations['B'] as $k=>$v){ ?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 ?>
    <li class="am-dropdown am-dropdown-up" data-am-dropdown > 
      <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" >
        <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-up" style="padding-left:4px;"></span>
      </a>
      <ul class="am-dropdown-content" style="margin-bottom:2px">
        <li class="am-dropdown-header"><?php echo $v['NavigationI18n']['name']//echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
          <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
        <?php }  // foreach top2?>
      </ul>
    </li>
	<?php }?>
	<?php if(!isset($v['SubMenu']) ) { ?>
	<li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
    <?php }?>
  <?php } // foreach top1?>
  </ul>
  <?php }?>
</div>
</div>	
<?php if(!empty($links)){?>
<div>
<div class="am-g-fixed am-g am-u-sm-centered" style="width:100%;">
  <ul  style="width:100%;" class="am-gallery am-avg-sm-6  am-avg-md-9 am-avg-lg-12 am-gallery-default" data-am-widget="gallery" data-am-gallery="{ pureview: false }" >
  <?php foreach($links as $k=>$v){?>
    <li  class="am-thumbnail   am-icon-sm" style="border:0;">
	    <?php if($v['LinkI18n']['img01']==""){ ?>
			 	  <div style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"> <?php echo $svshow->link($v['LinkI18n']['name'],$v['LinkI18n']['url'],array('target'=>$v['Link']['target'])); ?></div>
			  <?php  }else{  ?>
		 <?php echo 	$svshow->seo_link(array('type'=>'IMG','class'=>"dd",'url'=>$v['LinkI18n']['url'],'img'=>$v['LinkI18n']['img01'],'name'=>$v['LinkI18n']['name'],'sub_name'=>$v['LinkI18n']['name'],'class'=>'am-img-sx am-img-responsive','target'=>$v['Link']['target']));?>
		  <?php }?>
 </li>
  <?php }?>
  </ul>  
</div>
	</div>
<?php }?>
<!-- footer -->
<footer class="am-footer am-footer-default ">
  <div class="am-footer-miscs am-hide-sm-down">
    <p class="am-text-center">
        ©&nbsp;<?php echo date('Y');?>&nbsp;<?php echo $configs['copyright-display'];?><br class="am-show-sm-only"><?php echo "&nbsp;".$ld['copright'];echo "&nbsp;".Configure::read('HR.version'); ?>
        <?php if(isset($configs['icp_number'])){
    			echo '<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:#999;">'.$configs['icp_number'].'</a>'; 
			}
    	?>
        <?php if(isset($configs['copyright-display-status'])&&$configs['copyright-display-status']==0){?>Powered by <a href="http://www.seevia.cn" target="_blank">SEEVIA</a><?php }?>
        <?php if(isset($configs['page_loading_info'])&&$configs['page_loading_info']==1){?>
	    <?php echo $ld['footprint']?>&nbsp;<?php echo $this->data['memory_useage'];?>KB&nbsp;<?php echo $ld['system_response_time']?>&nbsp;<?php echo round((getMicrotime() - $GLOBALS['TIME_START'])*1000, 4) . "ms"?><?php }?>
    </p>
    <p><?php echo isset($configs['beiangov'])?$configs['beiangov']:''; ?></p>
    <?php echo isset($configs['google-js'])?$configs['google-js']:''; ?>
  </div>
</footer>
<!-- footer end -->
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
  <a href="#top" title=""><i class="am-gotop-icon am-icon-chevron-up"></i></a>
</div>