var xPosition = 0;		// 시작 가로 위치
var yPosition = 0;		// 시작 세로 위치
var Ygravity = 0.85;		// 움직임 속도
var maxDown = 400;			// 최대 다운 (더 이상 내려가지 않음)
var scrollPosition = 0;
var Y_B = 0;
var Y_A = 0;
var quickmenu;
var startTop = 0;
var tarPos = 0;

function quickMenu() {
	quickmenu = $("#quick"); //퀵메뉴의 id
	scrollPosition = $(window).scrollTop();
    Y_B = parseInt(scrollPosition);
    quickmenuTop = quickmenu.offset().top;
    quickmenuLeft = quickmenu.offset().left;

	if(startTop == 0) startTop = quickmenuTop;

    Y_A = parseInt(quickmenuTop, 10);
    if (Y_A != Y_B) {
        yOffset = Math.ceil(Math.abs(Y_B - Y_A) / 20);
		
        if (Y_B < Y_A)
            yOffset = -yOffset;
		
		tarPos = parseInt(quickmenuTop, 10) - startTop + yOffset;
		
		if (tarPos > 0) {
			if((quickmenuTop < document.body.scrollHeight - maxDown && startTop < scrollPosition) || yOffset < 0)
				quickmenu.css("top", tarPos +"px");
		}
    }
	
	/* pos-x 0에서 시작하여 1프레임 찍히는 깜박이 버그 수정 */
	quickmenu.css('visibility', 'visible');
	quickmenu.css("left", (parseInt(xPosition)) + "px");
}
$(document).ready(function(e) {
	window.setInterval("quickMenu()", 1); 
});
