<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>													
			</div>
		</div>	
	
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					 <iframe src="http://www.seethestats.com/stats/10460/VisitsByBrowser_21127d081_ifr.html" style="width:700px;height:300px;border:none;" scrolling="no" frameborder="0"></iframe>
  				 <iframe src="http://www.seethestats.com/stats/10460/VisitsByBrowserVersion_8a353de3c_ifr.html" style="width:700px;height:300px;border:none;" scrolling="no" frameborder="0"></iframe>
				</div>			
			</div>
		</div>
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
</body>
</html>
