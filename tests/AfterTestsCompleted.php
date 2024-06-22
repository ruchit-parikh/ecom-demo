<?php

namespace Tests;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

class AfterTestsCompleted implements ExecutionFinishedSubscriber
{
    public function notify(ExecutionFinished $event): void
    {
        // Make sure we clean-up the old sql file. So that don't keep an sql snapshot on the file-system
        if (file_exists('./database/snapshots/sql-snapshot.sql') && !env('KEEP_SQL_SNAPSHOT', false)) {
            unlink('./database/snapshots/sql-snapshot.sql');
        }
    }
}
