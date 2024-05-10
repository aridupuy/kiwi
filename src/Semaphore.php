<?php

namespace Adupuy\cdqueue;

use Adupuy\cdqueue\DB\DBManager;

class Semaphore
{
    const MAX_EXECUTION = 3;
    public static function tomarSemaforo($offset, $max): void {
        $ejecuciones = self::MAX_EXECUTION;
        DBManager::getConection()->exec("UPDATE Queue set Semaphore = true and Ejecuciones < $ejecuciones where id in (SELECT id FROM Queue where Semaphore = false LIMIT {$max} offset {$offset})");
    }

    public static function liberarSemaforo(?array $idsALiberar): void {
        if(empty($idsALiberar))
            return;
        $ids = implode(", ", $idsALiberar);
        DBManager::getConection()->exec("UPDATE Queue set Semaphore = false where id in ($ids)");
    }
}
