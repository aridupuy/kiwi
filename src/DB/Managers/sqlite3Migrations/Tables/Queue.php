<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables;

use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces\SqliteTableMigration;
use Sqlite3;
class Queue extends AbstractTableMigration
{

    public function execute() {
        error_log("CreatingQueue");
        return $this->db->exec($this->getQuery());
    }

    private function getQuery(): string
    {
        return "CREATE TABLE Queue (
        Id INTEGER PRIMARY KEY AUTOINCREMENT,
        Name TEXT NOT NUll,
        Fecha TEXT DEFAULT CURRENT_TIMESTAMP NOT NULL,
        Semaphore INTEGER DEFAULT 0 NOT NULL,
        ErrorMessage TEXT,
        Ejecuciones INTEGER DEFAULT 0 NOT NULL
    );
";
    }

    public function getName()
    {
        return "Queue";
    }
}
