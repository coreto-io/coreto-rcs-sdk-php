<?php

namespace Coreto\CoretoRCSSDK\Exceptions;

class InvalidScoreChangeException extends \Exception
{
    protected $message = 'The trust/performance change provided exceeds the given fluctuation.';
}
