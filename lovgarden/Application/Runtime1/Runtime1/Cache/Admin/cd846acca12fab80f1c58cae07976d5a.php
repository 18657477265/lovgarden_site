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
	
	
<section class="rt_wrap content mCustomScrollbar section-product-update">
 <div class="rt_content">
      <div class="page_title">
       <h2 class="fl">商品详情</h2>
       <?php print check_permission_view("/Admin/Product/product_list","返回产品列表",'fr top_rt_btn'); ?>
      </div>
     <section>
         <?php if(isset($errorMessage)): ?>
         <p style='color: red;'><?php echo $errorMessage; ?></p>
         <?php endif; ?>
         <form enctype="multipart/form-data" action="/Admin/Product/update/id/16" method="post">
                    <ul class="ulColumn2">
                     <li>
                      <span class="item_name" style="width:120px;">商品货号：</span>
                      
                      <input type="text" name="sku_id" class="textbox" placeholder="商品编号..." value="<?php echo $this_product_varient_info[$id]['sku_id']; ?>"  />
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">商品名称：</span>
                      <input type="text" name="varient_name" class="textbox" placeholder="商品名称" value="<?php echo $this_product_varient_info[$id]['varient_name']; ?>" />
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">商品简要：</span>
                      <input type="text" name="varient_summary" class="textbox" placeholder="商品简要" value="<?php echo $this_product_varient_info[$id]['varient_summary']; ?>" />
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">商品价格：</span>
                      <input type="text" name="varient_price" class="textbox" placeholder="商品价格"  value="<?php echo $this_product_varient_info[$id]['varient_price']; ?>" />
                     </li>
                     <li>
                      
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">商品状态：</span>
                      <label class="single_selection"><input type="radio" name="varient_status" value="1" <?php if($this_product_varient_info[$id]['varient_status'] == '1'): echo 'checked="checked"'; endif; ?> />上架</label>
                      <label class="single_selection"><input type="radio" name="varient_status" value="2" <?php if($this_product_varient_info[$id]['varient_status'] == '2'): echo 'checked="checked"'; endif; ?>/>下架</label>
                      <label class="single_selection"><input type="radio" name="varient_status" value="3" <?php if($this_product_varient_info[$id]['varient_status'] == '3'): echo 'checked="checked"'; endif; ?>/>删除</label>
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">配花程度：</span>
                      <label class="single_selection"><input type="radio" name="decoration_level" value="1" <?php if($this_product_varient_info[$id]['decoration_level'] == '1'): echo 'checked="checked"'; endif; ?>/>适中</label>
                      <label class="single_selection"><input type="radio" name="decoration_level" value="2" <?php if($this_product_varient_info[$id]['decoration_level'] == '2'): echo 'checked="checked"'; endif; ?>/>奢侈</label>
                      <label class="single_selection"><input type="radio" name="decoration_level" value="3" <?php if($this_product_varient_info[$id]['decoration_level'] == '3'): echo 'checked="checked"'; endif; ?>/>豪华</label>
                     </li>
                     
                      <li>
                      <span class="item_name" style="width:120px;">款式中是否有花瓶：</span>
                      <label class="single_selection"><input type="radio" name="vase" value="0" <?php if($this_product_varient_info[$id]['vase'] == '0'): echo 'checked="checked"'; endif; ?>/>没有花瓶</label>
                      <label class="single_selection"><input type="radio" name="vase" value="1" <?php if($this_product_varient_info[$id]['vase'] == '1'): echo 'checked="checked"'; endif; ?>/>有花瓶</label>
                     </li>
                     
                     <li>
                         <span class="item_name" style="width: 120px">商品所属专题类</span>
                         <?php print buildHtmlSelect2('lovgarden_article_category','id','article_category_name','category',$this_product_varient_info[$id]['category']); ?>
                     </li>
                     
                     <li>
                         <span class="item_name" style="width: 120px">包含颜色</span>
                         <?php print buildMultipleHtmlSelect('lovgarden_flower_color','id','flower_color','flower_color',$this_product_varient_info[$id]['flower_color_id']) ?>
                     </li>
                     <li>
                         <span class="item_name" style="width: 120px">可配送缓急</span>
                         <?php print buildMultipleHtmlSelect('lovgarden_hurry_level','id','hurry_level','hurry_level',$this_product_varient_info[$id]['hurry_level_id']) ?>
                     </li>
                     <li>
                         <span class="item_name" style="width: 120px">花卉品种</span>
                         <?php print buildMultipleHtmlSelect('lovgarden_flower_type','id','flower_name','flower_name',$this_product_varient_info[$id]['flower_type_id']) ?>
                     </li>
                     <li>
                         <span class="item_name" style="width: 120px">花卉产地</span>
                         <?php print buildMultipleHtmlSelect('lovgarden_flower_home','id','flower_home','flower_home',$this_product_varient_info[$id]['flower_home_id']) ?>
                     </li>
                     <li>
                         <span class="item_name" style="width: 120px">用花场合</span>
                         <?php print buildMultipleHtmlSelect('lovgarden_flower_occasion','id','flower_occasion','flower_occasion',$this_product_varient_info[$id]['flower_occasion_id']) ?>
                     </li>
                     <li class="add-images-in-product-update">
                         <?php foreach($key_value_images as $key => $value): ?>
                         <span class="product-varient-old-image"><img src="<?php echo C('IMAGE_CONFIG')['viewPath'].$value['image_url']; ?>" width="50px" height="50px"><a href="javascript:void(0);" data-row-id = <?php echo $value['id']; ?> data-product-varient-id=<?php echo $id ?>>移除</a></span>
                         <?php endforeach; ?>
                         <img style="width: 100px;height: 100px;display: none;" class="delete-processing" src="/Public/Admin/images/icon/timg.gif" />
                     </li>
                     <li><p><a class="add_more_image">继续添加图片</a></p></li>
                     <li>
                     
                     </li>
                    </ul>
                    <span class="item_name" style="width:120px;">商品详细描述：</span>
                    <textarea id="editor" name="varient_body"><?php echo $this_product_varient_info[$id]['varient_body']; ?></textarea>
                    <div><input type="submit" class="link_btn"/></div>
         </form>
     </section>
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