<?
	include_once $_SERVER['DOCUMENT_ROOT'] . '/_init.php';
	include_once $GP -> INC_WWW . '/count_inc.php';
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.doctor.php");
	$C_ListClass 	= new ListClass;
	$C_Doctor 	= new Doctor;

	//error_reporting(E_ALL);
	//@ini_set("display_errors", 1);

	$args = array();
	$args['show_row'] = 100;
    $args['view_type'] = "Y";
    $args['dr_menu'] = $dr_menu  ;

	$data = "";
	$data = $C_Doctor->Doctor_List(array_merge($_GET,$_POST,$args));

	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];

	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);

	$data_list_cnt 	= count($data_list);

?>
<div class="contents sub-contents bg-gray">
				<div class="wd-1200">
					<h3 class="sub-tit">
						전문 의료진
						<a href="/use/page01.html">
							<img src="/resource/images/cal-1.png" alt="">
							전체 의료진 일정보기
						</a>
					</h3>
					<ul class="doc-list mb30">
                    <?
                        $k=0;
                        for ($i = 0 ; $i < $data_list_cnt ; $i++) {
                            $dr_idx 			= $data_list[$i]['dr_idx'];
                            $dr_name			= $data_list[$i]['dr_name'];
                            $dr_center		= $data_list[$i]['dr_center'];
                            $dr_clinic			= $data_list[$i]['dr_clinic'];
                            $dr_thesis		= $data_list[$i]['dr_thesis'];
                            $dr_face_img		= $data_list[$i]['dr_face_img'];
                            $dr_choice		= $data_list[$i]['dr_choice'];
                            $dr_special		= $data_list[$i]['dr_special'];
                            $dr_regdate	 	= $data_list[$i]['dr_regdate'];
                            $dr_position		= $data_list[$i]['dr_position'];
                            $dr_desc 			= $data_list[$i]['dr_desc'];
                            $dr_history1 		= nl2Br($data_list[$i]['dr_history1']);
                            $dr_history2 		= nl2Br($data_list[$i]['dr_history2']);
                            $dr_history3 		= nl2Br($data_list[$i]['dr_history3']);
                            $dr_history4 		= nl2Br($data_list[$i]['dr_history4']);
                            $moning_arr		= explode('|', $data_list[$i]['dr_m_sd']);
                            $after_arr		= explode('|', $data_list[$i]['dr_a_sd']);


                            $cn_arr = explode(",", $dr_position);

                            $dr_img = '';
                            if($dr_face_img !=  '') {
                                $dr_img = "<img src='" . $GP -> UP_DOCTOR_URL . $dr_face_img . "' alt='" .  $dr_name ."' />";
                            }else{
                                $dr_img = "<img src=\"/images/no_image.jpg\" alt=\"이미지 없음\" />";
                            }
                        ?>
						<li>
                             <?=$dr_img?>
							<p class="name">
                                <span><?=$dr_name?></span>
                                <?
                                    $str = "";
                                    foreach ($cn_arr as $key => $val) {
                                        $str .= $GP -> DOCTOR_POSITION[$val] . ",";
                                    }
                                    echo rtrim($str , ",");
                                ?>
								<small><?=$dr_history4?></small>
							</p>
							<div class="btn-wrap">
								<a>
									<!-- <img src="/resource/images/cal-1.png" alt="">
									진료시간표 -->
								</a>
								<a href="/use/page01.html">
									<img src="/resource/images/more-detail.png" alt="">
									상세보기
								</a>
								<!-- <a>
									<img src="/resource/images/cal-1.png" alt="">
									진료시간표
								</a> -->
							</div>
                        </li>
                        <? } ?>
					</ul>
				</div>
			</div>