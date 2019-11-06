<!doctype html>
<html class="no-js" lang="en" ng-app="Sinema" ng-controller="RootController">

<head>
    <meta charset="utf-8">
    <title>Sinema<?php echo isset($title) ? ' - ' . $title: 'GRINDHAUS!!!!' ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="/css/vendor/normalize.css">
    <link rel="stylesheet" href="/css/vendor/boiler-reset.css">
    <link rel="stylesheet" href="/css/vendor/selectize.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/vendor/angular-flash.css">
    <link rel="stylesheet" href="/css/vendor/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/vendor/font-awesome.min.css">

    <script type="text/javascript" src="/js/vendor/moment.js"></script>
    <script type="text/javascript" src="/js/vendor/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/js/vendor/selectize.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-1.5.8.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-filter-0.5.17.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-flash.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-selectize.js"></script>

    <script type="text/javascript">viewVars = <?php echo json_encode($this->_ci_cached_vars); ?></script>

    <script type="text/javascript" src="/js/app.js"></script>
    <meta name="theme-color" content="#fafafa">
</head>


<body>
    <!--[if IE]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <?php if (!isset($userdata)) $userdata = [] ?>
    <?php $this->view('/partials/menu', $userdata); ?>
    <div class="content-container">
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <h1><?php echo isset($title) ? $title: 'GRINDHAUS!!!!' ?></h1>
