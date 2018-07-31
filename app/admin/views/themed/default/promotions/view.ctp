<?php
/*****************************************************************************
 * SV-Cart 编辑促销活动
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
?>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style>
label{font-weight:normal;}
 
.btnouter{}	

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
<div> 
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
		<?php echo $form->create('Promotion',array('action'=>'view/'.(isset($this->data['Promotion']['id'])?$this->data['Promotion']['id']:''),'onsubmit'=>'return promotions_check();'));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#edit_promotional_activities"><?php echo $ld['edit_promotional_activities']?></a></li>
		    		<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button type="submit"class="am-btn am-btn-success am-btn-sm am-radius" value="" style="margin-right: 0;"><?php echo $ld['d_submit'];?></button>
				<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<input type="hidden" name="data[Promotion][id]" value="<?php echo isset($this->data['Promotion']['id'])?$this->data['Promotion']['id']:'';?>">
			<div id="edit_promotional_activities" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['edit_promotional_activities'] ?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['promotion_name']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" id="promotion_title_<?php echo $v['Language']['locale']?>" name="data[PromotionI18n][<?php echo $k?>][title]" <?php if(isset($this->data['PromotionI18n'][$v['Language']['locale']])){?>value="<?php echo $this->data['PromotionI18n'][$v['Language']['locale']]['title'];?>"<?php }else{?>value=""<?php }?>/>
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
		    						<label class="am-u-lg-1 am-u-md-3 am-u-sm-3" style="padding-top:18px;padding-left: 0;"><?php echo $ld[$v['Language']['locale']]?>
		    							<em style="color:red;">*</em></label>
		    					<?php }?>
		    					<?php }}?>		
			    			</div>
						</div>
		      			<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['meta_description']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" id="promotion_meta_description_<?php echo $v['Language']['locale']?>" name="data[PromotionI18n][<?php echo $k?>][meta_description]" <?php if(isset($this->data['PromotionI18n'][$v['Language']['locale']])){?>value="<?php echo $this->data['PromotionI18n'][$v['Language']['locale']]['meta_description'];?>"<?php }else{?>value=""<?php }?>/>
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
		    						<label class="am-u-lg-1 am-u-md-3 am-u-sm-3" style="padding-top:15px;padding-left: 0;"><?php echo $ld[$v['Language']['locale']]?>
		    							<em style="color:red;">*</em></label>
		    					<?php }?>
		    					<?php }}?>
			    			</div>
						</div>
		      			<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" ></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
										<input id="PromotionI18n<?php echo $k;?>Locale" name="data[PromotionI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
									<?php if(isset($this->data['PromotionI18n'][$v['Language']['locale']])){?>
										<input id="PromotionI18n<?php echo $k;?>Id" name="data[PromotionI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($this->data['PromotionI18n'][$v['Language']['locale']]['id'])){echo $this->data['PromotionI18n'][$v['Language']['locale']]['id'];}?>">
									<?php }?>
										<input id="PromotionI18n<?php echo $k;?>PromotionId" name="data[PromotionI18n][<?php echo $k;?>][promotion_id]" type="hidden" value="<?php echo isset($this->data['Promotion']['id'])?$this->data['Promotion']['id']:'';?>">
									<?php }}?>
									<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
										<input type="hidden" name="data[PromotionI18n][<?php echo $k?>][locale]" value="<?php echo @$v['Language']['locale']?>" />
									<?php }}?>
			    				</div>
			    			</div>
						</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:4px;"><?php echo $ld['search_events_product']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="product_keyword" id="product_keyword" /> 
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
			    					<input type="button" value="<?php echo $ld['search']?>" class="am-btn am-btn-success am-btn-sm am-radius"  onclick="searchProducts2('product_select');" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:4px;"><?php echo $ld['search_results']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select data-am-selected="{maxHeight: 150}" name="product_select" id="product_select">
										<option value="">请选择</option>
			    					</select>
						    	</div>
						    	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
									<input type="button" value="+"  class="am-btn am-btn-success am-btn-sm am-radius" name="" onclick="special_preferences2()" />
								</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:10px;"><?php echo $ld['events_products']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<div id="special_preference2">
										<?php if( isset($PromotionProduct2) && sizeof($PromotionProduct2)>0){foreach( $PromotionProduct2 as $k=>$v ){?>
											<div>
												<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
													<a href="javascript:;" name="1" onclick="removeImg2(this)" style="text-decoration:none;">[-]</a>
													<input class="checkbox" style="display:none" type='checkbox' name='specialpreferences2[]' value="<?php echo $v['PromotionActivityProduct']['product_id']?>" checked>
													<?php echo isset($v['PromotionActivityProduct']['name'])?$v['PromotionActivityProduct']['name']:'';?>
												</div>
												<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="margin-bottom:10px;">
													<input type='text' name=prices2[] style="border:1px solid #649776" value='<?php echo $v["PromotionActivityProduct"]["price"]?>' />
												</div>
											</div>
										<?php }}?>
									</div>
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:2px;"><?php echo $ld['amount_lower']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Promotion][min_amount]" value="<?php echo isset($this->data['Promotion']['min_amount'])?$this->data['Promotion']['min_amount']:'';?>" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['amount_limit']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    						<input type="text" name="data[Promotion][max_amount]"   value="<?php echo isset($this->data['Promotion']['max_amount'])?$this->data['Promotion']['max_amount']:'';?>" />
		    						<?php echo $ld['means_no_ilmit']?>
		    					</div>
			    			</div>
			    		</div>
						<?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"><?php echo $ld['point_multiple']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Promotion][point_multiples]" style="width:120px;" value="<?php echo isset($this->data['Promotion']['point_multiples'])?$this->data['Promotion']['point_multiples']:'';?>"/>
			    				</div>
			    			</div>
			    		</div>
			    		<?php }?>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['promotion_start_date']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    						<input id="promotion_start_time" type="text" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="data[Promotion][start_time]" value="<?php echo isset($this->data['Promotion']['start_time'])?$this->data['Promotion']['start_time']:'';?>" readonly/>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em style="color:red;position:relative;top:20px;left: -18px;">*</em></label>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['promotion_end_date']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    						<input id="promotion_end_time" type="text" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="data[Promotion][end_time]" value="<?php echo isset($this->data['Promotion']['end_time'])?$this->data['Promotion']['end_time']:'';?>" readonly/>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em style="color:red;position:relative;top:20px;left: -18px;">*</em></label>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['concession']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select data-am-selected name="data[Promotion][type]" onchange="pagesizeq(this.options[this.options.selectedIndex].value)">
										<option value="2" <?php if(isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] == 2 ){ echo "selected"; } ?> ><?php echo $ld['specials']?></option>
										<option value="0" <?php if(isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] == 0 ){ echo "selected"; } ?> ><?php echo $ld['relief']?></option>
										<option value="1" <?php if(isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] == 1 ){ echo "selected"; } ?> ><?php echo $ld['discount']?></option>
									</select>
									
			    				</div>
			    				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 promotion_type_text" style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] == 2)?'display:none;':'display:block;' ?>">
			    					<input type="text"  name="data[Promotion][type_ext]" value="<?php echo isset($this->data['Promotion']['type_ext'])?$this->data['Promotion']['type_ext']:'';?>" />
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 promotion_type_text" style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] == 2)?'display:none;':'display:block;' ?>">
			    					<span id="helps"><?php echo $html->image('/admin/skins/default/img/help_icon.gif',array("onclick"=>"help_show_or_hide('help_text')"))?></span>
				    			</div>
			    			</div>
			    		</div>
			    		<div id="help_text" style="display:none;" class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3"></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php echo $ld['concessions_means_gift_maximum_number']?>
			    				</div>
			    			</div>
			    		</div>
						<div id="show_hide" class="am-form-group promotion_type_change" style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] != 2)?'display:none;':'display:block;' ?>">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" ><?php echo $ld['search_and_add_gifts']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="keywords" id="keywords" />
			    					<input type="hidden" name="brand_id" id="brand_id">
			    					<input type="hidden" name="products_id" id="products_id" value="0" />
			    					<input type="hidden" name="category_id" id="category_id">
			    				</div>
			    				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
		    						<input type="button" value="<?php echo $ld['search']?>" class="am-btn am-btn-success am-btn-sm am-radius"  onclick="searchProducts2('source_select2');" />
							</div>
			    			</div>
			    		</div>	
					<div class="am-form-group promotion_type_change"  style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] != 2)?'display:none;':'display:block;' ?>">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['search_results']?></label>
			    			<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select data-am-selected name="source_select2" id="source_select2">
										<option value="">请选择</option>
			    					</select>
			    				</div>
			    				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
									<input type="button" value="+"  class="am-btn am-btn-success am-btn-sm am-radius" onclick="special_preferences()" />
								</div>
			    			</div>
			    		</div>
					<div class="am-form-group promotion_type_change"  style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] != 2)?'display:none;':'display:block;' ?>">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3"></label>
			    			<label class="am-u-lg-5 am-u-md-5 am-u-sm-4 am-form-group-label" style="padding-left:40px;"><?php echo $ld['gifts_pecials']?></label>
			    			<label class="am-u-lg-5 am-u-md-5 am-u-sm-4 am-form-group-label"><?php echo $ld['price']?></label>
			    		</div>	
					<div class="am-form-group promotion_type_change"  style="<?php echo (isset($this->data['Promotion']['type']) && $this->data['Promotion']['type'] != 2)?'display:none;':'display:block;' ?>">
		    				<div id="special_preference">
		    					<?php if( isset($PromotionProduct) && sizeof($PromotionProduct)>0){foreach( $PromotionProduct as $k=>$v ){?>
		    					<div style="height: 40px;">
			    					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3"></label>
			    					<div class="am-u-lg-5 am-u-md-5 am-u-sm-4 am-text-left" style="">
			    						<a href="javascript:;" name="1" onclick="removeImg(this)" style="text-decoration:none;">[-]</a> <input class="checkbox" style="display:none" type='checkbox' name='specialpreferences[]' value="<?php echo $v['PromotionProduct']['product_id']?>" checked> <?php echo $v['PromotionProduct']['name'];?>
			    					</div>
									<div class="am-u-lg-5 am-u-md-5 am-u-sm-4" style="">
										<input type='text' name=prices[] style="width:100px;border:1px solid #649776;" value='<?php echo $v["PromotionProduct"]["price"]?>' />
									</div>
								</div>
								<?php }}?>
							</div>
			    		</div>
					</div>
				</div>
				<!-- <div  class="btnouter">
					<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
					<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				</div>  -->
			</div>
			<div id="detail_description" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['detail_description']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			 <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"> </label>
			    			<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">	
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<?php if($configs["show_edit_type"]){?>
						<div class="am-form-group">			
							<span style="right: 10%;" class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
							<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[PromotionI18n][<?php echo $k?>][short_desc]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['PromotionI18n'][$v['Language']['locale']]['short_desc'])?$this->data['PromotionI18n'][$v['Language']['locale']]['short_desc']:"";?></textarea>
							<script>
							var editor;
							KindEditor.ready(function(K) {
							editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {width:'80%',
	                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
							});
							</script>				
						</div>
						<?php }else{?>
						<div>
							<span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
							<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[PromotionI18n][<?php echo $k?>][short_desc]" rows="10"><?php echo isset($this->data['PromotionI18n'][$v['Language']['locale']]['short_desc'])?$this->data['PromotionI18n'][$v['Language']['locale']]['short_desc']:"";?></textarea>
							<?php echo $ckeditor->load("elm".$v['Language']['locale'],$locale); ?>
						</div>
						<?php }?>
						<?php }}?>
							</div><div class="am-cf"></div>
					</div>				    	
				</div>
			</div>
									
		<?php echo $form->end();?>
	</div>
</div>

<span id="gg" style="display:none">
	<?php echo $ld['gifts_pecials'];?>
	 <span id="tt"></span>
	<?php echo $ld['price']?> 
	<span id="special_preference"></span>
	<dl>
		<dt><?php echo $ld['search_and_add_gifts']?>：</dt>
		<dd>
			<input type="text" name="keywords" id="keywords" style="width:120px;" />
			<input type="button" value="<?php echo $ld['search']?>" onclick="searchProducts2();" />
			<input type="hidden" name="brand_id" id="brand_id">
			<input type="hidden" name="category_id" id="category_id">
			<select name="source_select1" id="source_select1"></select>
		</dd>
		<input type="button" value="+" name="" onclick="special_preferences()" />
	</dl>
</span>
<script type="text/javascript">
function special_preferences(){
	var special_preference = document.getElementById("special_preference");
	var tt = document.getElementById("tt");
	var source_select1 = document.getElementById("source_select2");
	for (i=0;i<source_select1.length;i++)
  {
  	if(source_select1.options[i].selected){
  		var specialpreferences = document.getElementsByName("specialpreferences[]");
  		for( var j=0;j<=specialpreferences.length-1;j++ ){
  			if( specialpreferences[j].value == source_select1.value ){
  				alert("<?php echo $ld['this_option_already_exists']?>");
  				return;
  			}
  		}

		var col_innerHTML = "<div class='am-form-group' style='margin-bottom:10px;'><div class='am-u-lg-6 am-u-md-6 am-u-sm-8 am-text-left' style='padding-top:7px;'><a href='javascript:;' name='1' onclick='removeImg(this)' style='text-decoration:none;'>[-]</a><input class='checkbox' type='checkbox' style='display:none' name=specialpreferences[] value="+source_select1.value+" checked>"+source_select1.options[i].text+"</div><div class='am-u-lg-2 am-u-md-4 am-u-sm-4' style='align:center;'><input type='text' name=prices[] style='width:100px;margin:0 auto;border:1px solid #649776;float:right' value='0'></div></div>";
		$("#special_preference").append(col_innerHTML);

  	}

  }

}
function special_preferences2(){
	var special_preference2 = document.getElementById("special_preference2");
//	var tt = document.getElementById("tt2");
	var source_select2 = document.getElementById("product_select");
	for (i=0;i<source_select2.length;i++)
  {
  	if(source_select2.options[i].selected){
  		var specialpreferences2 = document.getElementsByName("specialpreferences2[]");
  		for( var j=0;j<=specialpreferences2.length-1;j++ ){
  			if( specialpreferences2[j].value == source_select2.value ){
  				alert("<?php echo $ld['this_option_already_exists']?>");
  				return;
  			}
  		}
		var div_HTML = "<div><div><div style='width:1px;max-height:190px;'>&nbsp;</div><a style='padding-left:6px;' href='javascript:;' name='1' onclick='removeImg2(this)' style='text-decoration:none;'>[-]</a><input class='checkbox' type='checkbox' style='display:none' name=specialpreferences2[] value="+source_select2.value+" checked>"+source_select2.options[i].text+"<input type='hidden' name=prices2[] style='width:100px;border:1px solid #649776' value='0'></div></div>";
		$("#special_preference2").append(div_HTML);
		//alert("aa");

  	}

  }

}

function searchProducts2(where){
	var category_id = document.getElementById("category_id");//商品分类
	var brand_id = document.getElementById("brand_id");//商品品牌
	var product_keyword = document.getElementById("product_keyword");//搜索关键字
		$.ajax({
			url:admin_webroot+"products/searchProducts/",
			type:"POST",
			data:{category_id:category_id.value,brand_id:brand_id.value,product_keyword:product_keyword.value},
			dataType:"json",
			success:function(data){
				if(data.flag=="1"){
					var product_select_sel = document.getElementById(where);
					product_select_sel.innerHTML = "";
					if(data.content){
						for(i=0;i<data.content.length;i++){
							var opt = document.createElement("OPTION");
							opt.value = data.content[i]['Product'].id;
							opt.text = data.content[i]['Product'].code+"--"+data.content[i]['ProductI18n'].name;
							product_select_sel.options.add(opt);
						}
			     }
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}

			}
		});
}


function pagesizeq(s){
	var show_hide = document.getElementById('show_hide');
	var gg = document.getElementById('gg');
	if( s!=2 ){
		$(".promotion_type_change").hide();
		$(".promotion_type_text").show();
		//show_hide.style.display = "none";
	}else{
		$(".promotion_type_change").show();
		$(".promotion_type_text").hide();
		$(".promotion_type_text input[type='text']").val("0.00");
		//show_hide.style.display = "block";
		if(show_hide.innerHTML.length == 5){
			show_hide.innerHTML = gg.innerHTML;
		}
	}
}
//搜索商品


 function removeImg2(obj){
   $(obj).parent().parent().remove();
 }
 function removeImg(obj){
   $(obj).parent().parent().remove();
 }
 function promotions_check(){
 	var promotions_title_obj = document.getElementById("promotion_title_"+backend_locale);
 	var promotions_short_desc_obj = document.getElementById("promotion_meta_description_"+backend_locale);
 	var promotion_end_time = document.getElementById("promotion_end_time");
 	var promotion_start_time = document.getElementById("promotion_start_time");
	if(promotions_title_obj.value==""){
		alert("<?php echo $ld['enter_promotional_name']?>");
		return false;
	}
	if(promotions_short_desc_obj.value==""){
		alert("<?php echo $ld['enter_brief_description']?>");
		return false;
	}
	if(promotion_start_time.value==""){
		alert("请输入促销起始日期！");
		return false;
	}
	if(promotion_end_time.value==""){
		alert("请输入促销结束日期！");
		return false;
	}
	promotion_start_time = promotion_start_time.value;
	promotion_end_time = promotion_end_time.value;

	promotion_start_time = new Date(promotion_start_time.replace(/\-/g, "\/"));  
	promotion_end_time = new Date(promotion_end_time.replace(/\-/g, "\/")); 

    if(promotion_start_time > promotion_end_time){
        alert('促销起始日期 不能大于 促销结束日期！');
        return false;
    }
	return true;

 }
</script>
<style>
.ajax_promotion{
	padding-top:4px;
}
</style>
