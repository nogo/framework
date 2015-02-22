# NoGo Framework

## Why?

NoGo framwork sit on top of Slim framework to provide a MVC like structure.

## Comes with

- Configuration management
- Database connector and migration
- Controller interface
- Repository interface
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
