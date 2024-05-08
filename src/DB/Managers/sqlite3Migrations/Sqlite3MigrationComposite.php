<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations;

use \SQLite3;
class Sqlite3MigrationComposite
{
    private $migrations=[];
    private $db;

    public function __construct(\SQLite3 $db)
    {
        $this->db = $db;
        $this->migrations = $this->createMigrations();
    }

    public function execute() {
        foreach ($this->migrations as $migration) {
            $migration->execute($this->db);
        }
    }

    private function createMigrations(): array {
        return [
            new SqliteTableMigration()
        ];
    }
}
