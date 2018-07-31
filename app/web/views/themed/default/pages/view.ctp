<div class="am-container add_comment">
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
<style type='text/css'>
.static_pages h1,.static_pages h2,.static_pages h3,.static_pages h4,.static_pages h5{margin:1rem auto;margin-top:3rem;}
.static_pages ol, .static_pages ul{margin: 0 0 1.6rem;padding-left: 2em;}
.static_pages li{list-style: inherit;list-style-type:inherit;}
</style>
<?php if(isset($configs['enable_page_comment'])&&$configs['enable_page_comment']=='1'){ ?><div id="page_comment"></div><?php } ?>
<input type="hidden" id="page_id" value="<?php echo $page['Page']['id'] ?>">
<script type="text/javascript">
var wechat_shareTitle="<?php echo $page['PageI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($page['PageI18n']['content']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if(trim($page['PageI18n']['img01'])!=""&&$svshow->imgfilehave($server_host.(str_replace($server_host,'',$page['PageI18n']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$page['PageI18n']['img01'])); ?>";
<?php } ?>

var page_id = $('#page_id').val();
page_comment();
function page_comment(){
	if(!document.getElementById('page_comment'))return;
	$.ajax({ 
		url: web_base+"/pages/page_comment/"+page_id,
		dataType:"html",
		type:"POST",
		success: function(data){
			$('#page_comment').html(data);
	    }
	});
}
</script>