$(function(){
   $('.add-to-cart button').on('click',function(){
       
       $('#my_popup').popup({
           opacity: 0.3,
           transition: 'all 1s'
       });
       $('#my_popup').popup('show');
       
       setTimeout(function () {
        $('#my_popup').popup('hide');
       }, 2000);
   })
})