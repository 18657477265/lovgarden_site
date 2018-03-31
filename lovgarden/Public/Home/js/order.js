$(function(){
   $('a.checkout-to-flow2').on('click',function(){
       $("#review-order").submit();
   });
   
   $("#review-order").on('submit',function(){
       return true;
   });
   //计算价单
   function calculate_price_list() {
       calculate_vase_price_list();
       var product_original_cost = Number($('span.products_total_cost').text());
       var vase_total_cost = Number($('span.vase_total_cost').text());
       var cur_cost = Number($('span.cut_total_cost').text());
       var deliver_cost = Number($('span.deliver_cost').text());
       var final_user_cost = product_original_cost + deliver_cost + vase_total_cost - cur_cost;
       $('span.total-price-value').text(final_user_cost);
   }
   
   //计算花瓶价单
   function calculate_vase_price_list() {
       var vase_count = 0;
       $("select.vase-option").each(function(){
           if($(this).val() == '1') {
              vase_count = vase_count + 20;
           }
       });
       $("span.vase_total_cost").text(vase_count);
   }
   
   //改变花瓶选项的时候改变价格说明
   $("select.vase-option").on('change',function(){
       calculate_price_list();
   });
   
   //用户删除购物车中的商品
   $("body.order-flow1").on('click','.remove-user-cart-item',function(){
      var cart_id = $(this).data('target');
      var this_price = $(this).data('price');
      var this_row = $(this).parent().parent();
      var this_row_remove_icon = $(this).find('span.remove-processing-icon');
      $.confirm({
         buttons: {
            ok: {
              text: '确认',
              action: function () {
                 //执行ajax删除该购物车中的商品,并更新内存中的值
                    var send_message = {
                         delete_item: cart_id,
                    };
                    this_row_remove_icon.show(100);
                    $.ajax({
                        type: 'POST',
                        url: "/product/order_remove_cart_item",
                        data: send_message,
                        dataType: 'json',
                        success:function(data) {                           
                           if(data == '1') {
                              //添加删除的动态效果
                              this_row.fadeOut("fast", function (){
                                this_row.remove();
                              });
                              //更新价目表
                              setTimeout(function(){
                                  var product_original_cost = Number($('span.products_total_cost').text()) - Number(this_price);
                                  $('span.products_total_cost').text(product_original_cost);
                                  calculate_price_list();                                  
                              },500);
                           }else {
                               alert('出现未知错误,请稍后再试')
                           }
                        }         
                    });
              }
            },
            close: {
                 text: '关闭',
                 action: function () {
                 // do something
                 }
            },

         },
         title: '',
         content: '您确定要将该商品移除吗?',
      });
      
   });
})