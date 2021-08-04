<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function initMock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }
}
