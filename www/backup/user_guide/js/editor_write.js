EditorJSLoader.ready(function(Editor) {
	new Editor({
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
		txHost: '', // http://xxx.xxx.com
		txPath: '/editor/',
		wrapper: 'tx_trex_container',
		txIconPath: '/editor/images/icon/editor/',
		txDecoPath: '/editor/images/deco/contents/'
	});
});

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
	var _formGen = editor.getForm();
	
    // 내용
	var _content = editor.getContent();
	_formGen.createField(
    	tx.textarea({
    		'name': 'wr_content', 
    		'style': { 'display': 'none' } 
    	}, _content)
    );

    // 이미지
	var _images = editor.getAttachments('image', true);
	for(var i=0, len=_images.length; i<len; i++) {
		//if (_images[i].existStage) {
			_formGen.createField(
				tx.input({ 
					'type': 'hidden', 
					'name': 'images[]',
					'value': _images[i].data.imageurl.match(/([0-9]{10}_)?[a-z0-9]{32}\.[0-z]*/i)[0]
				})
			);
            _formGen.createField(
				tx.input({ 
					'type': 'hidden', 
					'name': 'inames[]',
					'value': _images[i].data.filename
				})
			);
		//}
	}
    
    // 파일
    var _files = editor.getAttachments('file', true);
	for(var i=0, len=_files.length; i<len; i++) {
		var fileVal = _files[i].data.attachurl.match(/([0-9]{10}_)?[a-z0-9]{32}\.[0-z]*/i);
		if (fileVal == null)
			fileVal = _files[i].data.attachurl.match(/[0-9]+$/);

		_formGen.createField(
			tx.input({ 
				'type': 'hidden',
				'name': 'files[]', 
				'value': fileVal[0]
			})
		);
		_formGen.createField(
			tx.input({ 
				'type': 'hidden',
				'name': 'fnames[]', 
				'value': _files[i].data.filename
			})
		);
	}
	return true;
}

$(document).ready(function(){
	$('#tx_image').mouseup(function(){ Editor.focusOnBottom(); });
	$('#tx_file').mouseup(function(){ Editor.focusOnBottom(); });
	$('#tx_media').mouseup(function(){ Editor.focusOnBottom(); });
});
