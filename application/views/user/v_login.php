<div class="row">
    <div class="col-md-12">
        <?php echo $this->session->flashdata('login_info'); ?>
        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">{{ ::pageTitle() }}</h4>
                <p class="card-category" ng-if="::pageSubtitle()">{{ ::pageSubtitle() }}</p>
            </div>
            <div class="card-body">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <form class="form-signin" method="POST" action="/admin/login">
                        <h2 class="form-signin-heading">Please Login</h2>
                        <div class="form-group">
                            <input class="form-control" type="text" name="username" id="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-lg btn-primary float-right" type="submit">Login</button>                       
                        </div>
                        <!--a class="btn btn-lg btn-primary btn-block" href="register">Register</a-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
