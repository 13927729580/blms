<style type="text/css">
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
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
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
<?php echo $form->create('advertisements',array('action'=>'view/'.$posid."/".(isset($advertisements_data['Advertisement'])?$advertisements_data['Advertisement']['id']:'')));?>
	<!-- 导航 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
	    <ul>
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#email_code"><?php echo $ld['email_code']?></a></li>  
		</ul>
	</div>

	<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
	    <input style="margin-left: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['d_submit']?>"  />
		<input style="margin-left: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius"  value="<?php echo $ld['d_reset']?>" />
	</div>
	<!-- 导航结束 -->
				<input name="data[Advertisement][id]" type="hidden" value="<?php echo isset($advertisements_data['Advertisement']['id'])?$advertisements_data['Advertisement']['id']:'';?>">
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v) {?>
<input name="data[AdvertisementI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
<?php }}?>
			<div id="basic_information"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_name']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<input name="data[AdvertisementI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])?$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['name']:'';?>">
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_position']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<select name="data[Advertisement][advertisement_position_id]" data-am-selected="{maxHeight:200}">
										<option value='0'><?php echo $ld['please_select']?></option>
										<?php foreach($advertisement_positions as $ap){ ?>
										<option value="<?php echo $ap['AdvertisementPosition']['id'];?>" <?php if(isset($posid)){if($posid==$ap['AdvertisementPosition']['id']){ ?>selected<?php }} ?>><?php echo $ap['AdvertisementPosition']['name'];?></option>
										<?php } ?>
									</select>
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_code']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[Advertisement][code]" value="<?php echo isset($advertisements_data['Advertisement']['code'])?$advertisements_data['Advertisement']['code']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
				    			
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['contacter']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[Advertisement][contact_name]" value="<?php echo isset($advertisements_data['Advertisement']['contact_name'])?$advertisements_data['Advertisement']['contact_name']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['contacter_email']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[Advertisement][contact_email]" value="<?php echo isset($advertisements_data['Advertisement']['contact_email'])?$advertisements_data['Advertisement']['contact_email']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['contacter_phone']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[Advertisement][contact_tele]" value="<?php echo isset($advertisements_data['Advertisement']['contact_tele'])?$advertisements_data['Advertisement']['contact_tele']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_link']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<input name="data[AdvertisementI18n][<?php echo $k;?>][url]" type="text" value="<?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])?$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['url']:'';?>">
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
				    				
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['link_type']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<label class="am-radio am-success" ><input type="radio"  data-am-ucheck name="data[AdvertisementI18n][<?php echo $k;?>][url_type]" value="0" <?php echo !isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])||(isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['url_type'])&&$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['url_type']==0)?"checked":"";?> /><?php echo $ld['direct_link']?></label>
										<label class="am-radio am-success" ><input  data-am-ucheck name="data[AdvertisementI18n][<?php echo $k;?>][url_type]" type="radio" value="1" <?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])&&$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['url_type']==1?"checked":"";?> /><?php echo $ld['indirect_link']?></label>
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['start_date']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<input type="text"   class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[AdvertisementI18n][<?php echo $k;?>][start_time]" value="<?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])?(date('Y-m-d',strtotime($advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['start_time']))):'';?>" />
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['end_date']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"   name="data[AdvertisementI18n][<?php echo $k;?>][end_time]" value="<?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])?(date('Y-m-d',strtotime($advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['end_time']))):'';?>" />
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['description']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    					<textarea name="data[AdvertisementI18n][<?php echo $k;?>][description]"><?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']])?$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['description']:'';?></textarea>
					    				</div>
						    			<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left" style="font-weight:normal;">
					    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    					</label>
						    			<?php }?>
				    				<?php }} ?>
				    			</div>
				    		</div>
						
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['valid']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<label class="am-radio am-success" ><input type="radio"  data-am-ucheck  value="1" name="data[Advertisement][status]" <?php echo !isset($advertisements_data['Advertisement']['status'])||(isset($advertisements_data['Advertisement']['status'])&&$advertisements_data['Advertisement']['status']==1)?"checked":"";?> /><?php echo $ld['yes']?></label>
									<label class="am-radio am-success" ><input type="radio"  data-am-ucheck  value="0" name="data[Advertisement][status]" <?php echo isset($advertisements_data['Advertisement']['status'])&&$advertisements_data['Advertisement']['status']==0?"checked":"";?> /><?php echo $ld['no']?></label>
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
				    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" class="input_sort" name="data[Advertisement][orderby]" value="<?php echo isset($advertisements_data['Advertisement']['orderby'])?$advertisements_data['Advertisement']['orderby']:'50';?>" />
				    				</div>
				    			</div>
				    		</div>
				    		
					</div>
				</div>
			</div>
			
			<div id="email_code"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['email_code']?></h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						
						<?php  if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $v['Language']['name'];?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[AdvertisementI18n][<?php echo $k;?>][code]" rows="10" style="width:auto;height:300px;"><?php echo isset($advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['code'])?$advertisements_data['AdvertisementI18n'][$v['Language']['locale']]['code']:"";?></textarea>
								<script type="text/javascript">
									var editor;
									KindEditor.ready(function(K) {
									editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {
									langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
									});
								</script>
				    			</div>
				    		</div>
						<?php }} ?>
						
					</div>
					<div  class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['d_reset']?></button>
					</div> 
				</div>
			</div>
			
			
			
		<?php echo $form->end();?>
	</div>
</div>