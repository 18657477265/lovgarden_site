$(function(){
   //验证手机号码
   function checkIsMobil(s) {
        var pattern = /^[1][3,4,5,7,8][0-9]{9}$/;
        if (!pattern.test(s)) {
            $('#telephone-error').text('输入的手机格式有误!');
            $('#telephone-error').fadeIn(300);
            return false;
        }
        else {
            $('#telephone-error').fadeOut(300);
            return true;
        }
   }
   //手机输入框光标离开时候检查(注册页面和登录页面)
   $('input[name="telephone"] , input[name="login_telephone"]').on('change',function(){
       checkIsMobil($(this).val());   
   });
   
   //验证密码
   function checkIsPassword(s) {
        var pattern = /^[0-9A-Za-z]{6,12}$/;
        if (!pattern.test(s)) {
            $('#password-error').text('密码由6-12位数字和字母组成');
            $('#password-error').fadeIn(300);
            return false;
        }
        else {
            $('#password-error').fadeOut(300);
            return true;
        }
   }
   //密码输入框光标离开时候检查
   $('input[name="password"] , input[name="login_password"]').on('change',function(){
      checkIsPassword($(this).val());
   });
   
   //验证确认密码
   function checkIsComfirmPassword(s) {
        var password = $('input[name="password"]').val();
        if (s != password) {
            $('#repassword-error').text('密码和确认密码不一致');
            $('#repassword-error').fadeIn(300);
            return false;
        }
        else {
            $('#repassword-error').fadeOut(300);
            return true;
        }
   }
   //确认密码输入框光标离开时候检查
   $('input[name="repassword"]').on('change',function(){
      checkIsComfirmPassword($(this).val());
   });
   
   //验证验证码
   function checkVerifyCode(s) {
        var pattern = /^\d{6}$/;
        if (!pattern.test(s)) {
            $('#verify-code-error').text('验证码有误');
            $('#verify-code-error').fadeIn(300);
            return false;
        }
        else {
            $('#verify-code-error').fadeOut(300);
            return true;
        }
   }
   
   //阻止表单默认提交，必须等验证字段都通过了才能提交服务器(注册页和登录页)
   $("#signup_form").on('submit',function(){
       var telephone = $('input[name="telephone"]').val();
       var password = $('input[name="password"]').val();
       var repassword = $('input[name="repassword"]').val();
       var auth_code = $('input[name="auth_code"]').val();
       if(checkIsMobil(telephone) && checkIsPassword(password) && checkIsComfirmPassword(repassword) && checkVerifyCode(auth_code)) {
         return true;
       }
       else {
           return false;
       }
   });
   
   $("#login_form").on('submit',function(){
       var telephone = $('input[name="login_telephone"]').val();
       var password = $('input[name="login_password"]').val();
       if(checkIsMobil(telephone) && checkIsPassword(password)) {
         return true;
       }
       else {
           return false;
       }
   });
   
   //忘记密码验证
   $("#reset_password_form").on('submit',function(){
       var telephone = $('input[name="telephone"]').val();
       var password = $('input[name="password"]').val();
       var repassword = $('input[name="repassword"]').val();
       var auth_code = $('input[name="auth_code"]').val();
       if(checkIsMobil(telephone) && checkIsPassword(password) && checkIsComfirmPassword(repassword) && checkVerifyCode(auth_code)) {
         return true;
       }
       else {
           return false;
       }
   });
   
   //提交订单地址验证
   $('#order_submit_confirm').on('submit',function(){
       var error_message_list = '';
       if($('input[name="first_name"]').val() == '') {
           error_message_list = error_message_list + '● 姓不能为空' + '<br/>';
       }
       if($('input[name="last_name"]').val() == '') {
           error_message_list = error_message_list + '● 名字不能为空' + '<br/>';
       }
       
       var pattern = /^[1][3,4,5,7,8][0-9]{9}$/;
       if(!pattern.test($('input[name="phone_number"]').val())) {
           error_message_list = error_message_list + '● 收货人联系方式格式有误' + '<br/>';
       }
       if($('#city-picker3').val() == '') {
           error_message_list = error_message_list + '● 省市不能为空' + '<br/>';
       }
       if($('#address_detail_location').val() == '') {
           error_message_list = error_message_list + '● 街道门牌等详细信息不能为空' + '<br/>';
       }
       var pattern = /^[0-9]{5,8}$/;
       if(!pattern.test($('#zip_code').val())) {
           error_message_list = error_message_list + '● 邮政编码格式不正确';
       }
       if(error_message_list != '') {
           $('#error-message').html(error_message_list);
           $('#error-message').show(300);
           return false;
       }
       else {
           $('#error-message').hide(100);
           return true;
       }
   });
   $('a.checkout-to-flow3').on('click',function() {
       $('#order_submit_confirm').submit();
   });
})