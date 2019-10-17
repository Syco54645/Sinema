<?php 
class FilmModel extends MY_Model {

    public function getFilms($limit=null, $offset=0) {

        $this->db->select('*');
        $this->db->from('films');
        if ($limit != null) {
            if ($offset > 0) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }

        $result = $this->db->get();
        //Utility::debug($this->db->last_query(), true);
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

    public function checkPrerollExists($id) {

        $this->db->select('*');
        $this->db->from('prerolls');
        $this->db->where('id', $id);

        return $this->db->count_all_results();
    }

    public function storePreroll($qd) {

        $this->db->insert('prerolls', $qd);
        return $this->db->insert_id();
    }

    public function getPrerolls() {

        $this->db->select('p.*, pt.preroll_type_name, pt.preroll_type_description');
        $this->db->from('prerolls p');
        $this->db->join('preroll_type pt', 'p.preroll_type_id=pt.id', 'left outer');

        $result = $this->db->get();

        return $result->result_array();
    }
    
    public function getPrerollById($id) {

        $this->db->select('p.*, pt.preroll_type_name, pt.preroll_type_description');
        $this->db->from('prerolls p');
        $this->db->where('p.id', $id);
        $this->db->join('preroll_type pt', 'preroll_type_id=pt.id', 'left outer');

        $result = $this->db->get();

        return $result->row_array();
    }

    public function updatePreroll($id, $qd) {

        $this->db->where('id', $id);
        $this->db->update('prerolls', $qd);
    }

    public function getPrerollTypes() {

        $this->db->select('*');
        $this->db->from('preroll_type');

        $result = $this->db->get();

        return $result->result_array();
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
}