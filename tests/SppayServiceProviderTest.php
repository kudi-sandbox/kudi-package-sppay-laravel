<?php

namespace Mboateng\Sppay\Tests;

use Mboateng\Sppay\Contracts\SppayClientContract;
use Mboateng\Sppay\Facades\Sppay;
use Mboateng\Sppay\SppayClient;

class SppayServiceProviderTest extends TestCase
{
    public function test_client_is_registered(): void
    {
        $this->assertInstanceOf(SppayClient::class, $this->app->make(SppayClientContract::class));
        $this->assertInstanceOf(SppayClient::class, $this->app->make('sppay'));
    }

    public function test_facade_resolves(): void
    {
        $this->assertInstanceOf(SppayClient::class, Sppay::getFacadeRoot());
    }
}
