<?php //pr($fav_articles) ?>
<div id="fav_articles">
<?php if(isset($fav_articles)&&sizeof($fav_articles)>0){?>
  <ul class="" style="margin:0;">
	<li>
	  <em>全部文章
		<?php if(isset($paging['total'])){?>
		(<?php echo $paging['total'];?>)
		<?php }?>
	  </em>
	</li>
  </ul>
<?php }?>
<?php if(sizeof($fav_articles)>0) {?>
  <table name="fav_article" class="am-table am-table-striped am-table-hover">
	<tr>
	  <th width="30"><input type="checkbox" name="articleall" value="checkbox" /></th>
	  <th>文章标题</th>
	  <th width="100" class="am-hide-sm-only am-text-center">文章作者</th>
	  <th class="am-text-center" style="white-space:nowrap">内容简介</th>
	  <th class="am-text-center operationstyle" style="width:100px;">查看详情</th>
	</tr>
	<?php foreach ($fav_articles as $k=>$v){?>
	<form name="article_<?php echo $v['Article']['id']?>" id="" method="post">
	<tr>
	  <td><input type="checkbox" name="checkbox_article" value="<?php echo $v['Article']['id'] ?>" /></td>
	  <td>
		<?php echo $v['ArticleI18n']['title'] ?>&nbsp;
	  </td>
	  <td class="am-hide-sm-only">
	  	<?php echo $v['ArticleI18n']['author'] ?>&nbsp;
	  </td>
	  <td align="center" class="am-text-truncate" style="max-width:200px;padding-left:20px;">
	  	<?php echo $v['ArticleI18n']['meta_description'] ?>&nbsp;
	  </td>
	  <td class="am-text-center">
		<a class="am-btn am-btn-secondary am-btn-xs" href="<?php echo $html->url('/articles/'.$v['Article']['id']) ?>">
		查看详情
		</a>
		<a class="am-btn am-btn-secondary am-btn-xs" href="javascript:void(0)" onclick="ajax_article(this,'<?php echo $v['Article']['id'] ?>','<?php echo @$_SESSION['User']['User']['id'] ?>')">
		取消收藏
		</a>
	  </td>
	</tr>
	</form>
	<?php }?>
  </table>
<?php }else{?>
  <table name="fav" class="am-table"><tr><td colspan="6" align="center" style="color:#909090;text-align:center;padding-top:28px;border:none;"><?php echo $ld['not_products_article'];?>！</td></tr></table>
<?php }?>

<?php if(sizeof($fav_articles)>0){?>
  <div class="pagenum">

  	<div class="am-btn am-btn-secondary am-btn-xs" style="margin-left:45px;">
		<span class="btncon fl deletehook"  onclick="ajax_article_all()" >批量取消</span>
	</div>

    <div class="pages am-pagination-right">
	  <?php if(isset($paging['total'])){ echo $this->element('pager');}?>
  	</div>
  </div>
<?php }?>
</div>
<script type="text/javascript">
var my_user_id = <?php echo @$_SESSION['User']['User']['id'] ?>;

function ajax_article (ele,type_id,user_id) {
	$.ajax({
		url:web_base+"/articles/ajax_article_disfavorite",
		type:"POST",
		dataType:"json",
		data:{'type_id':type_id,'user_id':user_id},
		success:function (data) {
			if(data.code == 1){
				alert('取消成功');
				favorites_article();
			}
		}
	})
}

function ajax_article_all () {
	var all_article_id = '';
	var all_checked = $("input[type='checkbox'][name='checkbox_article']:checked");
	if (all_checked.length>0) {
	for(i = 0;i < all_checked.length; i++){
	all_article_id+= ','+all_checked[i].value;
	}
	var substring = all_article_id.substring(1);
	console.log(substring);
	console.log(my_user_id);
	$.ajax({
		url:web_base+"/articles/ajax_article_disfavorite",
		type:"POST",
		dataType:"json",
		data:{'type_id':substring,'user_id':my_user_id},
		success:function (data) {
			if(data.code == 1){
				alert('取消成功');
				favorites_article();
			}
		}
	})
	}else{
	alert('请选择文章');
	}
}

	$("#fav_articles").find(".pages a").click(function(){
    var ajax_fav_articles=$(this).attr('href');
   	loadAttrOptionCase(ajax_fav_articles);
	return false;
});

function loadAttrOptionCase(ajax_fav_articles){
    $.ajax({ 
    	url: ajax_fav_articles,
		type:"POST", 
        dataType:"html",
		data: { },
		success: function(data){
				try{
                    $("#fav_articles").parent().html(data);
    			}catch (e){
    				alert(data);
    			}
      		}
      	});
}

$("input[type=checkbox][name='articleall']").click(function() {
    $('input[name="checkbox_article"]').prop("checked",this.checked);
});

var $subBox = $("input[name='checkbox_article']");
$subBox.click(function(){
    $("input[type=checkbox][name=articleall]").prop("checked",$subBox.length == $("input[name='checkbox_article']:checked").length ? true : false);
});

</script>