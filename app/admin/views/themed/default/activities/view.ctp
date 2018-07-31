<style type='text/css'>
.am-view-label{margin-top:0px;}
.am-form-group{margin-bottom:1.5rem;}
.am-form-horizontal .am-radio{display:inline;padding-top:0px;margin-right:2rem;}
.scrollspy-nav {
    top: 0;
    z-index: 100;
    background: #5eb95e;
    width: 100%;
    padding: 0 10px;
  }

  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
    color: #eee;
    padding: 10px 20px;
    display: inline-block;
  }

  .scrollspy-nav a.am-active {
    color: #fff;
    font-weight: bold;
  }
  .crumbs{
  	padding-left:0;
  	margin-bottom:22px;
  }
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g" style="margin:0;">
	<!-- 导航条 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']; ?></a></li>
		   	<?php if(isset($activity_info['Activity'])&&!empty($activity_info['Activity'])){ ?>
		   	<li><a href="#activity_config"><?php echo $ld['configration']; ?></a></li>
		   	<li class='am-hide'><a href="#activity_tag"><?php echo $ld['label_01']; ?></a></li>
		   	<?php } ?>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width:100%;">
		<?php echo $form->create('Activity',array('action'=>'/view/'.(isset($activity_info['Activity']['id'])?$activity_info['Activity']['id']:''),'onsubmit'=>"return check_Activity();"));?>
		<!-- 按钮 -->
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['d_reset']?></button>
		</div> 
		<div id="basic_information"  class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title"><?php echo $ld['basic_information']; ?></h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
		    		<input type='hidden' name="data[Activity][id]" value="<?php echo isset($activity_info['Activity'])?$activity_info['Activity']['id']:0; ?>" />
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['type']; ?></label>
			    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
			    				<select name="data[Activity][type]" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}">
			    					<option value=""><?php echo $ld['please_select']; ?></option>
			    					<option value="P" <?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['type']=='P'?'selected':''; ?>><?php echo $ld['product']; ?></option>
			    					<option value="A" <?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['type']=='A'?'selected':''; ?>><?php echo $ld['article']; ?></option>
			    					<option value="T" <?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['type']=='T'?'selected':''; ?>><?php echo $ld['topics']; ?></option>
			    					<option value="PC" <?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['type']=='PC'?'selected':''; ?>><?php echo $ld['product_categories']; ?></option>
			    					<option value="AC" <?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['type']=='AC'?'selected':''; ?>><?php echo $ld['article_categories']; ?></option>
			    				</select>
			    			</div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">&nbsp;</label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6" style="padding-right:0;">
			    				<input type='text' value='' placeholder="<?php echo $ld['keyword']; ?>" />
			    			</div>
			    			<div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-text-right">
			    				<button type="button" class="am-btn am-btn-success am-btn-xs am-radius" onclick="activity_keyword_search(this)"><?php echo $ld['search']?></button>
			    			</div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">&nbsp;</label>
			    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
				    			<select name="data[Activity][type_id]" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>',maxHeight:300}">
				    				<option value="0"><?php echo $ld['please_select']; ?></option>
				    				<?php	if(isset($article_info['Article'])){ ?>
				    				<option value="<?php echo $article_info['Article']['id']; ?>" selected><?php echo trim($article_info['ArticleI18n']['title'])==''?'-':$article_info['ArticleI18n']['title']; ?></option>
				    				<?php	} ?>
				    				<?php	if(isset($product_info['Product'])){ ?>
				    				<option value="<?php echo $product_info['Product']['id']; ?>" selected><?php echo trim($product_info['ProductI18n']['name'])==''?'-':$product_info['ProductI18n']['name']; ?></option>
				    				<?php	} ?>
				    				<?php	if(isset($topic_info['Product'])){ ?>
				    				<option value="<?php echo $topic_info['Topic']['id']; ?>" selected><?php echo trim($topic_info['TopicI18n']['title'])==''?'-':$topic_info['TopicI18n']['title']; ?></option>
				    				<?php	} ?>
				    				<?php	if(isset($category_info['CategoryArticle'])){ ?>
				    				<option value="<?php echo $category_info['CategoryArticle']['id']; ?>" selected><?php echo trim($category_info['CategoryArticleI18n']['name'])==''?'-':$category_info['CategoryArticleI18n']['name']; ?></option>
				    				<?php	} ?>
				    				<?php	if(isset($category_info['CategoryProduct'])){ ?>
				    				<option value="<?php echo $category_info['CategoryProduct']['id']; ?>" selected><?php echo trim($category_info['CategoryProductI18n']['name'])==''?'-':$category_info['CategoryProductI18n']['name']; ?></option>
				    				<?php	} ?>
				    			</select>
				    		</div>
			    		</div>
			    		<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">渠道</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<select name="data[Activity][channel]" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" onchange="activity_channel_change(this)">
								<option value="-1"><?php echo $ld['please_select']; ?></option>
								<option value="0" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='0'?'selected':''; ?>>线上</option>
								<option value="1" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='1'?'selected':''; ?>>线下</option>
							</select>
						</div>
						<span class="am-u-lg-1" style="color:red;display:block;margin-top:0.5rem;"><em>*</em></span>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['address']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type='text' value="<?php echo isset($activity_info['Activity'])?$activity_info['Activity']['address']:''; ?>" name="data[Activity][address]" />
						</div>
						<span class="am-u-lg-1" style="color:red;display:block;margin-top:0.5rem;"><em>*</em></span>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['date']?></label>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-4">
							<input type='text' id="date_1" value="<?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['start_date']!='0000-00-00 00:00:00'?date('Y-m-d',strtotime($activity_info['Activity']['start_date'])):''; ?>" name="data[Activity][start_date]" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						</div>
						<label class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-form-label am-text-center" style="width:7.5%;">-</label>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-4">
							<input type='text' id="date_2" value="<?php echo isset($activity_info['Activity'])&&$activity_info['Activity']['end_date']!='0000-00-00 00:00:00'?date('Y-m-d',strtotime($activity_info['Activity']['end_date'])):''; ?>" name="data[Activity][end_date]" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						</div>
				    		<span class="am-u-sm-1" style="color:red;display:block;margin-top:0.5rem;"><em>*</em></span>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['title']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type='text' value="<?php echo isset($activity_info['Activity'])?$activity_info['Activity']['name']:''; ?>" name="data[Activity][name]" />
						</div>
						<span class="am-u-lg-1" style="color:red;display:block;margin-top:0.5rem;"><em>*</em></span>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['picture']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" id="activity_image" name="data[Activity][image]" value="<?php echo isset($activity_info['Activity'])?$activity_info['Activity']['image']:''; ?>" />
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius am-margin-top-xs" onclick="select_img('activity_image')" value="<?php echo $ld['choose_picture']?>"/>
							<div class="img_select am-margin-top-xs am-margin-bottom-xs">
								<?php echo $html->image((isset($activity_info['Activity']['image'])&&$activity_info['Activity']['image']!="")?$activity_info['Activity']['image']:$configs['shop_default_img'],array('id'=>'show_activity_image'))?>
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['description']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<textarea name="data[Activity][description]" id="activity_description"><?php echo isset($activity_info['Activity'])?$activity_info['Activity']['description']:''; ?></textarea>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['price']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type='text' value="<?php echo isset($activity_info['Activity'])?$activity_info['Activity']['price']:'0'; ?>" name="data[Activity][price]" />
						</div>
					</div>
	    		    		<div class="am-form-group">
	    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label">主办方</label>
	    		    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	    		    				<input type='hidden' name="data[Activity][publisher_type]" value="S"  />
	    		    				<input type='hidden' name="data[ActivityPublisher][id]" value="<?php echo isset($activity_publisher_detail['ActivityPublisher'])?$activity_publisher_detail['ActivityPublisher']['id']:'0'; ?>"  />
	    		    				<input type='text' name="data[ActivityPublisher][name]" value="<?php echo isset($activity_publisher_detail['ActivityPublisher']['name'])?$activity_publisher_detail['ActivityPublisher']['name']:''; ?>"  />
	    		    			</div>
	    		    		</div>
	    		    		<div class="am-form-group">
	    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label">主办方Logo</label>
	    		    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	    		    				<input type="text" id="activity_publisher_image" name="data[ActivityPublisher][logo]" value="<?php echo isset($activity_publisher_detail['ActivityPublisher']['logo'])?$activity_publisher_detail['ActivityPublisher']['logo']:''; ?>" />
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius am-margin-top-xs" onclick="select_img('activity_publisher_image')" value="<?php echo $ld['choose_picture']?>"/>
							<div class="img_select am-margin-top-xs am-margin-bottom-xs">
								<?php echo $html->image((isset($activity_publisher_detail['ActivityPublisher']['logo'])&&$activity_publisher_detail['ActivityPublisher']['logo']!="")?$activity_publisher_detail['ActivityPublisher']['logo']:$configs['shop_default_img'],array('id'=>'show_activity_publisher_image')); ?>
							</div>
	    		    			</div>
	    		    		</div>
	    		    		<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">主办方<?php echo $ld['description']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<textarea name="data[ActivityPublisher][description]" id="activity_publisher_description"><?php echo isset($activity_publisher_detail['ActivityPublisher'])?$activity_publisher_detail['ActivityPublisher']['description']:''; ?></textarea>
						</div>
					</div>
					<div class="am-form-group">
	    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"><?php echo "SEO".$ld['attribute']?></label>
	    		    			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	    		    				<input type='text' name="data[Activity][seo_attribute]" value='<?php echo isset($activity_info['Activity']['seo_attribute'])?preg_replace('/\'/','&#39;',preg_replace('/\"/','&#34;',$activity_info['Activity']['seo_attribute'])):''; ?>' maxlength='100' />
	    		    			</div>
	    		    		</div>
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-6">
			    				<label class="am-radio am-success">
			    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[Activity][status]" <?php if(isset($activity_info['Activity']['status'])&&$activity_info['Activity']['status'] == 1||!isset($activity_info['Activity'])){echo "checked";} ?>/> <?php echo $ld['yes']?> </label> 
			    				<label class="am-radio am-success">
								<input type="radio" class="radio"  data-am-ucheck name="data[Activity][status]" value="0" <?php if(isset($activity_info['Activity']['status'])&&$activity_info['Activity']['status'] == 0){echo "checked";} ?>/><?php echo $ld['no']?></label>
			    			</div>
			    		</div>
				</div>
			</div>
		</div>
		<?php if(isset($activity_info['Activity'])&&!empty($activity_info['Activity'])){ ?>
	      <div id="activity_config"  class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title"><?php echo $ld['configration']; ?></h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
		    		<div class='am-panel-bd'>
			    		<div class='am-text-right'>
						<a class="am-btn am-btn-warning am-btn-xs am-radius" href="javascript:void(0);" onclick="ajax_activity_config_detail(0)"><span class="am-icon-plus"></span> <?php echo $ld['add']; ?></a>
			    		</div>
		    			<div id='activity_config_list'></div>
		    		</div>
		    </div>
	    	</div>
	    	<div id="activity_tag"  class="am-panel am-panel-default am-hide">
			<div class="am-panel-hd">
				<h4 class="am-panel-title"><?php echo $ld['label_01']; ?></h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
		    		<div class='am-panel-bd'>
		    			<div class='am-text-right'>
						<a class="am-btn am-btn-warning am-btn-xs am-radius" href="javascript:void(0);" onclick="ajax_activity_modify(0)"><span class="am-icon-plus"></span> <?php echo $ld['add']; ?></a>
			    		</div>
			  		<div id="activity_tag_list"></div>
		    		</div>
		    	</div>
	    	</div>
	    	<?php } ?>
		<?php echo $form->end();?>
	</div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="activity_config_detail">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $ld['configration']; ?>
      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		
    </div>
  </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="activity_tag_detail">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $ld['label_01']; ?>
      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    	<form class='am-form am-form-horizontal'>
    		<div class="am-form-group">
			<div class="am-input-group">
				<input type="text" class="am-form-field" name="data[ActivityTag][tag_name]" >
				<span class="am-input-group-btn">
					<button class="am-btn am-btn-default" type="button"><?php echo $ld['save']; ?></button>
				</span>
			</div>
    		</div>
    	</form>
    </div>
  </div>
</div>


<?php
	$google_translate_code='';
	foreach($backend_locales as $v){
		if($v['Language']['locale']==$backend_locale){
			$google_translate_code=$v['Language']['google_translate_code'];
			break;
		}
	}
?>
<script type="text/javascript">
var editor;
KindEditor.ready(function(K) {
    editor = K.create('#activity_description', {
            width:'100%',
            items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
            langType : '<?php echo $google_translate_code; ?>',filterMode : false
        }
    );
    K.create('#activity_publisher_description', {
            width:'100%',
            items:['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy',
                'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter','justifyright', 'justifyfull',
                'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat',
                'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','table',
                'hr', 'emoticons', 'baidumap', 'pagebreak','link', 'unlink', '|', 'about'],
            langType : '<?php echo $google_translate_code; ?>',filterMode : false
        }
    );
});

function activity_keyword_search(btn){
	var Activity_Type=$("select[name='data[Activity][type]']").val();
	var Activity_Type_Keyword=$(btn).parents('div.am-form-group').find("input[type='text']").val().trim();
	if(Activity_Type==''){alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['type']); ?>");return false;}
	if(Activity_Type_Keyword!=""||Activity_Type=='AC'||Activity_Type=='PC'){
		var SearchResult=[];
		if(Activity_Type=="P"){
			$.ajax({
				url:admin_webroot+"products/searchProducts/",
				type:"POST",
				data:{'product_keyword':Activity_Type_Keyword},
				dataType:"json",
				success:function(data){
					if(data.flag=='1'){
						$(data.content).each(function(index,item){
							SearchResult.push({'value':item['Product']['id'],'text':item['ProductI18n']['name']});
						});
					}
					BindTypeSearch(SearchResult);
				}
			});
		}else if(Activity_Type=='A'){
			$.ajax({
				url:admin_webroot+"articles/ajax_article_search/",
				type:"POST",
				data:{'article_keyword':Activity_Type_Keyword},
				dataType:"json",
				success:function(data){
					if(data.flag=='1'){
						$(data.content).each(function(index,item){
							SearchResult.push({'value':item['Article']['id'],'text':item['ArticleI18n']['title']});
						});
					}
					BindTypeSearch(SearchResult);
				}
			});
		}else if(Activity_Type=='T'){
			$.ajax({
				url:admin_webroot+"topics/ajax_topic_search/",
				type:"POST",
				data:{'topic_keyword':Activity_Type_Keyword},
				dataType:"json",
				success:function(data){
					if(data.flag=='1'){
						$(data.content).each(function(index,item){
							SearchResult.push({'value':item['Topic']['id'],'text':item['TopicI18n']['title']});
						});
					}
					BindTypeSearch(SearchResult);
				}
			});
		}else if(Activity_Type=='AC'){
			$.ajax({
				url:admin_webroot+"article_categories/ajax_category_search/",
				type:"POST",
				data:{'category_keyword':Activity_Type_Keyword},
				dataType:"json",
				success:function(data){
					if(data.flag=='1'){
						$(data.content).each(function(index,item){
							SearchResult.push({'value':item['CategoryArticle']['id'],'text':item['CategoryArticleI18n']['name']});
							if(typeof(item['SubCategory'])!='undefined'){
								$(item['SubCategory']).each(function(index2,item2){
									SearchResult.push({'value':item2['CategoryArticle']['id'],'text':"|--"+item2['CategoryArticleI18n']['name']});
									if(typeof(item2['SubCategory'])!='undefined'){
										$(item2['SubCategory']).each(function(index3,item3){
											SearchResult.push({'value':item3['CategoryArticle']['id'],'text':"|----"+item3['CategoryArticleI18n']['name']});
										});
									}
								});
							}
						});
					}
					BindTypeSearch(SearchResult);
				}
			});
		}else if(Activity_Type=='PC'){
			$.ajax({
				url:admin_webroot+"product_categories/ajax_category_search/",
				type:"POST",
				data:{'category_keyword':Activity_Type_Keyword},
				dataType:"json",
				success:function(data){
					if(data.flag=='1'){
						$(data.content).each(function(index,item){
							SearchResult.push({'value':item['CategoryProduct']['id'],'text':item['CategoryProductI18n']['name']});
							if(typeof(item['SubCategory'])!='undefined'){
								$(item['SubCategory']).each(function(index2,item2){
									SearchResult.push({'value':item2['CategoryProduct']['id'],'text':"|--"+item2['CategoryProductI18n']['name']});
									if(typeof(item2['SubCategory'])!='undefined'){
										$(item2['SubCategory']).each(function(index3,item3){
											SearchResult.push({'value':item3['CategoryProduct']['id'],'text':"|----"+item3['CategoryProductI18n']['name']});
										});
									}
								});
							}
						});
					}
					BindTypeSearch(SearchResult);
				}
			});
		}
	}
}

function BindTypeSearch(SearchResult){
	$("select[name='data[Activity][type_id]'] option[value!='0']").remove();
	$(SearchResult).each(function(index,item){
		$("select[name='data[Activity][type_id]']").append("<option value='"+item['value']+"'>"+item['text']+"</option>");
	});
	$("select[name='data[Activity][type_id]']").trigger('changed.selected.amui');
}

function check_Activity(){
	var Activity_name=$("input[type='text'][name='data[Activity][name]']").val().trim();
	if(Activity_name==''){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['title']); ?>");
		return false;
	}
	var Activity_channel=$("select[name='data[Activity][channel]']").val();
	if(Activity_channel=='-1'){
		alert("请选择渠道");
		return false;
	}else if(Activity_channel=='1'){
		var Activity_address=$("input[type='text'][name='data[Activity][address]']").val().trim();
		if(Activity_address==''){
			alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['address']); ?>");
			return false;
		}
	}
	var date1 = document.getElementById("date_1").value;
	var date2 = document.getElementById("date_2").value;
	if(date1==''||date2==''){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['date']); ?>");
		return false;
	}
	date1 = date1.replace(/\-/gi,"/");
	date2 = date2.replace(/\-/gi,"/");
	var time1 = new Date(date1).getTime();
	var time2 = new Date(date2).getTime();
	if(time1 > time2){
		alert('日期开始时间不能早于结束时间');
		return false;
	}
	return true;
}

ajax_activity_config();
function ajax_activity_config(){
	if(!document.getElementById('activity_config_list'))return;
	var activity_id=$("input[type='hidden'][name='data[Activity][id]']").val();
	$.ajax({
		url:admin_webroot+"activities/ajax_activity_config/"+activity_id,
		type:"GET",
		data:{},
		dataType:"html",
		success:function(result){
			$('#activity_config_list').html(result);
		}
	});
}

function ajax_activity_config_detail(activity_config_id){
	var activity_id=$("input[type='hidden'][name='data[Activity][id]']").val();
	$.ajax({
		url:admin_webroot+"activities/ajax_activity_config_detail/"+activity_config_id,
		type:"GET",
		data:{'activity_id':activity_id},
		dataType:"html",
		success:function(result){
			$('#activity_config_detail .am-modal-bd').html(result);
			$('#activity_config_detail .am-modal-bd input[type="radio"]').uCheck();
			$('#activity_config_detail').modal('open');
		}
	});
}

function ajax_activity_config_detail_submit(btn){
	var postForm=$(btn).parents('form');
	var config_name=$(postForm).find("input[type='text']").val();
	var config_type=$(postForm).find("select").val();
	if(config_name==''){
		alert('名称不能为空');
		return;
	}
	if(config_type==''){
		alert('请选择类型');
		return;
	}
	var activity_id=$("input[type='hidden'][name='data[Activity][id]']").val();
	$(btn).button('loading');
	$.ajax({
		url:admin_webroot+"activities/ajax_activity_config_detail/",
		type:"POST",
		data:postForm.serialize(),
		dataType:"JSON",
		success:function(result){
			$(btn).button('reset');
			alert(result.message);
			if(result.code=='1'){
				$('#activity_config_detail').modal('close');
				ajax_activity_config();
			}
		}
	});
}

function ajax_activity_config_remove(activity_config_id){
	if(confirm(js_confirm_deletion)){
		$.ajax({
			url:admin_webroot+"activities/ajax_activity_config_remove",
			type:"POST",
			data:{'activity_config_id':activity_config_id},
			dataType:"JSON",
			success:function(result){
				alert(result.message);
				if(result.code=='1'){
					ajax_activity_config();
				}
			}
		});
	}
}

function activity_channel_change(select){
	var activity_channel=select.value;
	if(activity_channel=='0'){
		$("input[name='data[Activity][address]']").val('');
		$("input[name='data[Activity][address]']").parents("div.am-form-group").hide();
	}else{
		$("input[name='data[Activity][address]']").parents("div.am-form-group").show();
	}
}

//ajax_activity_tag();
function ajax_activity_tag(){
	if(!document.getElementById('activity_tag_list'))return;
	var activity_id=$("input[type='hidden'][name='data[Activity][id]']").val();
	$.ajax({
		url:admin_webroot+"activities/ajax_activity_tag/"+activity_id,
		type:"GET",
		data:{},
		dataType:"html",
		success:function(result){
			$('#activity_tag_list').html(result);
		}
	});
}

function ajax_activity_modify(activity_tag_id){
	var activity_id=$("input[type='hidden'][name='data[Activity][id]']").val();
	if(typeof(activity_tag_id)=='undefined')activity_tag_id=0;
	if(activity_tag_id==0){
		
	}else{
		
	}
}
</script>