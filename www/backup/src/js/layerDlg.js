/* Layer Open ***************************************************************** */
function layerWin(mSrc, mId, mWidth, mHeight, scrollbar, closeBtn) {
	scrollbar = scrollbar != undefined ? scrollbar : 'auto';
	closeBtn = closeBtn != undefined ? closeBtn : true;
	CtrlWinDlg.setDlgTitle("").setDlgWidth(mWidth).setDlgHeight(mHeight).setLoad(mSrc).setScrolling(scrollbar).setCloseBtn(closeBtn).drawWinDlg(mId);
}

/* create DOM ***************************************************************** */
function createDOM(tag, id, attri) {
	if($('#' + id).length > 0) return document.getElementById(id);
	var dom = document.createElement(tag);
	dom.setAttribute("id", id);
	$.each(attri, function(i, v){
		dom.setAttribute(i, v);
	});
	document.body.appendChild(dom);
	return dom;
}

/**
 * 윈도우 위치값 계산을 위한 스코롤바 위치산정
 * @return
 */
var getNowScroll = function(){
	var de = document.documentElement;
	var b = document.body;
	var now = {};

	now.X = document.all ? (!de.scrollLeft ? b.scrollLeft : de.scrollLeft) : (window.pageXOffset ? window.pageXOffset : window.scrollX);
	now.Y = document.all ? (!de.scrollTop ? b.scrollTop : de.scrollTop) : (window.pageYOffset ? window.pageYOffset : window.scrollY);

	return now;

};

/**
 * 가상 윈도우 제어
 */
var CtrlWinDlg = {
	execFrame:"",
	execFrameHeight:"600px",
	mTitle:"",
	mTitleImage:"",
	mWidth:"800",
	mHeight:"500",
	execLoadMode:"load",
	execLoadPage:'',
	layout:'',
	scrolling:'auto',
	closeBtn:true,
	closeOut:true,
	setExecFrame:function(frm_name) {
		this.execFrame = frm_name;
	},
	getExecFrame:function() {
		return this.execFrame;
	},
	setExecFrameHeight:function(height) {
		this.execFrameHeight = height;
		return this;
	},
	getExecFrameHeight:function() {
		return this.execFrameHeight;
	},
	setDlgTitle:function(title) {
		this.mTitle = title;
		return this;
	},
	setDlgWidth:function(width) {
		this.mWidth = width;
		return this;
	},
	setDlgHeight:function(height) {
		this.mHeight = height;
		this.execFrameHeight = height;
		return this;
	},
	setLoad:function(page) {
		this.execLoadMode = 'page';
		this.execLoadPage = page;
		return this;
	},
	setExcute:function(frmId, m, r) {
		this.execLoadMode = 'submit';
		this.execLoadPage = frmId;
		
		var frm = CtrlForm.make(frmId, 'get', m);
		frm.setData('ReturnURL', r);
		frm.setAttr('target', 'UCCATTCH_WIN');
		return this;
	},
	setLayout:function(layout) {
		this.layout = layout;
		return this;
	},
	setScrolling:function(scrolling) {
		this.scrolling = scrolling;
		return this;
	},
	setCloseBtn:function(closeBtn) {
		this.closeBtn = closeBtn;
		return this;
	},
	setCloseOut:function(closeOut) {
		this.closeOut = closeOut;
		return this;
	},
	drawWinDlg:function(frmTarget) {
		var obj = createDOM('div', '__win_dlg_div__', {'style':"display:none;position:absolute;z-index:1000;"});//document.getElementById("__win_dlg_div__");
		this.drawWinContainer(frmTarget);
		
		this.modal_start();
		var pos = {'x':$(window).width(), 'y':$(window).height()};
		var nowScroll = getNowScroll();
		var xpx = pos.x/2 - this.mWidth/2;
		var xpy = (pos.y/2 + nowScroll.Y) - (this.mHeight/2);
		var mtop = this.closeBtn ? -50 : 0;
		
		xpx = xpx < 0 ? 0 : xpx;
		xpy = (xpy + mtop) < nowScroll.Y ? nowScroll.Y : xpy;
		with(obj.style) {
			position 	= "absolute";
			left 		= xpx + "px";
			top 		= (xpy + mtop) + "px";
			zIndex		= 1;
		}
		obj.style.zIndex 	= 999999999;
		$("#__win_dlg_div__").width(this.mWidth);
		$("#__win_dlg_div__").fadeIn('medium');
		if(this.execLoadMode == 'submit') {
			$("#" + this.execLoadPage).submit();
		}
	},
	/**
	 * 윈도우창을 닫을 때 실행해야 할 프로세스가 있으면 실행
	 */
	closeWinDlg:function() {
		this.modal_stop();

		this.setLayout('');
		$("#__win_dlg_div__").fadeOut('medium', '', function(){
			$("#__win_dlg_div__").html('');
		});
	},
	modal_start:function() {
		var nowScroll = getNowScroll();
		var mtop = this.closeBtn ? -50 : 0;
		var closeOut = this.closeOut;
		var dlg = createDOM('div', 'dlg_disable', {'style':"display:none;position:absolute;z-index:999;width:100%;", 'onclick':'return false;'});//.document.getElementById("dlg_disable");
		with (dlg.style) {
			position 		= 'absolute';
			left 			= '0px';
			top 			= '0px';
			background 		= "black";
			opacity 		= (60/100);
			MozOpacity 		= (60/100);
			KhtmlOpacity 	= (60/100);
			filter 			= 'alpha(opacity=60)';
		}
		var winHeight	 	= nowScroll.Y + this.mHeight + mtop + 75;	// 다이얼로그를 포함한 높이 (다이얼로그로 인해 증가치가 있을수 있음)
		var width			= $(document).width() > $(window).width() ? $(document).width()-18 : $(window).width();		// 문서 크기와 스크롤 크기중 큰것으로 적용
		var height 			= $(document).height() > $(window).height() ? $(document).height() : $(window).height();	// 문서 크기와 스크롤 크기중 큰것으로 적용
		height 				= winHeight > height ? winHeight : height;
		dlg.style.zIndex = 1000000;
		dlg.style.height = height + 'px';
		dlg.style.width = width + 'px';
		dlg.style.display = '';
		dlg.onclick = function() { if(closeOut) CtrlWinDlg.closeWinDlg(); };
	},
	modal_stop:function() {
		var dlg = $("#dlg_disable");
		dlg.fadeOut('fast');
	},
	drawWinContainer:function(ifrm_name) {
		var msg = new Array();
		var src = this.execLoadPage;
		
		scrolling = this.scrolling;
		closeBtn = this.closeBtn;
		title = this.mTitle;
		width = this.mWidth;
		
		if(closeBtn) msg.push("<img id='winLayerClose' class='fR' onmouseover='swapImg(this,\""+ rt_path +"/src/imgs/js/btn_winClose_on.png\")' onclick='CtrlWinDlg.closeWinDlg()' src='"+ rt_path +"/src/imgs/js/btn_winClose_off.png' />");
		if(title) msg.push("<span id='winLayerTitle'>&nbsp; " + this.mTitle + "</span>");
		msg.push("<table class='winLayer' border='0' width='100%' cellpadding='0' cellspacing='0'>");
		msg.push("<tr><td class='tLeft'></td>");
		msg.push("<td class='tCenter'></td>");
		msg.push("<td class='tRight'></td></tr>");
		msg.push("<tr><td class='cLeft'></td><td class='lh0' style='width:"+ (width-20) +"px;'>");
		msg.push("<iframe id='" + ifrm_name + "' name='" + ifrm_name + "' src='" + src + "' onLoad=\"$('#winLayerLoading').hide();\" style='width:100%;height:" + this.execFrameHeight + "px;background-color:#FFFFFF;' frameborder='0' scrolling='" + this.scrolling + "'></iframe>");
		msg.push("</td><td class='cRight'></td></tr>");
		msg.push("<tr><td class='bLeft'></td>");
		msg.push("<td class='bCenter'></td>");
		msg.push("<td class='bRight'></td></tr>");
		msg.push("</table>");
		msg.push("<div id='winLayerLoading'></div>");
		
		$("#__win_dlg_div__").html(msg.join("\r\n"));
		$("#_btn_close").css("cursor", "pointer");
	}
};
function ifrmObserver() {
	alert($("#payWindow").attr('src'));
	var inhtml = $("#payWindow").contents().find("form").html();
	alert(inhtml);
	$("#payWindow").contents().find("[name=\"submit22225\"]").click(function() {
		alert("click");
	});
}