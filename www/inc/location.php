<?
   $url = $_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'] ;

?>
<div class="location">
    <div class="inner">
        <ul class="section">
            <li class="none-data"></li>
			<li class="home">
				<a href="/index.html"><img src="/resource/images/home.png" alt="HOME"></a>
			</li>
			<li class="depth dp1">
                <button class="dropdown-toggle" type="button"><?=$GP -> Navi_Dep2[$dep1][$dep2][0];?></button>
				<ul class="dp-section dropdown-menu">
                    <!-- 리스트 -->
                    <?
					foreach($GP -> Navi_Dep2[$dep1] as $dep_key => $dep_val){
                    ?>
                        <li class="dropdown-item"><a href="<?=$dep_val[1]?>"><?=$dep_val[0]?></a></li>
                    <?
                        }
                    ?>
                </ul>
			</li>
			<li class="depth dp2">
				<div class="dropdown">
					<button class="dropdown-toggle" type="button"><?=$GP -> Navi_Dep3[$dep2][$dep3][0];?></button>
					<ul class="dp-section dropdown-menu">
						<!-- 리스트 -->
						<?
                         foreach($GP -> Navi_Dep3[$dep2] as $dep_key2 => $dep_val2){

							 if ($dep_val2[0] =="비급여진료비")
							{
								$target="target='_blank'" ;
							 }
                        ?>
                        <li class="dropdown-item"><a href="<?=$dep_val2[1]?>" <?=$target?> ><?=$dep_val2[0]?></a></li>
                        <?
                            }
                        ?>
                    </ul>
				</div>
			</li>
        </ul>
        <div class="share_wrap">
            <a href="#" class="btn_share">공유</a>
            <div class="drop_menu">
                <a href="#" class="share_b" onclick="blog_share()">블로그</a>
                <a href="#" class="share_f" onclick="facebook_share()">페이스북</a>
                <!-- <a href="#" class="share_i">인스타그램</a> -->
            </div>
        </div>
    </div>
</div>
<form id="myform">
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<input type="hidden" name="title" id="title" value="서울재활병원" />
</form>
