<style>
 .am-form-horizontal .am-radio,.am-form-horizontal .am-checkbox{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;display: inline-block;}
 .am-radio input[type="radio"], .am-checkbox input[type="checkbox"]{margin-left:0px;}
 .am-form-group-label{font-weight:bold;}
 .am-radio, .am-checkbox{padding-left:20px;}

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

	<?php echo $form->create('logistics_companies',array('action'=>'view/'.(isset($this->data['LogisticsCompany']['id'])?$this->data['LogisticsCompany']['id']:""),"onsubmit"=>"return check_logistics_companies();"));?>
<div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%">
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		    		<li><a href="#LogisticsMapping"><?php echo '物流公司配置';?></a></li>
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<input type="hidden" name="data[LogisticsCompany][id]" value="<?php echo $this->data['LogisticsCompany']['id'];?>">
			<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:17px;"><?php echo $ld['logistics_code']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][code]" id="LogisticsCompany_code" value="<?php echo $this->data['LogisticsCompany']['code'];?>" />
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;padding-left: 0;"><em style="color:red;">*</em></div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['query_code'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][express_code]"  id="LogisticsCompany_express_code"  value="<?php echo $this->data['LogisticsCompany']['express_code'];?>" />
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:6px;padding-left: 0;"><em style="color:red;">*</em></div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['logistics_company']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][name]" id="LogisticsCompany_name" value="<?php echo $this->data['LogisticsCompany']['name'];?>" />
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:6px;padding-left: 0;"><em style="color:red;">*</em></div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['contacter_name']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][contact_name]" value="<?php echo $this->data['LogisticsCompany']['contact_name']?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['contacter_phone']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][contact_phone]" value="<?php echo $this->data['LogisticsCompany']['contact_phone']?>" />
			    				</div>
			    			</div>
			    		</div>
			    			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"> <?php echo $ld['logistics_company_address']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][address]" value="<?php echo $this->data['LogisticsCompany']['address'] ?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['logistics_service_hotline']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][hotline]" value="<?php echo $this->data['LogisticsCompany']['hotline'] ?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['logistics_telephone']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][inquiry]" value="<?php echo $this->data['LogisticsCompany']['inquiry'] ?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"><?php echo $ld['logistics_telephone_complaints']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][complaint]" value="<?php echo $this->data['LogisticsCompany']['complaint'] ?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="margin-top:19px;"> <?php echo $ld['logistics_website']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[LogisticsCompany][website]" value="<?php echo $this->data['LogisticsCompany']['website'] ?>" />
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="padding-top:6px;"><?php echo $ld['status']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label  class="am-radio am-success">
			    						<input type="radio" name="data[LogisticsCompany][fettle]" data-am-ucheck value="1" <?php if(empty($this->data["LogisticsCompany"]["fettle"])||$this->data["LogisticsCompany"]["fettle"]=="1"){ echo "checked";}?> />
			    						<?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label  class="am-radio am-success">
										<input type="radio" name="data[LogisticsCompany][fettle]" data-am-ucheck value="0" <?php if($this->data["LogisticsCompany"]["fettle"]=="0"){ echo "checked";}?>/>
										<?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" style="padding-top:8px;"><?php echo $ld['shipping']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<?php foreach($shippings as $k=>$shipping){?>
										<label class="am-checkbox am-success">
											<input type="checkbox" data-am-ucheck  name="check[]" <?php $a=explode(";",$bb=$this->data['LogisticsCompany']['type']);$b=count($a); $c=$shipping['Shipping']['id'];for($i=0;$i< $b;$i++){if($a[$i]==$c){echo "checked";}}?> value="<?php echo $shipping['Shipping']['id']?>"/><?php echo $shipping['ShippingI18n']['name']?>
										</label>&nbsp;&nbsp;
									<?php } ?>
			    				</div>
			    			</div>
			    		</div>			
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label">(<?php echo $ld['api_options']?>)</label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<textarea name="data[LogisticsCompany][php_code]" style="width:500px;height:150px;"><?php echo $this->data['LogisticsCompany']['php_code'] ?></textarea>
			    				</div>
			    			</div>
			    		</div>
			    	 	</div>
		      	</div>
			</div>
	</div>
 	</div>
	<!-- 物流公司配置 --> 
<?php if(!empty($Resource_Info['shop_channel'])){ ?>
    <div>
       <div  class="am-panel-group admin-content am-detail-view" id="LogisticsMapping" style="width: 98%;margin-right: 1%;">
			<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">物流公司配置</h4>
			    </div>
		       <div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<div class="am-form-group">
						<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-group-label">类型</div>
							<div class="am-u-lg-7 am-u-md-4 am-u-sm-4  ">来源物流编码</div>
						</div>
					</div>
					<?php foreach($Resource_Info['shop_channel'] as $k=>$v){ ?>
					<div class="am-form-group">
						<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
						<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-group-label" style="margin-top:17px;"><?php echo $v; ?></label>
							<div class="am-u-lg-5 am-u-md-4 am-u-sm-4  ">
								<input type="hidden" name="data[LogisticsMapping][type][]" value="<?php echo $k; ?>"/>
								<input type="text" name="data[LogisticsMapping][logistics_id][]" value="<?php echo isset($LogisticsMapping_list[$k])?$LogisticsMapping_list[$k]:''; ?>"/>
							</div>
						</div>
					</div>
					<?php } ?>
                    </div>
			    </div>
            </div>
       </div>
    </div>
    <?php } ?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
function check_logistics_companies(){
	var LogisticsCompany_code=document.getElementById("LogisticsCompany_code").value;
	var LogisticsCompany_express_code=document.getElementById("LogisticsCompany_express_code").value;
	var LogisticsCompany_name=document.getElementById("LogisticsCompany_name").value;
	if(LogisticsCompany_code==""){
		alert("<?php printf($ld['name_not_be_empty'],$ld['logistics_code']); ?>");
		return false;
	}
	if(LogisticsCompany_express_code==""){
		alert("<?php printf($ld['name_not_be_empty'],$ld['query_code']); ?>");
		return false;
	}
	if(LogisticsCompany_name==""){
		alert("<?php printf($ld['name_not_be_empty'],$ld['logistics_company']); ?>");
		return false;
	}
	return true;
}
</script>