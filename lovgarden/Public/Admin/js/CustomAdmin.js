$(function(){
   $('.section-product-detail .add_more_image').on('click',function(){
       var newone = '<li class="multiple-images"><span class="item_name" style="width:120px;">上传图片：</span><span class="image-remove">[删除]</span><label class="nouploadImg"><input name="product_varient_images[]" type="file"/></label></li>';
       $('.multiple-images:last').after(newone);
       $(".section-product-update .add-images-in-product-update").after(newone);
   });
   //在product_update页面的add_more_image事件
   $('.section-product-update .add_more_image').on('click',function(){
      var newone = '<li class="multiple-images"><span class="item_name" style="width:120px;">上传图片：</span><span class="image-remove">[删除]</span><label class="nouploadImg"><input name="product_varient_images[]" type="file"/></label></li>';
       $(".section-product-update .add-images-in-product-update").after(newone); 
   });
   $('body').on('click','.image-remove',function(){
       $(this).parent().remove();
   });
   
   $(".permission_name").click(
        function(){
            if($(this).parent().next().css('display') == 'none'){
              $(this).parent().next().show(200);
              $(this).parent().next().css('border','none');
            }else {
              $(this).parent().next().hide(200);
              $(this).parent().next().css('border','1px solid #d0cdcd'); 
            }
        },
    );
   
   //ajax删除图片
   $(".product-varient-old-image a").on('click',function(){
       if(confirm("确定删除该图片?")){
           $(".delete-processing").css('display','inline-block');
           var row_id = $(this).data('row-id');
           var product_varient_id = $(this).data('product-varient-id');
           var delete_msg = {
                 row_id: row_id,
                 product_varient_id:product_varient_id,
           };
           var parent_node = $(this).parent();
           $.ajax({
                type: 'POST',
                url: "/index.php/Admin/Product/ajax_delete",
                data: delete_msg,
                dataType: 'json',
                success:function(data)
                {
                    if(data == '1') {
                       $(".delete-processing").css('display','none');
                       parent_node.remove();
                    }
                    else {
                        alert('图像删除失败');
                    }
                }         
           });
       }
   });
   //ajax 修改订单状态为已发送
   $('.order-send-button').on('click',function(){
       if(confirm("确定更新该订单状态为已发送?")){
           var order_id = $(this).data('order-id');
           var order_msg = {
                 order_id: order_id,
                 status:'sent'
           };
           $.ajax({
                type: 'POST',
                url: "/index.php/Admin/Order/updateStatus",
                data: order_msg,
                dataType: 'json',
                success:function(data)
                {
                    if(data == '200') {
                       alert('订单状态更新成功');
                    }
                    else {
                        alert('订单状态更新失败');
                    }
                }         
           });
       }
   });
   
   //ajax 根据id 删除区块block全部信息
   $('.block-delete').on('click',function(){
       if(confirm("确定删除该区块?")){
           $(".delete-processing").css('display','inline-block');
           var row_id = $(this).data('row-id');
           var delete_msg = {
                 row_id: row_id,
           };
           var parent_node = $(this).parent().parent();
           $.ajax({
                type: 'POST',
                url: "/index.php/Admin/block/ajax_block_delete",
                data: delete_msg,
                dataType: 'json',
                success:function(data)
                {
                    if(data == '1') {
                       $(".delete-processing").css('display','none');
                       parent_node.hide(300);
                    }
                    else {
                        alert('区块删除失败');
                    }
                }         
           });
       }
   });
   
   //ajax 根据id 删除role全部信息
   $('.role-delete').on('click',function(){
       if(confirm("确定删除该角色?")){
           $(".delete-processing").css('display','inline-block');
           var row_id = $(this).data('row-id');
           var delete_msg = {
                 row_id: row_id,
           };
           var parent_node = $(this).parent().parent();
           $.ajax({
                type: 'POST',
                url: "/index.php/Admin/Role/ajax_role_delete",
                data: delete_msg,
                dataType: 'json',
                success:function(data)
                {
                    if(data == '1') {
                       $(".delete-processing").css('display','none');
                       parent_node.hide(300);
                    }
                    else {
                        alert('角色删除失败');
                    }
                }         
           });
       }
   });
   
   //ajax 根据id 删除permission全部信息
   $('.permission-delete').on('click',function(){
       if(confirm("确定删除该权限?")){
           $(".delete-processing").css('display','inline-block');
           var row_id = $(this).data('row-id');
           var delete_msg = {
                 row_id: row_id,
           };
           var parent_node = $(this).parent().parent();
           $.ajax({
                type: 'POST',
                url: "/index.php/Admin/Permission/ajax_permission_delete",
                data: delete_msg,
                dataType: 'json',
                success:function(data)
                {
                    if(data == '1') {
                       $(".delete-processing").css('display','none');
                       parent_node.hide(300);
                    }
                    else {
                        alert('权限删除失败');
                    }
                }         
           });
       }
   });
   
   //按照权限将没有权限的menu隐藏，只有有权限的menu才有a标签
   $('.left-side-menu li').each(function(){
       if($(this).find('a').length == 0){
           $(this).css('display','none');
       }
   });
   $('.left-side-menu dl').each(function(){
       if($(this).find('a').length == 0){
           $(this).css('display','none');
       }
   });
   $('.left-side-menu dd').each(function(){
       if($(this).find('a').length == 0){
           $(this).css('display','none');
       }
   });
   //在任何位置，都将对标记有permission-check-link class的link进行检查，没有a标签就隐藏
   $('.permission-check-link').each(function(){
       if($(this).find('a').length == 0){
           $(this).css('display','none');
       }
   });
   //在一些情况下，直接是个a标签，没有多余的元素包含这个a标签，则url权限验证方法会自动过滤掉没有权限的a标签
})