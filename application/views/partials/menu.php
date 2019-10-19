<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Sinema</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Grindhouse
                </a>

                 <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/grindhouse/create" ng-if="viewVars.me.login">Create New</a>
                    <div class="dropdown-divider" ng-if="viewVars.me.login"></div>
                    <a class="dropdown-item" href="/grindhouse/upcoming">Upcoming</a>
                    <a class="dropdown-item" href="/grindhouse/past">Past</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>
                
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/admin/import-plex" ng-if="viewVars.me.login">Import From Plex</a>
                    <a class="dropdown-item" href="/admin/films" ng-if="viewVars.me.login">Manage Films</a>
                    <a class="dropdown-item" href="/admin/prerolls" ng-if="viewVars.me.login">Manage Prerolls</a>
                    <a class="dropdown-item" href="/admin/trailers" ng-if="viewVars.me.login">Manage Trailer</a>
                    <div class="dropdown-divider" ng-if="viewVars.me.login"></div>
                    <a class="dropdown-item" href="/admin/settings" ng-if="viewVars.me.login">Settings</a>
                    <a class="dropdown-item" href="/admin/logout" ng-if="viewVars.me.login">Logout</a>
                    <a class="dropdown-item" href="/admin/login" ng-if="!viewVars.me.login">Login</a>
                </div>
            
            </li>
            <li class="nav-item" ng-if="false">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" ng-if="false">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>