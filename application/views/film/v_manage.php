<div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-hover">
                <thead>
                    <tr class="row">
                        <th class="col-md-1">ID</th>
                        <th class="col-md-3">Title</th>
                        <th class="col-md-1">Year</th>
                        <th class="col-md-4">Summary</th>
                        <th class="col-md-1">Thumb</th>
                        <th class="col-md-1">Genres</th>
                        <th class="col-md-1">Subgenres</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row" ng-repeat="film in viewVars.films">
                        <td class="col-md-1">{{ ::film.id }}</td>
                        <td class="col-md-3"><a href="/admin/films/edit/{{ ::film.id }}">{{ ::film.title }}</a></td>
                        <td class="col-md-1">{{ ::film.year }}</td>
                        <td class="col-md-4">{{ ::film.summary }}</td>
                        <td class="col-md-1"><img class="film-image" ng-src="{{ ::film.thumbUrl }}" /></td>
                        <td class="col-md-1">
                            <div ng-repeat="genre in film.genres">
                                <a href="/admin/films?genreId={{ ::genre.genre_id }}">{{ ::genre.genre }}</a>
                            </div>
                        </td>
                        <td class="col-md-1">
                            <div ng-repeat="subgenre in film.subgenres">
                                <a href="/admin/films?subgenreId={{ ::subgenre.subgenre_id }}">{{ ::subgenre.subgenre }}</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>