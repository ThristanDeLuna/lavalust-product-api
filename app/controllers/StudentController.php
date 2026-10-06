<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// StudentController.php
// Web Systems and Technologies - Laboratory Activity 3
// Name: Thristan Ian O. De Luna
// Section: BSIT 3-F1

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Make sure a session is running before we read/write $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $data['title'] = 'Student Home';
        $data['error'] = '';

        // Password comes from .env (STUDENT_ACCESS_PASSWORD). Falls back to
        // a default only if it was never set, so the app doesn't hard-fail
        // when someone forgets to configure it.
        $correct_password = getenv('STUDENT_ACCESS_PASSWORD') ?: 'student123';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submitted = isset($_POST['password']) ? $_POST['password'] : '';

            if (hash_equals($correct_password, (string) $submitted)) {
                // Correct password -> grant access, middleware will now let
                // the profile route through.
                session_regenerate_id(true);
                $_SESSION['student_access'] = true;

                redirect('student/profile');
                exit;
            }

            $data['error'] = 'Incorrect password. Please try again.';
        }

        // Not authenticated yet (or password was wrong): show the login form,
        // do NOT set student_access.
        $this->call->view('student_home', $data);
    }

    // logs the student out by clearing the session flag the middleware checks
    public function logout()
    {
        unset($_SESSION['student_access']);
        session_regenerate_id(true);

        redirect('student');
        exit;
    }

    // profile page, protected by middleware
    public function profile()
    {
        // sample data ko na rin actually, sarili kong info
        $student = array(
            'student_id' => 'MCC2024-00005',
            'name'       => 'Thristan Ian O. De Luna',
            'course'     => 'BSIT',
            'year'       => '3rd Year',
            'section'    => 'F1',
            'email'      => 'delunathristanian@gmail.com'
        );

        $data['student'] = $student;
        $data['title'] = 'My Profile';

        $this->call->view('student_profile', $data);
    }
}

