<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Tables;

class Params extends AbstractTableMigration
{

    public function execute() {
        error_log("CreatingParams");
        return $this->db->exec($this->getQuery());
    }

    private function getQuery(): string
    {
        return "CREATE TABLE Parameters (
	idParameter INTEGER PRIMARY KEY AUTOINCREMENT,
	idQueue INTEGER,
	Param TEXT NOT NULL,
	CONSTRAINT Parameters_FK FOREIGN KEY (idQueue) REFERENCES Queue(id)
);
";
    }

    public function getName()
    {
        return "Params";
    }
}
