<h2><img src="/img/menu/leftmenu7_top.jpg" alt="사이트 정보" /></h2>
<ul>
	<? if(!IS_MEMBER) : ?>
	<li class="line"><a href="/member/login"><img src="/img/menu/leftmenu7<? if($subNum==1) echo "_ov"; ?>_01.jpg" alt="로그인" /></a></li>
	<li class="line"><a href="/member/join"><img src="/img/menu/leftmenu7<? if($subNum==2) echo "_ov"; ?>_02.jpg" alt="회원가입" /></a></li>
	<li class="line"><a href="/member/forget_idpwd"><img src="/img/menu/leftmenu7<? if($subNum==3) echo "_ov"; ?>_03.jpg" alt="아이디/비밀번호찾기" /></a></li>
	<? else : ?>
	<li class="line"><a href="/member/login/out"><img src="/img/menu/leftmenu7<? if($subNum==4) echo "_ov"; ?>_04.jpg" alt="로그아웃" /></a></li>
	<li class="line"><a href="/member/confirm/qry/member.modify"><img src="/img/menu/leftmenu7<? if($subNum==5) echo "_ov"; ?>_05.jpg" alt="회원정보수정" /></a></li>	
	<li class="line"><a href="/member/leave"><img src="/img/menu/leftmenu7<? if($subNum==6) echo "_ov"; ?>_06.jpg" alt="회원탈퇴" /></a></li>	
	<? endif; ?>
	<li><a href="/sub/sitemap"><img src="/img/menu/leftmenu7<? if($subNum==7) echo "_ov"; ?>_07.jpg" alt="사이트맵" /></a></li>
</ul>