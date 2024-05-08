<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces;

use Sqlite3;

interface SqliteTableMigration
{
    public function execute();
}
