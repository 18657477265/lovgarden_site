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
   //检测用户是否处于登录状态
   $.ajax({
        type: 'POST',
        url: "/user/get_user_status",
        //data: send_message,
        dataType: 'json',
        success:function(data) {
            if(data == '1') {
                //pc端
                $('.user-options ul li.option1 a').attr('href','/user/usercenter');
                $('.user-options ul li.option1 a').text('用户中心');
                $('.user-options ul li.option2 a').attr('href','/user/logout');
                $('.user-options ul li.option2 a').text('退出系统');
                
                //手机端
                $('.account-option1').attr('href','/user/usercenter');
                $('.account-option1').text('用户中心');
                $('.account-option2').attr('href','/user/logout');
                $('.account-option2').text('退出系统');
            }            
        }         
   });
   
   // 获取用户的购物车信息
   $.ajax({
        type: 'POST',
        url: "/user/get_cart_info",
        //data: send_message,
        dataType: 'json',
        success:function(data) {
              //alert(data);
              if(data == '0') {
                  
              }
              else {
                 var cart_info = '';
                 var total = 0;
                 for(var i=0;i<data.length;i++){
                    //alert(data[i].varient_name);
                    var this_price = parseInt(data[i].varient_price);
                    var row = "<tr><td>" + data[i].varient_name  + "</td><td class='item-price'>" + this_price + "</td><td class='item-number'>" + data[i].number  + "</td><td><span class='glyphicon glyphicon-trash remove-cart-item' data-target='" + data[i].varient_id + "'></span><span class='remove-processing-icon' style='display:none;'><img width=20px src='/Public/Home/images/spin.gif' /></span></td></tr>";
                    total = total +  this_price*data[i].number;
                    cart_info = cart_info + row;
                    $('.checkout__order-inner .checkout__summary tbody').html(cart_info);
                    $('.checkout__order-inner .checkout__summary .checkout__total').text(total);
                 }
              }
        }         
   });
   // 当结算按钮被点击时候 
   $('body').on('click','button.checkout__option--loud',function(){
       window.location.href = "/cart/detail";
   });
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
//   $("a.click-to-signup").on('click',function(event) {
//   	  event.preventDefault(); 
//      $('.singup-block').css('display','block');
//      $('.login-block').css('display','none');
//   });
//   $("a.click-to-login").on('click',function(event) {
//   	  event.preventDefault(); 
//      $('.singup-block').css('display','none');
//      $('.login-block').css('display','block');
//   });
   //注册页面点击按钮发送验证码ajax,发送之前检查倒计时60秒是否存在,存在继续倒计时
    var countdown=60;
    function settime(obj) { //发送验证码倒计时
        if (countdown == 0) { 
            obj.attr('disabled',false); 
            //obj.removeattr("disabled"); 
            obj.val("获取验证码");
            countdown = 60;
            $.cookie("total",countdown, { expires: -1 });
            return;
        } else { 
            obj.attr('disabled',true);
            obj.val("重新发送(" + countdown + ")");
            countdown--;
            $.cookie("total",countdown);
        } 
        setTimeout(function() { settime(obj) },1000) 
    }
    if($.cookie("total")!=undefined && $.cookie("total")!='NaN' && $.cookie("total")!='null') {
        countdown = $.cookie("total");
        settime($('.verification-get input.getCode'));
    }
   $('.verification-get input.getCode').on('click',function(){
           //$(this).val('验证码发送中');
           var this_button = $(this);
           var send_telephone = $('input[name="telephone"]').val();
           var pattern = /^[1][3,4,5,7,8][0-9]{9}$/;
           if(send_telephone != null && send_telephone != undefined && send_telephone != "" && pattern.test(send_telephone)) {
                settime(this_button);
                var send_message = {
                      send_telephone: send_telephone,
                };
                 $.ajax({
                     type: 'POST',
                     url: "/user/send_ali_message_code",
                     data: send_message,
                     dataType: 'json',
                     success:function(data) {
                         //alert(data);
                       if(data == '3') {
                           $('#telephone-error').text('输入的手机格式有误!');
                           $('#telephone-error').fadeIn(300);
                       }
                       else if(data == '5') {
                           alert('由于您多次点击发送,出于安全考虑,系统已将您冻结');
                       }
                     }         
                });
            }
            else {
                $('#telephone-error').text('输入的手机格式有误!');
                $('#telephone-error').fadeIn(300);
            }
   });
   
   $('.icon--wechat').on('click',function(event){
      event.stopPropagation();
      $('.wechat-popup').css('display','inline-block'); 
   });
   $('.wechat-popup').click(function(event){
      $('.wechat-popup').css('display','none'); 
   });  
   
   //product详情页根据包装切换内容
   $("body.product-detail-page input[type='radio']").on('change',function(){
     var new_sku_id = $("input[type='radio']:checked").val();
     var url = "/product/show/sku_id/" + new_sku_id;  
     var deliver_date = $("input[name='delivery_date']").val();
     var vase_status = '0';
     if($("input[name='vase_buy']").prop('checked')) {
        vase_status = '1';
     }
     //存储日期和花瓶的状态到radio点击的下一个页面上使用
     localStorage.setItem("deliver_date",deliver_date);
     localStorage.setItem("vase_status",vase_status);
     //alert(localStorage.getItem("deliver_date"));
     //alert(localStorage.getItem("vase_status"));
     window.location.href = url;  
   });
   
   //在product detail页面将localstorage里面的deliver_date 和 vase_status如果有的话赋值上去，并马上删除
   var deliver_date_send = localStorage.getItem("deliver_date");
   var vase_status_send = localStorage.getItem("vase_status");
   if(deliver_date_send != null && deliver_date_send != undefined && deliver_date_send != "") {
       $('.product-detail-page span.product-details__shipping-date-value').text(deliver_date_send);
       $(".product-detail-page input[name='delivery_date']").val(deliver_date_send);
       localStorage.removeItem("deliver_date");
   }
   if(vase_status_send == '1') {
       $(".product-detail-page input[name='vase_buy']").attr("checked",true);
       localStorage.removeItem("vase_status");
   }
   
   var device = equipmentCheck();
   if(device == 'pc') {
	   $("#shop-market").hover(function(){
	      $("#shop-menu-dropdown").css("display","block");
	   });
	   $("#shop-menu-dropdown , #shop-feature , #shop-brief ,#logo ,#help ,#login ,#cart").hover(function(){ },function() {
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
           
           //双保险 如果碰到有show-tablet-mobile的在PC上隐藏
           $(".show-tablet-mobile").css('display','none');
           
           //控制用户小弹窗的显示和消失
           $('#login').on('mouseenter',function(){
               $('.user-options').fadeIn(300);
           });
           $('#login').on('mouseleave',function(){
               $('.user-options').fadeOut(300);
           });
           
           //控制购物车小弹窗的显示和消失
           $('.shop.my-cart').on('mouseenter',function(){
                $('.checkout__order').fadeIn(300);
           });
           $('.shop.my-cart').on('mouseleave',function(){
                $('.checkout__order').fadeOut(300);
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
	   	  $('#mobile-filter-section').css('min-height',$(window).height());
	   	  $('body').addClass("stop-flow");
          $('#mobile-filter-section').fadeIn(300);
	   });
          
          //双保险 碰到有show-pc的在非PC端隐藏
          $('.show-pc').css('display','none');
          
           //控制用户小弹窗的显示和消失
           $('#login').on('click',function(){
               $('.user-options').fadeToggle(300);
           });
           
          //控制购物车小弹窗的显示和消失
          $('.shop.my-cart a').on('click',function(){
                $('.checkout__order').slideToggle(300);
          });              
   }
   else if (device == 'mobile') {
	   $('.mobile-menu-show').on('click',function(e) {
	   	  $('body').addClass("stop-flow");
	   	  $('#mobile-menu-section').css('min-height',$(window).height());
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
	   	  $('#mobile-filter-section').css('min-height',$(window).height()+'px');
                  $('#mobile-filter-section').fadeIn(300);
	   });
           
           //双保险 碰到有show-pc的在非PC端隐藏
           $('.show-pc').css('display','none');
           
           //控制购物车小弹窗的显示和消失           
           $('span.shopping-cart-icon').on('click',function(){
                $('.checkout__order').slideToggle(300);
           });  
   }
})