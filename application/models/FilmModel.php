<?php
class FilmModel extends MY_Model {

    public function getFilms($options = []) {

        $this->db->select('f.*');
        $this->db->from('films f');

        if (isset($options['typeId'])) {
            $this->db->where('preroll_type_id', $options['typeId']);
        }
        if (isset($options['limit']) && $options['limit'] != null) {
            if ($options['offset'] > 0) {
                $this->db->limit($options['limit'], $options['offset']);
            } else {
                $this->db->limit($options['limit']);
            }
        }

        if (isset($options['genreId'])) {
            $this->db->join('map_genre_film mgf', 'mgf.film_id = f.id');
            $this->db->where('genre_id', $options['genreId']);
        }

        if (isset($options['subgenreId'])) {
            $this->db->join('map_subgenre_film msf', 'msf.film_id = f.id');
            $this->db->where('subgenre_id', $options['subgenreId']);
        }

        if (isset($options['orderBy'])) {
            $this->db->order_by($options['orderBy']['field'], $options['orderBy']['direction']);
        }

        $result = $this->db->get();

        return $result->result_array();
    }

    public function storeFilm($qd) {

        $this->db->insert('films', $qd);
        return $this->db->insert_id();
    }

    public function getFilmById($id) {

        $this->db->select('*');
        $this->db->from('films');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function checkFilmExists($id) {

        $this->db->select('*');
        $this->db->from('films');
        $this->db->where('id', $id);

        return $this->db->count_all_results();
    }

    public function updateFilm($id, $qd) {

        $this->db->where('id', $id);
        $this->db->update('films', $qd);
    }

    public function storeGenre($qd) {

        // insert igore seems to be broken when expecting to get the id back. meh...
        //$request = $this->insert_ignore('genres', $qd);
        $this->db->insert('genres', $qd);
        return $this->db->insert_id();
    }

    public function getGenreBySlug($slug) {

        $this->db->select('*');
        $this->db->from('genres');
        $this->db->where('genre_slug', $slug);

        $result = $this->db->get();

        return $result->row();
    }

    public function getGenres() {

        $this->db->select('*');
        $this->db->from('genres');

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getSubgenreBySlug($slug) {

        $this->db->select('*');
        $this->db->from('subgenres');
        $this->db->where('subgenre_slug', $slug);

        $result = $this->db->get();

        return $result->row();
    }

    public function getSubgenres() {

        $this->db->select('*');
        $this->db->from('subgenres');

        $result = $this->db->get();

        return $result->result_array();
    }

    public function storeSubgenre($subgenres=[]) {

        foreach ($subgenres as $subgenre) {
            $qd = [
                "subgenre" => $subgenre,
                "subgenre_slug" => Utility::slugify($subgenre),
            ];
            $this->insert_ignore('subgenres', $qd);
        }
    }

    public function mapGenreToFilm($filmId, $genreId) {
        $qd = [
            "film_id" => $filmId,
            "genre_id" => $genreId,
        ];
        return $this->insert_ignore('map_genre_film', $qd);
    }

    public function mapsubGenreToFilm($filmId, $subgenreId) {
        $qd = [
            "film_id" => $filmId,
            "subgenre_id" => $subgenreId,
        ];
        return $this->insert_ignore('map_subgenre_film', $qd);
    }

    public function getFilmsForGenres($genres) {

        $this->db->select('film_id');
        $this->db->from('map_genre_film');
        $this->db->where_in('genre_id', $genres);

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getFilmsForSubgenres($subgenres) {

        $this->db->select('film_id');
        $this->db->from('map_subgenre_film');
        $this->db->where_in('subgenre_id', $subgenres);

        $result = $this->db->get();

        return $result->result_array();
    }


    public function getGenresForFilm($filmId) {

        $this->db->select('genre_id, genre');
        $this->db->from('map_genre_film');
        $this->db->where('film_id', $filmId);
        $this->db->join('genres g', 'genre_id=g.id');

        $result = $this->db->get();

        return $result->result_array();
    }


    public function getSubgenresForFilm($filmId) {

        $this->db->select('subgenre_id, subgenre');
        $this->db->from('map_subgenre_film');
        $this->db->where('film_id', $filmId);
        $this->db->join('subgenres sg', 'subgenre_id = sg.id');

        $result = $this->db->get();

        return $result->result_array();
    }


    public function featureSearch($search) {

        $genreFilms = [];
        if ($search['selected']['genre'] == true) {
            $tempFilms = $this->getFilmsForGenres($search['criteria']['genreId']);
            foreach ($tempFilms as $film) {
                $genreFilms[] = $film['film_id'];
            }
        }
        $genreFilms = array_unique($genreFilms) ;

        $subgenreFilms = [];
        if ($search['selected']['subgenre'] == true) {
            $tempFilms = $this->getFilmsForSubgenres($search['criteria']['subgenreId']);
            foreach ($tempFilms as $film) {
                $subgenreFilms[] = $film['film_id'];
            }
        }
        $subgenreFilms = array_unique($subgenreFilms);

        // todo only do an intersect IF the arrays are populated
        $finalFilmIds = array_intersect($genreFilms, $subgenreFilms);

        return array_values($finalFilmIds);
    }

    public function insertLibraryAlias($qd) {

        $this->db->insert('libraries', $qd);
        return $this->db->insert_id();
    }

    public function getLibraryAliases() {

        $this->db->Select('*');
        $this->db->from('libraries');

        $result = $this->db->get();

        return $result->result_array();
    }
}
