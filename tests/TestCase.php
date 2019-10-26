<?php

namespace DigiFactory\SvgFixer\Tests;

use DigiFactory\SvgFixer\SvgFixerMiddleware;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDummyRoutes();
    }

    protected function setUpDummyRoutes()
    {
        $this->app['router']->group(
            ['middleware' => SvgFixerMiddleware::class],
            function () {
                $this->app['router']->post('image', function () {
                    return 'Hello world!';
                });
            }
        );
    }
}
