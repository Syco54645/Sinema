<!doctype html>
<html class="no-js" lang="en" ng-app="Sinema" ng-controller="RootController">

<head>
    <meta charset="utf-8">
    <title>Sinema<?php echo isset($title) ? ' - ' . $title: 'GRINDHAUS!!!!' ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="/css/vendor/normalize.css">
    <link rel="stylesheet" href="/css/vendor/boiler-reset.css">
    <link rel="stylesheet" href="/css/vendor/selectize.css">
    <link rel="stylesheet" href="/css/vendor/angular-flash.css">
    <link rel="stylesheet" href="/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="/css/main.css">

    <script type="text/javascript" src="/js/vendor/moment.js"></script>
    <script type="text/javascript" src="/js/vendor/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/js/vendor/selectize.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-1.5.8.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-filter-0.5.17.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-flash.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-selectize.js"></script>

    <script type="text/javascript">viewVars = <?php echo json_encode($this->_ci_cached_vars); ?></script>

    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/controllers/menu.js"></script>
</head>


<body ng-cloak>
    <!--[if IE]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <?php if (!isset($userdata)) $userdata = [] ?>


    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/admin/settings">Sinema</a>
        <input class="form-control form-control-dark w-100 hidden" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item">
                <a class="nav-link" ng-if="viewVars.me.login" href="/admin/logout">
                    Log out
                </a>
                <a class="nav-link" ng-if="!viewVars.me.login" href="/admin/login">
                    Log in
                </a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar" ng-controller="MenuController">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">

                        <li class="nav-item sb-dropdown" ng-class="{ open: isOpen('grindhouse') }">
                            <a class="nav-link" ng-click="toggleDropdown($event)" data-id="grindhouse" href="#">
                                <i class="fa fa-film" aria-hidden="true"></i> Grindhouse
                            </a>
                            <ul class="sb-dropdown-container">
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('grindhouse-create') }">
                                    <a class="sb-dropdown-nav-link" href="/grindhouse/create" ng-if="viewVars.me.login">Create New</a>
                                </li>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('grindhouse-manage') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/grindhouses" ng-if="viewVars.me.login">Manage</a>
                                </li>
                                <div class="dropdown-divider" ng-if="viewVars.me.login"></div>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('grindhouse-upcoming') }">
                                    <a class="sb-dropdown-nav-link" href="/grindhouse/upcoming">Upcoming</a>
                                </li>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('grindhouse-past') }">
                                    <a class="sb-dropdown-nav-link" href="/grindhouse/past">Past</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item sb-dropdown" ng-class="{ open: isOpen('library') }">
                            <a class="nav-link" ng-click="toggleDropdown($event)" data-id="library" href="#">
                                <i class="fa fa-book" aria-hidden="true"></i> Libraries
                            </a>
                            <ul class="sb-dropdown-container">
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('film-manage') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/films" ng-if="::viewVars.me.login">Manage Films</a>
                                </li>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('preroll-manage') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/prerolls" ng-if="::viewVars.me.login && viewVars.sinemaSettings['enable-prerolls'] != '0'">Manage Prerolls</a>
                                </li>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('trailer-manage') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/trailers" ng-if="::viewVars.me.login && viewVars.sinemaSettings['enable-trailers'] != '0'">Manage Trailer</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item sb-dropdown" ng-class="{ open: isOpen('plex'), hidden: viewVars.sinemaSettings['enable-plex'] == '0' }">
                            <a class="nav-link" ng-click="toggleDropdown($event)" data-id="plex" href="#">
                                <i class="fa fa-video-camera" aria-hidden="true"></i> Plex
                            </a>
                            <ul class="sb-dropdown-container">
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('importplex-import_plex') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/import-plex" ng-if="::viewVars.me.login">Import</a>
                                </li>
                                <li class="sb-dropdown-item" ng-class="::{ active: isActive('importplex-export_plex') }">
                                    <a class="sb-dropdown-nav-link" href="/admin/export-plex" ng-if="::viewVars.me.login">Export</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item" ng-class="::{ active: isActive('admin-settings') }">
                            <a class="nav-link" href="/admin/settings">
                                <i class="fa fa-cog" aria-hidden="true"></i> Settings
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

    <?php /*
    <?php $this->view('/partials/menu', $userdata); ?>
    <div class="content-container">
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <h1><?php echo isset($title) ? $title: 'GRINDHAUS!!!!' ?></h1>
*/
?>
