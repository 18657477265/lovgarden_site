$(function(){
   $('.add-to-cart button').on('click',function(){
       var send_telephone='';
       var product_varient_info = {
           send_telephone: send_telephone,
       };
       $('.waiting-response').show(200);
       $.ajax({
          type: 'POST',
          url: "/product/ajax_add_to_cart",
          data: product_varient_info,
          dataType: 'json',
          success:function(data) {
            //alert(data);
            $('.waiting-response').hide(200);
            if(data == '1') {
                    
            }
            else if(data == '2') {
                 $('.response-message').html('您还未登录,请先登录');   
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
       
   })
})