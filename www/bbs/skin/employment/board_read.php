<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="board-list view wd-1200">
					<h3 class="sub-tit"></h3>
					<table>
                        <colgroup>
                            <col>
                            <col style="width:220px">
                        </colgroup>
						<thead>
							<tr>
								<th class="text-left">
									<span class="view-tit"><?=$jb_title?></span>
									<span class="date">모집기간 <?=$jb_etc1?> ~ <?=$jb_etc2?></span>
                                </th>
                                <th>
                                    <div class="btn-box inline right">
                                        <span class="btn-green" href="#"><?=$GP -> employment_TYPE[$jb_type]?></span>
                                    </div>
                                </th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2">
									<div class="view-box">
                                     <?=$content?>
									</div>
								</td>
							</tr>
                            <?php								
                              
									if($file_cnt > 0) {
										for($i=0; $i<$file_cnt; $i++)	{
											if($ex_jb_file_name[$i]) {
                                                //파일의 확장자
                                               
												$file_ext = substr( strrchr($ex_jb_file_name[$i], "."),1); 
                                                $file_ext = strtolower($file_ext);	//확장자를 소문자로...                                               	
												
												if ($file_ext=="gif" || $file_ext=="jpg" || $file_ext=="png" || $file_ext=="bmp") {	
                                                   							
													echo "<tr><td><a  class='file'  href='" . $GP->UP_IMG_SMARTEDITOR_URL ."jb_${jb_code}/${ex_jb_file_code[$i]}' target='_blank'>";
													echo "<img src=\"" . $GP->UP_IMG_SMARTEDITOR_URL ."jb_" . $jb_code . "/" . $ex_jb_file_code[$i] ."\" class='imgResponsive'>";
													echo "</a></td></tr>";
                                                }
                                                else{
                                                    $code_file = $GP->UP_IMG_SMARTEDITOR. "jb_${jb_code}/${ex_jb_file_code[$i]}";
                                                    echo "<tr><td><p>$filetext<a class='file' href=\"/bbs/download.php?downview=1&file=" . $code_file . "&name=" . $ex_jb_file_name[$i] . " \">$ex_jb_file_name[$i]</a></p></td></tr>";

                                                }
											}	 
										}
									}
								?>
						</tbody>
                    </table>

					<div class="btn-box flex m-top wd-1000">
                    <?
                    if($check_level >= 9 || $check_id == $jb_mb_id)
                    echo "<a style=\"float:left; margin-left:10px;\" href=\"#\" class=\"btn bg-blue\"  onclick=\"javascript:location.href='/notice/notice.php?jb_code=${jb_code}&jb_idx=${jb_idx}&search_key=${search_key}&search_keyword=${search_keyword}&page=${page}&jb_mode=tmodify&jb_mode=tmodify'\"class=\"btntype modify\" title='수정'>수정</a>";
                    //삭제권한(쓰기권한이 있어야 삭제 가능)
                    if($check_level >= 9 || $check_id == $jb_mb_id)
                    echo "<a style=\"float:left; margin-left:10px;\" href=\"#\"  class=\"btn bg-red\" onclick=\"javascript:location.href='${get_par}&jb_mode=tdelete'\" class=\"btntype modify\" title='삭제'>삭제</a>";

					//글목록버튼
					//echo "<a href=\"#\" onclick=\"javascript:location.href='${index_page}?jb_code=${jb_code}&${search_key}&search_keyword=${search_keyword}&page=${page}'\" class=\"btn bg-green\" title='목록'>목록</a>";
					?>
                        <a class="btn-deepblue" href="#" onclick="javascript:location.href='<?=$index_page?>?jb_code=<?=$jb_code?>&<?=$search_key?>&search_keyword=<?=$search_keyword?>&page=<?=$page?>'">목록보기</a>
                        <a class="btn-green" href="/notice/recruit_down01_20150708.hwp">
                            <img src="/resource/images/down.png" alt=""> 이력서양식 다운로드
                        </a>
                        <a class="btn-lightgreen" href="mailto:srh-recruit@hanmail.net">이메일 문의하기</a>
					</div>
					<style>
						@media screen and (max-width:768px) {
							.btn-box.flex {
								flex-flow: column-reverse;
							}
							.btn-box a:not(.link) {
								width:auto;
								margin-bottom:15px;
								font-size:28px;
							}
							.btn-box a:not(.link).btn-deepblue {
								margin-bottom:120px;
							}
							.btn-box a:not(.link).btn-green {
								margin-bottom:80px;
							}
							.btn-box a:not(.link).btn-lightgreen,
							.btn-box a:not(.link).btn-green {
								width:100% !important;
							}
						}
					</style>
