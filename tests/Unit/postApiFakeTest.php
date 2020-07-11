<?php

namespace Tests\Unit;

use Tests\TestCase;

class postApiFakeTest extends TestCase
{
    public function it_has_make_request_post()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\postApiFakeCommand::class));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRequest()
    {
        $this->artisan('postapifake:send https://jsonplaceholder.typicode.com/users 200 foo bar')
            ->expectsOutput('the application ended successfully')
            ->assertExitCode(0);
    }
}
