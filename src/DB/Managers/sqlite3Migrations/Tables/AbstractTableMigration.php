<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables;
use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces\SqliteTableMigration;
use SQLite3;

abstract class AbstractTableMigration implements SqliteTableMigration {
    protected Sqlite3 $db;
    public function __construct(Sqlite3 $db) {
        $this->db = $db;
    }

    public abstract function execute();
    public abstract function getName();
}
