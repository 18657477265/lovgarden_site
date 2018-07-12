<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1 , user-scalable=no">
     <link rel="shortcut icon" href="/favicon.ico" />
     <title>用户中心|花点馨思(丽de花苑)</title>
     <link rel="stylesheet" href="/Public/Home/css/bootstrap.min.css"/>
     <link rel="stylesheet" href="/Public/Home/css/bootstrap-maizi.css"/>
     <link rel="stylesheet" href="/Public/Home/css/nice-select.css">
     <link rel="stylesheet" href="/Public/Home/css/style.css"/>
     <link rel="stylesheet" href="/Public/Home/css/component.css" />
     <link rel="stylesheet" href="/Public/Home/css/usercenter.css" />
     <script src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="/Public/Home/js/bootstrap.min.js"></script>
     <script src="/Public/Home/js/jquery.cookie.js"></script>
     <script src="/Public/Home/js/jquery.nice-select.min.js"></script>
</head>
<body class="page reset-password-page">
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
  <div class="header-gap" style='height: 200px;'></div>
  <div class="container-fluid singup-block">	
			<div id="tabs" class="tabs">
                            <nav style="position: relative;z-index: 1;">
					<ul>
						<li><a href="#section-1" class="icon-order"><span>订单</span></a></li>
						<li><a href="#section-2"><span>Drinks</span></a></li>
						<li><a href="#section-3"><span>Food</span></a></li>
						<li><a href="#section-4"><span>Lab</span></a></li>
                                                <li><a href="#section-5"><span>Shop</span></a></li>
					</ul>
				</nav>
				<div class="content">
                                    <section id="section-1" style="padding-bottom: 140px;">
                                      <?php if(!empty($orders)): ?>
					                        <form class="container-fluid order-filter-form" action="/Home/User/usercenter" method="GET" id="order_filter">
                                            <div class="row col-md-3 col-sm-3 col-xs-12 order-status-filter filter-item">
                                                <select name="order_status" id="order_status" aria-required="true" aria-invalid="false" class="valid">
                                                  <option value="">订单状态</option>
                                                  <option value="1" <?php if($filter_selection['order_status']=='1'){print 'selected="true"';} ?>>未支付</option>
                                                  <option value="2" <?php if($filter_selection['order_status']=='2'){print 'selected="true"';} ?>>已支付</option>                        
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12 order-id-filter filter-item">
                                                <input type="text" name="order-id-filter" id="order-id-filter" class="text-box form-control" title="订单编号" placeholder="订单编号" autocomplete="off" maxlength="30" value="<?php print $filter_selection['order_id']; ?>">
                                            </div>
                                            <div class="row col-md-3 col-sm-3 col-xs-12 order-order-by filter-item">
                                                <select name="order_order_" id="order_order_by" aria-required="true" aria-invalid="false" class="valid">             
                                                  <option value="1" <?php if($filter_selection['order_order_']=='1'){print 'selected="true"';} ?>>按时间倒序</option>
                                                  <option value="2" <?php if($filter_selection['order_order_']=='2'){print 'selected="true"';} ?>>按时间顺序</option>                        
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12 order-filter-submit filter-item">
                                                <button type="submit" class="text-box">查询</button>
                                            </div>    
                                            </form>
                                      <?php else: ?> 
                                      <h4 style="margin-top:100px;text-align:center;">没有您要找的订单,去<a href="/product/select_list" style="color: orange;">逛逛吧</a></h4>
                                      <?php endif; ?>
                                            <?php foreach($orders as $k=>$v): ?>
                                                        <article class="order-item">
                                                                    <header class="order-img-box col-md-3 col-xs-12 col-sm-4">
                                                                        <div class="order-img-div">
                                                                            <img class="order-img" src="<?php print C('IMAGE_CONFIG')['viewPath'].$v['image_url'][0]; ?>">
                                                                        </div>
                                                                    </header>
                                                                    <div class="col-md-5 col-xs-12 col-sm-4 order-info-box">
                                                                        <h3 class="order-info-title"><a href="#"><?php $count = count($v['varient_name']); if($count>1) { print $v['varient_name'][0].'等'.$count.'个';}else {print $v['varient_name'][0];} ?></a></h3>
                                                                        <div class="order-info-title-plus">订单号:<?php print $v['order_id']; ?></div>
                                                                        <ul class="order-info-body">
                                                                            <li class="listItemCard-module_bulletPoint_tJqoi" style='font-size: 13px;'>收获人电话:<?php print $v['telephone']; ?></li>
                                                                            <li class="listItemCard-module_bulletPoint_tJqoi" style='font-size: 13px;' >优惠码:<?php if(!empty($v['order_coupon_code'])){ print $v['order_coupon_code'];}else{print '无';} ?></li>
                                                                            <li class="listItemCard-module_bulletPoint_tJqoi" style='font-size: 13px;' >配送地址:<?php print $v['area'].$v['address']; ?></li>

                                                                        </ul>
                                                                    </div>
                                                                    <footer class="col-md-4 col-xs-12 col-sm-4 order-operation-box">
                                                                        <div class="order-price">应付款:<?php print $v['order_final_price']; ?></div>
                                                                        <ul class="order-extra-info">
                                                                            <li>创建日期:<?php print $v['order_create_time']; ?></li>
                                                                            <li>订单状态: <?php print get_order_status_label($v['order_status']); ?></li>
                                                                        </ul>
                                                                        <div class="order-operation-options">
                                                                            <a href="/user/user_order_detail/order_id/<?php print $v['order_id']; ?>" target="_blank">详情</a>
                                                                            <?php if($v['order_status'] == '1'): ?>
                                                                            <a href="/user/user_order_pay/order_id/<?php print $v['order_id']; ?>">去付款</a>
                                                                            <?php elseif($v['order_status'] == '2'): ?>
                                                                            <a onclick='javascript:void(0);' style="cursor:default;text-decoration: none;background-color: #5ac689;">已付款</a>
                                                                            <?php endif ?>
                                                                        </div>
                                                                    </footer>
                                                        </article>
                                            <?php endforeach; ?>      
					</section>
					<section id="section-2">
						<div class="mediabox">
							<img src="/Public/Home/images/04.png" alt="img04" />
							<h3>Asparagus Cucumber Cake</h3>
							<p>Chickweed okra pea winter purslane coriander yarrow sweet pepper radish garlic brussels sprout groundnut summer purslane earthnut pea tomato spring onion azuki bean gourd. </p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/05.png" alt="img05" />
							<h3>Magis Kohlrabi Gourd</h3>
							<p>Salsify taro catsear garlic gram celery bitterleaf wattle seed collard greens nori. Grape wattle seed kombu beetroot horseradish carrot squash brussels sprout chard.</p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/06.png" alt="img06" />
							<h3>Ricebean Rutabaga</h3>
							<p>Celery quandong swiss chard chicory earthnut pea potato. Salsify taro catsear garlic gram celery bitterleaf wattle seed collard greens nori. </p>
						</div>
					</section>
					<section id="section-3">
						<div class="mediabox">
							<img src="/Public/Home/images/02.png" alt="img02" />
							<h3>Noodle Curry</h3>
							<p>Lotus root water spinach fennel kombu maize bamboo shoot green bean swiss chard seakale pumpkin onion chickpea gram corn pea.Sushi gumbo beet greens corn soko endive gumbo gourd.</p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/06.png" alt="img06" />
							<h3>Leek Wasabi</h3>
							<p>Sushi gumbo beet greens corn soko endive gumbo gourd. Parsley shallot courgette tatsoi pea sprouts fava bean collard greens dandelion okra wakame tomato.</p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/01.png" alt="img01" />
							<h3>Green Tofu Wrap</h3>
							<p>Pea horseradish azuki bean lettuce avocado asparagus okra. Kohlrabi radish okra azuki bean corn fava bean mustard tigernut wasabi tofu broccoli mixture soup.</p>
						</div>
					</section>
					<section id="section-4">
						<div class="mediabox">
							<img src="/Public/Home/images/03.png" alt="img03" />
							<h3>Tomato Cucumber Curd</h3>
							<p>Chickweed okra pea winter purslane coriander yarrow sweet pepper radish garlic brussels sprout groundnut summer purslane earthnut pea tomato spring onion azuki bean gourd. </p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/01.png" alt="img01" />
							<h3>Mushroom Green</h3>
							<p>Salsify taro catsear garlic gram celery bitterleaf wattle seed collard greens nori. Grape wattle seed kombu beetroot horseradish carrot squash brussels sprout chard.</p>
						</div>
						<div class="mediabox">
							<img src="/Public/Home/images/04.png" alt="img04" />
							<h3>Swiss Celery Chard</h3>
							<p>Celery quandong swiss chard chicory earthnut pea potato. Salsify taro catsear garlic gram celery bitterleaf wattle seed collard greens nori. </p>
						</div>
					</section>
					<section id="section-5">
                                        
                                           
                                                                                                                                                    
					</section>
				</div><!-- /content -->
			</div><!-- /tabs -->		 	
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
      <textarea style="z-index: 0;width: 99%;margin-bottom: 12px;font-size: 16px;line-height: 1.5;resize: none;color: #333;border: 1px solid #ddd; padding:1px;height: 150px;" class="control" name="feedback" placeholder="请输入您的留言，如果您想要更好得咨询体验，可以点击页面最下方的QQ按钮或者拨打18657477265或者15168188557"></textarea>
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
    <script src="/Public/Home/js/validator.js"></script>
    <script src="/Public/Home/js/nocache.js"></script>
    <script src="/Public/Home/js/cbpFWTabs.js"></script>
    <script>
	new CBPFWTabs( document.getElementById( 'tabs' ) );

        $(document).ready(function() {
           $('#order_status').niceSelect();
           $('#order_order_by').niceSelect();
        });
    
    </script>
</body>
</html>