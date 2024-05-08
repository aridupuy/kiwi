<?php

namespace Adupuy\cdqueue\DB\Managers;

use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Sqlite3MigrationComposite;
use SQLite3;
class SqliteManager {
    private SQLite3 $db;

    private function singleton () {
        if(!empty($this->db))
            return $this->db;
        $this->db = new SQLite3(getcwd().'/src/DB/Sqlite3/Queue.sqlite');
        if(!$this->validate())
            $this->migrate();
        return $this->db;
    }

    private function validate()
    {
        $result = $this->db->query("SELECT name FROM sqlite_master WHERE type='table'");
        return $result->fetchArray();
    }

    private function migrate() {
        $migration = new Sqlite3MigrationComposite($this->db);
        $migration->execute();
    }
    public function isActive(): bool
    {
        return true;
    }

    public function getConectObject()
    {
        return $this->singleton();
    }
}
