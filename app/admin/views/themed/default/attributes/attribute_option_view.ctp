<style>
.am-form-horizontal .am-form-label{padding-top:6px;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:0px;}
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-radio input[type="radio"]{margin-left:0px;}
.img_select{max-width:150px;max-height:120px;}
.am-form-group{margin-top:10px;}
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
<div class="am-g" style="margin-left:0;margin-right:0;">
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
	    	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<?php echo $form->create('Attributes',array('action'=>"/attribute_option_view/{$attribute_id}/".(isset($attribute_option_data['AttributeOption']['id'])?$attribute_option_data['AttributeOption']['id']:"0"),'onSubmit'=>'return check_attr_option();','name'=>'AttributeOptionForm','class'=>'am-form am-form-horizontal'));?>
	<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin:0;">
		<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
		<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
	</div>
	<input name="data[AttributeOption][id]" id="AttributeOption_id" type="hidden" value="<?php echo isset($attribute_option_data['AttributeOption']['id'])?$attribute_option_data['AttributeOption']['id']:'0';?>">
	<input name="data[AttributeOption][attribute_id]" type="hidden" value="<?php echo $attribute_id; ?>">
	<div class="am-panel-group admin-content" id="accordion" style="width:100%;">
		<div id="basic_information" class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<label><?php echo $ld['basic_information']?></label>
				</h4>
		    </div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd">
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" style="padding-top:15px;"><?php echo $ld['language']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<select name="data[AttributeOption][locale]" data-am-selected >
	                            <?php foreach($option_language as $k=>$v){ ?>
	                            <option value="<?php echo $k; ?>" <?php echo isset($attribute_option_data['AttributeOption']['locale'])&&$attribute_option_data['AttributeOption']['locale']==$k?"selected":''; ?>><?php echo $v; ?></option>
	                            <?php } ?>
	                        </select>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" style="padding-top:10px;"><?php echo $ld['option_name']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" id="attr_option_name" class="am-form-field am-radius"   name="data[AttributeOption][option_name]" value="<?php echo isset($attribute_option_data['AttributeOption']['option_name'])?$attribute_option_data['AttributeOption']['option_name']:''; ?>" >
						</div>
						<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></label>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" style="padding-top:10px;"><?php echo $ld['option_value']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" id="attr_option_value" class="am-form-field am-radius"   name="data[AttributeOption][option_value]" value="<?php echo isset($attribute_option_data['AttributeOption']['option_value'])?$attribute_option_data['AttributeOption']['option_value']:''; ?>" >
						</div>
					</div>
					<?php if($attribute['Attribute']['type']=='customize' || $attribute['Attribute']['type']=='multiple_customize'){ ?>
                    <div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" ><?php echo $ld['price']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" name="data[AttributeOption][price]" class="am-form-field am-radius"   value="<?php echo isset($attribute_option_data['AttributeOption']['price'])?$attribute_option_data['AttributeOption']['price']:'0.00'; ?>" >
						</div>
					</div>
					
                    <?php } ?>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['thumbnail']?>1</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input id="attribute_option_image1" type="text" name="data[AttributeOption][attribute_option_image1]" value="<?php echo isset($attribute_option_data['AttributeOption']['attribute_option_image1'])?$attribute_option_data['AttributeOption']['attribute_option_image1']:'';?>" />
							
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('attribute_option_image1')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>&nbsp;
	                        
	                        <div class="img_select" style="margin:5px;">
								<?php echo $html->image((isset($attribute_option_data['AttributeOption']['attribute_option_image1'])&&$attribute_option_data['AttributeOption']['attribute_option_image1']!="")?$attribute_option_data['AttributeOption']['attribute_option_image1']:$configs['shop_default_img'],array('id'=>'show_attribute_option_image1'))?>
							</div>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['thumbnail']?>2</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input id="attribute_option_image2" type="text" name="data[AttributeOption][attribute_option_image2]" value="<?php echo isset($attribute_option_data['AttributeOption']['attribute_option_image2'])?$attribute_option_data['AttributeOption']['attribute_option_image2']:'';?>" />
							
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('attribute_option_image2')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>&nbsp;
	                        
	                        <div class="img_select" style="margin:5px;">
								<?php echo $html->image((isset($attribute_option_data['AttributeOption']['attribute_option_image2'])&&$attribute_option_data['AttributeOption']['attribute_option_image2']!="")?$attribute_option_data['AttributeOption']['attribute_option_image2']:$configs['shop_default_img'],array('id'=>'show_attribute_option_image2'))?>
							</div>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" style="padding-top:4px;"><?php echo $ld['status']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<label class="am-radio am-success">
								<input type="radio" data-am-ucheck value="1" name="data[AttributeOption][status]" <?php echo !isset($attribute_option_data['AttributeOption']['status'])||(isset($attribute_option_data['AttributeOption']['status'])&&$attribute_option_data['AttributeOption']['status']==1)?"checked":""; ?> />
								<?php echo $ld['yes']?>
							</label>&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio" data-am-ucheck value="0" name="data[AttributeOption][status]" <?php echo isset($attribute_option_data['AttributeOption']['status'])&&$attribute_option_data['AttributeOption']['status']==0?"checked":"";?> />
								<?php echo $ld['no']?>
							</label>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label am-text-left" ><?php echo $ld['sort']?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" name="data[AttributeOption][orderby]" class="am-form-field am-radius"   value="<?php echo isset($attribute_option_data['AttributeOption']['orderby'])?$attribute_option_data['AttributeOption']['orderby']:'50'; ?>" >
						</div>
					</div>
				</div>
			</div>
			
		</div>
						
		
	</div>
				
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function check_attr_option(){
    var attr_option_name=document.getElementById("attr_option_name").value;
    var attr_option_value=document.getElementById("attr_option_value").value;
    if(attr_option_name==""){
        alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['option_name']) ?>");
        return false;
    }
    if(attr_option_value==""){
        alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['option_value']) ?>");
        return false;
    }
    return true;
}
</script>