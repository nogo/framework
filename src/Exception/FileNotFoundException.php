<?php

namespace Nogo\Framework\Exception;

class FileNotFoundException extends \Exception
{

    public function __construct($message = "", $code = 0, $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }

}
