<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>丽de花苑后台管理系统</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css">
<!--[if lt IE 9]>
<script src="/Public/Admin/js/html5.js"></script>
<![endif]-->
<script src="/Public/Admin/js/jquery.js"></script>
<script src="/Public/Admin/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>

	(function($){
		$(window).load(function(){
			
			$("a[rel='load-content']").click(function(e){
				e.preventDefault();
				var url=$(this).attr("href");
				$.get(url,function(data){
					$(".content .mCSB_container").append(data); //load new content inside .mCSB_container
					//scroll-to appended content 
					$(".content").mCustomScrollbar("scrollTo","h2:last");
				});
			});
			
			$(".content").delegate("a[href='top']","click",function(e){
				e.preventDefault();
				$(".content").mCustomScrollbar("scrollTo",$(this).attr("href"));
			});
			
		});
	})(jQuery);
</script>
</head>
<body class="product-detail">
<!--header-->
<header>
 <h1><img src="/Public/Admin/images/admin_logo.png"/></h1>
 <ul class="rt_nav">
  <li><a href="http://www.mycodes.net" target="_blank" class="website_icon">站点首页</a></li>
  <li><a href="#" class="clear_icon">清除缓存</a></li>
  <li><a href="#" class="admin_icon">DeathGhost</a></li>
  <li><a href="#" class="set_icon">账号设置</a></li>
  <li><a href="/Admin/User/logout" class="quit_icon">安全退出</a></li>
 </ul>
</header>
<!--aside nav-->
<!--aside nav-->
<aside class="lt_aside_nav content mCustomScrollbar">
 <h2><a href="/Admin/Index/index">起始页</a></h2>
 <ul class="left-side-menu">
  <li>
   <dl>
    <dt>商品管理</dt>
    <!--当前链接则添加class:active-->
    <dd><?php print check_permission_view("/Admin/Product/product_list","商品列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/Product/add","商品添加"); ?></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>图片区块管理</dt>
    <dd><?php print check_permission_view("/Admin/Block/block_list","图片区块列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/Block/add","图片区块添加"); ?></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>会员管理</dt>
    <dd><?php print check_permission_view("/Admin/User/user_list","会员列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/User/user_add","添加会员"); ?></dd>
   </dl>
  </li>
   <li>
   <dl>
    <dt>角色管理</dt>
    <dd><?php print check_permission_view("/Admin/Role/role_list","角色列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/Role/add","添加角色"); ?></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>权限管理</dt>
    <dd><?php print check_permission_view("/Admin/Permission/permission_list","权限列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/Permission/add","添加权限"); ?></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>订单管理</dt>
    <dd><?php print check_permission_view("/Admin/Order/order_list","订单列表"); ?></dd>
   </dl>
  </li>
  <li>
  <li>
   <dl>
    <dt>文章管理</dt>
    <dd><?php print check_permission_view("/Admin/Article/article_list","文章列表"); ?></dd>
    <dd><?php print check_permission_view("/Admin/Article/add_article","添加文章"); ?></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>基础设置</dt>
    <dd><a href="setting.html">站点基础设置示例</a></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt>在线统计</dt>
    <dd><a href="discharge_statistic.html">流量统计</a></dd>
    <dd><a href="sales_volume.html">销售额统计</a></dd>
   </dl>
  </li>
  <li>
   <p class="btm_infor">© DeathGhost.cn 版权所有</p>
  </li>
 </ul>
</aside>
	
	

<section class="rt_wrap content mCustomScrollbar">
<link rel="stylesheet" href="/Public/Home/css/datepicker.css">
<style>
    .datepicker {
        top: 169px;
        left: 681.612px;
        z-index: 10;
        position: absolute;
        display: block;
        background-color: rgb(255, 255, 255);
        border: 2px solid green;
    }
</style>
<script src="/Public/Home/js/bootstrap-datepicker.js">"></script>
<script src="/Public/Home/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<script>
      $(document).ready(function() {
         $('input[name="order_create_time_start"]').datepicker({
              format: 'yyyy-mm-dd',
              language: 'zh-CN',
              autoclose: true,                       
         });
         $('input[name="order_create_time_end"]').datepicker({
              format: 'yyyy-mm-dd',
              language: 'zh-CN',
              autoclose: true,                       
         });
      });
</script>
    <div class="rt_content" style="overflow-x:scroll;">
      <div class="page_title">
       <h2 class="fl">订单列表</h2>
      </div>
     <form class="mtb" action="/Admin/Order/order_list" method="GET">
         <input name="order_id" type="text" class="textbox textbox_100" placeholder="订单编号" value="<?php print $filter_selection['order_id']; ?>"/>
       <input name="order_owner" type="text" class="textbox textbox_100" placeholder="下单用户" value="<?php print $filter_selection['order_owner']; ?>"/>
       <input name="telephone" type="text" class="textbox textbox_100" placeholder="收货人电话" value="<?php print $filter_selection['telephone']; ?>"/>
       <input name="order_create_time_start" type="text" class="textbox textbox_100" placeholder="订单创建时间区间-开始" value="<?php print $filter_selection['order_create_time_start']; ?>"/>
       <input name="order_create_time_end" type="text" class="textbox textbox_100" placeholder="订单创建时间区间-结束" value="<?php print $filter_selection['order_create_time_end']; ?>"/>
       <select class="select" name='order_status'>
        <option value='all'>订单状态</option>
        <option  value="1" <?php if($filter_selection['order_status'] === '1'): echo "selected='selected'"; endif; ?>>未付款</option>
        <option value="2"  <?php if($filter_selection['order_status'] === '2'): echo "selected='selected'"; endif; ?>>已付款</option>
        <option value="3"  <?php if($filter_selection['order_status'] === '3'): echo "selected='selected'"; endif; ?>>退款</option>
       </select>
       <input name="others" type="text" class="textbox textbox_225" placeholder="输入名字备注地址等其他信息..." value="<?php print $filter_selection['others']; ?>"/>
       <input type="submit" value="查询" class="group_btn"/>
      </form>
      <table class="table" style='width: 3000px;'>
       <tr>
        <th>订单编号</th>
        <th>下单用户</th>
        <th>收货人姓名</th>
        <th>收货人电话</th>
        <th>收获地址</th>
        <th>邮编</th>
        <th>备注(贺卡)</th>
        <th>创建时间</th>
        <th>商品总价</th>
        <th>运费</th>
        <th>花瓶总价</th>
        <th>优惠码</th>
        <th>优惠码减价</th>
        <th>会员减价</th>
        <th>付款最终价</th>
        <th>订单状态</th>
        <th>支付时间</th>
        <th>操作</th>
       </tr>
       <?php foreach($orders as $k => $v): ?>
        <tr>
         <td class="center"><?php print $v['order_id']; ?></td>
         <td><?php print $v['order_owner']; ?></td>
         <td class="center"><?php print $v['first_name'].$v['last_name']; ?></td>
         <td class="center"><?php print $v['telephone']; ?></td>
         <td class="center"><?php print $v['area'].$v['address']; ?></td>
         <td class="center"><?php print $v['post_code']; ?></td>
         <td class="center"><?php print $v['content_body']; ?></td>
         <td class="center"><?php print $v['order_create_time']; ?></td>
         <td class="center"><?php print $v['order_products_total_price']; ?></td>
         <td class="center"><?php print $v['order_deliver_price']; ?></td>
         <td class="center"><?php print $v['order_vases_price']; ?></td>
         <td class="center"><?php print $v['order_coupon_code']; ?></td>
         <td class="center"><?php print $v['order_coupon_cut']; ?></td>
         <td class="center"><?php print $v['order_vip_level_cut']; ?></td>
         <td class="center"><?php print $v['order_final_price']; ?></td>
         <td class="center"><?php print get_order_status_label($v['order_status']); ?></td>
         <td class="center"><?php print $v['pay_time']; ?></td>
         <td class="center">
             <a target='_blank' href="/Admin/Order/detail/id/<?php print $v['id']; ?>" data-order-id="<?php print $v['id']; ?>" title="所含商品信息" class="link_icon" target="_blank">&#118;</a>
         </td>
        </tr>
       <?php endforeach; ?>
      </table>
      <aside class="paging">
        <?php echo $page; ?>
      </aside>
 </div>
</section>



	
<script src="/Public/Admin/js/ueditor.config.js"></script>
<script src="/Public/Admin/js/ueditor.all.min.js"> </script>
<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
</script>
<script src="/Public/Admin/js/CustomAdmin.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/CustomStyle.css">
</body>
</html>