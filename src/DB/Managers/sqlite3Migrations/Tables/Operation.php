<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables;

class Operation extends AbstractTableMigration {

    public function execute() {
        error_log("CreatingOperation");
        return $this->db->exec($this->getQuery());
    }

    private function getQuery(): string
    {
        return "CREATE TABLE Operation (
	idOperation INTEGER PRIMARY KEY AUTOINCREMENT,
	fecha INTEGER,
	desde int ,
	hasta int NOT NULL
);
";
    }

    public function getName()
    {
        return "Operation";
    }
}
