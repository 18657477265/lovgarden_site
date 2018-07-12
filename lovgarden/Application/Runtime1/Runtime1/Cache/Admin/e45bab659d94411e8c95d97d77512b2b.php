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
	
	
<link rel="stylesheet" type="text/css" href="/Public/Admin/LoadImg-master/assets/css/loadimg.min.css">
<script src="/Public/Admin/LoadImg-master/assets/js/loadimg.min.js"></script>
<section class="rt_wrap content mCustomScrollbar section-product-detail">
 <div class="rt_content">
      <div class="page_title">
       <h2 class="fl">文章添加</h2>
       <a title="" class="fr top_rt_btn add_icon" href="/Admin/Product/add">添加文章</a>
      </div>
     <section>
         <?php if(isset($errorMessage)): ?>
         <p style='color: red;'><?php echo $errorMessage; ?></p>
         <?php endif; ?>
         <form enctype="multipart/form-data" action="/Admin/Article/update_article/id/39" method="post">
                    <ul class="ulColumn2">
                     <li>
                      <span class="item_name" style="width:120px;">文章标题：</span>                      
                      <input type="text" name="article_title" class="textbox" placeholder="文章标题" value="<?php echo $data['article_title']; ?>"  />
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">文章简要描述：</span>
                      <textarea name="article_summary"><?php echo $data['article_summary']; ?></textarea>
                     </li>                 
                     <li>
                         <span class="item_name" style="width: 120px">文章类别:</span>
                         <?php print buildHtmlSelect2('lovgarden_article_category','id','article_category_name','article_category_name',$data['article_category']) ?>
                     </li>
                      <li>
                      <span class="item_name" style="width:120px;">文章状态：</span>
                      <label class="single_selection"><input type="radio" name="article_publish" value="1" <?php if($data['article_publish'] == '1'): echo 'checked="checked"'; endif; ?> />发布</label>
                      <label class="single_selection"><input type="radio" name="article_publish" value="2" <?php if($data['article_publish'] == '2'): echo 'checked="checked"'; endif; ?>/>未发布</label>
                      <label class="single_selection"><input type="radio" name="article_publish" value="3" <?php if($data['article_publish'] == '3'): echo 'checked="checked"'; endif; ?>/>删除</label>
                     </li>
                     <li>
                      <span class="item_name" style="width:120px;">关联id：</span>                      
                      <input type="text" name="related_article_ids" class="textbox" placeholder="关联id" value="<?php echo $data['related_article_ids']; ?>"  />
                     </li>
                        <div style="width: 15%;">
                            <label id="upload" exist-img="<?php  print C('IMAGE_CONFIG')['viewPath'].$data['banner_image']; ?>">
                              <input name="banner_image" type="file" />
                           </label>
                       </div>
                     <li style="margin-top: 214px;">
                      <span class="item_name" style="width:120px;">商品详细描述：</span>
                      <textarea id="editor" name="article_body"><?php echo $data['article_body']; ?></textarea>
                     </li>  
                    </ul>
                    <div><input type="submit" class="link_btn"/></div>
         </form>
     </section>
 </div>
</section>
<script type="text/javascript">
$(function() {
    $('#upload').loadImg({
            "text" : "文章banner图片",
            "fileExt" : ["png","jpg"],
    });
});
</script>



	
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