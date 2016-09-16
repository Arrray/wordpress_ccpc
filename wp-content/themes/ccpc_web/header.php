<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <!--	<meta name="viewport" content="initial-scale=1, width=device-width">-->
    <!--强制双核浏览器切换到webkit内核-->
    <meta name="renderer" content="webkit|ie-stand">
    <meta name="force-rendering" content="webkit|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="author" content="Ontides"/>
    <meta name="Keywords" content="中国大学生程序设计竞赛"/>
    <meta name="Description" content="这里中国大学生程序设计竞赛的官方网站"/>
    <!--<link rel="shortcut icon" href="favicon.ico">-->
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <link rel="stylesheet" href="<?= site_url() ?>/wp-content/themes/public/css/reset.css">
    <link rel="stylesheet" href="<?= site_url() ?>/wp-content/themes/public/css/main.css">
<!--    <script type="text/javascript" src="--><?//= site_url() ?><!--/wp-content/themes/public/js/jquery.js"></script>-->
    <script src="//cdn.bootcss.com/jquery/2.2.4/jquery.js"></script>
<!--    <script src="--><?//= site_url() ?><!--/wp-content/themes/public/js/maybe/myfocus-2.0.4.min.js"></script>-->
    <script type="text/javascript" src="<?= site_url() ?>/wp-content/themes/public/js/slide.js"></script>
    <script type="text/javascript" src="<?= site_url() ?>/wp-content/themes/public/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?= site_url() ?>/wp-content/themes/public/js/jquery.skitter.js"></script>
    <script type="text/javascript" src="<?= site_url() ?>/wp-content/themes/public/js/main.js"></script>
<!--    <script src="--><?//= site_url() ?><!--/wp-content/themes/public/js/maybe/main.js"></script>-->
    <script>
        $( document ).ready(function() {
            $.getScript('<?= site_url() ?>/wp-content/themes/public/js/jquery.easing.1.3.js');
        });
    </script>
    <!--main.js选择在最后引用-->
    <?php wp_head(); ?>
    <!---->
</head>
<body>
<div class="container">
<!--    <div class="top" style="height: 30px;width: 100%;background-color: #f1f1f1">-->
<!--        <div style="width: 1200px;height:30px;margin: 0 auto;">-->
<!--            <a href="http://dev.ccpc.io/#/?_k=4wy3sn" style="display: block;width: 80px;position: relative;text-align: center;float:right;line-height: 30px;font-size: 15px;color: #676568;text-decoration: none">登录</a>-->
<!--        </div>-->
<!--    </div>-->
    <div class="header">
        <div class="logoBackground">
            <div class="logo mainWidth">
                <a href="#">
                    <img src="<?= site_url() ?>/wp-content/themes/public/img/LOGO-CCPC.png" alt="Logo"
                         style="height: 60px;width: auto;position: relative;
top: 25px;left: 30px">
                </a>
            </div>
        </div>
        <div class="navContainer">
            <div class="nav mainWidth">
                <?php wp_nav_menu() ?>
            </div>
        </div>
    </div>
