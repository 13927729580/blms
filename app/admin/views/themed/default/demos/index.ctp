<div class='ListSearchPage'>
	<?php echo $form->create('',array('action'=>'/','class'=>'am-form am-form-horizontal ListSearchForm','type'=>'get'));?>
	<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>下拉框</label>
			<div class='am-u-lg-6 am-u-md-6 am-u-sm-8 am-padding-right-0 am-padding-left-0'>
				<select data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data'] ?> '}">
					<option value='0'><?php echo $ld['all_data']; ?></option>
					<?php for($i=0;$i<10;$i++){ ?>
					<option value="<?php echo $i; ?>"><?php echo '选项'.$i; ?></option>
					<?php } ?>
				</select>
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>日期</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<div class="am-input-group am-input-group-sm">
					<input type="text" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-default" type="button" onclick="clearSearchTime(this)"><span class="am-icon-remove"></span> </button>
					</span>
				</div>
			</div>
			<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-padding-right-0 am-padding-left-0 am-padding-top-xs am-text-center'>-</div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<div class="am-input-group am-input-group-sm">
					<input type="text" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-default" type="button" onclick="clearSearchTime(this)"><span class="am-icon-remove"></span> </button>
					</span>
				</div>
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>价格</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<input type='text' />
			</div>
			<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-padding-right-0 am-padding-left-0 am-padding-top-xs am-text-center'>-</div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<input type='text' />
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>关键字</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6  am-padding-right-0 am-padding-left-0">
				<input type='text' placeholder="关键字" />
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>&nbsp;</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-padding-right-0 am-padding-left-0">
				<button type='submit' class='am-btn am-btn-success am-btn-sm am-radius'><?php echo $ld['search']; ?></button>
			</div>
		</li>
	</ul>
	<p class='am-text-left am-margin-xs am-padding-left-sm'>
		<a href='javascript:void(0);' onclick="advanced_search_toggle(this);"><?php echo $ld['advanced_search']; ?>&nbsp;<span class='am-icon am-icon-angle-down'></span></a>
	</p>
	<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 advanced_search">
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>分组多选</label>
			<div class='am-u-lg-6 am-u-md-6 am-u-sm-8 am-padding-right-0 am-padding-left-0'>
				<select multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data'] ?> '}">
					<optgroup label="水果">
						<option value="a">Apple</option>
						<option value="b">Banana</option>
						<option value="o">Orange</option>
						<option value="m">Mango</option>
					</optgroup>
					<optgroup label="装备">
						<option value="phone">iPhone</option>
						<option value="im">iMac</option>
						<option value="mbp">Macbook Pro</option>
					</optgroup>
				</select>
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>日期</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<div class="am-input-group am-input-group-sm">
					<input type="text" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-default" type="button" onclick="clearSearchTime(this)"><span class="am-icon-remove"></span> </button>
					</span>
				</div>
			</div>
			<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-padding-right-0 am-padding-left-0 am-padding-top-xs am-text-center'>-</div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<div class="am-input-group am-input-group-sm">
					<input type="text" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-default" type="button" onclick="clearSearchTime(this)"><span class="am-icon-remove"></span> </button>
					</span>
				</div>
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>价格</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<input type='text' />
			</div>
			<div class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-padding-right-0 am-padding-left-0 am-padding-top-xs am-text-center'>-</div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-right-0 am-padding-left-0">
				<input type='text' />
			</div>
		</li>
		<li>
			<label class='am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label'>关键字</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-padding-right-0 am-padding-left-0">
				<input type='text' />
			</div>
		</li>
	</ul>
	<?php echo $form->end(); ?>
	<div class='am-u-lg-2 am-u-md-3 am-u-sm-3 am-padding-bottom-lg'>
		<div class='am-text-default am-margin-top-sm'>分类</div>
		<hr class='am-margin-top-xs am-margin-bottom-xs' />
		<ul class="am-avg-sm-1 am-category-tree">
			<?php for($x=1;$x<=5;$x++){ ?>
			<li class='am-parent'>
				<i class='am-icon am-icon-angle-down'></i><?php echo $html->link('分类'.$x,''); ?>
				<ul class='am-avg-sm-1'>
					<?php for($y=1;$y<=5;$y++){ ?>
					<li><?php echo $html->link('分类'.$x.$y,''); ?></li>
					<?php  } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
	</div>
	<div class='am-u-lg-10 am-u-md-9 am-u-sm-9'>
		<p class='am-text-right am-margin-top-sm'>
			<a class="am-btn am-btn-warning am-btn-xs am-radius" href="javascript:void(0);" data-am-modal="{target: '#demoUpload', closeViaDimmer: 1}">
				<span class="am-icon-plus"></span> <?php echo $ld['batch_upload'] ?>
			</a>
			<a class="am-btn am-btn-warning am-btn-xs am-radius" href="<?php echo $html->url('/demos/view'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a>
		</p>
		<div class='dataList'>
			<div class='dataHead'>
				<div class='am-u-lg-3 am-u-md-3 am-u-sm-3'><label class='am-checkbox am-success am-margin-0'><input type='checkbox' data-am-ucheck />列名1</label></div>
				<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>列名2&nbsp;<a href=''><i class='am-icon am-icon-sort-up'></i></a></div>
				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'>列名3</div>
				<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>列名4&nbsp;<a href=''><i class='am-icon am-text-default am-icon-sort-down'></i></a></div>
				<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'>列名5</div>
				<div class='am-u-lg-2 am-u-md-3 am-u-sm-3'>操作</div>
				<div class='am-cf'></div>
			</div>
			<div class='dataBody'>
				<?php
					for($x='a';$x<='g';$x++){
				?>
				<div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3'>
						<div class='am-fl am-padding-right-sm'>
							<label class='am-checkbox am-success am-margin-0'>
								<input type='checkbox' data-am-ucheck /><?php echo $html->image($configs['shop_default_img']); ?>
							</label>
						</div>
						<div class='am-fl'><?php echo '列1'.$x; ?><br ><span style='color:#ccc;'><?php echo '列1-'.$x; ?></span></div>
						<div class='am-cf'></div>
					</div>
					<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><?php echo '列2'.$x; ?></div>
					<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'><span class='am-icon am-icon-check am-text-success'></span></div>
					<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><?php echo date('Y-m-d'); ?></div>
					<div class='am-u-lg-1 am-u-md-1 am-u-sm-1'>
                                            <a class='am-text-danger'>▲</a>&nbsp;<a class='am-text-success'>▼</a>
					</div>
					<div class='am-u-lg-2 am-u-md-3 am-u-sm-3'>
						<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/demos/view'); ?>">
							<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
						</a>
						<a class="am-btn am-btn-default am-btn-xs am-text-danger" href="">
							<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
						</a>
					</div>
					<div class='am-cf'></div>
				</div>
				<?php
					}
				?>
				<div class='noData'>
					<div class='am-u-sm-12 am-text-center'><?php echo $ld['no_data_found']; ?></div>
				</div>
			</div>
			<div class='dataFooter'>
				<div class='am-fl am-padding-top-xs am-padding-right-sm'>
					<label class='am-checkbox am-success am-margin-0'><input type='checkbox' data-am-ucheck /><?php echo $ld['select_all']?></label>
				</div>
				<div class='am-fl'>
					<select id='batch_operate_type' data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>',btnSize:'xs'}">
						<option value='0'><?php echo $ld['batch_operate']?></option>
						<option value='1'><?php echo $ld['batch_delete']?></option>
						<option value='1'><?php echo $ld['batch_export']?></option>
					</select>
				</div>
				<div class='am-fl am-margin-left-sm'><button type='button' class='am-btn am-btn-danger am-btn-xs' onclick="batch_operate(this)"><?php echo $ld['submit']?></button></div>
				<div class='am-fr'>
					<?php echo $this->element('pagers');?>
				</div>
				<div class='am-cf'></div>
			</div>
		</div>
	</div>
	<div class='am-cf'></div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="demoUpload">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $ld['batch_upload'] ?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <form action="<?php echo $html->url('/demos/uploadpreview'); ?>" method='post' class='am-form am-form-horizontal'>
    		<div class='am-form-group'>
    			<input type='file' />
    		</div>
   		<div class='am-form-group'>
    			<?php echo $html->link($ld['download_example_batch_csv'],"");?>
    		</div>
    		<div class='am-form-group'>
    			<button type='submit' class='am-btn am-btn-success am-btn-sm am-radius'><?php echo $ld['upload'] ?></button>
    		</div>
      </form>
    </div>
  </div>
</div>

<style type='text/css'>
.ListSearchPage,.ListSearchPage>form{background:#f8f8f8;}
.ListSearchPage>div[class*=am-u-]{background:#fff;}
.ListSearchPage>form+div[class*=am-u-],.ListSearchPage>form+div[class*=am-u-]+div[class*=am-u-]{border-left:1rem solid #f8f8f8;border-right:1rem solid #f8f8f8;}
.ListSearchPage>form+div[class*=am-u-]+div[class*=am-u-]{border-left:none;}
form.ListSearchForm{padding:1rem;padding-bottom:0.5rem;margin-bottom:10px;}
form.ListSearchForm ul[class*=am-avg-]>li{margin-bottom:10px;}
form.ListSearchForm ul[class*=am-avg-]>li label.am-form-label{padding-left:0px;}
form.ListSearchForm .advanced_search{display:none;}
form.ListSearchForm ul[class*=am-avg-] div.am-input-group input[type='text'][data-am-datepicker]{padding:0.5em 0.25rem;}
form.ListSearchForm ul[class*=am-avg-] div.am-input-group input[type='text'][data-am-datepicker]+span.am-input-group-btn button{padding-left:0.5rem;padding-right:0.5rem;}
form.ListSearchForm .am-selected-btn.am-btn-default{background:#fff;}

ul.am-category-tree{border:1px solid #ccc;border-radius:5px;}
ul.am-category-tree>li{border-bottom:1px solid #ccc;padding:0.5rem;}
ul.am-category-tree>li.am-parent{cursor:pointer;}
ul.am-category-tree>li i.am-icon{margin-right:0.5rem;}
ul.am-category-tree>li:last-child{border-bottom:none;}
ul.am-category-tree li a,ul.am-category-tree li a:hover{color:#333;}
ul.am-category-tree>li.am-parent ul{display:none;margin-left:1.5rem;}
ul.am-category-tree>li.am-parent ul.am-in{display:block;}
ul.am-category-tree>li.am-parent ul li{padding:0.25rem;}
ul.am-category-tree>li.am-parent ul li a:hover{text-decoration:underline;}

div.dataList{width:100%;margin:0 auto;margin-bottom:1rem;}
div.dataList>div.dataHead div[class*=am-u-]{padding:0.25rem 0px;}
div.dataList div.dataHead,div.dataList div.dataBody>div{border-bottom:1px solid #ccc;}
div.dataList div.dataBody>div:last-child{border:none;}
div.dataList>div.dataHead,div.dataList>div.dataHead label.am-checkbox{font-weight:600;}
div.dataList>div.dataHead label.am-checkbox,div.dataList>div.dataBody label.am-checkbox{min-height:1.8rem;}
div.dataList>div.dataBody div[class*=am-u-]{padding:0.5rem 0px;}
div.dataList>div.dataBody div[class*=am-u-] img{max-width:50px;}
div.dataList>div.dataBody div[class*=am-u-] span.am-icon{cursor:pointer;}
div.dataList>div.dataFooter{margin-top:1rem;}
div.dataList>div.dataFooter>div.am-fl button{margin-top:4px;}
</style>
<script type='text/javascript'>
//去除搜索时间
function clearSearchTime(btn){
	$(btn).parents('div.am-input-group').find("input[type='text']").val('');
}

$("ul.am-category-tree>li.am-parent a,ul.am-category-tree>li.am-parent i.am-icon").on('click',function(){
	var treeRow=$(this).parent();
	var treeIcon=$(treeRow).find("i.am-icon");
	var subTree=$(treeRow).find("ul");
	if(subTree.hasClass('am-in')){
		treeIcon.removeClass('am-icon-angle-up').addClass('am-icon-angle-down');
		subTree.removeClass('am-in');
	}else{
		treeIcon.removeClass('am-icon-angle-down').addClass('am-icon-angle-up');
		subTree.addClass('am-in');
	}
	return false;
});

$("div.dataHead input[type='checkbox'],div.dataFooter input[type='checkbox']").on('click',function(){
	var dataList=$(this).parents('div.dataList');
	if($(this).prop('checked')){
		dataList.find("div.dataBody input[type='checkbox']").prop('checked',true).uCheck('check');
	}else{
		dataList.find("div.dataBody input[type='checkbox']").prop('checked',false).uCheck('uncheck');
	}
});

$("div.dataBody span.am-icon").on('click',function(){
	if($(this).hasClass('am-icon-check')){
		$(this).removeClass('am-icon-check am-text-success').addClass('am-icon-times am-text-danger');
	}else{
		$(this).removeClass('am-icon-times am-text-danger').addClass('am-icon-check am-text-success');
	}
});

function batch_operate(btn){
	var batch_operate_type=$('#batch_operate_type').val();
	if(batch_operate_type=='0')return;
	if(confirm(confirm_operation)){
		
	}
}

function advanced_search_toggle(btn){
	$(".advanced_search").toggle();
	if($(".advanced_search").is(':visible')){
		$(btn).find('span.am-icon').removeClass('am-icon-angle-down').addClass('am-icon-angle-double-down');
	}else{
		$(btn).find('span.am-icon').removeClass('am-icon-angle-double-down').addClass('am-icon-angle-down');
	}
}
</script>