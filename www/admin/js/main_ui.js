fn.init = function(){
	$('#main-category').find('.toggle').on('click',function(wrap,contain){
		wrap = $('#main-category');
		contain = wrap.find('.contain');
		if (wrap.hasClass('active')){
			contain.removeClass('visible');
			setTimeout(function(){
				contain.slideUp();
				wrap.removeClass('active');
			},2000);
		} else {
			wrap.addClass('active');
			contain.slideDown();
			setTimeout(function(){
				contain.addClass('visible');
			},250);
		}
		return false;
	});
	$('#main-latest').children('.header').find('a').on('click',function(me,target,visible,current){
		me = $(this),
		target = me.data('target'),
		visible = $('#main-latest').children('.header').find('.visible');
		current = visible.data('current');
		visible.removeClass(current).addClass(target);
		$('#main-latest').children('.header').find('a').removeClass('active');
		me.addClass('active');
		visible.data('current',target);
		
		$('#main-latest').find('.panel').removeClass('active').eq(me.parent().index()).addClass('active');
	});

	$('#top-banner .btn-toggle').trigger('click');

}