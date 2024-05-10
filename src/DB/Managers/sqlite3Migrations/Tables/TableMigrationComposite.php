<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables;

use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces\TableMigration;
use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\SqliteTableMigration;
use SQLite3;

class TableMigrationComposite
{
    private $migrations = [];

    public function __construct(Sqlite3 $db) {
        /**@type SqliteTableMigration [] */
        $this->migrations = $this->composeMigrations($db);
    }

    private function composeMigrations(Sqlite3 $db) {
        return [
            new Queue($db),
            new Params($db),
            new Operation($db)
        ];
    }

    public function execute(): bool {
        foreach ($this->migrations as $migration) {
            if(!$migration->execute()){
                error_log("Fallo al crear {$migration->getName()}");
                return false;
            }

        }
        return true;
    }

}
