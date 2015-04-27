<?php

namespace Nogo\Framework\Middleware;

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\Middleware;

/**
 * Before
 *
 * @author Danilo Kuehn <dk@nogo-software.de>
 */
class Database extends Middleware
{         
    public function call()
    {
        $app = $this->app;
        
        $configuration = $app->config('database');
        if (!empty($configuration)) {
            $app->log->debug('Call middleware [Database]');

            $app->container->singleton('database', function () {
                return new Capsule();
            });
            $app->database->addConnection($configuration);
            $app->database->setAsGlobal();
            $app->database->bootEloquent();
        } else {
            $app->log->debug('Middleware[Database]: No configuration [database] found.');
        }

        $this->next->call();
    }
}
