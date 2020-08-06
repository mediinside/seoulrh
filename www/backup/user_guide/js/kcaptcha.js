if (typeof(KCAPTCHA_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');

    var KCAPTCHA_JS = true;

	var md5_norobot_key = '';
	$(document).ready(function() {
	    $('#kcaptcha').attr('src', rt_path + '/img/js/load_kcaptcha.gif');
	    $('#kcaptcha').attr('title', '글자가 잘 안 보이는 경우 클릭하시면 새로운 글자가 나옵니다.');
	    $('#kcaptcha').live('click', function() {
			$.post(rt_path + '/check/kcaptcha/session', function(data) {
				$('#kcaptcha').attr('src', rt_path + '/check/kcaptcha/image/' + (new Date).getTime());
				md5_norobot_key = data;
	        });
		});
		$('#kcaptcha').click();

		if (typeof $.validator != 'undefined') {
			$.validator.addMethod('wrKey', function(value, element) {
				return this.optional(element) || hex_md5(value) == md5_norobot_key;
			});
		}
	});
}