<?php

namespace Adupuy\cdqueue\DB;

class DBManager
{

    public static function getConection() {
        return DbResolver::resolve()->getConection();
    }
}
