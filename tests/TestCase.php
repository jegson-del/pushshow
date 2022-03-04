<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('oauth_clients')) {
            resolve(ClientRepository::class)->createPersonalAccessClient(
                null, config('app.name') . ' Personal Access Client', config('app.url')
            );
        }
    }
}
