<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// StudentMiddleware.php
// Simple middleware para sa protection ng /student/profile route

class StudentMiddleware 
{
    public function handle(Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // simple access condition ko lang, session variable
        if (!isset($_SESSION['student_access']) || $_SESSION['student_access'] !== true) {
            // hindi pwede, ibalik sa home page na lang
            redirect('student');
            exit;
        }

        // allowed, pupunta sa profile page
        return $next();
    }
}