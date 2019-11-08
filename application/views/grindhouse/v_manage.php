<div class="manage-grindhouse row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header ">
                <h4 class="card-title">{{ ::pageTitle() }}</h4>
                <p class="card-category" ng-if="::pageSubtitle()">{{ ::pageSubtitle() }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Tagline</th>
                                <th>Updated</th>
                                <th>Created</th>
                                <th>Showing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="grindhouse in viewVars.grindhouses">
                                <td>{{ ::grindhouse.id }}</td>
                                <td><a href="/admin/grindhouse/edit/{{ ::grindhouse.id }}">{{ ::grindhouse.title }}</a></td>
                                <td>{{ ::grindhouse.tagline }}</td>
                                <td>{{ ::grindhouse.last_updated }}</td>
                                <td>{{ ::grindhouse.date_created }}</td>
                                <td>{{ ::grindhouse.show_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
