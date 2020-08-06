<?
$total_pages=ceil($total_cnt/$limit);
$total_count	= ceil($total_pages/10);	// 전체 페이지 그룹값
$now_page	= ceil($page/10);				// 현재 페이지가 속해있는 페이지 단위
$start_page	= ($now_page-1)*10+1;	// 시작 페이지
$end_page	= $start_page+(10-1);		// 마지막 페이지
if ($end_page > $total_pages)
	$end_page = $total_pages;	// 종료페이지가 총 페이지보다 크면 종료페이지값에 마지막 페이지 값을 넣는다.


//첫페이지로 가기.
if($total_pages > 1 && $page > 1)
{
	//echo "<a href=$PHP_SELF?page=1&$get_url>처음</a>&nbsp;";
}
						

// 페이지가 10를 넘으면(페이지 카운터가 10이상이면) 이전 페이지 표시.
if ($page>10) 
{
	$newpage=$start_page-1;
	//echo "<a href=$PHP_SELF?page=$newpage&$get_url> << </a>&nbsp;";
}
						 

//이전 페이지로 가기
if($page != '' && $total_cnt > 0)
{
	if($page != 1) {
		$pre_page = $page-1;
	}else{
		$pre_page = 1;
	}
	echo "<li class='boxPagingPrev'><span><a href=$PHP_SELF?page=$pre_page&$get_url><strong>&lt;</strong></a></span></li><li class='boxPagingNum'>";
}											  						  
						  

// 현재 페이지의 페이지 링크를 뿌린다.
for ($i=$start_page;$i<=$end_page;$i++) 
{			  							

	if ($i!=$page)
	{
		echo "<a href=$PHP_SELF?page=$i&$get_url>";
	    echo "[${i}]";
 		echo "</a>";
  	}
	else
	{
		echo "<a href='#;'><strong>[${i}]</strong></a>";
	}							  							  							

}//for



//다음페이지로 가기
if($page<=$total_pages)
{
		if($page == $total_pages) {
			$next_page=$total_pages;
		}else{
			$next_page=$page+1;
		}
    echo "</li><li class='boxPagingNext'><span><a href=$PHP_SELF?page=$next_page&$get_url><strong>&gt;</strong></a></span></li>";
}

						

// 전체 페이지가 $page_scale를 넘거나 마지막 페이지 카운터가 아니면 다음 버튼 표시.

if($total_pages > 10  && $end_page != $total_pages)
{
	$newpage=$end_page+1;
	//echo "&nbsp;<a href=$PHP_SELF?page=$newpage&$get_url> >> </a>";
}


//마지막 페이지로 가기.
if($total_pages > 1 && $page < $end_page)
{
	//echo "&nbsp;<a href=$PHP_SELF?page=$total_pages&$get_url>마지막</a>";
}
?>