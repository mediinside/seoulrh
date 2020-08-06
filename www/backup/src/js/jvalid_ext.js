if (typeof(VALID_EXT_JS) == 'undefined') {
	if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');

    var VALID_EXT_JS = true;

	function validHangul(fld) {
		var pattern = /([^가-힣\x20])/i;
		if (!pattern.test(fld)) {
			return true;
		}
		return false;
	}

	$(document).ready(function() {
		$.validator.addMethod('hangul', function(value, element) {
			return this.optional(element) || validHangul(value);
		}, '한글이 아닙니다. (자음, 모음만 있는 한글은 처리하지 않습니다.)');
	
		$.validator.addMethod('alphanumunder', function(value, element) {
			return this.optional(element) || /(^[a-zA-Z0-9\_]+$)/.test(value);
		}, '영문, 숫자, _ 가 아닙니다.');
		
		$.validator.addMethod('alpha_dash', function(value, element) {
			return this.optional(element) || /(^[a-zA-Z0-9\_\-]+$)/.test(value);
		}, '영문, 숫자, 언더바(_), 대쉬(-) 이외의 문자는 입력할 수 없습니다.');
		
		$.validator.addMethod('alpha_dash_slash', function(value, element) {
			return this.optional(element) || /(^[a-zA-Z0-9\_\-\/]+$)/.test(value);
		}, '영문, 숫자, 언더바(_), 대쉬(-), 슬래쉬(/) 이외의 문자는 입력할 수 없습니다.');
	});
}
