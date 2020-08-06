$(document).ready(function(){
	var edtNo = 0;
	var edt_config = Array($('.tx-editor-container').length);
	
	$('.tx-editor-container').each(function() {
		$(this).attr('id', $(this).attr('id') + edtNo);
		$(this).find('.tx-text').attr('rel', edtNo).click(function() {
			Editor.switchEditor($(this).attr('rel'));
		});
		
		$('.tx_load_content').eq(edtNo).attr('id', 'tx_load_content'+ edtNo);
		$('.tx_canvas_wysiwyg').eq(edtNo).attr('name', 'tx_canvas_wysiwyg'+ edtNo);
		
		$(this).find('[id]').each(function() {
			$(this).attr('id', $(this).attr('id') + edtNo);
		});
		
		edt_config[edtNo] = {
			form: 'fwrite',
			pvpage: '#host#path/pages/pv/#pvname.html',
			canvas: {
				selectedMode: edt_mode
			},
			events: {
				preventUnload: false
			},
			sidebar: {
				attachbox: {
					show: true
				},
				embeder: {
					media: { popPageUrl: '/inc/editor/media/' + edt_table }
				},
				attacher: {
					image: {
						features: { left:10, top:10, width:550, height:400 },
						popPageUrl: '/inc/editor/image/' + edt_table,
						checksize: true
					},
					file: {
						features: { left:10, top:10, width:550, height:400 },
						popPageUrl: '/inc/editor/file/' + edt_table,
						checksize: true,
						boxonly: true
					}
				},
				capacity: {
					maximum: upload_size
				}
			},
			plugin: {
				fullscreen: {
					use: true
				}
			},
			initializedId: edtNo +'',
			txHost: '', // http://xxx.xxx.com
			txPath: '/src/editor/',
			wrapper: 'tx_trex_container'+ edtNo,
			txIconPath: '/src/editor/images/icon/editor/',
			txDecoPath: '/src/editor/images/deco/contents/'
		}
		
		edtNo++;
	});
	
	EditorJSLoader.ready(function(Editor) {
		edt_init(0);
	});
	
	/*
	// CONTENT
	Editor.modify({
		'attachments': function() {
			var allattachments = [];
			for (var i in attachments) {
				allattachments = allattachments.concat(attachments[i]);
	        }
			return allattachments;
		}(),
		'content': $tx('tx_load_content')
	});
	*/
	
	function edt_init(edtNo) {
		new Editor(edt_config[edtNo]);

		Trex.Attachment.Image.prototype.getSaveHtml = function(data) {
			return '<img src="' + data.imageurl + '" class="tx-daum-image" alt="이미지"/>';
		};
		Trex.Attachment.Image.prototype.getDispHtml = function(data) {
			return '<img id="' + data.dispElId + '" src="' + data.imageurl + '" class="txc-image" alt="이미지"/>';
		};
		Trex.Attachment.Image.prototype.getDispText = function(data) {
			return '<img src="' + data.imageurl + '" class="tx-daum-image" alt="이미지"/>';
		};
		
		Editor.getCanvas().observeJob(Trex.Ev.__IFRAME_LOAD_COMPLETE, function(ev) {
			Editor.modify({
				'attachments': function() {
					var allattachments = [];
					for (var i in attachments) {
						allattachments = allattachments.concat(attachments[i]);
			        }
					return allattachments;
				}(),
				'content': $tx('tx_load_content'+ edtNo)
			});
			if(edt_config[edtNo + 1] != undefined) {
				edt_init(edtNo + 1);
			}
		});
	}

	$('#tx_image').mouseup(function(){ Editor.focusOnBottom(); });
	$('#tx_file').mouseup(function(){ Editor.focusOnBottom(); });
	$('#tx_media').mouseup(function(){ Editor.focusOnBottom(); });
});


function validForm(editor) {
	var _validator = new Trex.Validator();
	var _content = editor.getContent();
	/*
    if (!_validator.exists(_content) && !edtNoRequired) {
        alert('내용을 입력하십시오.');
        return false;
    }
	*/
	
    $('#write_submit').remove();
	$('#loading').show();
    return true;
}

function setForm(editor) {
    // 내용
	for(var edtNo = 0; edtNo < $('.tx-editor-container').length; edtNo++) {		
		var _formGen = Editor.__MULTI_LIST[edtNo].getForm();
		var _content = Editor.__MULTI_LIST[edtNo].getContent();;
		
		_formGen.createField(
	    	tx.textarea({
	    		'name': $('.tx-editor-container').length > 1 ? 'wr_content[]' : 'wr_content',
	    		'style': { 'display': 'none' } 
	    	}, _content)
	    );
	    // 이미지
		var _images = Editor.__MULTI_LIST[edtNo].getAttachments('image', true);
		
		for(var i=0, len=_images.length; i<len; i++) {
			//if (_images[i].existStage) {
				_formGen.createField(
					tx.input({ 
						'type': 'hidden',
						'name': $('.tx-editor-container').length > 1 ? 'images['+ edtNo +'][]' : 'images[]',
						'value': _images[i].data.imageurl.match(/([0-9]{10}_)?[a-z0-9]{32}\.[0-z]*/i)[0]
					})
				);
	            _formGen.createField(
					tx.input({
						'type': 'hidden',
						'name': $('.tx-editor-container').length > 1 ? 'inames['+ edtNo +'][]' : 'inames[]',
						'value': _images[i].data.filename
					})
				);
			//}
		}
	    
	    // 파일
	    var _files = Editor.__MULTI_LIST[edtNo].getAttachments('file', true);
		for(var i=0, len=_files.length; i<len; i++) {
			var fileVal = _files[i].data.attachurl.match(/([0-9]{10}_)?[a-z0-9]{32}\.[0-z]*/i);
			if (fileVal == null)
				fileVal = _files[i].data.attachurl.match(/[0-9]+$/);
			
			_formGen.createField(
				tx.input({
					'type': 'hidden',
					'name': $('.tx-editor-container').length > 1 ? 'files['+ edtNo +'][]' : 'files[]',
					'value': fileVal[0]
				})
			);
			_formGen.createField(
				tx.input({
					'type': 'hidden',
					'name': $('.tx-editor-container').length > 1 ? 'fnames['+ edtNo +'][]' : 'fnames[]',
					'value': _files[i].data.filename
				})
			);
		}
	}
	return true;
}