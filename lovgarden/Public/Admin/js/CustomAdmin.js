$(function(){
   $('.add_more_image').on('click',function(){
       var newone = '<li class="multiple-images"><span class="item_name" style="width:120px;">上传图片：</span><span class="image-remove">[删除]</span><label class="nouploadImg"><input name="product_varient_images[]" type="file"/></label></li>';
       $('.multiple-images:last').after(newone);
   });
   $('body').on('click','.image-remove',function(){
       $(this).parent().remove();
   });
})