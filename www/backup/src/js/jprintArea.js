
(function($) {
    var printAreaCount = 0;

    $.fn.printArea = function()
        {
            var ele = $(this);

            var idPrefix = "printArea_";

            removePrintArea( idPrefix + printAreaCount );

            printAreaCount++;

            var iframeId = idPrefix + printAreaCount;

            var iframe = document.createElement('IFRAME');

            $(iframe).attr('style','position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
            $(iframe).attr('id', iframeId);

            document.body.appendChild(iframe);

            var doc = iframe.contentWindow.document;
            var hlinks = $('head link');
            var hjss = $('head script');
            var objClass = $(ele).attr("class") != undefined ? $(ele).attr("class") : '';
            var objId = $(ele).attr("id") != undefined ? $(ele).attr("id") : '';

            hlinks.each(function() {
            	if($(this).attr('rel').toLowerCase() == 'stylesheet' )
            		doc.write('<link type="text/css" rel="stylesheet" href="' + $(this).attr('href') + '"></link>');
            });
            
            doc.write('<script type="text/javascript">var rt_path = ""; var rt_charset = "utf-8";</script>');
	        
            /*
            hjss.each(function() {
                if($(this).attr('src') != undefined )
                    doc.write('<script type="text/javascript" src="' +$(this).attr('src') + '"></script>');
            });
            */
            
            doc.write('<div id="' + objId + '" class="' + objClass + '">' + $(ele).html() + '</div>');
            doc.close();
            
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }

    var removePrintArea = function(id)
        {
            $( "iframe#" + id ).remove();
        };

})(jQuery);

