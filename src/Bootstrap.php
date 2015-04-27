<?php

namespace Nogo\Framework;

use Nogo\Framework\Config\SlimLoader;
use Nogo\Framework\Controller\SlimController;
use Slim\Slim;

/**
 * Bootstrap
 *
 * @author Danilo Kuehn <dk@nogo-software.de>
 */
class Bootstrap
{

    /**
     * @var Slim
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param Slim $app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;
        $this->app->container->singleton('configuration', function() use ($app) {
            return new SlimLoader($app);
        });
    }

    /**
     * @return Slim
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Add configuration
     * @param type $file
     * @return \Nogo\Framework\Bootstrap
     */
    public function configure($file)
    {
        $this->app->configuration->import($file)->refresh();
        return $this;
    }

    /**
     * Configurate logger
     * @return \Nogo\Framework\Bootstrap
     */
    public function log()
    {
        $logClass = $this->app->config('log.class');
        if (empty($logClass)) {
            $logClass = 'Nogo\Framework\Log\Writer';
        }
        $logPath = $this->app->config('log_dir');
        if (empty($logPath)) {
            $logPath = './';
        }

        $this->app->config(
            'log.writer', new $logClass(array('path' => $logPath))
        );
        return $this;
    }

    /**
     * Route
     * @param array $routes
     * @return \Nogo\Framework\Bootstrap
     */
    public function route(array $routes = array())
    {
        $config = $this->app->config('routes');
        if (empty($config)) {
            $routes = array();
        }
        $routes = array_merge($config, $routes);

        foreach ($routes as $class) {
            $ref = new \ReflectionClass($class);
            if ($ref->implementsInterface('Nogo\Framework\Controller\SlimController')) {
                /**
                 * @var SlimController $controller
                 */
                $controller = new $class();
                $controller->enable($this->app);
                $this->app->log->debug('Register and enable controller [' . $class . '].');
            }
        }
        return $this;
    }

    /**
     * Slim->run()
     * @return \Nogo\Framework\Bootstrap
     */
    public function run()
    {
        $this->app->run();
        return $this;
    }

}
