$(function(){
   //获取购物车中信息
   $.ajax({
          type: 'POST',
          url: "/cart/ajax_get_cart_items",
          dataType: 'json',
          success:function(data) {
            if(data != '0') {
                //如果商品数目不为0,则显示结算按钮，否则隐藏
                $('.link-to-cart-page').show(100);
            }
            $('.add_to_cart_number').text(data);
          }         
    });
    
    $('body').on('click','.remove-cart-item',function(){
        
        $(this).hide(100);
        var delete_item = $(this);
        var delete_item_code = $(this).data('target');
        var remove_processing_icon = $(this).next();
        remove_processing_icon.show(100);
        var send_message = {
             delete_item: delete_item_code,
        };
        $.ajax({
            type: 'POST',
            url: "/product/remove_cart_item",
            data: send_message,
            dataType: 'json',
            success:function(data) {
               if(data == '1') {
                  //刷新当前页面
                  window.location.reload();
               }else {
                   alert('出现未知错误,请稍后再试')
               }
            }         
        });
    });
})