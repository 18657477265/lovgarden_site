$(function(){
   //获取购物车中信息
   $.ajax({
          type: 'POST',
          url: "/cart/ajax_get_cart_items",
          dataType: 'json',
          success:function(data) {
            $('.add_to_cart_number').text(data);
          }         
    });
})