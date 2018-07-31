<style type='text/css'>
#user_favorite .am-tab-panel{min-height:80px;}
#user_favorite .imgout a img{width: 50px;margin:0.5rem auto;}
#user_favorite .am-nav>li.am-active>a{color: #0e90d2;}
#user_favorite .am-nav>li.am-active>a,#user_favorite .am-nav>li.am-active>a:focus,#user_favorite .am-nav>li.am-active>a:hover{color: #0e90d2;}
</style>
<div class="am-tabs" data-am-tabs="{noSwipe: 1}" id="user_favorite">
	<ul class="am-tabs-nav am-nav am-nav-tabs am-nav-tabs_1">
		<li class="am-active"><a href="#favorites_collect" style="margin-right:0px;"><?php echo $ld["product"]?></a></li>
		<li><a href="#favorites_article" style="margin-right:0px;"><?php echo $ld["article"]?></a></li>
		<li><a href="#favourite_course" style="margin-right:0px;">课程</a></li>
	</ul>
 	<div class="am-tabs-bd">
		<div class="am-tab-panel am-active" id="favorites_collect">
			<div class="progress">
				<?php if(isset($fav_products)&&sizeof($fav_products)>0){ ?>
				  <ul class="" style="margin:0;">
					<li>
					  <p style="margin-bottom: 10px;"><?php echo $ld["all_goods"]?>
						<?php if(isset($paging['total'])){?>
						(<?php echo $paging['total'];?>)
						<?php }?>
					  </p>
					</li>
				  </ul>
				<?php }?>
				<?php if(isset($fav_products)&&sizeof($fav_products)>0){ ?>
				  <table name="fav" class="am-table am-table-striped">
					<?php foreach ($fav_products as $k=>$v){?>
					<form name="buy_nowproduct<?php echo $v['Product']['id']?>" id="buy_nowproduct<?php echo $v['Product']['id']?>" method="post" action="<?php echo $this->webroot;?>carts/buy_now">
					  <input type="hidden" name="type" value="product" />
					  <input type="hidden" name="id" value="<?php echo $v['Product']['id'];?>" />
					  <input type="hidden" name="quantity" value="1" />
					<tr>
					  	<td width='30'><input type="checkbox" name="checkbox[]" value="<?php echo $v['UserFavorite']['id']?>" style="margin-left: 10px;" /></td>
						<td class='am-text-center' width='15%'>
							<div class="imgout"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['Product']['img_thumb'],'name'=>$v['ProductI18n']['name']));?>
							</div>
						</td>
						<td width='25%'><p class="pro_name am-hide-sm-only"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name']));?></p></td>
					  	<td width='100' class="am-hide-sm-only" style="text-align: center;"><?php echo date("Y-m-d",strtotime($v['UserFavorite']['created']))?></td>
					  	<td  style="text-align: center;" align="center"><nobr><?php echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);?>
							<?php if(isset($v['Product']['off'])&&sizeof($v['Product']['off']&&$v['Product']['off']!=100)>0){ printf($ld['sale_off'],$v['Product']['off']);echo $svshow->image("/img/green/redjiantou.jpg",array("alt"=>""));  }?>
							</nobr>
						</td>
					  	<td width='120' class="am-text-center">
						  	<?php if(constant("Product")=="AllInOne"){?>
							<a href="javascript:void(0);" class="am-btn am-btn-secondary am-btn-xs am-margin-xs" onclick="buy_now_no_ajax(<?php echo $v['Product']['id']?>,1,'product')"><?php echo $ld["buy"]?></a><br><?php } ?>
							<a class="am-btn am-btn-danger am-btn-xs" href="javascript:del_fav_products(<?php echo $v['Product']['id']?>,'<?php echo $user_id?>','p','<?php echo $this->webroot;?>')"><?php echo $ld["delete"]?></a>
						  </td>
						</tr>
					</form>
					<?php }?>
				  </table>
				<?php }else{?>
				  <table name="fav" class="am-table" >
				  	  <tr style="height:70px;">
				  	  	<td colspan="6" align="center" style="color:#909090;text-align:center;border:none;"><?php echo $ld['not_products_collection'];?>！</td>
				  	  </tr>
				  </table>
				<?php } ?>
				<?php if(sizeof($fav_products)>0){?>
				<div class="pagenum">
					<div class="am-btn am-btn-secondary am-btn-xs" style="margin-left:45px;">
						<span class="btncon fl deletehook"  onclick="diachange('delete')" ><?php echo $ld["check_the_products_deleted"]?></span>
					</div>
					<div class="pages am-pagination-right">
						<?php if(isset($paging['total'])){ echo $this->element('pager');}?>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<div class="am-tab-panel" id="favorites_article">
			<table name="fav" class="am-table">
				<tr>
					<td  colspan="6" align="center" style="color:#909090;text-align:center;border:none;padding-top: 28px;border:none;"><?php echo $ld['not_products_article'];?></td>
				</tr>
			</table>
		</div>
		<div class="am-tab-panel" id="favourite_course">
			<table class="am-table">
				<tr>
					<td  colspan="6" align="center" style="color:#909090;text-align:center;border:none;padding-top: 28px;border:none;">暂无收藏!</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
//文章列表ajax
function diachange(obj){
	if(obj!=''){
		var id=document.getElementsByName('checkbox[]');
		var i;
		var j=0;
		var aa="";
		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				aa+=","+id[i].value
				j++;
			}
		}
		
		if( j>=1 ){
			if(confirm('<?php echo $ld['ok']?>'+obj+'?')){
				batch_action(aa.substring(1),obj);
			}
		}else{
			alert('<?php echo $ld['please_select']?>!');
		}
	}
}

var batch_Success = function(data){
	//var result = eval('('+data+')');//把返回的Jason text转换成object(array类型)
	//box.Close();
//	msg_box.Show();
	if(data=="1")
	{
		location.reload();
	}
	else
	{
		alert("<?php echo $ld['delete_failed']?>!");
	}
//	document.getElementById('message_content').innerHTML = data.message;
}
//操作员复选框全部选取
$("input[type=checkbox][name='chkall']").click(function() {
    $('input[name*="checkbox"]').prop("checked",this.checked);
});
var $subBox = $("input[name*='checkbox']");
$subBox.click(function(){
    $("input[type=checkbox][name=chkall]").prop("checked",$subBox.length == $("input[name*='checkbox']:checked").length ? true : false);
});

function batch_action(aa,obj){
	//box.Show(); ;
	var sUrl = web_base+"/favorites/batch/"+aa+"/"+obj;
	var postData ={
		is_ajax:1
	};
	$.post(sUrl, postData, batch_Success,'text')
}

favorites_article();
function favorites_article(){
	$.ajax({
		url:web_base+ "/favorites/favourite_article",
		type:"POST",
		dataType:"html",
		data: {},
		success: function(result){
			$("#favorites_article").html(result);
		}
	});
}

favorites_course();
function favorites_course(){
	$.ajax({
		url:web_base+ "/favorites/favourite_course",
		type:"POST",
		dataType:"html",
		data: {},
		success: function(result){
			$("#favourite_course").html(result);
		}
	});
}
</script>
