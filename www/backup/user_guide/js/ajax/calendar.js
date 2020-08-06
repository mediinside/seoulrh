var get_schedule = function(cate, cate_seg, qstr, date_ym)
{
    $.ajax({
        type: 'POST',
        url: '/ajax/ajax_calendar',
        data: {
			'lst': "month",
			'cate': cate,
			'qstr': qstr,
			'cate_seg': cate_seg,
			'date_ym': encodeURIComponent(date_ym)
        },
        cache: false,
        async: false,
        success: function(result)
		{
            switch(result) {
                default :
					$('#cal_box1').html(result);
					//alert(result);
					break;
            }
        }
    });
}
