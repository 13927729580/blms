<style type="text/css">
 
 .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 
.img_select{max-width:150px;max-height:120px;}
.scrollspy-nav {
    top: 0;
    z-index: 500;
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
<div class="am-g" style="margin-left:0;margin-right:0;">
	<!--导航条-->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
		    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  style="width:100%;">
		<?php echo $form->create('brands',array('action'=>'view/'.(isset($this->data['Brand'])?$this->data['Brand']['id']:''),'name'=>'BrandForm','onsubmit'=>'return brands_input_checks();'));?>
		<!-- 右上角按钮 -->
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
          	<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
			<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
        </div>  
		  	<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">			
			    		<input id="id" name="data[Brand][id]" type="hidden" value="<?php echo isset($this->data['Brand']['id'])?$this->data['Brand']['id']:'';?>">
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<input name="data[BrandI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
						<?php }}?>
			    		<div class="am-form-group" >
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld["brand_code"]?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="code" onblur="operator_change()" name="data[Brand][code]" value="<?php echo isset($this->data['Brand']['code'])?$this->data['Brand']['code']:'';?>" />
			    				</div>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label" style="padding-top:10px;"><em style="color:red;">*</em></div>
			    			</div>
			    		</div>		
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-view-label"><?php echo $ld['brand_name']?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
			    					<input id="brands_name_<?php echo $v['Language']['locale'];?>" name="data[BrandI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($this->data['BrandI18n'][$v['Language']['locale']])?$this->data['BrandI18n'][$v['Language']['locale']]['name']:'';?>">
			    				</div>
			    					<?php if(sizeof($backend_locales)>1){?>
			    						<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?>
			    							<em style="color:red;">*</em></label>
			    					<?php }?>
			    				
			    			<?php }}?>
			    			</div>
			    		</div>		
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld['brand_logo']?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  style="margin-top:10px;">
			    					<input name="data[BrandI18n][<?php echo $k;?>][img01]" id="brandi18n_logo_<?php echo $v['Language']['locale'];?>" type="text" value="<?php echo isset($this->data['BrandI18n'][$v['Language']['locale']])?$this->data['BrandI18n'][$v['Language']['locale']]['img01']:'';?>">
			    					
			    					<input type="button"  class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('brandi18n_logo_<?php echo $v['Language']['locale'];?>')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
								
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['BrandI18n'][$v['Language']['locale']])&&$this->data['BrandI18n'][$v['Language']['locale']]['img01']!="")?$this->data['BrandI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_brandi18n_logo_'.$v['Language']['locale']))?>
									</div>
			    				</div>
			    			<?php }}?>
			    			</div>
			    		</div>	
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-view-label"><?php echo $ld['brand_image']?>1</label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Brand][img01]" id="brand_img01" value="<?php echo isset($this->data['Brand']['img01'])?$this->data['Brand']['img01']:'';?>" />
			    					
			    					<input type="button"   class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('brand_img01')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
									
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['Brand']['img01'])&&$this->data['Brand']['img01']!="")?$this->data['Brand']['img01']:$configs['shop_default_img'],array('id'=>'show_brand_img01'))?>
									</div>
						    					
			    				</div>
			    			</div>
			    		</div>		
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-view-label"><?php echo $ld['brand_image']?>2</label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Brand][img02]" id="brand_img02" value="<?php echo isset($this->data['Brand']['img02'])?$this->data['Brand']['img02']:'';?>" />
			    					
			    					<input type="button"  class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('brand_img02')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
									
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['Brand']['img02'])&&$this->data['Brand']['img02']!="")?$this->data['Brand']['img02']:$configs['shop_default_img'],array('id'=>'show_brand_img02'))?>
									</div>
			    				</div>
			    			</div>
			    		</div>
			    		<?php if(isset($all_brand)&& !empty($all_brand)){?>
			    		<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label" style="margin-top:7px;" >
								<?php echo $ld['sort']?>
							</label>
							<div  class="am-u-lg-3 am-u-md-4 am-u-sm-4">
								<div class="am-u-lg-12" style="margin-top:0;">
									<label class="am-radio am-success">
										<input type="radio" name="orderby" value="0" data-am-ucheck/><?php echo $ld['front']?>
									</label>&nbsp;&nbsp;
									<label class=" am-radio am-success">
										<input checked type="radio" name="orderby" value="1" data-am-ucheck/>
										<?php echo $ld['final']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="orderby" value="2" data-am-ucheck/><?php echo $ld['at']?>
									</label>
								</div>
							</div>
						</div>
						<div class="am-form-group">
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">&nbsp;</div>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<select id='orderby' name="orderby_sel" data-am-selected="{font-size:8px;}">
										<?php foreach($all_brand as $v){?>
											<option value="<?php echo $v['Brand']['id']?>"><?php echo $v['BrandI18n']['name'];?></option>
										<?php }?>
									</select>
								</div>
								<label class="am-u-lg-3 am-u-md-9 am-u-sm-7" style="margin-top:13px;"><?php echo $ld['after']?></label>	
							</div>
							<div class="am-u-sm-4 am-u-md-3 am-hide-lg-only">&nbsp;</div>
								
						</div>
			    		<?php }?>	
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld['display']?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7 am-u-end">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Brand][status]" value="1" <?php echo !isset($this->data['Brand']['status'])||(isset($this->data['Brand']['status'])&&$this->data['Brand']['status']==1)?"checked":"";?> ><?php echo $ld['yes']?></label>&nbsp;&nbsp;
									<label class="am-radio am-success">
									<input name="data[Brand][status]" type="radio" data-am-ucheck value="0" <?php echo isset($this->data['Brand']['status'])&&$this->data['Brand']['status']==0?"checked":"";?> ><?php echo $ld['no']?></label> 
								</div>
			    			</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld['recommend']?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7 am-u-end">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Brand][recommand_flag]" value="1" <?php echo (isset($this->data['Brand']['recommand_flag'])&&$this->data['Brand']['recommand_flag']==1)?"checked":"";?> ><?php echo $ld['yes']?></label>&nbsp;&nbsp;
									<label class="am-radio am-success">
									<input name="data[Brand][recommand_flag]" type="radio" data-am-ucheck value="0" <?php echo !isset($this->data['Brand']['status'])||(isset($this->data['Brand']['recommand_flag'])&&$this->data['Brand']['recommand_flag']==0)?"checked":"";?> ><?php echo $ld['no']?></label> 
								</div>
			    			</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-view-label"><?php echo $ld['external_links']?></label>
			    			<div class="am-u-lg-7 am-u-sm-7 am-u-sm-7 am-u-end">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[Brand][url]" value="<?php echo isset($this->data['Brand']['url'])?$this->data['Brand']['url']:'';?>" />
									<?php echo $ld['page_url_desc']; ?>
								</div>
			    			</div>
							
			    		</div>
						
			    	</div>
			 	
		  	</div>
		  	<!-------------1--button over----------------->
			<div id="detail_description" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['detail_description']?>
					</h4>
			    </div>
			    	<!-------------------------->		  
						<div class="am-panel-collapse am-collapse am-in"> 
							<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">&nbsp;</label>		  
								<div  class="am-u-lg-9 am-u-md-8 am-u-sm-8" style="margin-left:15px;">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<?php if($configs["show_edit_type"]){?>			
								<div class="am-form-group">
								<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[BrandI18n][<?php echo $k;?>][description]" rows="10" style="width:auto;height:300px;">
								<?php echo isset($this->data['BrandI18n'][$v['Language']['locale']]['description'])?$this->data['BrandI18n'][$v['Language']['locale']]['description']:"";?>
								</textarea>
								<script>
								var editor;
								KindEditor.ready(function(K) {
								editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {
								width:'80%',
								langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
								});
								</script>
							</div>

						<!-------------------------->		
			    		<?php }else{?>	
				    		 <div class="am-form-group">
				    		 	 <div ><span class="ckeditorlanguage  am-form-group-label"><?php echo $v['Language']['name'];?></span></div>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[BrandI18n][<?php echo $k;?>][description]" rows="10">
									<?php echo isset($this->data['BrandI18n'][$v['Language']['locale']]['description'])?$this->data['BrandI18n'][$v['Language']['locale']]['description']:"";?>
								</textarea>
								<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
				    		        </div>	
						<?php }?>
						<?php }}?>	  
		      			   </div>		  
		      		      <div class="am-cf"></div>	  
		      		 </div>	 
			    		   	           
					 </div>   	
			       </div> 
			</div>	
		<?php echo $form->end();?>
	</div>	  
</div>
<script type="text/javascript">
var logocd=false;
var submit_flag=true;
function brands_input_checks(){
	var brands_name_obj = document.getElementById("brands_name_"+backend_locale);
	var code = document.getElementById("code").value;
	if(brands_name_obj.value==""){
		alert("<?php echo $ld['enter_brand name']; ?>");
		return false;
	}
	if(code==""){
		alert(please_enter_the_brand_code);
		return false;
	}
	return submit_flag;
}

//快速添加类目
function doinsertcattype(){
	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"category_types/doinsertcattype/";//访问的URL地址
		var cfg = {
				method: 'POST',
				form: {
					id: 'catform3',
					useDisabled: true
				}
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
				if(result.flag==1){
					 var node = Y.one('#product_category_type_id');
					 node.set('innerHTML', result.cattype);
					 btnClose();
				}
				if(result.flag==2){
					alert(result.message);
				}
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}
function operator_change(){
	submit_flag=false;
	var code = document.getElementById("code").value;
	if(code!=""){
		$.ajax({
			type:"POST",
			url:admin_webroot+"brands/act_view/"+id,
			dataType: "json",
			data: "code="+code.value,
			success:function(data){
					if(data.code==1){
                     	submit_flag=true;
						return true;
                     }else{
                          alert(js_brand_code_duplication);
                          submit_flag=false;
                          return false;
                     }
			}
		});
	}else{
		submit_flag=false;
		return false;
	}
}
</script>
