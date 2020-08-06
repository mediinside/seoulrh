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
	execFrameHeight:"200px",
	mTitle:"팝업창",
	mTitleImage:"",
	mWidth:"300",
	mHeight:"300",
	execLoadMode:"load",
	execLoadPage:'',
	layout:'',
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
	drawWinDlg:function(frmTarget) {
		var obj = createDOM('div', '__win_dlg_div__', {'style':"display:none;position:absolute;z-index:1000;width:300px;height:300px;"});//document.getElementById("__win_dlg_div__");
		this.drawWinContainer(frmTarget);
		
		this.modal_start();
		var pos = {'x':$(window).width(), 'y':$(window).height()};
		var nowScroll = getNowScroll();
		var xpx = pos.x/2 - this.mWidth/2;
		var xpy = (pos.y/2 + nowScroll.Y) - (this.mHeight/2) - 100;
		xpx = xpx < 0 ? 0 : xpx;
		xpy = xpy < 0 ? 0 : xpy;
		with(obj.style) {
			position 	= "absolute";
			left 		= xpx + "px";
			top 		= xpy + "px";
			zIndex		= 1;
		}
		obj.style.zIndex 	= 999999999;
		$("#__win_dlg_div__").height(this.mHeight).width(this.mWidth);
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

		$("#__win_dlg_div__").html('');
		this.setLayout('');
		$("#__win_dlg_div__").fadeOut('medium');
	},
	modal_start:function() {
		var dlg = createDOM('div', 'dlg_disable', {'style':"display:none;position:absolute;z-index:999;width:100%;", 'onclick':'return false;'});//.document.getElementById("dlg_disable");
		with (dlg.style) {
			left 			= '0px';
			top 			= '0px';	
			background 		= "black";
			opacity 		= (50/100);
			MozOpacity 		= (50/100);
			KhtmlOpacity 	= (50/100);
			filter 			= 'alpha(opacity=50)';
		}
		var height 	= $(document).height();//document.body.scrollHeight;
		var width	= $(document).width();//document.body.scrollWidth;
		dlg.style.zIndex = 1000000;
		dlg.style.height = height + 'px';
		dlg.style.width = width + 'px';
		dlg.style.display = '';
	},
	modal_stop:function() {
		var dlg = document.getElementById("dlg_disable");
		dlg.style.display = 'none';
	},
	drawWinContainer:function(ifrm_name) {
		var msg = new Array();
		var src = '/api/loading';
		if(this.execLoadMode == 'page' && this.execLoadPage) {
			src = this.execLoadPage;
		}
		
		this.setExecFrame(ifrm_name);
		
		msg.push("<table border='0' width='100%' cellpadding='0' cellspacing='0'>");
		msg.push("<td width='*' ><span style='font-size:12px;font-weight:bold;color:#fff'>" + this.mTitle + "</span></td>");
		msg.push("<td align='right'><a id='_btn_close' onclick='CtrlWinDlg.closeWinDlg()' style='font-size:12px;font-weight:bold;color:#fff;'>[닫기]</a></td></tr>");
		msg.push("</table>");
		msg.push("<table border='0' width='100%' cellpadding='0' cellspacing='0'>");
		msg.push("<tr><td>");
		msg.push("<iframe id='" + ifrm_name + "' name='" + ifrm_name + "' src='" + src + "' style='width:100%;height:" + this.execFrameHeight + "px;background-color:#FFFFFF;overflow-y:hidden;' frameborder='0' scroll='no'></iframe>");
		msg.push("</td></tr>");
		msg.push("</table>");
		
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