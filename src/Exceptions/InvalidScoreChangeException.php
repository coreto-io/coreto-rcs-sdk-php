<?php

namespace Coreto\CoretoReputationCollectionSDK\Exceptions;

class InvalidScoreChangeException extends \Exception
{
    protected $message = 'The trust/performance change provided exceeds the given fluctuation.';
}
