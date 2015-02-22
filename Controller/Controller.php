<?php
namespace Nogo\Framework\Controller;

use Slim\Slim;

interface Controller
{
    public function enable(Slim $app);
}
