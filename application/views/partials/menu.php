<?php
//taken from https://stackoverflow.com/questions/44467377/bootstrap-4-multilevel-dropdown-inside-navigation
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Sinema</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Grindhouse
                </a>

                 <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/grindhouse/create" ng-if="viewVars.me.login">Create New</a>
                    <a class="dropdown-item" href="/admin/grindhouses" ng-if="viewVars.me.login">Manage</a>
                    <div class="dropdown-divider" ng-if="viewVars.me.login"></div>
                    <a class="dropdown-item" href="/grindhouse/upcoming">Upcoming</a>
                    <a class="dropdown-item" href="/grindhouse/past">Past</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>

                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                    <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">Plex</a>
                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="/admin/import-plex" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-plex'] != '0'">Import From Plex</a></li>
                            <li><a class="dropdown-item" href="/admin/export-plex" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-plex'] != '0'">Export From Plex</a></li>

                            <li class="dropdown-submenu hidden">
                                <a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                    <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu hidden">
                                <a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Subsubmenu action</a></li>
                                    <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">Manage</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/films" ng-if="viewVars.me.login">Manage Films</a></li>
                            <li><a class="dropdown-item" href="/admin/prerolls" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-prerolls'] != '0'">Manage Prerolls</a></li>
                            <li><a class="dropdown-item" href="/admin/trailers" ng-if="viewVars.me.login && viewVars.sinemaSettings['enable-trailers'] != '0'">Manage Trailer</a></li>
                        </ul>
                    </li>
                    <li><div class="dropdown-divider" ng-if="viewVars.me.login"></div></li>
                    <li><a class="dropdown-item" href="/admin/settings" ng-if="viewVars.me.login">Settings</a></li>
                    <li><a class="dropdown-item" href="/admin/logout" ng-if="viewVars.me.login">Logout</a></li>
                    <li><a class="dropdown-item" href="/admin/login" ng-if="!viewVars.me.login">Login</a></li>
                </ul>
            </li>
            <li class="nav-item hidden">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0 hidden" ng-if="false">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>
