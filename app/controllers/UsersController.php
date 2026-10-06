<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/*
 * ============================================
 * Laboratory Exercise No. 4 - Part F and G
 * File location in your project: app/controllers/UsersController.php
 * ============================================
 */

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load the model so it becomes available as $this->UsersModel
        $this->call->model('UsersModel');
    }

    /**
     * Retrieve all users and pass them to the view.
     * Flow: UsersController -> UsersModel::all() -> $users -> View
     */
    public function index()
    {
        // 1 & 2. Call UsersModel and execute all()
        // 3. Store the returned records
        $users = $this->UsersModel->all();

        // 4. Pass the records to the view
        $data['users'] = $users;

        // 5. Load the user view
        $this->call->view('users_view', $data);
    }
}