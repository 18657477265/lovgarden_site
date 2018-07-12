<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1 , user-scalable=no">
     <title>购物车是空的|花点馨思(丽de花苑)</title>
     <link rel="stylesheet" href="/Public/Home/css/bootstrap.min.css"/>
     <link rel="stylesheet" href="/Public/Home/css/nice-select.css">
     <link rel="stylesheet" href="/Public/Home/css/datepicker.css">
     <link rel="stylesheet" href="/Public/Home/css/flow.css"/>
     <link rel="stylesheet" href="/Public/Home/css/style.css"/>
     <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="/Public/Home/js/bootstrap.min.js"></script>
     <script src="/Public/Home/js/jquery.nice-select.min.js"></script>
     <script src="/Public/Home/js/jquery.cookie.js"></script>
     <script src="/Public/Home/js/bootstrap-datepicker.js">"></script>
     <script src="/Public/Home/js/locales/bootstrap-datepicker.zh-CN.js"></script>
</head>
<body class="page">
  <!--flow 查看提交订单的头-->
  <div class="container-fluid flow-top-banner">
      <div class="col-md-1 col-sm-2 col-xs-3 checkout-help-link"><a class="utility-nav__item-node" href="/product/select_list">精品店</a></div>
    <div class="page-header page-header--checkout col-md-10 col-sm-8 col-xs-6">
      <div class="page-header__logo">
          <a rel="home" class="page-header__logo-link" href="/"><img src="/Public/Home/images/temp_lovgarden_logo.png" /></a>
      </div>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-3 checkout-help-link">
       <a class="utility-nav__item-node" href="/help">帮助中心</a>
    </div>
  </div>    
  <div>
      <h2 class="empty-suggest">您的购物车空空如也，先去<br/><a href="/product/select_list">逛一下</a>吧</h2>
      <p class="empty-icon"><img src='/Public/Home/images/emptyCart.png' /></p> 
  </div>
</body>
</html>