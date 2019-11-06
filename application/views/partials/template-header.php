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
    <link rel="stylesheet" href="/css/vendor/angular-flash.css">
    <link rel="stylesheet" href="/css/vendor/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="/css/vendor/light-bootstrap-dashboard.css?v=2.0.0">
    <link rel="stylesheet" href="/css/main.css">

    <script type="text/javascript" src="/js/vendor/moment.js"></script>
    <script type="text/javascript" src="/js/vendor/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/js/vendor/selectize.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-1.5.8.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-filter-0.5.17.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-flash.min.js"></script>
    <script type="text/javascript" src="/js/vendor/angular-selectize.js"></script>
    <script src="/js/vendor/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>

    <script type="text/javascript">viewVars = <?php echo json_encode($this->_ci_cached_vars); ?></script>

    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/controllers/menu.js"></script>
    <meta name="theme-color" content="#fafafa">
</head>


<body>
    <!--[if IE]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <?php if (!isset($userdata)) $userdata = [] ?>

    <div class="wrapper">
        <div class="sidebar" data-image="../assets/img/sidebar-5.jpg" data-color="black">
            <!--

            Tip 2: you can also add an image using data-image tag
            -->
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="/admin" class="simple-text">
                        Sinema
                    </a>
                </div>
                <ul class="nav" ng-controller="MenuController">

                    <li class="nav-item sb-dropdown" ng-class="{ open: isOpen('grindhouse') }">
                        <a class="nav-link" ng-click="toggleDropdown($event)" data-id="grindhouse">
                            <i class="nc-icon"></i>
                            <p>Grindhouse</p>
                        </a>
                        <ul class="sb-dropdown-container">
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('grindhouse-create') }">
                                <a class="sb-dropdown-nav-link" href="/grindhouse/create" ng-if="viewVars.me.login">Create New</a>
                            </li>
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('grindhouse-manage') }">
                                <a class="sb-dropdown-nav-link" href="/admin/grindhouses" ng-if="viewVars.me.login">Manage</a>
                            </li>
                            <div class="dropdown-divider" ng-if="viewVars.me.login"></div>
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('grindhouse-upcoming') }">
                                <a class="sb-dropdown-nav-link" href="/grindhouse/upcoming">Upcoming</a>
                            </li>
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('grindhouse-past') }">
                                <a class="sb-dropdown-nav-link" href="/grindhouse/past">Past</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item sb-dropdown" ng-class="{ open: isOpen('library') }">
                        <a class="nav-link" ng-click="toggleDropdown($event)" data-id="library">
                            <i class="nc-icon"></i>
                            <p>Libraries</p>
                        </a>
                        <ul class="sb-dropdown-container">

                            <li class="sb-dropdown-item" ng-class="{ active: isActive('film-manage') }">
                                <a class="sb-dropdown-nav-link" href="/admin/films" ng-if="viewVars.me.login">Manage Films</a>
                            </li>
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('preroll-manage') }">
                                <a class="sb-dropdown-nav-link" href="/admin/prerolls" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-prerolls'] != '0'">Manage Prerolls</a>
                            </li>
                            <li class="sb-dropdown-item" ng-class="{ active: isActive('trailer-manage') }">
                                <a class="sb-dropdown-nav-link" href="/admin/trailers" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-trailers'] != '0'">Manage Trailer</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" ng-class="{ active: isActive('admin-settings') }">
                        <a class="nav-link" href="/admin/settings">
                            <i class="nc-icon"></i>
                            <p>Settings</p>
                        </a>
                    </li>

                    <li class="nav-item active active-pro">
                        <a class="nav-link active" href="/help">
                            <i class="nc-icon"></i>
                            <p>Version</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <a class="navbar-brand hidden" href="#pablo"> Dashboard </a>
                    <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="nav navbar-nav mr-auto hidden">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="dropdown">
                                    <i class="nc-icon nc-palette"></i>
                                    <span class="d-lg-none">Dashboard</span>
                                </a>
                            </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <i class="nc-icon nc-planet"></i>
                                    <span class="notification">5</span>
                                    <span class="d-lg-none">Notification</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Notification 1</a>
                                    <a class="dropdown-item" href="#">Notification 2</a>
                                    <a class="dropdown-item" href="#">Notification 3</a>
                                    <a class="dropdown-item" href="#">Notification 4</a>
                                    <a class="dropdown-item" href="#">Another notification</a>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nc-icon nc-zoom-split"></i>
                                    <span class="d-lg-block">&nbsp;Search</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="no-icon">{{ ::viewVars.me.username }}</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown hidden">
                                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="no-icon">Dropdown</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" ng-if="viewVars.me.login" href="/admin/logout">
                                    <span class="no-icon">Log out</span>
                                </a>
                                <a class="nav-link" ng-if="!viewVars.me.login" href="/admin/login">
                                    <span class="no-icon">Log in</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">







    <?php /*
    <?php $this->view('/partials/menu', $userdata); ?>
    <div class="content-container">
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <h1><?php echo isset($title) ? $title: 'GRINDHAUS!!!!' ?></h1>
*/
?>
