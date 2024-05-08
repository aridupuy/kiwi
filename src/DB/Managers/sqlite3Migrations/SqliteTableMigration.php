<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations;

use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces\Migration;
use Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables\TableMigrationComposite;

class SqliteTableMigration implements Migration {

    public function execute($db): bool {
        $composite  = new TableMigrationComposite($db);
        return $composite->execute();
    }
}
