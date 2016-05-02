<?php

use Trackit\Models\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $user;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $this->user = factory(User::class)->create();

        return $app;
    }

    public function createAuthHeader()
    {
        $server = [
            'HTTP_AUTHORIZATION' => "Bearer ".$this->user->api_token,
        ];

        return $server;
    }

    /**
     *
     */
    public function getUser()
    {
        return $this->user;
    }
}
