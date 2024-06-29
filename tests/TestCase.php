<?php

namespace Tests;

use App\Providers\TestServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mail;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(TestServiceProvider::class);

        Mail::fake();
    }
}
