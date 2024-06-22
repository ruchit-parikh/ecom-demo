<?php

namespace Tests;

use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;

class BeforeTestsStarted implements ExecutionStartedSubscriber
{
    public function notify(ExecutionStarted $event): void
    {
        // Make sure we clean-up the old sql file. Because we are starting a new dusk test case
        if (file_exists('./database/snapshots/sql-snapshot.sql') && !env('KEEP_SQL_SNAPSHOT', false)) {
            unlink('./database/snapshots/sql-snapshot.sql');
        }
    }
}
