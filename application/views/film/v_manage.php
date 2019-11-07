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
                            <th>ID</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Summary</th>
                            <th>Thumb</th>
                            <th>Genres</th>
                            <th>Tags</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="film in viewVars.films">
                            <td>{{ ::film.id }}</td>
                            <td><a href="/admin/films/edit/{{ ::film.id }}">{{ ::film.title }}</a></td>
                            <td>{{ ::film.year }}</td>
                            <td>{{ ::film.summary | cut}}</td>
                            <td><img class="film-image" ng-src="{{ ::film.thumbUrl }}" /></td>
                            <td>
                                <div ng-repeat="genre in film.genres">
                                    <a href="/admin/films?genreId={{ ::genre.genre_id }}">{{ ::genre.genre }}</a>
                                </div>
                            </td>
                            <td>
                                <div ng-repeat="tag in film.tags">
                                    <a class="tag" href="/admin/films?tagId={{ ::tag.tag_id }}">{{ ::tag.tag }}</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
