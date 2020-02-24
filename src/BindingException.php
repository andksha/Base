<?php

namespace Anso\Framework\Base;

use Exception;

class BindingException extends Exception
{
    protected $code = 500;
}