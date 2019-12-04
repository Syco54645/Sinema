<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">{{ ::pageTitle() }}</h4>
                <p class="card-category" ng-if="::pageSubtitle()">{{ ::pageSubtitle() }}</p>
                <div class="card-actions">
                    <a class="btn btn-primary" href="/admin/prerolls/create">Add</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Summary</th>
                                <th>Thumb</th>
                                <th>Series</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="preroll in viewVars.prerolls">
                                <td>{{ ::preroll.id }}</td>
                                <td><a href="/admin/prerolls/edit/{{ ::preroll.id }}">{{ ::preroll.title }}</a></td>
                                <td>{{ ::preroll.summary | cut }}</td>
                                <td><img class="preroll-image" ng-src="{{ ::preroll.thumbUrl }}" /></td>
                                <td><a href="/admin/prerolls?seriesId={{ ::preroll.preroll_series_id }}">{{ ::preroll.preroll_series_name }}</a></td>
                                <td><a href="/admin/prerolls?typeId={{ ::preroll.preroll_type_id }}">{{ ::preroll.preroll_type_name }}</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
