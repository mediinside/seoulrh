<?
CLASS ListClass
{
	private $DB;
	private $GP;

	private $page_info = array();
	private $conf_info = array();

	function __construct() {
		global $C_DB , $GP;
		$this -> DB = $C_DB;
		$this -> GP = $GP;
	}

	function setVals ($args) {
		extract($args);
		if (!$q_idx) die('IDX 설정이 없습니다.');

		$this -> conf_info = $args;
		$this -> conf_info['page'] 			= ($page)? $page : 1;
		$this -> conf_info['show_row'] 	= (isset($show_row))? $show_row : $this -> GP -> LIST_ROW;
		$this -> conf_info['show_page'] = ($show_page)? $show_page : $this -> GP -> LIST_PAGE;
		$this -> conf_info['q_where'] 	= ($q_where)? ' WHERE ' . $q_where : '';
		$this -> conf_info['q_group'] 	= ($q_group)? ' GROUP BY ' . $q_group : '';
		$this -> conf_info['q_order'] 	= ($q_order)? ' ORDER BY ' . $q_order : '';
		$this -> conf_info['search_rst'] 	= array($sc_type, $sc_val);
		$this -> conf_info['list_dsp_none'] 	= $list_dsp_none;
		$this -> conf_info['search_match'] 	= $search_match;
		$this -> conf_info['totalCount'] 	= $totalCount;
		$this -> conf_info['union'] 	= $union;
	}

	function listInfo ($args) {
		$args['search_rst'] = array($args['sc_type'], $args['sc_val']);
		//print_r($args['search_rst']);
		$this -> setVals($args);
		extract($this -> conf_info);

		// QRY
		$qry_def = $this -> conf_info['q_str'] . $this -> conf_info['q_order'];

		if ($excel_file) {
			$qry_get = $this -> getQuery('excel');
		} else {
			// 검색어가 있을경우
			if (!empty($search_rst[0])) {
				$qry_get = $this -> getQuery('search');
			} else {
				$qry_get = $this -> getQuery();
			}
        }
        //print_r($qry_get);

		if ($this -> conf_info['q_see']) {
			print_r($qry_get);// die($qry_get);
			exit;
		}

		if (!$list_dsp_none || $sc_type) {
			$data = $this -> DB -> execSqlList ($qry_get);
		}


		if($excel_file)
		{
			$args = '';
			$args['excel'] = $excel;
			$args['excel_add'] = $excel_add;
			$args['data'] = $data;
			$this -> getExcel($args);
			exit;
		}
		if($args['ajax'] == true){

			if($args['pagetype'] != '') {
				$this -> getPageLinkAjaxList($args['pagetype']);
			}else{
				$this -> getPageLinkAjax();
			}
		}else if($args['mobile'] == true) {
			$this -> getMobilePageLink();
		}else{
			if($args['pagetype'] == 'main_search') {
				$this -> getPageMainSearchLinkAjax();
			} else if($args['pagetype'] == 'admin'){
				$this -> getPageLink_20131008();
			}else if($args['pagetype'] == 'comment'){			
				$this -> getCommPageLink();
			}else{
				$this -> getPageLink();
			}
		}

		$this -> getSearchForm();

		$rst['data'] 				= $data;
		$rst['page_info'] 	= $this -> page_info;

		return $rst;
	}

	function listInfoCustomer ($args) {
		$args['search_rst'] = array($args['sc_type'], $args['sc_val']);
		//print_r($args['search_rst']);
		$this -> setVals($args);
		extract($this -> conf_info);

		## KB 데이터를 위한 쿼리
		if($args['search_st_name'] && $args['union'] == true) {
			$this -> conf_info['union_where'] 	= "st_name like '%".$args['search_st_name']."%'";
		}

		// QRY
		$qry_def = $this -> conf_info['q_str'] . $this -> conf_info['q_order'];

		if ($excel_file) {
			$qry_get = $this -> getQuery('excel');
		} else {
			// 검색어가 있을경우
			if (!empty($search_rst[0])) {
				$qry_get = $this -> getQuery('search');
			} else {
				$qry_get = $this -> getQuery();
			}
		}

		if ($this -> conf_info['q_see']) {
			print_r($qry_get);// die($qry_get);
			exit;
		}


		if (!$list_dsp_none || $sc_type) {
			$data = $this -> DB -> execSqlList ($qry_get);
		}


		if($excel_file)
		{
			$args = '';
			$args['excel'] = $excel;
			$args['excel_add'] = $excel_add;
			$args['data'] = $data;
			$this -> getExcel($args);
			exit;
		}
		if($args['ajax'] == true){

			if($args['pagetype'] != '') {
				$this -> getPageLinkAjaxList($args['pagetype']);
			}else{
				$this -> getPageLinkAjax();
			}
		}else if($args['mobile'] == true) {
			$this -> getMobilePageLink();
		}else{
			if($args['pagetype'] == 'main_search') {
				$this -> getPageMainSearchLinkAjax();
			}else{
				$this -> getPageLink();
			}
		}

		$this -> getSearchForm();

		$rst['data'] 				= $data;
		$rst['page_info'] 	= $this -> page_info;

		return $rst;
	}



	function addWhere ($str = '') {
		extract($this -> conf_info);
		if ($str) $this -> conf_info['q_where'] .= (($q_where)? ' AND ' : ' WHERE ') . $str;
		return $qry_def = 'SELECT ' . $q_col. ' FROM ' . $q_table. $this -> conf_info['q_where']. $q_group. $q_order;
	}

	function getQuery ($type = '') {
		extract($this -> conf_info);

		if (!$list_dsp_none || $sc_type) {
			if ($search_rst[0]) {
				if (preg_match("/\//",$search_rst[1])) {
					$targets = explode('/',$search_rst[1]);

					$i = 0;
					foreach ($targets as $v) {
						$addBar = ($i)? ',' : '';
						$target_str .= $addBar . "'$v'";
						$i = 1;
					}
					$str = " " . $search_rst[0]." IN (" . $target_str . ")";

				} else {
					if(is_array($this -> conf_info['search_match']) && in_array($search_rst[0],$this -> conf_info['search_match']))
						$str = " " . $search_rst[0]." = '" . $search_rst[1] . "'";
					else
						//$str = "REPLACE(" . $search_rst[0].", ' ','') like '%" . $search_rst[1] . "%'";
						$str = $search_rst[0]." like '%" . $search_rst[1] . "%'";
				}
			}
			//echo print_r($str);
			$rst = $this -> addWhere ($str);

			$this -> setCounts ();
			$this -> getCounts ();
			if ($this -> conf_info['show_row']) {
				$add_limit = " LIMIT " . $this -> page_info['start_limit'] . ", " . $this -> conf_info['show_row'];
			}

			//$rst .= $add_limit;
			// 엑셀이 아닌경우에만 limit
			if ($type != 'excel') $rst .= $add_limit;
		}
		return $rst;
	}


	// 검색 형식 설정
	function searchTypeChoice ($col,$val) {
		if(is_array($this -> conf_info['search_match']) && in_array($col,$this -> conf_info['search_match']))
			$str = " " . $col." = '" .$val . "'";
		else
			$str = "REPLACE(" . $col.", ' ','') like '%" . $val . "%'";

		return $str;
	}

	function setCounts () {
		extract($this -> conf_info);

		$qry = 'SELECT COUNT(' . $q_idx. ') FROM ' . $q_table. $q_where . $q_group;
		if($totalCount){
			$rst = $totalCount;
		}else{
			if ($q_group) {
				$rst = $this -> DB -> execSqlList ($qry);
				$rst = count($rst);
			} else {
				$rst = $this -> DB -> execSqlOneCol ($qry);
			}
		}
		
		$this -> page_info['total'] = $rst;
	}

	// page, total, show_row, show_page
	function getCounts () {
		extract($this -> conf_info);
		extract($this -> page_info);

		$startnum 	= ($page - 1) * $show_row;
		$totalpages	= ceil($total / $show_row);
		$startpage 	= ((ceil(($page / $show_page) - 0.01) - 1) * $show_page) + 1;
		$endpage   	= $startpage + ($show_page - 1);
		$endpage   	= ($totalpages < $endpage) ? $totalpages : $endpage;
		$prevpage  	= ($startpage != 1) ? $startpage - $show_page : 1;
		$nextpage  	= (($endpage + 1) > $totalpages) ? $totalpages : $endpage + 1;
		$startrownum = $total - (($show_row * $page) - $show_row) ;

		$this -> page_info['start_limit'] = $startnum;
		$this -> page_info['start'] = $startpage;
		$this -> page_info['end'] 	= $endpage;
		$this -> page_info['prev'] 	= $prevpage;
		$this -> page_info['next'] 	= $nextpage;
		$this -> page_info['tpage'] = $totalpages;
		$this -> page_info['start_num'] = $startrownum;
		$this -> page_info['totalpages'] = $totalpages;
		$this -> page_info['page'] = $page;
	}
	
	function getPageLink()
	{
		extract($this -> page_info);
		extract($this -> conf_info);
		
		

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}

		if($total > 0)
		{			
			$rst ='<a href="?page='  . $prev . '&' . $tail . '" title="이전 목록"class="btn prev"><span></span></a> ';
			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"btn\"";
				}
				$rst .= ($i != $page)? "<a href='?page=" . $i . '&' . $tail . "' class='btn'>":"<a href='#' class='btn current'>";
				$rst .= ($i == $page)? "$i" : "$i";
				$rst .= ($i != $page)? "</a>":"</a>";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= ' <a href="?page=' . $this -> page_info['next'] . '&'. $tail . '" title="다음 목록" class="btn next"><span></span></a>';

		} else {
			//$rst = ($tqry)?  "<span><font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.</span>":"<span>등록된 데이터가 없습니다.</span>";
		}

		$this -> page_info['link'] = $rst;
	}
	
	function getCommPageLink()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}
			
		if($total > 0)
		{			
			$rst ='<a href="?com_page='  . $prev . '&' . $tail . '" title="이전으로 이동하기"><span class="btn btn-default prev"><<</span></a> ';
			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"page_Last\"";
				}
				$rst .= ($i != $page)? "<a href='?com_page=" . $i . '&' . $tail . "'>":"<a href='javascript:;'>";
				$rst .= ($i == $page)? "<span class='btn btn-default numPage'>$i</span>" : "<span>$i</span>";
				$rst .= ($i != $page)? "</a>":"</a>";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= ' <a href="?com_page=' . $this -> page_info['next'] . '&'. $tail . '" title="다음 페이지 이동하기"><span class="btn btn-default next">>></span></a>';

		} else {
			$rst = ($tqry)?  "<span><font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.</span>":"<span>등록된 데이터가 없습니다.</span>";
		}

		$this -> page_info['link'] = $rst;
	}


	function getPageLink_20131008()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

	//echo "===========================================".$tail;

		if ($search_rst[0] && $search_rst[1]) {
			$tail = 'search_key=' . $search_rst[0] . '&search_content=' . $search_rst[1];
		}
		if($total > 0)
		{
			$rst = '<li class="boxPagingPrev"><span><a href="?page='  . $prev . '&' . $tail . '"><strong>&lt;</strong></a></span></li><li class="boxPagingNum">';
			
			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"page_Last\"";
				}
				$rst .= ($i != $page)? "<a href='?page=" . $i . '&' . $tail . "' $pageLastclass>":"";
				$rst .= ($i == $page)? "<strong>$i</strong>" : "".$i."";
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= '</li><li class="boxPagingNext"><span><a href="?page=' . $this -> page_info['next'] . '&'. $tail . '"><strong>&gt;</strong></a></span></li>';

		} else {
			//$rst = ($tqry)?  "<li class='nonList'><font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.</li>":"<li class='nonList'>등록된 데이터가 없습니다.</li>";
		}

		$this -> page_info['link'] = $rst;
	}

	function getMobilePageLink()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}

		if($total > 0)
		{
			$rst = "";
			$rst .= "<a href='?page="  . $prev . '&' . $tail . "'> ";
			$rst .= "<img alt=이전 src='/img/page_prev.jpg' align='absmiddle'>";
			$rst .= "</a>&nbsp;";

			for($i = $start ; $i <= $end ; $i++){
				$rst .= ($i != $page)? "<a href='?page=" . $i . '&' . $tail . "'>":"";
				$rst .= ($i == $page)? "<strong><span><img src='/img/page_on.jpg' align='absmiddle'></span></strong>" : "<span><img src='/img/page_off.jpg' align='absmiddle'></span>";
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? " " : "";
			}


			$rst .= "&nbsp;<a href='?page=" . $this -> page_info['next'] . '&'. $tail . "'> ";
			$rst .= "<img alt=다음 src='/img/page_next.jpg' align='absmiddle'>";
			$rst .= "</a>";

			//$rst .= '<a class="next" href="?page=' . $this -> page_info['next'] . '&'. $tail . '"><img alt=다음 src="/img/page_next.jpg" align="absmiddle"></a>';

		} else {
			$rst = ($tqry)?  "<font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.":"<br><br>등록된 데이터가 없습니다.</p>";
		}

		$this -> page_info['link'] = $rst;
	}

	// list_page_script
	function getPageLink_old()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}

		if($total > 0)
		{

				$front = ($img_front)? "<img src='$img_front' border='0' align='absmiddle'>":"◀";
				$next 	= ($img_next)? "<img src='$img_next' border='0' align='absmiddle'>":"▶";

				$front_s = ($img_front_s)? "<img src='$img_front_s' border='0' align='absmiddle'>":"◀◀";
				$next_e 	= ($img_next_e)? "<img src='$img_next_e' border='0' align='absmiddle'>":"▶▶";

			//if($addElement) $addElement = "&".$addElement;
			$rst = "<a href='?page=1&" . $tail . "'> ";
			$rst .= $front_s;
			$rst .= "</a>&nbsp;";

			$rst .= "<a href='?page="  . $prev . '&' . $tail . "'> ";
			$rst .= $front;
			$rst .= "</a>&nbsp;";
			for($i = $start ; $i <= $end ; $i++){
				$rst .= ($i != $page)? "<a href='?page=" . $i . '&' . $tail . "'>":"";
				$rst .= ($i == $page)? "<b>$i</b>" : $i;
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? "  " : "";
			}

			$rst .= "&nbsp;<a href='?page=" . $this -> page_info['next'] . '&'. $tail . "'> ";
			$rst .= $next;
			$rst .= "</a>";


			$rst .= "&nbsp;<a href='?page=" . $this -> page_info['tpage'] . '&'. $tail . "'> ";
			$rst .= $next_e;
			$rst .= "</a>";
		} else {
			$rst = ($tqry)?  "<font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.":"등록된 데이터가 없습니다.";
		}

		if ($class) {
			$rst = "<table class='$class'><tr><td>" . $rst . "</td></tr></table>";
		}
		$this -> page_info['link'] = $rst;
	}

	function getPageLinkAjaxList($listname)
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}
		
		if($total > 0)
		{
			$rst = "<li class=\"boxPagingPrev\"><span><a href=\"javascript:" . $listname ."('" . $i . "', '" . $tail . "')\"><strong>&lt;</strong></a></span></li><li class=\"boxPagingNum\">";
			
			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"page_Last\"";
				}
				$rst .= ($i != $page)? "<a href=\"javascript:" . $listname ."('" . $i . "', '" . $tail . "')\" $pageLastclass>":"";
				$rst .= ($i == $page)? "<strong>$i</strong>" : "".$i."";
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= "</li><li class=\"boxPagingNext\"><span><a href=\"javascript:" . $listname ."('" . $this -> page_info['next'] . "', '" . $tail . "')\"><strong>&gt;</strong></a></span></li>";

		} else {
			$rst = ($tqry)?  "<font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.":"<br>등록된 데이터가 없습니다.<br><br>";
		}

		$this -> page_info['link'] = $rst;
	}




	function getPageLinkAjax()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}

		if($total > 0)
		{
			$rst = "<a class=\"pre\" href=\"javascript:parent.go_List('" . $i . "', '" . $tail . "')\"><img alt=이전 src=\"" . $this->GP->IMG_PATH. "/comm_img/btn_page_prev.gif\" width=\"56\" height=\"27\" align=\"absmiddle\"></a>&nbsp;&nbsp;";
			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"page_Last\"";
				}
				$rst .= ($i != $page)? "<a href=\"javascript:parent.go_List('" . $i . "', '" . $tail . "')\" $pageLastclass>":"";
				$rst .= ($i == $page)? "<strong><span>$i</span></strong>" : "<span>".$i."</span>";
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= "&nbsp;&nbsp;<a class=\"next\" href=\"javascript:parent.go_List('" . $this -> page_info['next'] . "', '" . $tail . "')\"><img alt=다음 src=\"" . $this->GP->IMG_PATH. "/comm_img/btn_page_next.gif\" width=\"57\" height=\"27\" align=\"absmiddle\"></a>";

		} else {
			$rst = ($tqry)?  "<font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.":"<br><br>등록된 데이터가 없습니다.</p>";
		}

		$this -> page_info['link'] = $rst;
	}

	function getPageMainSearchLinkAjax()
	{
		extract($this -> page_info);
		extract($this -> conf_info);

		if ($search_rst[0] && $search_rst[1]) {
			$tail .= '&sc_type=' . $search_rst[0] . '&sc_val=' . $search_rst[1];
		}

		if($total > 0)
		{
			$rst  = "<a class=\"pre\" href=\"javascript:parent.myshopSearchListView('" . $i . "', '" . $tail . "')\">";
			$rst .= "<img alt=이전 src=\"" . $GP->IMG_PATH. "/comm_img/btn_page_prev.gif\" width=\"56\" height=\"27\" align=\"absmiddle\"></a> &nbsp;";

			for($i = $start ; $i <= $end ; $i++){
				$pageLastclass = "";
				if ($i>=10) {
					$pageLastclass = "class=\"page_Last\"";
				}
				$rst .= ($i != $page)? "<a href=\"javascript:parent.myshopSearchListView('" . $i . "', '" . $tail . "')\" $pageLastclass>":"";
				$rst .= ($i == $page)? "<strong><span>$i</span></strong>" : "<span>".$i."</span>";
				$rst .= ($i != $page)? "</a>":"";
				$rst .= ($i != $end) ? " " : "";
			}
			$rst .= "&nbsp;&nbsp;<a class=\"next\" href=\"javascript:parent.myshopSearchListView('" . $this -> page_info['next'] . "', '" . $tail . "')\">";
			$rst .= "<img alt=다음 src=\"" . $GP->IMG_PATH. "/comm_img/btn_page_next.gif\" width=\"57\" height=\"27\" align=\"absmiddle\"></a>";
		} else {
			$notdataRst = "<li class='no_data'><div class='no_data'>등록된 데이터가 없습니다.</div></li>";
			$rst = ($tqry)?  "<font color='red'><b>$tqry</b></font> 에 대한 검색 결과가 없습니다.":$notdataRst;
		}

		$this -> page_info['link'] = $rst;
	}


	function getSearchForm () {
		global $C_Func;
		$search_add = '';
		extract($this -> conf_info);

		$rst = "<script>
					function reLocPage(o)
					{
						if(o.value=='')
						{
							location.href='" . $_SERVER['PHP_SELF'] . "?$tail';
						}

					}
					function eoCheckSearchForm(f)
					{
						if(f.sc_type.value=='')
						{
							alert('검색형태를 선택해주세요');
							f.sc_type.focus();
							return false;
						}

						f.action = '" . $_SERVER['PHP_SELF'] . "';
					}
					</script>";
		$rst .= "<table border='0' cellpadding='0' height='0' cellspacing='0'><tr>";
		$rst .= "<form name='searchListForm' method='get' onSubmit='return eoCheckSearchForm(this)'>";

		$rst .= '<td valign=\'bottom\'>' . $C_Func -> makeSelect('sc_type',$search,$search_rst[0],"onchange='reLocPage(this)'", '- 선택 -') . '</td>';
		$rst .= '<td><input type=\'text\' name=\'sc_val\' value=\'' . $search_rst[1] . '\'></td>';
		$rst .= $this -> makeInputValue ($tail);
		$rst .= '<td style=\'padding-left:5px\'><input type=\'submit\' value=\' 검색 \'></td>';
		$rst .= '</tr>';
		$rst .= $search_add == ''?'':$search_add;
		$rst .= '</form>';
		$rst .= '</table>';

		$this -> page_info['search'] 	= $rst;
	}


	function makeInputValue ($data) {
		$data_arr = explode('&', $data);
		$str = '';
		if (is_array($data_arr)) {
			foreach ($data_arr as $v) {
				$input_val = explode('=' , $v);
				$str .= "<input type='hidden' name='" . $input_val[0] . "' id='" . $input_val[0] . "' value='" . $input_val[1] . "'>";
			}
		}
		return $str;
	}
/*
*		엑셀설정 시작 V.0.1
*
*/
	function getExcel ($args = '') {

		if (is_array($args)) foreach ($args as $k => $v) ${$k} = $v;

		$excel_name = $this -> conf_info['excel_file'];
		$excel_name = iconv("UTF-8", "EUC-KR", $excel_name);

		@header( "Cache-Control:no-cache");
		@header( "Pragma:no-cache");
		@header(	"Content-type: application/vnd.ms-excel; charset=utf-8");
		@header( "Content-Disposition: attachment; filename=" . $excel_name . ".xls" );
		@header( "Content-Description: PHP4 Generated Data" );

		$str = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		$str .= "<table border='1' width='100%'>";

		if(is_array($excel))
		{
			$keyArr = array_keys($excel);
			$valueArr = array_values($excel);

			$str .= "<tr>";
			if (is_array($keyArr)) {
				foreach ($keyArr as $v)
				{
					$str .= "<td bgcolor='CCCCCC'>" . $v . "</td>";
				}
			}
			$str .= "</tr>";
		}

		$rstCnt1 = count($excel);
		$rstCnt2 = count($data);

		// 연결연산자가 있는지 확인한다.
		if (is_array($valueArr)) {
			foreach ($valueArr as $k => $v) {
				if (eregi('\^', $v)) {
					$get_and_arr = explode('^', $v);
					$store_and_position[] = $k;
				}
			}
		}

		for($i = 0; $i < $rstCnt2; $i++)
		{
			$str .= "<tr>";
			for($j = 0 ;$j < $rstCnt1 ; $j++)
			{
				$column_name = $valueArr[$j];
				$get_column_data = $this -> checkReplaceData ($data[$i],$column_name);

				// yuhwanni 11.29 데이터가 컬럼명으로 들어가는거 방지.
				if($get_column_data == $column_name) $get_column_data = '';

				$data_str = "";

				if ($get_column_data && !eregi('\^', $get_column_data)) {
					$data_str = $get_column_data;
				}else{
					if (eregi('\^', $column_name)) {
						$get_and_arr = explode('^', $column_name);
						foreach ($get_and_arr as $k2 => $v2) {
							//$add_sep = (eregi("'",$v2)) ? str_replace("'","",$v2) : '';
							$data_str .= $add_sep . $this -> checkReplaceData ($data[$i],$v2);
						}
					}
				}
				// if ($column_name == 'mb_type') {
				// 	$data_str = $GP->RESERVE_CL_TYPE[$get_column_data];
				// }else if ($column_name == 'dr_clinic') {
				// 	$data_str = $GP->CLINIC_TYPE[$get_column_data];
				// }else if ($column_name == 'rd_stauts') {
				// 	$data_str = $GP->RESERVE_RESULT[$get_column_data];
				// }
				$str .= "<td>".$data_str."</td>";
			}
			$str .= "</tr>";
		}
		$str .= "</table>";

		echo $str . $excel_add;
		exit;
	}

	function checkReplaceData ($data,$column_name) {
		$rst = ($data[$column_name])? $data[$column_name] : $column_name ;
		if (!empty($this -> conf_info['excel_kr']) && array_key_exists($column_name,$this -> conf_info['excel_kr'])) {
			foreach ($this -> conf_info['excel_kr'][$column_name] as $k => $v) {
				if ($rst == $k) {
					$rst = $v;
					return $rst;
					break;
				}
			}
			$rst = '';
		}

		return $rst;
	}
}
?>