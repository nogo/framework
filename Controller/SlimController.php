<?php
namespace Nogo\Framework\Controller;

use Slim\Slim;

interface SlimController
{
    public function enable(Slim $app);
}
