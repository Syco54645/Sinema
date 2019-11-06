<div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-hover">
                <thead>
                    <tr class="row">
                        <th class="col-md-1">ID</th>
                        <th class="col-md-3">Title</th>
                        <th class="col-md-4">Tagline</th>
                        <th class="col-md-1">Updated</th>
                        <th class="col-md-1">Created</th>
                        <th class="col-md-1">Showing</th>
                        <th class="col-md-1">Create Playlist</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row" ng-repeat="grindhouse in viewVars.grindhouses">
                        <td class="col-md-1">{{ ::grindhouse.id }}</td>
                        <td class="col-md-3"><a href="/admin/grindhouse/edit/{{ ::grindhouse.id }}">{{ ::grindhouse.title }}</a></td>
                        <td class="col-md-4">{{ ::grindhouse.tagline }}</td>
                        <td class="col-md-1">{{ ::grindhouse.last_updated }}</td>
                        <td class="col-md-1">{{ ::grindhouse.date_created }}</td>
                        <td class="col-md-1">{{ ::grindhouse.show_date }}</td>
                        <td class="col-md-1"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
