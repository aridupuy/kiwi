<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\DBManager;

class Semaphore
{
    public static function tomarSemaforo($offset, $max): void {
        DBManager::getConection()->exec("UPDATE Queue set Semaphore = true where id in (SELECT id FROM Queue where Semaphore = false LIMIT {$max} offset {$offset})");
    }

    public static function liberarSemaforo(array $idsALiberar): void {
        $ids = implode(", ", $idsALiberar);
        DBManager::getConection()->exec("UPDATE Queue set Semaphore = false where id in ($ids)");
    }
}
