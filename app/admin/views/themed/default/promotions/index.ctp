<style>
	.am-form-label{font-weight:bold;}
	.am-panel-title div{font-weight:bold;}
 
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-panel-title{font-weight:bold;}
 
</style>

<div>
	<div class="">
		<?php echo $form->create('',array('action'=>'/','name'=>"ReportPromotionForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
		<div class="">
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
				<li  style="margin-bottom:10px;">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['event_name']?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<input type="text" class="name am-form-field" name='promotion_title' value="<?php if(isset($promotion_title))echo $promotion_title;?>"/>
					</div>
				</li>
				<li style="margin-bottom:10px;">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label-text" style="padding-left:0;padding-right:0.5rem;"><?php echo $ld['promotion_date']?></label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;padding-left:1rem;width:35%;">	<div class="am-input-group">
						<input type="text" class="am-form-field" name="start_time" value="<?php echo $start_time;?>" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly/>
						 <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
					</div>
					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:6px;"><em>-</em></label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-left:0;padding-right:0;width:32%;">
						<div class="am-input-group">
						<input type="text" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="end_time" value="<?php echo $end_time;?>" readonly/>
						 <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
					</div>
				</div>
				 
				</li>
						
						<li style="margin-bottom:10px;" >
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text"> </label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">	
					 	<input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" class="search_article" />
					</div>	
							
					</li>
			</ul>
		</div>
		<?php echo $form->end();?>
	</div>					
	
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/promotions/view'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
<div>
		<!---表单的开始---->
   <div  id="tablelist">	
	<?php echo $form->create('',array('action'=>'',"type"=>"post",'name'=>"TopicForm",'onsubmit'=>"return false"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-4 am-u-md-3 am-u-sm-4 ">
						     	<label class="am-checkbox am-success" style="font-weight:bold;">
		                             <span class="am-hide-sm-only"><input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck/></span>
							<?php echo $ld['event_name']?>
						      </label>
					         </div>
					       <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['type']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only"><?php echo $ld['event_start_time']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['event_end_time']?></div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-2"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($promotions) && sizeof($promotions)>0){foreach($promotions as $k=>$promotion){  ?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd">
					        	<div class="am-u-lg-4 am-u-md-3 am-u-sm-4 ">
						     <label class="am-checkbox am-success">
				                <span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $promotion['Promotion']['id'] ?>"   data-am-ucheck/></span>
				 	              <?php echo $promotion['PromotionI18n']['title']?> 
			                                </label>&nbsp;&nbsp;
			                 
						 </div>
					  	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $promotion['Promotion']['typename']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only"><?php echo $promotion['Promotion']['start_time']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $promotion['Promotion']['end_time']?></div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-5 am-action">
							<a class="am-btn am-btn-success am-btn-xs  am-seevia-btn" target='_blank' href="<?php echo '/../promotions/view/'. $promotion['Promotion']['id'] ; ?>"><span class="am-icon-eye"></span>  <?php echo $ld['preview']; ?>
							 </a>
							<?php 	if($svshow->operator_privilege('promotions_edit')){?> <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('view/'.$promotion['Promotion']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a> 
                    <?php }?> <?php if($svshow->operator_privilege('promotions_remove')){?>
			 <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'promotions/remove/<?php echo $promotion['Promotion']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a> <?php 	}?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }}else{?>
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		 
			<?php }?>
		</div>
    
	<?php if(isset($promotions) && sizeof($promotions)){?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-3 am-u-md-4 am-u-sm-3 am-hide-sm-only" style="margin-left:8px;">
			  <label class="am-checkbox am-success">
				<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck>
				<?php echo $ld['select_all']?>
			  </label>&nbsp;&nbsp;
				<button type="submit" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="delete_promotions()" ><?php echo $ld['batch_delete']?></button>
			</div>
			<div class="am-u-lg-8 am-u-md-7 am-u-sm-12">
				<?php echo $this->element('pagers')?>
			</div>
            <div class="am-cf"></div>
		</div>
	<?php }?>
    </div>	
<?php echo $form->end();?>
	 </div>
</div>
<script>
     //删除jS方法
     function delete_promotions(){
     	 
     	 var checkboxes=document.getElementsByName('checkboxes[]');
     	  var postData="";
     	  for(var i=0;i<checkboxes.length ;i++){
	     	  	  if(checkboxes[i].checked){ 
				postData+="&checkboxes[]="+checkboxes[i].value;
			      }
			   }
       
    	if(postData=="" ){
		alert(j_please_select);
		return;
        }
        
        if(confirm('确定删除？')){
   	    		$.ajax({
   	    			type:"POST",
   	    		       url:admin_webroot+"promotions/batch_operations/",
   	    			data:postData,
   	    		      datatype: "json",
   	    			success:function(data){
				window.location.href = window.location.href;
			}
   	    		});
   	    	
   	    	
   	    	}
        
  }      
</script>