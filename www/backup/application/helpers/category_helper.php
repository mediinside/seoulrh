<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function make_category($args=array()) {
    extract($args); // type, code, id, lst, disabled
    
    if($lst)	{ $lo = 'true';  $first = '전체'; }
	else		{ $lo = 'false'; $first = '선택하세요'; }
    
    $is_multi = FALSE;
    if (is_array($code))
        $is_multi = TRUE;
	
	$CI =& get_instance();
	$CI->load->model('M_category');
    $result = $CI->M_category->get_category($type, TRUE);
    
    $topt = $sopt = '';
    foreach ($result as $row) {
    	if (is_numeric($row['code']))
    		$topt .= "<option value='".$row['code']."'>".$row['ca_name']."</option>";
		else
			$sopt .= "<option value='".$row['code']."'>".$row['ca_name']."</option>";
    }
    
    $disabled = $disabled ? "disabled='disabled'" : ''; 
    
    $change = " onchange='javascript:changeCate(this, ".$lo.", \"".$qstr."\");' ";
    $scate = ($sopt) ? "<select id='sub_".$id."' style='display:none;'>".$sopt."</select>" : ''; 
    
    if (!$is_multi) {
        $result['code']   = $code;
        $result['select'] = "<select id='".$id."1' name='".$id."'".$change." ".$disabled."><option value=''>".$first."</option>".$topt."</select>";
    }
    else {
        $no = 1;
        $list = array();
        foreach ($code as $key => $row) {
            $list[$key]->select = "<select id='".$id.$no."' name='".$id."[".$key."]'".$change."><option value=''>".$first."</option>".$topt."</select>";
            $no++;            
        }
        
        $result['code'] = implode("','", $code);
        $result['list'] = $list;
    }
    
    $result['scate'] = $scate;
    return $result;
}

/* 상품 카테고리 - 전체 카테고리 반환 (ex.가전>TV>삼성) */
function category_fullCate($cate_arr, $icon=' > ') {
	$ret_str = array();
	foreach($cate_arr as $cate) {
		$ret_str[$cate['ca_id']] = isset($ret_str[$cate['ca_pid']]) ? $ret_str[$cate['ca_pid']] .$icon. $cate['ca_name'] : $cate['ca_name'];
	}
	return $ret_str;
}

/* 상품 카테고리 - 전체 카테고리 ID값을 배열로 반환 */
function category_fullCateId($cate_arr) {
	$ret_str = array();
	foreach($cate_arr as $cate) {
		$ret_str[$cate['ca_id']] = isset($ret_str[$cate['ca_pid']]) ? array_merge($ret_str[$cate['ca_pid']], array($cate['ca_id'])) : array($cate['ca_id']);
	}
	return $ret_str;
}
?>
