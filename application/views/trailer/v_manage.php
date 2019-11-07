<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">{{ ::pageTitle() }}</h4>
                <p class="card-category" ng-if="::pageSubtitle()">{{ ::pageSubtitle() }}</p>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-responsive">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Title</td>
                            <td>Summary</td>
                            <td>Thumb</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="trailer in viewVars.trailers">
                            <td>{{ ::trailer.id }}</td>
                            <td><a href="/admin/trailers/edit/{{ ::trailer.id }}">{{ ::trailer.title }}</a></td>
                            <td>{{ ::trailer.summary | cut }}</td>
                            <td><img class="trailer-image" ng-src="{{ ::trailer.thumbUrl }}" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
