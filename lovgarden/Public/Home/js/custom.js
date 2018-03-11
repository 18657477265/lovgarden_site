$(function(){
   //判断是否是移动端还是PC端 test
   function equipmentCheck() { 
	   	var width = window.innerWidth;
	   	if(width < 768) {
	   		return "mobile";
	   	}
	   	else if(width >= 768 && width <1199 ) {
	   		return "tablet";
	   	}
	   	else {
	   		return "pc";
	   	}
   }
   
   //检测product detail 页面中的radio是否被选中
   $(".product-variant-choice-border input[name='sku']").on('change',function(){
   	$(".product-variant-choice-border input[name='sku']").each(function(){
        if (this.checked) {
           $(this).parent().addClass("product-variant-choice-choosen");
        }
        else {
           $(this).parent().removeClass("product-variant-choice-choosen");
        } 
   	});
   });
   //product detail 页面中的datepicker点击事件
   $("#delivery_date_catalog_product").on('change',function() {
   	alert($(this).val());
   });
   //login页面注册和登录框互相转换
   $("a.click-to-signup").on('click',function(event) {
   	  event.preventDefault(); 
      $('.singup-block').css('display','block');
      $('.login-block').css('display','none');
   });
   $("a.click-to-login").on('click',function(event) {
   	  event.preventDefault(); 
      $('.singup-block').css('display','none');
      $('.login-block').css('display','block');
   });
   var device = equipmentCheck();
   if(device == 'pc') {
	   $("#shop-market").hover(function(){
	      $("#shop-menu-dropdown").css("display","block");
	   });
	   $("#shop-menu-dropdown").hover(function(){ },function() {
	   	  $("#shop-menu-dropdown").css("display","none");
	   });

	   /*product list 页面 filter 展开*/
	   $(".filter-deliver-time").mouseover(function(){
	      $(".filter-deliver-time .input-choice").css("display","block");
	      $(".filter-deliver-time .filter-input-icon").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	   });
	   $(".filter-deliver-time").mouseout(function(){
	      $(".filter-deliver-time .input-choice").css("display","none");
	      $(".filter-deliver-time .filter-input-icon").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	   });
	   //花材种类
	   $(".filter-flower-type").mouseover(function() {
	   	  $(".filter-flower-type .dynamic-span").css("height","150%");
	   	  $(".input-choice-two").css("display","block");
	   	  $(".filter-flower-type .filter-input-icon").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	   });
	   $(".filter-flower-type").mouseout(function() {
	   	  $(".filter-flower-type .dynamic-span").css("height","100%");
	   	  $(".input-choice-two").css("display","none");
	   	  $(".filter-flower-type .filter-input-icon").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	   });
	   //用花情景
	   $(".filter-flower-hoilday").mouseover(function() {
	   	  $(".filter-flower-hoilday .dynamic-span-holiday").css("height","150%");
	   	  $(".input-choice-three").css("display","block");
	   	  $(".filter-flower-hoilday .filter-input-icon").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	   });
	   $(".filter-flower-hoilday").mouseout(function() {
	   	  $(".filter-flower-hoilday .dynamic-span-holiday").css("height","100%");
	   	  $(".input-choice-three").css("display","none");
	   	  $(".filter-flower-hoilday .filter-input-icon").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	   });
	   //鲜花色系
	   $(".filter-flower-color").mouseover(function() {
	   	  $(".filter-flower-color .dynamic-span-color").css("height","150%");
	   	  $(".input-choice-four").css("display","block");
	   	  $(".filter-flower-color .filter-input-icon").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	   });
	   $(".filter-flower-color").mouseout(function() {
	   	  $(".filter-flower-color .dynamic-span-color").css("height","100%");
	   	  $(".input-choice-four").css("display","none");
	   	  $(".filter-flower-color .filter-input-icon").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	   });

	   //产品排序
	   $(".filter-order").mouseover(function(){
	      $(".filter-order .input-choice").css("display","block");
	      $(".filter-order .filter-input-icon").removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
	   });
	   $(".filter-order").mouseout(function(){
	      $(".filter-order .input-choice").css("display","none");
	      $(".filter-order .filter-input-icon").removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
	   });
   }
   else if(device == 'tablet') {
	   $('#shop-market').on('click',function(e) { 
	      $("#shop-menu-dropdown").slideToggle(500);
	   });

	   function close_other_filter_category(except_itself) {
          $("#shop-filter-popup ul").each(function(){
             if(!$(this).hasClass(except_itself)) {
             	var icon_span = $(this).prev().children("span");
                if(icon_span.hasClass("glyphicon-minus")) {
                    $(this).slideUp(300);
                    icon_span.removeClass("glyphicon-minus");
	   	  	        icon_span.addClass("glyphicon-plus");
                }
             }
          });
	   }
	   //product filter
	   $('.filter-mobile-popup-delivertime').on('click',function() {
	   	  if($('.filter-mobile-popup-delivertime span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-delivertime span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-delivertime span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-delivertime span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-delivertime span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-delivertime-choice");
	   	  $(".filter-mobile-popup-delivertime-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-flowertype').on('click',function() {
	   	  if($('.filter-mobile-popup-flowertype span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-flowertype span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-flowertype span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-flowertype span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-flowertype span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-flowertype-choice");
	   	  $(".filter-mobile-popup-flowertype-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-occasion').on('click',function() {
	   	  if($('.filter-mobile-popup-occasion span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-occasion span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-occasion span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-occasion span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-occasion span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-occasion-choice");
	   	  $(".filter-mobile-popup-occasion-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-flower-color').on('click',function() {
	   	  if($('.filter-mobile-popup-flower-color span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-flower-color span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-flower-color span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-flower-color span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-flower-color span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-flower-color-choice");
	   	  $(".filter-mobile-popup-flower-color-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-order').on('click',function() {
	   	  if($('.filter-mobile-popup-order span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-order span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-order span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-order span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-order span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-order-choice");
	   	  $(".filter-mobile-popup-order-choice").slideToggle(300);
	   });

	   /*关闭按钮*/
	   $('.filter-mobile-popup-title').on('click',function() {
	   	  $('body').removeClass("stop-flow");
          $('#mobile-filter-section').fadeOut(300);
	   });
	   /*开启按钮*/
	   $(".mobile-filter").on('click',function() {
	   	  $('#mobile-filter-section').css('height',$(window).height());
	   	  $('body').addClass("stop-flow");
          $('#mobile-filter-section').fadeIn(300);
	   });

   }
   else if (device == 'mobile') {
	   $('.mobile-menu-show').on('click',function(e) {
	   	  $('body').addClass("stop-flow");
	   	  $('#mobile-menu-section').css('height',$(window).height());
	   	  $('#mobile-menu-section').show();
	   });

	   $('.mobile-menu-close').on('click',function(e) {
	   	  $('body').removeClass("stop-flow");
	   	  $('#mobile-menu-section').hide();
	   });

	   $('.account.top').on('click',function() {
	   	  if($('.account span').hasClass("glyphicon-plus")) {
	   	  	$('.account span').removeClass("glyphicon-plus");
	   	  	$('.account span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.account span').removeClass("glyphicon-minus");
	   	  	$('.account span').addClass("glyphicon-plus");
	   	  }
	   	  $(".account.foder").slideToggle(300);
	   });

	   $('.mobile-shop.top').on('click',function() {
	   	  if($('.mobile-shop span').hasClass("glyphicon-plus")) {
	   	  	$('.mobile-shop span').removeClass("glyphicon-plus");
	   	  	$('.mobile-shop span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.mobile-shop span').removeClass("glyphicon-minus");
	   	  	$('.mobile-shop span').addClass("glyphicon-plus");
	   	  }
	   	  $(".mobile-shop.foder").slideToggle(300);
	   });

	   $('.deliver-time.top').on('click',function() {
	   	  if($('.deliver-time span').hasClass("glyphicon-plus")) {
	   	  	$('.deliver-time span').removeClass("glyphicon-plus");
	   	  	$('.deliver-time span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.deliver-time span').removeClass("glyphicon-minus");
	   	  	$('.deliver-time span').addClass("glyphicon-plus");
	   	  }
	   	  $(".deliver-time.foder").slideToggle(300);
	   });

	   $('.other-service.top').on('click',function() {
	   	  if($('.other-service span').hasClass("glyphicon-plus")) {
	   	  	$('.other-service span').removeClass("glyphicon-plus");
	   	  	$('.other-service span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.other-service span').removeClass("glyphicon-minus");
	   	  	$('.other-service span').addClass("glyphicon-plus");
	   	  }
	   	  $(".other-service.foder").slideToggle(300);
	   });

	   $('.about-us.top').on('click',function() {
	   	  if($('.about-us span').hasClass("glyphicon-plus")) {
	   	  	$('.about-us span').removeClass("glyphicon-plus");
	   	  	$('.about-us span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.about-us span').removeClass("glyphicon-minus");
	   	  	$('.about-us span').addClass("glyphicon-plus");
	   	  }
	   	  $(".about-us.foder").slideToggle(300);
	   });

	   $('.about-help.top').on('click',function() {
	   	  if($('.about-help span').hasClass("glyphicon-plus")) {
	   	  	$('.about-help span').removeClass("glyphicon-plus");
	   	  	$('.about-help span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.about-help span').removeClass("glyphicon-minus");
	   	  	$('.about-help span').addClass("glyphicon-plus");
	   	  }
	   	  $(".about-help.foder").slideToggle(300);
	   });

	   //filter mobile js
	   function close_other_filter_category(except_itself) {
          $("#shop-filter-popup ul").each(function(){
             if(!$(this).hasClass(except_itself)) {
             	var icon_span = $(this).prev().children("span");
                if(icon_span.hasClass("glyphicon-minus")) {
                    $(this).slideUp(300);
                    icon_span.removeClass("glyphicon-minus");
	   	  	        icon_span.addClass("glyphicon-plus");
                }
             }
          });
	   }
	   //product filter
	   $('.filter-mobile-popup-delivertime').on('click',function() {
	   	  if($('.filter-mobile-popup-delivertime span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-delivertime span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-delivertime span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-delivertime span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-delivertime span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-delivertime-choice");
	   	  $(".filter-mobile-popup-delivertime-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-flowertype').on('click',function() {
	   	  if($('.filter-mobile-popup-flowertype span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-flowertype span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-flowertype span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-flowertype span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-flowertype span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-flowertype-choice");
	   	  $(".filter-mobile-popup-flowertype-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-occasion').on('click',function() {
	   	  if($('.filter-mobile-popup-occasion span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-occasion span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-occasion span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-occasion span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-occasion span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-occasion-choice");
	   	  $(".filter-mobile-popup-occasion-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-flower-color').on('click',function() {
	   	  if($('.filter-mobile-popup-flower-color span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-flower-color span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-flower-color span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-flower-color span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-flower-color span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-flower-color-choice");
	   	  $(".filter-mobile-popup-flower-color-choice").slideToggle(300);
	   });

	   $('.filter-mobile-popup-order').on('click',function() {
	   	  if($('.filter-mobile-popup-order span').hasClass("glyphicon-plus")) {
	   	  	$('.filter-mobile-popup-order span').removeClass("glyphicon-plus");
	   	  	$('.filter-mobile-popup-order span').addClass("glyphicon-minus");
	   	  }
	   	  else {
	   	  	$('.filter-mobile-popup-order span').removeClass("glyphicon-minus");
	   	  	$('.filter-mobile-popup-order span').addClass("glyphicon-plus");
	   	  }
	   	  close_other_filter_category("filter-mobile-popup-order-choice");
	   	  $(".filter-mobile-popup-order-choice").slideToggle(300);
	   });

	   /*关闭按钮*/
	   $('.filter-mobile-popup-title').on('click',function() {
	   	  if ($('body').hasClass("stop-flow")) {
             $('body').removeClass("stop-flow");
          }
          $('#mobile-filter-section').fadeOut(300);
	   });
	   /*开启按钮*/
	   $(".mobile-filter").on('click',function() {
	   	  $('body').addClass("stop-flow");
	   	  $('#mobile-filter-section').css('height',$(window).height()+'px');
          $('#mobile-filter-section').fadeIn(300);
	   });

   }
})