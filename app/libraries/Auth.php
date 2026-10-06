<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth
{
    protected $_lava;

    public function __construct() {
        $this->_lava = lava_instance();
        $this->_lava->call->database();
        $this->_lava->call->library('session');
    }

    public function register($username, $password, $role = 'user') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->_lava->db->table('users')->insert([
            'username'   => $username,
            'password'   => $hash,
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

   public function login($username, $password)
{
    $username = trim($username);

    $result = $this->_lava->db->table('users')
                         ->where('username', $username)
                         ->get();

    // Normalize into a plain associative array regardless of what get() returned
    $user = null;

    if (is_array($result)) {
        if (isset($result['id'])) {
            // get() returned the row directly
            $user = $result;
        } elseif (isset($result[0])) {
            // get() returned a list of rows
            $user = is_object($result[0]) ? (array) $result[0] : $result[0];
        }
    } elseif (is_object($result)) {
        $user = (array) $result;
    }

    if (!$user || !isset($user['password'])) {
        return false;
    }

    if (!password_verify($password, $user['password'])) {
        return false;
    }

    $this->_lava->session->set_userdata([
        'user_id'   => $user['id'],
        'username'  => $user['username'],
        'role'      => $user['role'],
        'logged_in' => true
    ]);

    return true;
}




    public function is_logged_in() {
        return (bool) $this->_lava->session->userdata('logged_in');
    }

    public function logout() {
        $this->_lava->session->unset_userdata(['user_id', 'username', 'role', 'logged_in']);
    }
}