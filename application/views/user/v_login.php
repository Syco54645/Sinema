
<?php echo $this->session->flashdata('login_info'); ?>
<div class="row">
    <div class="col-md-12">
        <form class="form-signin" method="POST" action="/admin/login">
            <h2 class="form-signin-heading">Please Login</h2>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input class="form-control" type="text" name="username" id="username" placeholder="Username" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input class="form-control" type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                </div>
            </div>
            <!--a class="btn btn-lg btn-primary btn-block" href="register">Register</a-->
        </form>
    </div>
</div>

