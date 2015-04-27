# NoGo Framework

## Why?

NoGo framwork sit on top of Slim framework to provide a MVC like structure.

## Comes with

- Configuration management
- Controller interface
- Twig template

## Controller interface

The interface provide a enable function which loaded at application start. The
controller must be define in a configuration file, to loaded automatically. This
function should contain routing informations.

```
use Nogo\Framework\Controller;
use Slim\Slim;

class MyController implements Controller
{
    public function enable(Slim $app)
    {
        $app->get('/hello/:name', array($this, 'itemsAction'));
    }

    public function itemsAction($name)
    {
        // do something
    }
}

```

## Your index.php


```
define('ROOT_DIR', realpath(dirname(__FILE__) . '/../' ));
require_once ROOT_DIR . '/vendor/autoload.php';

$bootstrap = new \Nogo\Framework\Bootstrap(new Slim\Slim());
$bootstrap
        ->configure(ROOT_DIR . '/app/config.yml')
        ->log()
        ->route()
        ->run();

```
