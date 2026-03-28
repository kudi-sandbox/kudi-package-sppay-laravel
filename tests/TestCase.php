<?php

namespace Mboateng\Sppay\Tests;

use Mboateng\Sppay\SppayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SppayServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('sppay.base_url', 'https://engine.sppay.dev');
        $app['config']->set('sppay.access_token', 'test-token');
    }
}
