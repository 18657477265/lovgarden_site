$(function(){
   $('.faq .article-list-link').on('click',function(){
       
       var icon_span = $(this).find("span");
       var next_div = $(this).parent().next();
       if(icon_span.hasClass("glyphicon-triangle-top")) {
           next_div.slideDown(300);      
           icon_span.removeClass("glyphicon-triangle-top");
	   icon_span.addClass("glyphicon-triangle-bottom");
       }
       else if(icon_span.hasClass("glyphicon-triangle-bottom")) {
           next_div.slideUp(300);      
           icon_span.removeClass("glyphicon-triangle-bottom");
	   icon_span.addClass("glyphicon-triangle-top");
       }
   })
})