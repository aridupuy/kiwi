<?php

namespace Adupuy\cdqueue\DB;

use Adupuy\cdqueue\DB\Managers\SqliteManager;

class DbResolver
{
    static  $instance;
    private $dbs = [];

    private function __construct() {
        $this->dbs = $this->initialize();
    }

    public static function resolve(): self {
        if(empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    private function initialize(): array  {
        return [
            "sqlite3" => new SqliteManager()
        ];
    }

    public function getConection() {
        foreach ($this->dbs as $db) {
            if ($db->isActive())
                return $db->getConectObject();
        }
    }

}
