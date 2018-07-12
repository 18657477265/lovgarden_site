<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
	
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1 , user-scalable=no">
     <link rel="shortcut icon" href="/favicon.ico" />
     <title>首页|花点馨思(丽de花苑)</title>
     <link rel="stylesheet" href="/Public/Home/css/bootstrap.min.css"/>
     <link rel="stylesheet" href="/Public/Home/css/bootstrap-maizi.css"/>
     <link rel="stylesheet" href="/Public/Home/css/animate.css"/>
     <link rel="stylesheet" href="/Public/Home/css/style.css"/>
     <script src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="/Public/Home/js/bootstrap.min.js"></script>
     <script src="/Public/Home/js/jquery.cookie.js"></script>
     <script src="/Public/Home/js/jquery.popupoverlay.js"></script>
</head>
<body class="page">
  <nav class="fixed-top">
    <div class="navbar-desktop">
        <ul> 
            <li id="shop-market" class="shop"><a href="#home" style="color: orange;">精品店</a>
                
            </li>
            <li id="shop-feature" class="shop"><a href="#bbs">花苑特色</a></li>
            <li id="shop-brief" class="shop"><a href="#html5">花苑介绍</a></li>
            <li id="logo" class="shop"><a href="/"><img src="/Public/Home/images/lovgarden_logo.png"></a></li>
            <li id="help" class="shop"><a href="/article/helpcenter">帮助</a></li>
            <li id="login" class="shop">
                <a href="javascript:void(0);">
                    <span class="glyphicon glyphicon-user"></span>                
                </a>
                <div class="user-options">
                    <span class="arrow-icon"></span>
                    <ul>
                        <li class="option1"><span><a href="/user/login">登录</a></span></li>
                        <li class="option2"><span><a href="/user/register">注册</a></span></li>
                    </ul>
               </div>
            </li>
            <li id="cart" class="shop my-cart"><a title="我的购物车" href="javascript:void(0);"><span class="glyphicon glyphicon-shopping-cart shopping-cart-icon"><span class='add_to_cart_number'>0</span></span></a>
                <div class="checkout__order" style="display: none;">
                    <div class="checkout__order-inner">
                        <table class="checkout__summary">
                            <thead>
                            <tr><th>购物车中商品</th><th>单价</th><th>数量</th><th></th></tr>
                            </thead>
                            <tfoot>
                            <tr><th colspan="4">总价 <span class="checkout__total">0</span></th></tr>
                            </tfoot>
                            <tbody>
                            
                            </tbody>
                        </table><!-- /checkout__summary -->
                        <p style="display: none;" class="link-to-cart-page"><button class="checkout__option checkout__option--loud">结算</button></p>
                   </div>
               </div>
            </li>
        </ul>
   </div>
   
   <div class="navbar-mobile"> 
     <div class="container">
       <div class="col-xs-3 shop-mobile menu-list-mobile">         
           <a><span class="glyphicon glyphicon-menu-hamburger mobile-menu-show"></span></a> 
       </div>
       <div class="col-xs-6 shop-mobile logo-mobile">
           <a href="/" title='花点馨思首页'><img src="/Public/Home/images/lovgarden_logo.png"></a>
       </div>
       <div class="col-xs-3 shop-mobile cart-mobile">
           <a href="#contact" title="我的购物车"><span class="glyphicon glyphicon-shopping-cart shopping-cart-icon"><span class='add_to_cart_number'>0</span></span></a>
           <div class="checkout__order" style="display: none;">
                    <div class="checkout__order-inner">
                        <table class="checkout__summary">
                            <thead>
                                <tr><th>购物车中商品</th><th>单价</th><th>数量</th><th></th></tr>
                            </thead>
                            <tfoot>
                            <tr><th colspan="4">总价 <span class="checkout__total">0</span></th></tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table><!-- /checkout__summary -->
                        <p style="display: none;" class="link-to-cart-page"><button class="checkout__option checkout__option--loud">结算</button></p>
                   </div>
          </div>
       </div> 
     </div>    
   </div>
  </nav>

<div id="my-cart-detail-info">
    
</div>

    <section id="shop-menu-dropdown">
    <div class="container-fluid">
      <div class="col-md-2 col-sm-2">
        <ul class="flower-style">
          <li><a class="bold" href="www.baidu.com">全部</a></li>
          <li><a class="bold">刚采就送</a></li>
          <li><a class="bold">艺术款</a></li>
          <li><a class="bold">流行风</a></li>
          <li><a class="bold">情人节/七夕</a></li>
        </ul>

         <ul class="flower-time">
          <li class="bold">配送时间</li>
          <li><a>当天 +20</a></li>
          <li><a>次日 免费</a></li>
          <li><a>不急 免费</a></li>
        </ul>

        <ul class="flower-origin">
          <li class="bold">花卉原产地</li>
          <li><a>云南昆明</a></li>
          <li><a>日本</a></li>
          <li><a>新西兰</a></li>
        </ul>
      </div>
      <div class="col-md-2 col-sm-2">
        <ul class="flower-category">
          <li class="bold">鲜花品种</li>
          <li><a>康乃馨</a></li>
          <li><a>玫瑰</a></li>
          <li><a>百合花</a></li>
          <li><a>郁金香</a></li>
          <li><a>向日葵</a></li>
          <li><a>牡丹</a></li>
          <li><a>绣球花</a></li>
          <li><a>小苍兰</a></li>
          <li><a>雏菊</a></li>
          <li><a>兰花</a></li>
          <li><a>毛茛</a></li>
          <li><a>微景观</a></li>
        </ul>
      </div>
      <div class="col-md-2 col-sm-2">
        <ul class="flower-occasion">
          <li class="bold">使用场合</li>
          <li><a>周年纪念日</a></li>
          <li><a>生日快乐</a></li>
          <li><a>恭喜祝贺</a></li>
          <li><a>告别</a></li>
          <li><a>我很抱歉</a></li>
          <li><a>喜得贵子</a></li>
          <li><a>传递关心</a></li>
          <li><a>感谢</a></li>
        </ul>

        <ul class="flower-service">
          <li class="bold">鲜花服务</li>
          <li><a>婚礼</a></li>
          <li><a>大事</a></li>
          <li><a>活动</a></li>
          <li><a>装饰</a></li>
        </ul>
      </div>
      
        <div class="col-md-3 col-sm-6">
          <div class="menu-block1 menu-block">
              <a class="menu-block-img" href="/flowers/all">
                  <img src="/Public/Home/images/home/home_menu_block1.png">
              </a>
              <div class="menu-block-des">
                <h3 class="menu-block-des-title"><a href="/flowers/all" target="">SHOP ALL</a></h3> 
                <p class="menu-block-des-content">Shop a wide array of sustainably grown farm direct bouquets and hand-crafted, custom designs by local Artisan Florists.<br></p>
              </div>
           </div>
        </div>
        <div class="col-md-3 mobile-gone">
          <div class="menu-block2 menu-block">
              <a class="menu-block-img" href="/flowers/all">
                  <img src="/Public/Home/images/home/home_menu_block1.png">
              </a>
              <div class="menu-block-des">
                <h3 class="menu-block-des-title"><a href="/flowers/all" target="">SHOP ALL2</a></h3> 
                <p class="menu-block-des-content">Shop a wide array of sustainably grown farm direct bouquets and hand-crafted, custom designs by local Artisan Florists.<br></p>
              </div>
           </div>
        </div>
      
    </div>
  </section>
  <div id="mobile-menu-section">
	  <section id="shop-menu-popup">
	    <ul>
	      <li class="account top"><a>账号</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="account foder"><a href="/user/login" class="account-option1">登录</a></li>
	      <li class="account bottom foder"><a href="/user/register" class="account-option2">注册</a></li>
	      <li class="mobile-shop top"><a style="color: orange;">精品店</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="mobile-shop foder"><a>全部</a></li>
	      <li class="mobile-shop foder"><a>刚采就送</a></li>
	      <li class="mobile-shop foder"><a>鲜花流行风</a></li> 
	      <li class="mobile-shop foder"><a>情人节/七夕</a></li>
	      <li class="mobile-shop bottom foder"><a>重要节日</a></li>
	      <li class="deliver-time top"><a>配送时间</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="deliver-time foder"><a>当天</a></li>
	      <li class="deliver-time foder"><a>次日</a></li>
	      <li class="deliver-time bottom foder"><a>不急</a></li>
	      <li class="other-service top"><a>其他服务</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="other-service foder"><a>活动</a></li>
	      <li class="other-service foder"><a>婚礼</a></li>
	      <li class="other-service foder"><a>重要的事</a></li>
	      <li class="other-service bottom foder"><a>装饰</a></li>
	      <li class="about-us top"><a>花苑特色</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="about-us foder"><a>花苑介绍</a></li>
	      <li class="about-us foder"><a>关于我们</a></li>
	      <li class="about-us bottom foder"><a>微信公众号</a></li>
	      <li class="about-help top"><a>帮助</a><span class="glyphicon glyphicon-plus"></span></li>
	      <li class="about-help foder"><a>帮助中心</a></li>
	      <li class="about-help foder"><a>关于配送</a></li>
	      <li class="about-help foder"><a>用户须知</a></li>
	      <li class="about-help foder"><a>网站地图</a></li>
	    </ul>
	  </section>
	  <span class="mobile-menu-close glyphicon glyphicon-remove"></span>
   </div>

   <!-- banner 左侧无边框描述栏款式配置代码-->
   <div class="banner wow slideInRight" data-wow-offset="30">
		<div class="content-block-full-wide">
		<div class="block-background-img">
                    <img class="show-pc" data-img-pc="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[0]['image_pc'] ?>" width="100%" />
      <img class="show-tablet" data-img-tablet="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[0]['image_pc'] ?>" width="100%" />
			<img class="show-mobile" data-img-mobile="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[0]['image_mobile'] ?>" width="100%" />
	    </div>
			<div class="content-block-left-description col-md-6 col-xs-12 wow zoomIn" data-wow-offset="30" data-wow-delay="0.5s">
			  <h2 class="content-block-left-description-title blend-into-image"><?php print $blocks[0]['block_title'] ?></h2>
			  <p class="content-block-left-description-body blend-into-image"><?php print $blocks[0]['block_body'] ?></p>
			  <p class="content-block-left-description-button"><a href='<?php print $blocks[0]["block_link"] ?>' class="button button-wide"><?php print $blocks[0]['block_link_title'] ?></a></p>
			</div>
		</div>
   </div>
   
   <!--block 3框栏配置代码-->
   <div class="html-content-block">
     <div class="how-it-works container-fluid">
        <h2 class="wow zoomIn" data-wow-offset="30">如何订花</h2>
          <h4>简单3步 1 2 3</h4>
     <div class="col-md-4 col-sm-4 col-xs-12 show-part  wow zoomIn" data-wow-offset="30">
            <span class="glyphicon glyphicon-shopping-cart"></span>
          <h4>选择您喜欢的款式</h4>
          <p>我们的花卉来自世界各地，您在这儿可以有大量的选择</p>
     </div>
     <div class="col-md-4 col-sm-4 col-xs-12 show-part  wow zoomIn" data-wow-offset="30">
            <span class="glyphicon glyphicon-scissors"></span>
          <h4>从农场采摘</h4>
          <p>您在这儿按照配送日期下单，我们会从农场采购刚采摘的花材，制作和配送，当然您也可以做加急处理</p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 show-part  wow zoomIn" data-wow-offset="30">
          <span class="glyphicon glyphicon-thumbs-up"></span>
          <h4>enjoy来自我们的艺术鲜花</h4>
          <p>送到您手里的鲜花有我们的鲜花品质保证</p>
    </div>
    <div style="clear: both;"></div>
    <p class="content-block-left-description-button wow zoomIn" data-wow-offset="30"><a class="button button-wide">去购买</a></p>
    </div>
  </div>

  <!-- banner 右侧有边框描述栏款式配置代码-->
   <div class="banner wow slideInLeft" data-wow-offset="30">
    <div class="content-block-full-wide">
    <div class="block-background-img">
      <img class="show-pc" data-img-pc="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[1]['image_pc'] ?>" width="100%" />
      <img class="show-tablet" data-img-tablet="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[1]['image_pc'] ?>" width="100%" />
      <img class="show-mobile" data-img-mobile="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[1]['image_mobile'] ?>" width="100%" />
      </div>
      <div class="content-block-left-description col-md-6 col-sm-6 col-xs-12 right-square wow zoomIn" data-wow-offset="30" data-wow-delay="0.2s">
        <div class="right-square-content">
          <!--<h4><?php print $blocks[1]['block_title'] ?></h4>-->
          <h2 class="content-block-left-description-title"><?php print $blocks[1]['block_title'] ?></h2>
          <p class="content-block-left-description-body"><?php print $blocks[1]['block_body'] ?></p>
          <p class="content-block-left-description-button"><a href="<?php print $blocks[0]['block_link'] ?>" class="button button-wide"><?php print $blocks[0]['block_link_title'] ?></a></p>
        </div>
      </div>
    </div>
   </div>

   <!--block 3框栏配置代码-->
   <div class="html-content-block">
     <div class="how-it-works container-fluid">
        <h2 class="wow zoomIn" data-wow-offset="30">省钱30%+免费配送</h2>
          <h4>日常鲜花订阅</h4>
          <p>给自己和她(他)每周或者每月一次鲜花，让她(他)的房间四季如春,天天好心情。</p>
     <div class="col-md-4 col-sm-4 col-xs-12 show-part wow zoomIn" data-wow-offset="30">
            <span class="glyphicon glyphicon-yen"></span>
          <h4>为您省时省力</h4>
     </div>
     <div class="col-md-4 col-sm-4 col-xs-12 show-part wow zoomIn" data-wow-offset="30">
            <span class="glyphicon glyphicon-heart"></span>
          <h4>鲜花品质保证</h4>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 show-part wow zoomIn" data-wow-offset="30">
          <span class="glyphicon glyphicon-calendar"></span>
          <h4>按您的计划配送</h4>
    </div>
    <div style="clear: both;"></div>
    <p class="content-block-left-description-button wow zoomIn" data-wow-offset="30"><a class="button button-wide">马上订阅</a></p>
    </div>
  </div>

   <!-- banner 左侧有边框描述栏款式配置代码-->
   <div class="banner wow slideInRight" data-wow-offset="30">
    <div class="content-block-full-wide">
    <div class="block-background-img">
      <img class="show-pc" data-img-pc="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[2]['image_pc'] ?>" width="100%" />
      <img class="show-tablet" data-img-tablet="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[2]['image_pc'] ?>" width="100%" />
      <img class="show-mobile" data-img-mobile="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[2]['image_mobile'] ?>" width="100%" />
      </div>
      <div class="content-block-left-description col-md-6 col-sm-6 col-xs-12 left-square wow zoomIn" data-wow-offset="30" data-wow-delay="0.2s">
        <div class="left-square-content">
          <h2 class="content-block-left-description-title"><?php print $blocks[2]['block_title'] ?></h2>
          <!--<h4>DELIVER SAME DAY</h4>-->
          <p class="content-block-left-description-body"><?php print $blocks[2]['block_body'] ?></p>
          <p class="content-block-left-description-button"><a href="<?php print $blocks[1]['block_link'] ?>" class="button button-wide"><?php print $blocks[2]['block_link_title'] ?></a></p>
        </div>
      </div>
    </div>
   </div>
   
   <!--block 单框-->
   <div class="html-content-block wow zoomIn" data-wow-offset="30">
   	<div class="happiness container-fluid">
	   	   <h2>满意保证</h2>
	       <h4>Guarantee</h4>
	       <span class="icon icon--happiness-guaranteed"></span>
	       <div class="description">
	         <p>如果配送不及时或者鲜花到手时候有质量问题,我们会按流程马上包退，包换，请放心购买</p>
	       </div>
	       <p class="content-block-left-description-button"><a class="button button-wide">马上购买</a></p>
    </div>
   </div>

   <!-- banner 右侧有边框描述栏款式配置代码-->
   <div class="banner wow slideInLeft" data-wow-offset="30">
    <div class="content-block-full-wide">
    <div class="block-background-img">
      <img class="show-pc" data-img-pc="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[3]['image_pc'] ?>" width="100%" />
      <img class="show-tablet" data-img-tablet="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[3]['image_pc'] ?>" width="100%" />
      <img class="show-mobile" data-img-mobile="<?php print C('IMAGE_CONFIG')['viewPath'].$blocks[3]['image_mobile'] ?>" width="100%" />
      </div>
      <div class="content-block-left-description col-md-6 col-sm-6 col-xs-12 right-square wow zoomIn" data-wow-offset="30" data-wow-delay="0.2s">
        <div class="right-square-content">
          <h2 class="content-block-left-description-title"><?php print $blocks[3]['block_title'] ?></h2>
          <!--<h4>EVENTS & CORPORATE SERVICES</h4>-->
          <p class="content-block-left-description-body"><?php print $blocks[3]['block_body'] ?></p>
          <p class="content-block-left-description-button"><a href="<?php print $blocks[3]['block_link'] ?>" class="button button-wide"><?php print $blocks[3]['block_link_title'] ?></a></p>
        </div>
      </div>
    </div>
   </div>
  
   <!--block 底座-->
      <div class="container footer wow slideInUp" data-wow-offset="30">
   		<div class="col-md-3 col-sm-3">
   			<div><h3>The Bouqs Co.</h3>
   				<ul>
   					<li><a>About</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Our Difference</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Press</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Bouqs Video</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Blog</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
          </ul>
        </div>
   		</div>
   		<div class="col-md-3 col-sm-3">
   			<div><h3>ADDITIONAL SERVICES</h3>
   				<ul>
   					<li><a>Events</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Weddings</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Corporate Gifts</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Gift Cards</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
          </ul>
        </div>
   		</div>
   		<div class="col-md-3 col-sm-3">
   			<div><h3>WORK WITH US</h3>
   				<ul>
   					<li><a>Jobs</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Florists</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>Affiliates</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
          </ul>
        </div>
   		</div>
   		<div class="col-md-3 col-sm-3">
   			<div><h3>帮助</h3>
   				<ul>
   					<li><a href="/article/faq/id/32">花卉护理</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a href="/article/helpcenter">帮助中心</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a href="/article/faq/id/28">关于配送</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>法律条款</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
            <li><a>站点地图</a><span class="show-mobile glyphicon glyphicon-menu-right"></span></li>
          </ul>
        </div>
   		</div>
   </div>
   <!--社交媒体-->
   
   <div class="page-footer__social wow slideInUp" data-wow-offset="30">
        <ul class="share-buttons">
          <li class="share-buttons__item"><a class="share-buttons__button icon icon--wechat" title="微信公众号" target="_blank" href=javascript:void(0);>Follow The Bouqs on Twitter</a></li>
          <li class="share-buttons__item show-pc"><a class="share-buttons__button icon icon--qq" title="QQ客服" target="_blank" href="//wpa.qq.com/msgrd?v=3&uin=1048290286&site=qq&menu=yes">Follow The Bouqs on Facebook</a></li>
          <li class="share-buttons__item show-tablet-mobile"><a class="share-buttons__button icon icon--qq" title="QQ客服" target="_blank" href="mqqwpa://im/chat?chat_type=wpa&uin=1048290286&version=1&src_type=web&web_src=oicqzone.com">Follow The Bouqs on Facebook</a></li>
          <li class="share-buttons__item"><a class="share-buttons__button icon icon--tuangou" title="新浪微博" target="_blank" href="https://www.pinterest.com/thebouqsco/">Follow The Bouqs on Pinterest</a></li>        
        </ul>
       <div class="wechat-popup" style="display: none;"><img src="/Public/Home/images/wechat.png" style="width: 200px;" /></div>
   </div>
   <div class="foot-copyright wow slideInUp" data-wow-offset="10">
     <p>本站点版权归丽de花苑所有 -2018年3月</p>
   </div>
   <div class="feedback-box">
  <div class="content" style="border-color: orange;"> <a class='message_bar_close' href="javascript:void(0);" style="text-decoration: none;">X</a>
    <div class="confirm">
      <h1><span class="glyphicon glyphicon-envelope"></span> <strong>BOOOM!</strong> <span>花点馨思会尽快反馈</span></h1>
    </div>
    <header>花点馨思留言板</header>
    <section class="message-bar" style="padding-top: 0;margin-right: 0px;">
      <p class="error-message"></p>
      
      <input  class="control tel-input" type="tel" name="user_tel" placeholder="请输入您的联系方式" />
      <textarea style="z-index: 0;width: 99%;margin-bottom: 12px;font-size: 16px;line-height: 1.5;resize: none;color: #333;border: 1px solid #ddd; padding:1px;height: 150px;" class="control" name="feedback" placeholder="请输入您的留言，如果您想要更好的咨询体验，可以点击页面底部的QQ按钮或者拨打18657477265和15168188557"></textarea>
      <button id='submit' style="background-color: orange;border: 1px solid orange;color: #fff;cursor: pointer;">提交您的留言</button>      
    </section>
  </div>
</div>
<button id="feedback" style="border-radius: 25px;"><span class="glyphicon glyphicon-envelope send-message-icon"></span>想留言点这里</button>
<link rel="stylesheet" href="/Public/Home/css/message-bar.css"/>
<script src="/Public/Home/js/message-bar.js"></script>
   
    <script src="/Public/Home/js/jquery.singlePageNav.min.js"></script>
    <script src="/Public/Home/js/wow.min.js"></script> 
    <script src="/Public/Home/js/custom.js"></script>
    <script src="/Public/Home/js/nocache.js"></script>
    <script src="/Public/Home/js/popup.js"></script>
    <script>
      $(function(){
        new WOW().init();
      })
    </script>
</body>

</html>