<?
	include_once($GP -> INC_ADM_PATH."inc.login_check.php");
	if($lang) {
		include_once $GP -> INC_ADM_PATH.'menu_'.$lang.'.php';
	}else{
		include_once $GP -> INC_ADM_PATH.'menu.php';		
	}
	
	$arr_chk_fld = explode(',',$_SESSION['adminfld']);
	$arr_chk_fld_sub = explode(',',$_SESSION['adminfldsub']);
	// echo $lang;
?>
	<button class="boxLeftMenuIcon">LeftMenu</button>
	<div class="dim"></div>
	<div class="boxLeft">
		<div class="boxTitleArea" id="top_title" style="cursor:pointer;">
			<h1><?=$GP-> WEB_TITLE?></h1>
		</div>
		<?
			$menuQuery = "";
			$menuMainArr = array("/","","index.html");
		
			$folderNameArr = explode("/",$_SERVER['SCRIPT_NAME']);

			$folderName = ($lang == "eng" || $lang == "ru" || $lang == "mong" || $lang == "prevention") ? $folderNameArr[4] : $folderNameArr[3];	
		
			if(in_array($folderName,$menuMainArr)){
				$menuQuery = "";			
			}else{
				$menuQuery = $folderName;
						}								
		?>
		<ul class="boxLeftMenu">
			<?
				if($_GET['m_tab']) {
					if($_SESSION['main_tab'] != $_GET['m_tab']) {
						unset($_SESSION['sub_tab']);
					}
				}

				foreach ($GP -> MENU_ADMIN as $key => $val){
					$a1_class = "top_menu_off";
					if(!empty($_GET['m_tab']))	{
						$_SESSION['main_tab'] = $_GET['m_tab'];

						if($val['tab'] == $_GET['m_tab']) {
							$a1_class = "top_menu_on";
						}
					}else{
						if(!empty($_SESSION['main_tab'])) {
							if($val['tab'] == $_SESSION['main_tab'])	{
								$a1_class = "top_menu_on";
							}
						}
						else
						{
							if($val['tab'] == "1")	{
								$a1_class = "top_menu_on";
							}
						}
                    }
                    
                    //print_r($val['folder']) ;
                  
					
					if(in_array($val['folder'], $arr_chk_fld)) {
						if ($val['folder'] == 'doctor' &&  ($_SESSION['adminid'] == 'medi2'  || $_SESSION['adminid'] == 'anyspine') && $lang != "eng") {
							echo '';
						}else{
				?>
						<li><a href="<?=$val['link']?>" class="<?=$a1_class?>"><?=$val['name']?></a></li>
				<?
						}
					}
				}
			?>			
		</ul>		
	</div><div class="boxContent">		
		<div class="boxContentHeader">
			<? 
						
				foreach ($GP -> MENU_SUB_ADMIN[$menuQuery] as $key => $val) {
			?>
			<span class="boxTopNav"><strong><?=$key?></strong></span>
			<?
			}
			?>	
			<span class="boxTopBtns">
				<!-- <a href="/admin/bbs/bbs_list.php?m_tab=2">KOR</a>&nbsp;&nbsp;|&nbsp;&nbsp;	
				<a href="/admin/eng/bbs/bbs_list.php?m_tab=1">ENG</a>&nbsp;&nbsp;|&nbsp;&nbsp;	
				<a href="/admin/ru/bbs/bbs_list.php?m_tab=1">RU</a>&nbsp;&nbsp;|&nbsp;&nbsp;	
				<a href="/admin/mong/bbs/bbs_list.php?m_tab=1">MONG</a>&nbsp;&nbsp;&nbsp;&nbsp;	 -->
				<!-- <a href="/admin/prevention/bbs/bbs_list.php?m_tab=1">PREVENTION</a>&nbsp;&nbsp;&nbsp;&nbsp;	 -->
               
				<a href="/admin/">HOME</a>&nbsp;&nbsp;|&nbsp;&nbsp;	
       			 <a href="/" target="_blank">홈페이지</a>&nbsp;&nbsp;|&nbsp;&nbsp;				
       			 <? if ($_SESSION['suserlevel'] == '3') {  ?>
       			 	<!-- <a href="/admin/prevention/cs/cs_list.php">폭력방지위원회</a>&nbsp;&nbsp;|&nbsp;&nbsp; -->
       			 <? } ?>							
				<a href="/admin/login/action/login.proc.php?mode=logout">LOGOUT</a>
			</span>
		</div>
		<div id="BoardHead" class="boxBoardHead">
				<ul>
				<?
					foreach ($GP -> MENU_SUB_ADMIN[$menuQuery] as $key => $val) {
						foreach ($val as $key2 => $val2) {
							$a2_class = "";
							if(!empty($_GET['tab']))	{
								$_SESSION['sub_tab'] = $_GET['tab'];

								if($val2['tab'] == $_GET['tab']) {
									$a2_class = "active";
								}
							}else{
								if(!empty($_SESSION['sub_tab'])) {
									if($val2['tab'] == $_SESSION['sub_tab'])	{
										$a2_class = "active";
									}
								}else{

									if($val2['tab'] == "1")	{
										$a2_class = "active";
									}
								}
                            }                            
                           
							if(in_array($val2['title'], $arr_chk_fld_sub)) {
								if ($val['tab'] == '1' && $val['title'] == 'doctor1' &&  ($_SESSION['adminid'] == 'medi2'  || $_SESSION['adminid'] == 'anyspine') && $lang == "eng") {
									echo '';
                                }
                                elseif($folderName == 'doctor' && $val2['tab'] <> "1" || $folderName == 'doctor1' || $folderName == 'doctor2' || $folderName == 'doctor3'){
                                 ?>
                                    <li><a href="<?=$val2['link']?>&tab=<?=$val2['tab']?>" class="<?=$a2_class?>"><?=$val2['name']?></a></li>
                                 <?
                                }
                                else{
				?>
							<li><a href="<?=$val2['link']?>?tab=<?=$val2['tab']?>" class="<?=$a2_class?>"><?=$val2['name']?></a></li>
				<?
								}
							}
						}
					}
				?>
				</ul>
		</div>		