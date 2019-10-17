<?php 
class AuthModel extends MY_Model {

    public function isAuthed() {
        return isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in'] != null && $this->session->userdata['logged_in'] != false;
    }

    public function getUserLevel() {
        return $this->session->userdata['user_level'];
    }

    public function loginUser($username, $password) {
        $auths = $this->config->item('auth');
        $user = null;
        foreach ($auths as $k=>$auth) {
            if ($auth['username'] == $username && $auth['password'] == $password) {
                $user = $auth;
                $user['logged_in'] = true;
                unset($user['password']);
            }
        }
        return $user;
    }
}