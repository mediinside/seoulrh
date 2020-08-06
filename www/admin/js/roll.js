slider.rollOption = function(){
	var wrap = $('#roll');
	return {
		speed: 500,
		responsive: true,
		touchEnabled: true,
		pager : false,
		controls : false,
		autoControls :false,
		auto: false,
		infiniteLoop : false,
		onSlideBefore : function(element,oldEq,newEq){
			if (newEq == 0){
				wrap.find('.controller').find('.btn_prev').hide();
			} else if (newEq == wrap.find('.swiper').children().length - 1){
				wrap.find('.controller').find('.btn_next').hide();
			} else {
				wrap.find('.controller').find('.btn_prev').show();
				wrap.find('.controller').find('.btn_next').show();
			}
		},
		onSliderLoad : function(){
			wrap.find('.controller').find('.btn_prev').hide();
			wrap.find('.controller').find('.btn_prev').off('click').on('click',function(){
				slider.roll.goToPrevSlide();
				return false;
			});
			wrap.find('.controller').find('.btn_next').off('click').on('click',function(){
				slider.roll.goToNextSlide();
				return false;
			});
		}
	}
};
fn.sliderRoll = function(){
	if (co.device == 'mb'){
		if (!slider.roll){
			slider.roll = $('#roll').find('.swiper').bxSlider(slider.rollOption());
		} else {
			slider.roll.reloadSlider();
		}
	} else {
		if (slider.roll){
			slider.roll.destroySlider();
			setTimeout(function(){
				$('#roll').find('.swiper').css({'transform':''});
				$('#roll').find('.swiper').children('li').css({'width':''});
			});
		}
	}
};

fn.init = function(){
	fn.sliderRoll();
};
fn.resize = function(){
	fn.sliderRoll();
};