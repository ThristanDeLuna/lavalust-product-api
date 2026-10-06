<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Used by `php lava migration ...`. The Migration library refuses to run
// unless MIGRATION_ENABLED=true, so these routes are inert on production.
class MigrationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('migration');
    }

    public function create_migration($migration_class) { $this->migration->create_migration($migration_class); }
    public function migrate()      { $this->migration->migrate(); }
    public function rollback()     { $this->migration->rollback(); }
    public function rollback_all() { $this->migration->rollback_all(); }
    public function refresh()      { $this->migration->refresh(); }
    public function status()       { $this->migration->status(); }
}
