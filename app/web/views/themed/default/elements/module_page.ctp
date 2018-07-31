<?php
	if(isset($paging)&&!empty($paging)){
		if(isset($paging['pageCount'])&&$paging['pageCount']>0){
?>
<style>
	.pages
	{
		text-align: center;
	}
	.pages em
	{
		background: #149842;
		border:none;
	}
	.pages>em:hover
	{
		cursor:pointer;
	}
	.pages>span:hover
	{
		cursor:pointer;
	}
</style>
<div class="pages am-pagination-center">
<?php
	if($pagination->setPaging($paging)){
		$leftArrow = $ld['previous'];
		$rightArrow = $ld['next'];
		$prev = $pagination->prevPage($leftArrow,false);
		$prev = $prev?$prev:$leftArrow;
		$next = $pagination->nextPage($rightArrow,false);
		$next = $next?$next:$rightArrow;
		$pages = $pagination->pageNumbers("  ");
		//echo $pagination->result()."<br>";
		echo $prev." ".$pages." ".$next;
		//echo $pagination->resultsPerPage(NULL, ' ');
	}
?>
</div>
<?php
		}
	}
?>