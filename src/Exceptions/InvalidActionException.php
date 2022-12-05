<?php

namespace Coreto\CoretoRCSSDK\Exceptions;

class InvalidActionException extends \Exception
{
    protected $message = 'The action provided is not valid.';
}
