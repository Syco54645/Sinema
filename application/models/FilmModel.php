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

        if (isset($options['tagId'])) {
            $this->db->join('map_tag_film msf', 'msf.film_id = f.id');
            $this->db->where('tag_id', $options['tagId']);
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

        return $this->db->get()->row_array();
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

    public function getTagBySlug($slug) {

        $this->db->select('*');
        $this->db->from('tags');
        $this->db->where('tag_slug', $slug);

        $result = $this->db->get();

        return $result->row();
    }

    public function getTags() {

        $this->db->select('*');
        $this->db->from('tags');

        $result = $this->db->get();

        return $result->result_array();
    }

    public function storeTag($tags=[]) {

        foreach ($tags as $tag) {
            $qd = [
                "tag" => $tag,
                "tag_slug" => Utility::slugify($tag),
            ];
            $this->insert_ignore('tags', $qd);
        }
    }

    public function mapGenreToFilm($filmId, $genreId) {
        $qd = [
            "film_id" => $filmId,
            "genre_id" => $genreId,
        ];
        return $this->insert_ignore('map_genre_film', $qd);
    }

    public function mapTagToFilm($filmId, $tagId) {
        $qd = [
            "film_id" => $filmId,
            "tag_id" => $tagId,
        ];
        return $this->insert_ignore('map_tag_film', $qd);
    }

    public function getFilmsForGenres($genres, $mode) {

        $this->db->select('film_id');
        $this->db->from('map_genre_film');
        if ($mode == "matchAny") {
            $this->db->where_in('genre_id', $genres);
        } else {
            foreach ($genres as $genre) {
                $this->db->where('genre_id', $genre);
            }
        }

        $result = $this->db->get();

        return $result->result_array();
    }

    public function getFilmsForTags($tags, $mode) {

        $this->db->select('film_id');
        $this->db->from('map_tag_film');
        if ($mode == "matchAny") {
            $this->db->where_in('tag_id', $tags);
        } else {
            foreach ($tags as $tag) {
                $this->db->where('tag_id', $tag);
            }
        }

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


    public function getTagsForFilm($filmId) {

        $this->db->select('tag_id, tag');
        $this->db->from('map_tag_film');
        $this->db->where('film_id', $filmId);
        $this->db->join('tags sg', 'tag_id = sg.id');

        $result = $this->db->get();

        return $result->result_array();
    }


    public function featureSearch($search) {

        $genreFilms = [];
        if ($search['selected']['genre'] == true) {
            $tempFilms = $this->getFilmsForGenres($search['criteria']['genreId'], $search['options']['genreMode']);
            foreach ($tempFilms as $film) {
                $genreFilms[] = $film['film_id'];
            }
        }
        $genreFilms = array_unique($genreFilms);

        $tagFilms = [];
        if ($search['selected']['tag'] == true) {
            $tempFilms = $this->getFilmsForTags($search['criteria']['tagId'], $search['options']['tagMode']);
            foreach ($tempFilms as $film) {
                $tagFilms[] = $film['film_id'];
            }
        }
        $tagFilms = array_unique($tagFilms);

        $finalFilmIds = [];

        if (count($genreFilms) || count($tagFilms)) {
            if ($search['options']['genreTagIntersect']) {
                $finalFilmIds = array_intersect($genreFilms, $tagFilms);
            } else {
                $finalFilmIds = array_merge($genreFilms, $tagFilms);
                $finalFilmIds = array_unique($finalFilmIds);
            }
        } elseif (count($genreFilms)) {
            $finalFilmIds = $genreFilms;
        } elseif (count($tagFilms)) {
            $finalFilmIds = $tagFilms;
        }
        //Utility::debug(count($finalFilmIds), true);

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
