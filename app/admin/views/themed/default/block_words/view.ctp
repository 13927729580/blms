<style>
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

<?php 
	echo $form->create('block_words',array('action'=>'/view/'.$id,'name'=>"SeearchForm",'id'=>"SearchForm","type"=>"post",'onsubmit'=>'return formsubmit();'));
?>
<!-- 导航条 -->
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">	
	<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" style="margin-right:20px;" value="<?php echo $ld['d_submit']?>" /> 
	<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
					
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  style="width:100%;padding-left:0;padding-right:0;">
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in" style="min-height:30rem;">
        	<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
        		<input type="hidden" name="data[BlockWord][id]" value="<?php echo $id; ?>" />
            	<div class="am-g">
 					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label" style="margin-top:0.8rem;"><?php echo $ld['type'] ?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<select data-am-selected="{noSelectedText:''}" name="data[BlockWord][type]" id="word_type">
								<option value=""><?php echo $ld['please_select'] ?></option>
								<option value="0" <?php echo isset($wordinfo)&&$wordinfo['BlockWord']['type']=="0"?"selected":""; ?>><?php echo $ld['filter'] ?></option>
								<option value="1" <?php echo isset($wordinfo)&&$wordinfo['BlockWord']['type']=="1"?"selected":""; ?>><?php echo $ld['replace'] ?></option>
							</select>
						</div>
					</div>
					<div class="am-form-group">
						<label style="margin-top:0.7rem;" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['keyword'] ?></label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<input type="text" name="data[BlockWord][word]" id="word" value="<?php echo isset($wordinfo)?$wordinfo['BlockWord']['word']:""; ?>" />
						</div>	
					</div>			
            	</div>
            </div>
        </div>
     </div>
</div>
<?php
	echo $form->end();
?>
<script type="text/javascript">
function formsubmit()
{
	var type=document.getElementById("word_type").value;
	var word=document.getElementById("word").value;
	if(type==""){
		alert('请选择类型');
		return false;
	}
	if(word==""){
		alert('关键字不能为空');
		return false;
	}
	return true;
}
</script>