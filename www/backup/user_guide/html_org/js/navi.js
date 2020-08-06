$(document).ready(function() {	
	$('#navi .navi_main .navi_main_obj').mouseover(function() {
		var selCode = this.getAttribute('subcode');
		var subCode = null;

		$('#navi .navi_main .navi_main_obj').each(function(){
			subCode = this.getAttribute('subcode');

			if( selCode == subCode && $('.navi_sub_'+subCode)) {
				$('#navi .navi_sub_'+subCode).fadeIn(200);
			} else if( $('.navi_sub_'+subCode) ) {
				$('#navi .navi_sub_'+subCode).fadeOut(100);;
			}
		});
	});
});
