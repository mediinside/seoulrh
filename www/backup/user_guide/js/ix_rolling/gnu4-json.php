<?php
/**
 * ix_rolling 그누보드 최근글 json
 * @charset utf8
 */
$g4_path = "..";
// common.php 의 상대 경로
include_once ("$g4_path/common.php");
if (!function_exists('json_encode')) {
	function json_encode($in)
	{
		$txt = '{';
		$arr_1 = array();
		foreach ($in['results'] as $v) {
			$_txt = '{';
			$arr = array();
			foreach ($v as $name => $value) {
				$arr[] = '"' . $name . '":"' . ascii_to_entities(str_replace(array('/', '"'), array('\/', '\"'), $value)) . '"';
			}
			$_txt .= join(",", $arr);
			$_txt .= '}';
			$arr_1[] = $_txt;
		}
		$txt .= '"results":[' . join(",", $arr_1) . "]";
		$txt .= '}';
		return $txt;
	}

}
if (!empty($board)) {
	if ($member['mb_level'] < $board['bo_list_level']) {
		header("HTTP/1.1 404 Not Found");
		header("X-message: board permission denied!");
	} else {
		header("Content-Type: application/json;charset=utf-8");
		$sql = "SELECT wr_id, wr_subject AS text, wr_datetime AS created_at FROM " . $write_table;
		$sql .= " WHERE wr_is_comment=0 ";
		if ($_GET['since_id'] > 0) {
			$sql .= " wr_id > " . $_GET['since_id'] . " ";
		}
		$sql .= " ORDER BY wr_num LIMIT 0,10";
		$result = mysql_query($sql);
		$rows = array("results" => array());
		while ($row = mysql_fetch_object($result)) {
			$row -> text = strip_tags($row -> text);
			$row -> profile_image_url = '';
			$row -> href = $g4['url'] . "/bbs/board.php?bo_table=$bo_table&wr_id=$row->wr_id";
			$row -> from_user = '';
			$row -> id = $row -> wr_id;
			$row -> created_at = date("r", strtotime($row -> created_at));
			if ($g4['charset'] != 'utf-8') {
				$row -> text = mb_convert_encoding($row -> text, 'UTF-8', 'EUC-KR');
			}
			$rows["results"][] = $row;
		}
		echo json_encode($rows);
	}
} else {
	header("HTTP/1.1 404 Not Found");
	header("X-message: board not found!");
}

function ascii_to_entities($str)
{
	$count = 1;
	$out = '';
	$temp = array();

	for ($i = 0, $s = strlen($str); $i < $s; $i++) {
		$ordinal = ord($str[$i]);

		if ($ordinal < 128) {
			if (count($temp) == 1) {
				$out .= '&#' . array_shift($temp) . ';';
				$count = 1;
			}

			$out .= $str[$i];
		} else {
			if (count($temp) == 0) {
				$count = ($ordinal < 224) ? 2 : 3;
			}

			$temp[] = $ordinal;

			if (count($temp) == $count) {
				$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

				$out .= '&#' . $number . ';';
				$count = 1;
				$temp = array();
			}
		}
	}

	return $out;
}
