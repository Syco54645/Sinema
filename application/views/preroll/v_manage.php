<div>
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Title</td>
                                <td>Summary</td>
                                <td>Thumb</td>
                                <td>Series</td>
                                <td>Type</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="preroll in viewVars.prerolls">
                                <td>{{ ::preroll.id }}</td>
                                <td><a href="/admin/prerolls/edit/{{ ::preroll.id }}">{{ ::preroll.title }}</a></td>
                                <td>{{ ::preroll.summary }}</td>
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