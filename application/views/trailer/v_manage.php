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
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="trailer in viewVars.trailers">
                                <td>{{ ::trailer.id }}</td>
                                <td><a href="/admin/trailers/edit/{{ ::trailer.id }}">{{ ::trailer.title }}</a></td>
                                <td>{{ ::trailer.summary }}</td>
                                <td><img class="trailer-image" ng-src="{{ ::trailer.thumbUrl }}" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
