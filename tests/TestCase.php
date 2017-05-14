<?php

use App\User;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $user;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    public function withoutMiddleware() {
        $this->app->instance('middleware.disable', true);
    }

    public function withMiddleware() {
        $this->app->instance('middleware.disable', false);
    }
}
