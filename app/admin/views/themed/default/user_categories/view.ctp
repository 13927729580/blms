<style type="text/css">
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
</style>
<div class="am-g">
	<div class="am-u-lg-2  am-u-md-2 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion">
		<?php echo $form->create('user_categories',array('action'=>'/view/'.(isset($usercategory_data['UserCategory']['id'])?$usercategory_data['UserCategory']['id']:'0'),'onsubmit'=>'return user_categorie_submit();return false;'));?>
			<input type="hidden" name="data[UserCategory][id]" value="<?php echo isset($usercategory_data['UserCategory']['id'])?$usercategory_data['UserCategory']['id']:'0';?>" />
			<div id="basic_information"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
				</div>
			    	<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['code']; ?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
				    					<input type="text" name="data[UserCategory][code]" value="<?php echo isset($usercategory_data['UserCategory']['code'])?$usercategory_data['UserCategory']['code']:'';?>" onchange="categorycode_check(this)" />
				    				</div>
								<label style="font-weight:normal;" class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left">
									<em style="color:red;">*</em>
								</label>
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['category_name']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
					    			<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
					    				<input type="text" name="data[UserCategory][name]" value="<?php echo isset($usercategory_data['UserCategory']['name'])?$usercategory_data['UserCategory']['name']:'';?>" />
					    			</div>
								<label style="font-weight:normal;" class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left">
									<em style="color:red;">*</em>
								</label>
					    		</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['description']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
									<textarea name="data[UserCategory][description]" style="height:80px;resize:none;"><?php echo isset($usercategory_data['UserCategory']['description'])?$usercategory_data['UserCategory']['description']:'';?></textarea>
				    				</div>
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"  style="margin-top:16px;"><?php echo $ld['valid']?></label>
				    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-6">
								<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
					    				<label class="am-radio am-success"><input type="radio" data-am-ucheck value="1" name="data[UserCategory][status]" <?php echo (isset($usercategory_data['UserCategory']['status'])&&$usercategory_data['UserCategory']['status'] == 1)||!isset($usercategory_data['UserCategory'])?"checked":''; ?>/> <?php echo $ld['yes']?> </label>&nbsp;&nbsp;&nbsp;&nbsp;
					    				<label class="am-radio am-success">
										<input type="radio" data-am-ucheck name="data[UserCategory][status]" value="0" <?php echo isset($usercategory_data['UserCategory']['status'])&&$usercategory_data['UserCategory']['status'] == 0?"checked":''; ?> /><?php echo $ld['no']?></label>
					    			</div>
				    			</div>
				    		</div>
						<div class="am-form-group am-hide">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
							<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
									<input type="text" class="input_sort" name="data[UserCategory][orderby]" value="<?php echo isset($usercategory_data['UserCategory']['orderby'])?$usercategory_data['UserCategory']['orderby']:'50';?>" />
								</div>
							</div>
						</div>
			    			<div  class="am-form-group">
			    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">&nbsp;</label>
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    					<div class="am-u-lg-9 am-u-md-11 am-u-sm-11 btnouter">
						      		<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['d_submit']; ?></button>
									<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius"><?php echo $ld['d_reset']; ?></button>
								</div>
							</div>
				  	     </div> 
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">
function user_categorie_submit(){
	var category_code=$("input[name='data[UserCategory][code]']").val();
	var category_name=$("input[name='data[UserCategory][name]']").val();
	if(category_code==''){
		alert("<?php printf($ld['name_not_be_empty'],$ld['code']); ?>");
		return false;
	}else{
		var category_code_error=$("input[name='data[UserCategory][code]']").attr('code_error');
		if(category_code_error=='0'){
			alert("<?php echo $ld['code_already_exists']; ?>");
			return false;
		}
	}
	if(category_name==""){
		alert("<?php printf($ld['name_not_be_empty'],$ld['category_name']); ?>");
		return false;
	}
	return true;
}

function categorycode_check(input_obj){
	var category_code=$(input_obj).val().trim();
	if(category_code!=""){
		$(input_obj).attr("code_error",'0');
		var category_id=$("input[name='data[UserCategory][id]']").val();
		$.ajax({
			url:admin_webroot+"user_categories/categorycode_check",
			type:"POST",
			data: {'categoryid':category_id,'categorycode':category_code},
			dataType:"json",
			success:function(data){
				if(data.code == 1){
					$(input_obj).attr("code_error",'1');
				}else{
					$(input_obj).attr("code_error",'0');
				}
			}
		});
	}else{
		$(input_obj).attr("code_error",'0');
	}
}
</script>