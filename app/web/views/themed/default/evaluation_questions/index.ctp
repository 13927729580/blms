<?php //pr($question_list); ?>
<style>
@media only screen and (max-width: 640px)
{
	
	body h5{height:40px;}
	.tianjia{font-size:14px;}
	.questions_list ul{font-size:12px;}
	.questions_fu .add{padding:5px 10px;}
	.questions_fu .add_all{padding:5px 15px;}
}
.am-checkbox input[type=checkbox]{margin-top:5px;}
h5
{
    font-size: 25px;
    color: #424242;
    padding: 0px 0;
    font-weight: 300;
    line-height:15px;
	height:30px;
}
.questions_fu .questions_log
{
      margin: 20px 0 50px 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 0 0 15px #ccc;
    padding: 10px 20px 30px 20px;
}
.questions_header{border-bottom:1px solid #ddd;padding:20px 0;}

.add
{
background:#c2dcc2;
color:#008000;
border:1px dashed #008000;
padding:10px 15px;
border-radius:5px;
}
.add:hover{color:#008000;}
.add_all{padding:10px 25px;}
.red{color:#008000;}
.riqi{color:#9c9c9c;}
.am-checkbox, .am-radio{margin-top:0;}
.questions_fu .am-list{padding:0px 0;margin-bottom:30px;}
.delete_all
{
    color: #333;
    background: #f3f3f3;
    padding: 5px 30px;
    border-radius: 7px;
    border: 1px solid #333;
}
.delete_all:hover
{
color:#333;
cursor:pointer;
}
label.am-checkbox, label.am-radio{margin-bottom:0;}
.questions_fu .am-list>li{border-bottom:1px dashed #ccc;margin: 0 0 0 10px;padding:15px 0;}
.questions_fu .am-list>li:last-child{border:none;}
.caozuo a{color:#808080;margin:0 5px;}
.caozuo a:last-child{color:red;}
a:hover{cursor:pointer;color:#149941;}
.tianjia{float:right;}
.tianjia>div:first-child{padding-right:10px;}
.kongbai
{
padding:20px 0;
text-align:center;
    font-size: 14px;
    color: #999;
    height:50px;
}
</style>

<div class="am-g questions_fu">
	<div class="am-g questions_log">
		<div class="questions_header am-g">
			<div class="am-u-lg-9 am-u-md-6 am-u-sm-12"><h5>我上传的题目</h5></div>
			<div class="tianjia">
				<div class="am-fl">
				<a  class="am-btn am-btn-warning am-radius am-btn-xs"  href="/evaluation_questions/view">
					<span class="am-icon-plus">&nbsp;添加</span>
				</a>
			</div>
			<div class="am-fr">
				<a  class="am-btn am-btn-warning am-radius am-btn-xs"  href="/evaluation_questions/upload">
					<span class="am-icon-plus">&nbsp;批量导入</span>
				</a>
			</div>
		</div>
	</div>
		<!--列表开始-->
		<div class="am-g questions_list">
			<ul class="am-list">
				<?php if(isset($question_list)){foreach($question_list as $k=>$v){?>
						<li style="">
							<div class="am-g">
								<div class="am-u-lg-1am-u-md-1 am-u-sm-1"></div>
								<div class="am-u-lg-6 am-u-md-5 am-u-sm-5" style="/*overflow:hidden;*/white-space: nowrap;text-overflow: ellipsis;">
								<label style="display:inline-block;" class="am-checkbox am-secondary">
										<input type="checkbox"  class="checkbox" value="<?php echo $v['UserQuestion']['id']?>"  data-am-ucheck/>
									</label>
									<span class="font_length"><?php echo htmlspecialchars($v['UserQuestion']['name'])?></span>
								</div>
								<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center" style="padding:0;"><?php echo $v['UserQuestion']['question_type']==0?'单选题':'多选题';?></div>
								<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center" style="padding:0;">
									<div class="<?php echo $v['UserQuestion']['status']==0?'':'red';?>"><?php echo $v['UserQuestion']['status']==0?'未审核':'已审核';?></div>
								</div>
								<div class="am-u-lg-2 am-hide-md-only am-hide-sm-only riqi am-text-center"  style="padding:0;"><?php echo date("Y年m月d日",strtotime($v['UserQuestion']['created']));?></div>
								<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-center caozuo"  style="padding:0;">
									<a class="<?php echo $v['UserQuestion']['status']==0?'am-hide':'am-show';?>" href="/evaluation_questions/view/<?php echo $v['UserQuestion']['id']?> ">查看</a>
									<a class="<?php echo $v['UserQuestion']['status']==0?'am-show':'am-hide';?>" href="/evaluation_questions/view/<?php echo $v['UserQuestion']['id']?>" >编辑</a>
									<a onclick="questions_remove(<?php echo $v['UserQuestion']['id']?>);">删除</a>
								</div>
								<div class="am-cf"></div>
							</div>
						</li>
				<?php }}?>
			</ul>
			<?php if(isset($question_list)&&sizeof($question_list)>0){?>
			<div class="am-g" style="margin-left:10px;">
					<div class="am-u-sm-3" style="padding:0;min-width: 130px;"><a class="delete_all" onclick="questions_remove_all();">批量删除</a></div>
					<div class="am-u-sm-9"><?php echo $this->element('pager');?></div>
					<div class="am-cf"></div>
			</div>
			<?php }else{ ?>
					<div class="kongbai">当前没有题目</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
function questions_remove(question_id)
{
	if(confirm(confirm_delete)){
		$.ajax({
			url: web_base+"/evaluation_questions/remove",
			type:"POST",
			dataType:"JSON", 
			data:{'question_id':question_id},
			success: function(data){
				if(data.code=='1')
				{
					location.reload();
				}
				else
				{
					alert(data.message);
				}
			}
		});
	}
}

//批量删除
function questions_remove_all()
{
var question_id = [];
	$(".checkbox:checked").each(function() {
             question_id.push($(this).val());
        });
        //alert(question_id.length);
        if(question_id.length>0)
        {
        	if(confirm(confirm_delete)){
		$.ajax({
			url: web_base+"/evaluation_questions/remove",
			type:"POST",
			dataType:"JSON", 
			data:{'question_id':question_id},
			success: function(data){
				if(data.code=='1')
				{
					location.reload();
				}
				else
				{
					alert(data.message);
				}
			}
		});
	}
		}
}

var font_length = document.querySelectorAll('.font_length');
for(var i = 0;i<font_length.length;i++){
	if(font_length[i].innerText.length>30){
		str=font_length[i].innerText.substring(0,30)+"...";
		font_length[i].innerText = str;
	}
}
</script>