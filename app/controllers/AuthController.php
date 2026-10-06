<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function register() {
        $this->call->library('auth');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->auth->register(
                $this->io->post('username'),
                $this->io->post('password')
            );
            redirect('auth/login');
        }
        $this->call->view('auth/register');
    }

    public function login() {
        $this->call->library('auth');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->auth->login(
                $this->io->post('username'),
                $this->io->post('password')
            )) {
                redirect('products');
            } else {
                echo 'Login failed!';
            }
        }
        $this->call->view('auth/login');
    }

    public function logout() {
        $this->call->library('auth');
        $this->auth->logout();
        redirect('auth/login');
    }
}