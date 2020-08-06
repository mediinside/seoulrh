<? if( !isset($pageNum) || !isset($subNum) ) { $pageNum=0; $subNum=0; } ?>
<div id="header">
	<div class="wrap">
		<h1 class="logo1"><a href="/" onfocus="this.blur();"><img src="/img/common/logo1.jpg" alt="새누리당 강남구(갑)국회의원" /></a></h1>
		<h1 class="logo2"><a href="/" onfocus="this.blur();"><img src="/img/common/logo2.jpg" alt="강남의 새로운 심장 심윤조" /></a></h1>
		<div id="top_navi">
			<ul class="lnb">
				<li class="bar"><a href="/sub/sitemap"><img src="/img/common/topmenu_sitemap.gif" alt="사이트맵" /></a></li>
				<? if(!IS_MEMBER) : ?>
				<li class="bar"><a href="/member/join"><img src="/img/common/topmenu_join.gif" alt="회원가입" /></a></li>			
				<li><a href="/member/login"><img src="/img/common/topmenu_login.gif" alt="로그인" /></a></li>				
				<? else : ?>
				<li class="bar"><a href="/member/confirm/qry/member.modify"><img src="/img/common/topmenu_modify.gif" alt="정보수정" /></a></li>
				<li><a href="/member/login/out"><img src="/img/common/topmenu_logout.gif" alt="로그아웃" /></a></li>
				<? endif; ?>
			</ul>
			<ul class="link_social">
				<li><a href="http://blog.naver.com/yjshim2012" target="_blank"><img src="/img/common/social_b.gif" alt="b" /></a></li>
				<li><a href="http://me2day.net/yjshim2012" target="_blank"><img src="/img/common/social_m.gif" alt="m" /></a></li>
				<li><a href="#"><img src="/img/common/social_f.gif" alt="f" /></a></li>
				<li><a href="http://twitter.com/yjshim2012" target="_blank"><img src="/img/common/social_t.gif" alt="t" /></a></li>
				<li><img src="/img/common/title_social.gif" alt="Social Club" /></li>
			</ul>
		</div>
		<p><script  type="text/javascript">flashWrite("/flash/menu.swf?pageNum=<?=$pageNum;?>&subNum=<?=$subNum;?>","980","66","menuFlash","#ffffff","transparent");</script></p>
	</div>
</div>