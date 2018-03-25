$(function(){
   $('.add-to-cart button').on('click',function(){
       //收集商品信息
       var sku_id = $('input[name="sku"]:checked').val();
       var deliver_time = $('span.product-details__shipping-date-value').text();
       var vase = '2';
       if($("input[name='vase_buy']").is(':checked')) {
           vase = '1';
       }
       //收集商品信息
       var product_varient_info = {
           sku_id: sku_id,
           deliver_time:deliver_time,
           vase:vase
       };
       //出发用户等待图标，阻止用户继续点击
       $('.waiting-response').show(200);
       $("p.add-to-cart button").attr("disabled","disabled");
       //发送ajax请求，获取状态码
       $.ajax({
          type: 'POST',
          url: "/product/ajax_add_to_cart",
          data: product_varient_info,
          dataType: 'json',
          success:function(data) {
            //接收请求，关闭等待图标
            $('.waiting-response').hide(200);
            $("p.add-to-cart button").removeAttr("disabled");            
            //console.log(data);                        
            if(data == '1') {
                 $('.response-message').html('<span class="glyphicon glyphicon-ok"></span>已加入购物车');
                 var number = $('.add_to_cart_number').text();
                 number ++;
                 $('.add_to_cart_number').text(number);
            }
            else if(data == '2') {
                 $('.response-message').html('您还未登录,请先登录');   
            }
            else if(data == '4') {
                 $('.response-message').html('Sorry,目前系统繁忙,请稍后再试');   
            }
            else {
                $('.response-message').html(data); 
            }
            $('#add_to_cart_popup').popup({
                opacity: 0.5,
                transition: 'all 0.7s'
            });
            $('#add_to_cart_popup').popup('show');       
            setTimeout(function () {
               $('#add_to_cart_popup').popup('hide');
            }, 1500);
          }         
        });
       
   });

})