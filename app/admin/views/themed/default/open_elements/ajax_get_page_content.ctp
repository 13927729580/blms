<div class="am-container">
  <?php if($this->params['controller']=="static_pages" && $this->params['action']="view" && $this->params['url']['url']=="/"){}else{echo $this->element("ur_here");}?>
  <div class="static_pages auto_zoom">
	<?php if(isset($page) && (!empty($page['PageI18n']['img01']) || !empty($page['PageI18n']['img02']) || !empty($page['PageI18n']['content']))){?>
	<?php if(!empty($page['PageI18n']['img01'])){
			 echo $html->image($page['PageI18n']['img01']); 
		      } if(!empty($page['PageI18n']['img02'])){
		     	echo $html->image($page['PageI18n']['img02']); 
		     } if(!empty($page['PageI18n']['content'])){echo $page['PageI18n']['content'];} ?>
	<?php }else{echo "<div class='not_exist'>".$ld['no_content']."</div>";}?>
  </div>
</div>