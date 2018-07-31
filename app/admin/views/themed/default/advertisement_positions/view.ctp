<style>
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
	.btnouter{margin:0;}
</style>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 95%;margin-right: 2.5%;">
		
		<?php echo $form->create('advertisement_positions',array('action'=>'view/'.(isset($this->data['AdvertisementPosition'])?$this->data['AdvertisementPosition']['id']:'0')));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
				   	<?php if($svshow->operator_privilege("advertisement_positions_view")&&isset($advertisement_position['AdvertisementPosition']['id'])){ ?>
					<li><a href="#ads_list"><?php echo $ld['ads_list']?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<input name="data[AdvertisementPosition][id]" type="hidden" value="<?php echo isset($advertisement_position['AdvertisementPosition']['id'])?$advertisement_position['AdvertisementPosition']['id']:'';?>">
			<input name="data[AdvertisementPosition][template_name]" type="hidden" value="<?php if($templatename!=''){echo $templatename;}else{echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['template_name']:'';}?>">
			<div id="basic_information"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_position_name']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[AdvertisementPosition][name]" value="<?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['name'] : '';?>" />
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['position_code']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input maxlength="20" type="text" name="data[AdvertisementPosition][code]" value="<?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['code']:'';?>"/>
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_width']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[AdvertisementPosition][ad_width]" value="<?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['ad_width']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_height']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[AdvertisementPosition][ad_height]" value="<?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['ad_height']:'';?>" />
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_position_description']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<textarea style="width:500px;height:150px;" name="data[AdvertisementPosition][position_desc]"><?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['position_desc']:'';?></textarea>
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" class="input_sort" name="data[AdvertisementPosition][orderby]" value="<?php echo isset($advertisement_position['AdvertisementPosition'])?$advertisement_position['AdvertisementPosition']['orderby']:'50';?>"/>
				    				</div>
				    			</div>
				    		</div>
						
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['ad_js']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<textarea name="ads_js" style="width:500px;height:50px;"><?php echo isset($js_code)?$js_code:'';?></textarea>
				    				</div>
				    			</div>
				    		</div>
				    		
					</div>
				</div>
			</div>
			
			<?php if($svshow->operator_privilege("advertisement_positions_view")&&isset($advertisement_position['AdvertisementPosition']['id'])){ ?>
			<div id="ads_list"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['ads_list']?></h4>
				</div>
				<div class="am-fr">
					<a href="<?php echo $html->url('/advertisements/view/'.$advertisement_position['AdvertisementPosition']['id'].'/0');  ?>" class="am-btn am-btn-warning am-btn-sm am-radius"><span class="am-icon-plus"></span>添加</a>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd">
						<table class="am-table">
								<thead>
									<tr>
										<th><?php echo $ld['ad_name']?></th>
										<th><?php echo $ld['ad_position']?></th>
										<th><?php echo $ld['media_types']?></th>
										<th><?php echo $ld['start_date']?></th>
										<th><?php echo $ld['end_date']?></th>
										<th><?php echo $ld['hits']?></th>
										<th><?php echo $ld['sort']?></th>
										<th><?php echo $ld['status']; ?></th>
										<th><?php echo $ld['operate']?></th>
									</tr>
								</thead>
								<?php if(isset($advertisement_list) && sizeof($advertisement_list)>0){foreach($advertisement_list as $k=>$v){?>
							<tr>
								<td><?php echo $v['AdvertisementI18n']['name'];?></td>
								<td><?php echo isset($advertisement_position_data[$v['Advertisement']['advertisement_position_id']])?$advertisement_position_data[$v['Advertisement']['advertisement_position_id']]:'';?></td>
								<td><?php if($v['Advertisement']['media_type']==0){echo $ld['picture'];}if($v['Advertisement']['media_type']==1){echo "Flash";}if($v['Advertisement']['media_type']==2){echo $ld['email_code'];}if($v['Advertisement']['media_type']==3){echo $ld['word'];}?></td>
								<td><?php echo date("Y-m-d",strtotime($v['AdvertisementI18n']['start_time']));?></td>
								<td><?php echo date("Y-m-d",strtotime($v['AdvertisementI18n']['end_time']));?></td>
								<td><?php echo $v['Advertisement']['click_count'];?></td>
								<td><?php echo $v['Advertisement']['orderby'];?></td>
								<td><?php if( $v['Advertisement']['status']==1){
										echo $html->image('/admin/skins/default/img/yes.gif');
									}else{
										echo $html->image('/admin/skins/default/img/no.gif');	
									}
								     ?></td>
								<td>

									<a href="<?php echo $html->url('/advertisements/view/'.$advertisement_position['AdvertisementPosition']['id']."/{$v['Advertisement']['id']}") ?>" class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" target="_blank"><span class="am-icon-pencil-square-o"></span><?php echo $ld['edit'] ?></a>

									<a href="javascript:void(0)" onclick="if(confirm('<?php echo $ld['confirm_delete_the_ad'] ?>')){window.location.href = admin_webroot+'advertisements/remove/<?php echo $v['Advertisement']['id'] ?>';}" class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete"><span class="am-icon-trash-o"></span><?php echo $ld['delete'] ?></a>
							</td>
							</tr>
							<?php }}else{?>
							<tr>
								<td><?php echo $ld['no_curent_ads']?></td>
							</tr>
							<?php }?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
		<?php echo $form->end();?>
	</div>
</div>