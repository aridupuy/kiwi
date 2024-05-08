<?php

namespace Adupuy\cdqueue\DB\Managers\sqlite3Migrations\Interfaces;

interface Migration
{
    public function execute($db);
}
