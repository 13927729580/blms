<?php
/*****************************************************************************
 * SV-Cart 编辑专题
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
//pr($this->data);
?>
<style type="text/css">
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{margin-top: 10px;}
.img_select{max-width:150px;max-height:120px;}

.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
</style>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  style="width: 95%;margin-right: 2.5%;">
		<?php echo $form->create('StaticPage',array('action'=>'/view/'.(isset($this->data['Page']['id'])?$this->data['Page']['id']:''),
		'onsubmit'=>'return pages_check();'));?>
		<!-- 导航 -->
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			<ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
				<li><a href="#content"><?php echo $ld['content']?></a></li>
			</ul>
		</div>
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			<button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
		</div>

			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0"><?php echo $ld['title']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10" style="margin-top:10px;">
			    					<input type="text"  class="page-title" id="page_title_<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][title]" value="<?php echo isset($this->data['PageI18n'][$v['Language']['locale']]['title'])?$this->data['PageI18n'][$v['Language']['locale']]['title']:'';?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-view-label am-text-left" style="font-weight:normal;padding:0;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['subtitle']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-10" style="margin-top:10px;">
					    				<input type="text" class="conf" id="page_subtitle_<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][subtitle]" value="<?php echo isset($this->data['PageI18n'][$v['Language']['locale']])?$this->data['PageI18n'][$v['Language']['locale']]['subtitle']:'';?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-view-label am-text-left" style="font-weight:normal;padding:0;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>

					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0"><?php echo $ld['picture_01']?></label>
						<div class="am-u-lg-9 am-u-md-10 am-u-sm-9" style="margin-bottom:1rem">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-g am-container">
								<div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="margin-top:10px;">
									<div style="position:realtive;min-height:120px;">
									<input type="text" id="upload_img_text_1<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][img01]" value="<?php echo @$this->data['PageI18n'][$v['Language']['locale']]['img01']?>" />
									<input type="button" class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('upload_img_text_1<?php echo $v['Language']['locale']?>')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>
									</div>
								
								
									<div class="img_select" style="position:absolute;right:-129px;top:0px;">
									<?php echo $html->image((isset($this->data['PageI18n'][$v['Language']['locale']]['img01'])&&$this->data['PageI18n'][$v['Language']['locale']]['img01']!="")?$this->data['PageI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_upload_img_text_1'.$v['Language']['locale']))?>
									
								</div>
                                                                            
									
								</div>
							</div>
						 <?php }} ?>
						</div>	
					</div>

                                   <div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['picture_01']?>2</label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9" style="margin-bottom:1rem">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    			<div class="am-g am-container">
			    				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="margin-top:10px;">
			    					<div style="position:realtive;min-height:120px">
			    					<input type="text" id="upload_img_text_2<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][img02]" value="<?php echo @$this->data['PageI18n'][$v['Language']['locale']]['img02']?>" />
			    					<input type="button" class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('upload_img_text_2<?php echo $v['Language']['locale']?>')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
			    				</div>
			    				 		
			    						 <div class="img_select" style="position:absolute;right:-129px;top:0px;">
										<?php echo $html->image((isset($this->data['PageI18n'][$v['Language']['locale']]['img02'])&&$this->data['PageI18n'][$v['Language']['locale']]['img02']!="")?$this->data['PageI18n'][$v['Language']['locale']]['img02']:$configs['shop_default_img'],array('id'=>'show_upload_img_text_2'.$v['Language']['locale']))?>
									</div>
			    				</div> 
			    			</div>
			    			<?php }} ?>
			    			</div>
			    		</div>	
			    		
						<?php if(isset($backend_locales) && sizeof($backend_locales) > 0){
									foreach($backend_locales as $k => $v){?>
						<input id="PageI18n<?php echo $k;?>Locale" name="data[PageI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo @$v['Language']['locale'];?>">
						<?php 		if(isset($this->data['PageI18n'][$v['Language']['locale']])){?>
						<input id="PageI18n<?php echo $k;?>Id" name="data[PageI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo @$this->data['PageI18n'][$v['Language']['locale']]['id'];?>"> 
						<input id="PageI18n<?php echo $k;?>PageId" name="data[PageI18n][<?php echo $k;?>][page_id]" type="hidden" value="<?php echo @$this->data['Page']['id'];?>">
						<?php }	}	}?>
						<input type="hidden" name="data[Page][id]" value="<?php echo isset($this->data['Page']['id'])?$this->data['Page']['id']:'';?>" />
							
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['routeurl']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10" >
			    				<input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" />
			    				<input type="hidden" id="route_url_h" value="0">(<?php echo $ld['routeurl_desc'] ?>)
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['meta_keywords']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10"  >
			    					<input type="text" id="meta_keywords_<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][meta_keywords]" value="<?php echo isset($this->data['PageI18n'][$k]['meta_keywords'])?@$this->data['PageI18n'][$k]['meta_keywords']:'';?>" />
			    				</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-view-label am-text-left " style="font-weight:normal;padding:0;">
										<?php echo $ld[$v['Language']['locale']]?>
									</label>
								<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group" style="margin-top:10px;">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['meta_description']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10"  >
			    					<input type="text" id="meta_description_<?php echo $v['Language']['locale']?>" name="data[PageI18n][<?php echo $k;?>][meta_description]" value="<?php echo isset($this->data['PageI18n'][$k]['meta_description'])?@$this->data['PageI18n'][$k]['meta_description']:'';?>" />
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-view-label am-text-left " style="font-weight:normal;padding:0;">
			    						<?php echo $ld[$v['Language']['locale']]?>
			    					</label>
			    				<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['posted_time']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10">
			    					<input type="text" name="data[Page][showtime]" value="<?php  if(isset($this->data['PageI18n']) && isset($this->data['Page']['showtime']) && $this->data['Page']['showtime']!=0){echo $this->data['Page']['showtime'];} elseif(isset($this->data['Page']) && !empty($this->data['Page'])){echo $this->data['Page']['created'];}?>"/>
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"  style="margin-top:16px;padding-right: 0;"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-6" style="margin-left: 25px;"><div>
			    				<label class="am-radio am-success" style="padding-top:2px;">
			    				<input type="radio" class="radio"  data-am-ucheck value="1" name="data[Page][status]" <?php if(isset($this->data['Page']['status'])&&$this->data['Page']['status'] == 1){echo "checked";}else{echo "checked";} ?>/> <?php echo $ld['yes']?> </label> 
			    				<label class="am-radio am-success" style="padding-top:2px;">
								<input type="radio" class="radio"  data-am-ucheck name="data[Page][status]" value="0" <?php if(isset($this->data['Page']['status'])&&$this->data['Page']['status'] == 0){echo "checked";} ?>/><?php echo $ld['no']?></label>
			    			</div></div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label" style="padding-right: 0;"><?php echo $ld['sort']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-10" style="margin-top:10px;">
			    					<input type="text" class="input_sort" name="data[Page][orderby]" value="<?php echo isset($this->data['Page']['orderby'])?$this->data['Page']['orderby']:50;?>" />
			    				</div>
			    			</div>
			    		</div>
					</div>
				 
					
				</div>
			</div>
			<div id="content" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['content']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"></label>
						<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
		      			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		      				<div class="am-u-lg-10 am-u-sm-9 am-padding-left-0">
								<div class="am-form-group">
									<?php echo $this->element('editor',array('editorName'=>"data[PageI18n][{$k}][content]",'editorId'=>'elm'.$v['Language']['locale'],'editorValue'=>isset($this->data['PageI18n'][$v['Language']['locale']]['content'])?$this->data['PageI18n'][$v['Language']['locale']]['content']:'','editorLang'=>$v['Language']['google_translate_code'])); ?>
								</div>
							</div>
							<div class="am-u-lg-2 am-u-sm-3"><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span></div>
						<?php }}?>
						</div>
						<div class="am-cf"></div>
		      		</div>
		       </div>
		    </div>
		<?php echo $form->end();?>
	</div>	  
</div>
<script type="text/javascript">
document.onmousemove=function(e)
{
 var obj = Utils.srcElement(e);
 if (typeof(obj.onclick) == 'function' && obj.onclick.toString().indexOf('listTable.edit') != -1)
 {
 obj.title = "<?php echo $ld['click_to_edit_content']?>";
 obj.style.cssText = 'background: #21964D;';
 obj.onmouseout = function(e)
 {
 this.style.cssText = '';
 }
 }
 else if (typeof(obj.href) != 'undefined' && obj.href.indexOf('listTable.sort') != -1)
 {
 obj.title = "<?php echo $ld['click_on_sorted_list']?>";
 }
}
 function add_to_seokeyword(obj,keyword_id){

	var keyword_str = GetId(keyword_id).value;
	var keyword_str_arr = keyword_str.split(",");
	for( var i=0;i<keyword_str_arr.length;i++ ){
		if(keyword_str_arr[i]==obj.value){
			return false;
		}
	}
	if(keyword_str!=""){
		GetId(keyword_id).value+= ","+obj.value;
	}else{
		GetId(keyword_id).value+= obj.value;
	}
}
function pages_check(){
	var page_title_obj = document.getElementById("page_title_"+backend_locale);
	var pagetitle = document.getElementsByClassName('page-title');
	var conf = document.getElementsByClassName('conf');
	if(pagetitle[0].value=="" || pagetitle[1].value==""){
		alert("<?php echo $ld['enter_subject_name']?>");
		return false;
	}
	if(conf[0].value=="" || conf[1].value==""){
		alert("请输入副标题名称！");
		return false;
	}
	return true;
}
function checkrouteurl(){
	var route_url = document.getElementById("Route_url").value;
	if(route_url!=""){
		YUI().use("io",function(Y) {
		var rUrl = admin_webroot+"routes/select_route_url/";//访问的URL地址
		var rfg = {
			method: "POST",
			data:"route_url="+route_url
		};
		var request = Y.io(rUrl, rfg);//开始请求
		var newhtml = "";
		var handleSuccess = function(ioId, o){
			try{
				eval('var result='+o.responseText);
			}catch(e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
			if(result.type==1){
				alert(result.message);
				document.getElementById("route_url_h").value=1;
			}else{
				document.getElementById("route_url_h").value=0;
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
		});
	}
}

</script>
